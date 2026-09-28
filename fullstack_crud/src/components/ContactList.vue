<template>
  <div class="container card">
    <ToastMessage ref="toast" title="Contact Delete" :body="toastBody" />
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
import { ref, onMounted } from 'vue'
import axios from 'axios'
import ToastMessage from './ToastMessage.vue'

const contacts = ref([])
const contactToDelete = ref(null)
const isDeleting = ref(false)
const deleteError = ref('')
const toast = ref(null)
const toastBody = ref('The contact was deleted successfully.')

const getContacts = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/contacts')

    contacts.value = response.data.contacts
  } catch (error) {
    console.error('Error fetching contacts:', error)
    throw new Error('Contact list cannot be fetched: ' + error.message, { cause: error })
  }
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
    await getContacts()

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
</script>

<style scoped></style>
