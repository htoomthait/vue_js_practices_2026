<script setup>
import PostList from './components/PostList.vue'
</script>

<template>
  <main class="main_container">
    <h1 class="bottom_line pb-4">Simple API Call in Vue JS</h1>

    <PostList />
  </main>
</template>

<style scoped>
.main_container {
  display: flex;
  flex-direction: column;
  width: 80%;

  margin: 0 auto;
}
.bottom_line {
  border-bottom: 1px solid #000;
}
.pb-1 {
  padding-bottom: 4px;
}
.pb-2 {
  padding-bottom: 8px;
}
.pb-3 {
  padding-bottom: 12px;
}
.pb-4 {
  padding-bottom: 16px;
}
</style>
