<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <main class="main_container">
    <h1>View Sample Page</h1>
    <hr />
    <nav>
      <RouterLink to="/"> Home </RouterLink> &nbsp;
      <RouterLink to="/about_us"> About </RouterLink>
    </nav>
    <hr />
    <RouterView />
  </main>
</template>

<style scoped>
.main_container {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 80%;
  margin: 0 auto;
}
</style>
