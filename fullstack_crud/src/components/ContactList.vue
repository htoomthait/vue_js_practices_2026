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
        <tr>
          <th scope="col" class="bg-primary text-white">#</th>
          <th scope="col" class="bg-primary text-white">Name</th>
          <th scope="col" class="bg-primary text-white">Email</th>
          <th scope="col" class="bg-primary text-white">Designation</th>
          <th scope="col" class="bg-primary text-white">Contact No</th>
          <th scope="col" class="bg-primary text-white">Actions</th>
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
          <td>
            <RouterLink :to="`/edit-contact/${contact.id}`" class="btn btn-outline-warning mr-2">
              Edit</RouterLink
            >
            <button class="btn btn-outline-danger" @click="openDeleteConfirmation(contact)">
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

  <template v-if="contactToDelete">
    <div
      class="modal fade show"
      tabindex="-1"
      role="dialog"
      aria-modal="true"
      aria-labelledby="delete-contact-title"
      style="display: block"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="delete-contact-title" class="modal-title">Delete contact?</h5>
            <button
              type="button"
              class="btn-close"
              aria-label="Close"
              :disabled="isDeleting"
              @click="cancelDelete"
            ></button>
          </div>
          <div class="modal-body">
            <p class="mb-0">Are you sure you want to delete {{ contactToDelete.name }}?</p>
            <p v-if="deleteError" class="text-danger mt-3 mb-0" role="alert">
              {{ deleteError }}
            </p>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              :disabled="isDeleting"
              @click="cancelDelete"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-danger"
              :disabled="isDeleting"
              @click="confirmDelete"
            >
              {{ isDeleting ? 'Deleting...' : 'Delete' }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop fade show"></div>
  </template>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import ToastMessage from './ToastMessage.vue'

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
    const response = await axios.get('http://localhost:8000/api/contacts', {
      params: { page, per_page: perPage.value, search: searchTerm.value.trim() },
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
    const response = await axios.delete(
      `http://localhost:8000/api/contacts/${contactToDelete.value.id}`,
    )
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

onMounted(() => {
  getContacts()
})

onUnmounted(() => clearTimeout(searchTimeout))
</script>

<style scoped></style>
