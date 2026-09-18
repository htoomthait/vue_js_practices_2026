<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const posts = ref([])
const postFetchError = ref('')
const isLoading = ref(false)

// Sorting state variables
const sortKey = ref('') // Holds the active column key ('userId', 'id', etc.)
const sortOrder = ref('asc') // Tracks sorting order: 'asc' or 'desc'

onMounted(async () => {
  isLoading.value = true
  try {
    const response = await axios.get('https://jsonplaceholder.typicode.com/posts')
    posts.value = response.data
  } catch (err) {
    postFetchError.value = err.message || 'something went wrong on post fetch'
  } finally {
    isLoading.value = false
  }
})

// Helper function to render visual arrows dynamically in HTML
const getSortIcon = (key) => {
  if (sortKey.value !== key) return '↕' // Neutral icon for unsorted columns
  return sortOrder.value === 'asc' ? '▲' : '▼'
}

// Core Sorting Function
const sortBy = (key) => {
  if (sortKey.value === key) {
    // If clicking the same header, toggle the direction
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    // If clicking a brand new header, set it to active and default to ASC
    sortKey.value = key
    sortOrder.value = 'asc'
  }
}

// Computed property that dynamically returns the sorted array
const sortedPosts = computed(() => {
  if (!sortKey.value) return [...posts.value]

  const sorted = [...posts.value]

  return sorted.sort((a, b) => {
    const aValue = a[sortKey.value]
    const bValue = b[sortKey.value]

    if (aValue === bValue) return 0

    const modifier = sortOrder.value === 'asc' ? 1 : -1

    if (typeof aValue === 'string' && typeof bValue === 'string') {
      return aValue.localeCompare(bValue) * modifier
    }

    return (Number(aValue) - Number(bValue)) * modifier
  })
})
</script>

<template>
  <h2>Post List</h2>
  <!-- Loading Overlay (Shows only when isLoading is true) -->
  <div v-if="isLoading" class="loading-overlay">
    <div class="spinner"></div>
  </div>
  <table class="post_table">
    <thead>
      <tr>
        <th @click="sortBy('userId')" class="sortable-header">
          User Id <span class="sort-icon">{{ getSortIcon('id') }}</span>
        </th>
        <th @click="sortBy('id')" class="sortable-header">
          Id <span class="sort-icon">{{ getSortIcon('id') }}</span>
        </th>
        <th>Title</th>
        <th>Body</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="post in sortedPosts" :key="post.id">
        <td>{{ post.userId }}</td>
        <td>{{ post.id }}</td>
        <td>{{ post.title }}</td>
        <td>{{ post.body }}</td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
.post_table {
  width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
  font-family: sans-serif;
  font-size: 16px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.post_table thead tr {
  background-color: #009879;
  color: #ffffff;
  text-align: left;
}

.post_table th,
.post_table td {
  padding: 12px 15px;
}

.post_table tbody tr {
  border-bottom: 1px solid #dddddd;
}

.post_table tbody tr:nth-of-type(even) {
  background-color: #f3f3f3;
}

.post_table tbody tr:last-of-type {
  border-bottom: 2px solid #009879;
}

.post_table tbody tr:hover {
  background-color: #f1f1f1;
  font-weight: bold;
}
/* 2. Overlapping Screen with Backdrop Blur */
.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.6); /* Semi-transparent white mask */
  backdrop-filter: blur(4px); /* Frosty/blurred effect */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10; /* Forces overlay to sit above the table */
}

/* 3. Pure CSS Loading Spin Wheel */
.spinner {
  width: 45px;
  height: 45px;
  border: 4px solid #e5e7eb; /* Light grey track */
  border-top-color: #4f46e5; /* Indigo active indicator */
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
