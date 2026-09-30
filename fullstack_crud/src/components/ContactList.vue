<template>
  <div class="container card">
    <ToastMessage ref="toast" title="Contact Delete" :body="toastBody" />
    <div class="px-3 pt-3">
      <label for="contact-search" class="form-label">Search contacts</label>
      <input
        id="contact-search"
        v-model="searchTerm"
        type="search"
        class="form-control"
        placeholder="Search by name, email, or contact number"
        @input="searchContacts"
        @keydown="searchContacts"
        @blur="searchContacts"
      />
    </div>
    <table class="table table-hover mt-4">
      <thead>
        <tr class="">
          <th scope="col" class="bg-primary text-white">
            <span class="d-flex justify-content-center align-items-center gap-1 height-50">
              <span class="material-icons">numbers</span>
              <button
                type="button"
                class="btn btn-link text-white p-0"
                :aria-label="`Sort by ID ${orderBy === 'id' && orderDirection === 'asc' ? 'descending' : 'ascending'}`"
                :disabled="isLoading"
                @click="makeOrderBy('id')"
              >
                <span class="material-icons">{{
                  orderBy === 'id'
                    ? orderDirection === 'asc'
                      ? 'arrow_drop_up'
                      : 'arrow_drop_down'
                    : 'unfold_more'
                }}</span>
              </button>
            </span>
          </th>
          <th scope="col" class="bg-primary text-white">
            <span class="d-flex justify-content-center align-items-center gap-1 height-50">
              <span class="material-icons">person</span>
              Name
              <button
                type="button"
                class="btn btn-link text-white p-0"
                :aria-label="`Sort by name ${orderBy === 'name' && orderDirection === 'asc' ? 'descending' : 'ascending'}`"
                :disabled="isLoading"
                @click="makeOrderBy('name')"
              >
                <span class="material-icons">{{
                  orderBy === 'name'
                    ? orderDirection === 'asc'
                      ? 'arrow_drop_up'
                      : 'arrow_drop_down'
                    : 'unfold_more'
                }}</span>
              </button>
            </span>
          </th>
          <th scope="col" class="bg-primary text-white">
            <span class="d-flex justify-content-center align-items-center gap-1 height-50">
              <span class="material-icons">alternate_email</span>
              Email
            </span>
          </th>
          <th scope="col" class="bg-primary text-white">
            <span class="d-flex justify-content-center align-items-center gap-1 height-50">
              <span class="material-icons">work</span>
              Designation
            </span>
          </th>
          <th scope="col" class="bg-primary text-white">
            <span class="d-flex justify-content-center align-items-center gap-1 height-50">
              <span class="material-icons">phone</span>
              Contact No
            </span>
          </th>
          <th scope="col" class="bg-primary text-white">
            <span class="d-flex justify-content-center align-items-center gap-1 height-50">
              <span class="material-icons">settings</span>
              Actions
            </span>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          :class="contact.id % 2 === 0 ? 'table-secondary' : 'table-primary'"
          v-for="contact in contacts"
          :key="contact.id"
        >
          <td>{{ contact.id }}</td>
          <td>{{ contact.name }}</td>
          <td>{{ contact.email }}</td>
          <td>{{ contact.designation }}</td>
          <td>{{ contact.contact_no }}</td>
          <td class="d-flex justify-content-center">
            <RouterLink
              :to="`/edit-contact/${contact.id}`"
              class="btn btn-outline-warning mr-2 d-inline-flex align-items-center gap-1"
            >
              <span class="material-icons"> edit_calendar </span>
              Edit
            </RouterLink>

            <button
              class="btn btn-outline-danger d-inline-flex align-items-center gap-1"
              @click="openDeleteConfirmation(contact)"
            >
              <span class="material-icons">delete</span>
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 pb-3">
      <div class="d-flex align-items-center gap-2">
        <label for="contacts-per-page" class="form-label mb-0">Per page</label>
        <select
          id="contacts-per-page"
          v-model.number="perPage"
          class="form-select form-select-sm w-auto"
          @change="changePerPage"
        >
          <option v-for="size in pageSizes" :key="size" :value="size">{{ size }}</option>
        </select>
        <small class="text-body-secondary"> {{ fromItem }}–{{ toItem }} of {{ totalItems }} </small>
      </div>
      <nav aria-label="Contact list pages">
        <ul class="pagination pagination-sm mb-0">
          <li class="page-item" :class="{ disabled: currentPage <= 1 || isLoading }">
            <button
              type="button"
              class="page-link"
              :disabled="currentPage <= 1 || isLoading"
              @click="loadPage(currentPage - 1)"
            >
              Previous
            </button>
          </li>
          <li
            v-for="(page, index) in paginationItems"
            :key="`${page}-${index}`"
            class="page-item"
            :class="{ active: page === currentPage, disabled: page === '...' }"
          >
            <span v-if="page === '...'" class="page-link">...</span>
            <button
              v-else
              type="button"
              class="page-link"
              :aria-current="page === currentPage ? 'page' : undefined"
              :disabled="isLoading"
              @click="loadPage(page)"
            >
              {{ page }}
            </button>
          </li>
          <li class="page-item" :class="{ disabled: currentPage >= lastPage || isLoading }">
            <button
              type="button"
              class="page-link"
              :disabled="currentPage >= lastPage || isLoading"
              @click="loadPage(currentPage + 1)"
            >
              Next
            </button>
          </li>
        </ul>
      </nav>
    </div>
  </div>

  <ConfirmBox
    v-if="contactToDelete"
    :object-to-act="contactToDelete"
    :is-action-doing="isDeleting"
    :action-error="deleteError"
    box-title="Delete Contact?"
    action-name="Delete"
    :handle-cancel-action="cancelDelete"
    :handle-confirm-action="confirmDelete"
  />
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import api from '../services/api'
import ToastMessage from './ToastMessage.vue'
import ConfirmBox from './ConfirmBox.vue'

