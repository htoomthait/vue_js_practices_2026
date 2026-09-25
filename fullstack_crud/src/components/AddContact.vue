<template>
  <div class="container">
    <div class="row d-flex justify-content-center border border-success">
      <div class="col-md-6 border border-danger">
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
              <button class="btn btn-primary" type="submit">Add Contact</button>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const initContact = {
  name: '',
  email: '',
  contact_no: '',
  designation: '',
}

const contact = ref(initContact)

const handleAddContact = async () => {
  console.log('You clicked handle Add Contact')
  console.log('Fill contact', contact.value)
}
</script>

<style scoped></style>
