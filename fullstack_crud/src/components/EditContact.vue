<template>
  <div class="container add-contact-container">
    <ToastMessage ref="toast" title="Contact Update" :body="toastBody" />
    <div class="row d-flex justify-content-center">
      <div class="col-md-6 card p-4">
        <h2 class="h5">Edit contact #{{ route.params.id }}</h2>
        <form action="#" @submit.prevent="handleAddContact" novalidate>
          <fieldset>
            <div class="form-group">
              <label for="txtName" class="form-label mt-4"> Name:</label>
              <input
                type="text"
                name="name"
                id="txtName"
                class="form-control"
                placeholder="Enter Name"
                v-model="contact.name"
                required="true"
                @blur="validateForm"
                @keypress="validateForm"
              />
            </div>
            <small class="text-danger">{{ contactError.name_err }}</small>

            <div class="form-group">
              <label for="txtEmail" class="form-label mt-4"> Email:</label>
              <input
                type="email"
                name="email"
                id="txtEmail"
                class="form-control"
                placeholder="Enter Email"
                required="true"
                v-model="contact.email"
                @blur="validateForm"
                @keypress="validateForm"
              />
              <small class="text-danger">{{ contactError.email_err }}</small>
            </div>

            <div class="form-group">
              <label for="txtContactNo" class="form-label mt-4"> Contact No:</label>
              <input
                type="text"
                name="contact_no"
                id="txtContactNo"
                class="form-control"
                placeholder="Enter Contact No"
                required="true"
                v-model="contact.contact_no"
                @blur="validateForm"
                @keypress="validateForm"
              />
              <small class="text-danger">{{ contactError.contact_no_err }}</small>
            </div>

            <div class="form-group">
              <label for="txtName" class="form-label mt-4"> Designation:</label>
              <input
                type="text"
                name="designation"
                id="txtDesignation"
                class="form-control"
                placeholder="Enter Designation"
                required="true"
                v-model="contact.designation"
                @blur="validateForm"
                @keypress="validateForm"
              />
              <small class="text-danger">{{ contactError.designation_err }}</small>
            </div>
            <div class="form-group mt-4 d-flex justify-content-end">
              <RouterLink to="/home" class="btn btn-outline-primary border-radius-10 mr-2">
                Back to Contact List
              </RouterLink>
              <button class="btn btn-primary border-radius-10" type="submit">Update Contact</button>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'
import ToastMessage from './ToastMessage.vue'

const route = useRoute()

const initContact = {
  name: '',
  email: '',
  contact_no: '',
  designation: '',
}

const initContactError = {
  name_err: '',
  email_err: '',
  contact_err: '',
  designation_err: '',
}

const contact = ref({ ...initContact })
const contactError = ref({ ...initContactError })
const toast = ref(null)
const toastBody = ref('The contact was added successfully.')
const isInitForm = ref(true)

const fetchContactById = async (id) => {
  try {
    const accessToken = localStorage.getItem('access_token')
    const response = await api.get(`/contacts/${id}`, {
      headers: accessToken ? { Authorization: `Bearer ${accessToken}` } : {},
    })

    if (response.status == 200) {
      const fetchedContact = response.data.contact

      contact.value = { ...fetchedContact }
    }
  } catch (error) {
    console.log(error)
  }
}

onMounted(() => {
  fetchContactById(route.params.id)
})

const validateForm = () => {
  let isFormValid = true

  if (!isInitForm.value) {
    if (contact.value.name.trim() == '') {
      contactError.value.name_err = 'Name input box is required to fill.'
      isFormValid = false
    } else if (contact.value.name.trim().length < 3) {
      contactError.value.name_err = 'Please enter at least 3 characters.'
      isFormValid = false
    } else {
      contactError.value.name_err = ''
      isFormValid = true
    }

    if (contact.value.email.trim() == '') {
      contactError.value.email_err = 'Email input box is required to fill.'
      isFormValid = false
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(contact.value.email)) {
      contactError.value.email_err = 'Please enter a valid email address format.'
      isFormValid = false
    } else {
      contactError.value.email_err = ''
      isFormValid = true
    }

    if (contact.value.contact_no.trim() == '') {
      contactError.value.contact_no_err = 'Contact input box is required to fill.'
      isFormValid = false
    } else {
      contactError.value.contact_no_err = ''
      isFormValid = true
    }

    if (contact.value.designation.trim() == '') {
      contactError.value.designation_err = 'Designation_err input box is required to fill.'
      isFormValid = false
    } else if (contact.value.designation.trim().length < 2) {
      contactError.value.designation_err = 'Designation should have at least 2 characters.'
      isFormValid = false
    } else {
      contactError.value.designation_err = ''
      isFormValid = true
    }
  }

  return isFormValid
}

const handleAddContact = async () => {
  const accessToken = localStorage.getItem('access_token')
  const dataToPost = { ...contact.value }
  isInitForm.value = false

  if (!validateForm()) {
    return
  }

  try {
    const response = await api.put(`/contacts/${route.params.id}`, dataToPost, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${accessToken}`,
      },
    })

    if (response.status === 200) {
      toastBody.value = response.data.message
      toast.value?.showToast()

      // contact.value = { ...initContact }
      isInitForm.value = true
    }
  } catch (error) {
    if (error.response) {
      // Server responded with an error status
      if (error.response.status === 500) {
        toastBody.value = error.response.data.message || 'Server error cannot proceed this time.'
      } else {
        toastBody.value = error.response.data.message || 'Something went wrong.'
      }
    } else {
      // No response from server
      toastBody.value = 'Cannot connect to the server.'
    }
  }

  toast.value?.showToast()
  // isInitForm.value = true
}
</script>

<style scoped>
.add-contact-container {
  position: relative;
}
</style>