const contacts = ref([])
const contactToDelete = ref(null)
const isDeleting = ref(false)
const deleteError = ref('')
const toast = ref(null)
const toastBody = ref('The contact was deleted successfully.')
const pageSizes = [5, 10, 15, 20, 30]
const perPage = ref(5)
const currentPage = ref(1)
const lastPage = ref(1)
const totalItems = ref(0)
const isLoading = ref(false)
const searchTerm = ref('')
const orderBy = ref('id')
const orderDirection = ref('desc')
let searchTimeout
const fromItem = computed(() =>
  totalItems.value ? (currentPage.value - 1) * perPage.value + 1 : 0,
)
const toItem = computed(() => Math.min(currentPage.value * perPage.value, totalItems.value))
const paginationItems = computed(() => {
  if (lastPage.value <= 5) {
    return Array.from({ length: lastPage.value }, (_, index) => index + 1)
  }

  if (currentPage.value <= 3) {
    return [1, 2, 3, 4, '...', lastPage.value]
  }

  if (currentPage.value >= lastPage.value - 2) {
    return [1, '...', lastPage.value - 3, lastPage.value - 2, lastPage.value - 1, lastPage.value]
  }

  return [
    1,
    '...',
    currentPage.value - 1,
    currentPage.value,
    currentPage.value + 1,
    '...',
    lastPage.value,
  ]
})

const getContacts = async (page = currentPage.value) => {
  isLoading.value = true
  try {
    const response = await api.get('/contacts', {
      params: {
        page,
        per_page: perPage.value,
        search: searchTerm.value.trim(),
        orderBy: orderBy.value,
        orderDirection: orderDirection.value,
      },
    })
    const paginator = response.data.contacts

    contacts.value = paginator.data
    currentPage.value = paginator.current_page || page
    lastPage.value = paginator.last_page || 1
    totalItems.value = paginator.total ?? contacts.value.length
  } catch (error) {
    console.error('Error fetching contacts:', error)
    throw new Error('Contact list cannot be fetched: ' + error.message, { cause: error })
  } finally {
    isLoading.value = false
  }
}

const loadPage = (page) => {
  if (page >= 1 && page <= lastPage.value && page !== currentPage.value) {
    getContacts(page)
  }
}

const changePerPage = () => {
  getContacts(1)
}

const searchContacts = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => getContacts(1), 300)
}

/** Delete Contact with confirmation group start */
const openDeleteConfirmation = (contact) => {
  contactToDelete.value = contact
  deleteError.value = ''
}

const cancelDelete = () => {
  if (!isDeleting.value) {
    contactToDelete.value = null
    deleteError.value = ''
  }
}

const confirmDelete = async () => {
  if (!contactToDelete.value || isDeleting.value) return

  isDeleting.value = true
  try {
    const response = await api.delete(`/contacts/${contactToDelete.value.id}`)
    contactToDelete.value = null
    await getContacts(currentPage.value)

    toastBody.value = response.data.message || 'The contact was deleted successfully.'
    toast.value?.showToast()
  } catch (error) {
    deleteError.value = error.response?.data?.message || 'Unable to delete this contact. Try again.'
  } finally {
    isDeleting.value = false
  }
}

/** Delete Contact with confirmation group end */

const makeOrderBy = (sortBy) => {
  if (orderBy.value === sortBy) {
    orderDirection.value = orderDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    orderBy.value = sortBy
    orderDirection.value = 'asc'
  }

  getContacts(1)
}

onMounted(() => {
  getContacts()
})

onUnmounted(() => clearTimeout(searchTimeout))
</script>

<style scoped></style>
