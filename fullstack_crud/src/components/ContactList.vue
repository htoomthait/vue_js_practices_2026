<template>
  <div class="container">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Designation</th>
          <th scope="col">Contact No</th>
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
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const contacts = ref([])

const getContacts = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/contacts')

    contacts.value = response.data.contacts
  } catch (error) {
    console.error('Error fetching contacts:', error)
    throw new Error('Contact list cannot be fetched: ' + error.message, { cause: error })
  }
}

onMounted(() => {
  getContacts()
})
</script>

<style scoped></style>
