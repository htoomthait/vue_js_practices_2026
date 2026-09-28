<template>
  <div class="container add-contact-container">
    <ToastMessage ref="toast" title="Contact Adding" :body="toastBody" />
    <div class="row d-flex justify-content-center">
      <div class="col-md-6 card p-4">
        <form action="#" @submit.prevent="handleAddContact">
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
              />
            </div>

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
              />
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
              />
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
              />
            </div>
            <div class="form-group mt-4 d-flex justify-content-end">
              <RouterLink to="/" class="btn btn-outline-primary border-radius-10 mr-2">
                Back to Contact List
              </RouterLink>
              <button class="btn btn-primary border-radius-10" type="submit">Add Contact</button>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import ToastMessage from './ToastMessage.vue'

const initContact = {
  name: '',
  email: '',
  contact_no: '',
  designation: '',
}

const contact = ref({ ...initContact })
const toast = ref(null)
const toastBody = ref('The contact was added successfully.')

const handleAddContact = async () => {
  const dataToPost = { ...contact.value }

  await axios.post('http://localhost:8000/api/contacts', dataToPost)
  toastBody.value = `${dataToPost.name} was added successfully.`
  toast.value?.showToast()

  contact.value = { ...initContact }
}
</script>

<style scoped>
.add-contact-container {
  position: relative;
}
</style>
