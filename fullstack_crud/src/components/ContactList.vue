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
        <tr class="table-secondary">
          <td>1</td>
          <td>1</td>
          <td>1</td>
          <td>1</td>
          <td>1</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted } from 'vue'

const getContacts = async () => {
  try {
    const response = await axios.get('https://localhost:8000/api/contacts')

    return response.data
  } catch (error) {
    console.error('Error fetching contacts:', error)
  }
}

onMounted(() => {
  getContacts()
})
</script>

<style scoped></style>
