<script setup>
import { useRoute } from 'vue-router'
import Header from './components/Header.vue'
import { computed } from 'vue'

const route = useRoute()

const showHeader = computed(() => {
  return route.path !== '/' && route.path !== '/login'
})
</script>

<template>
  <div>
    <Header v-if="showHeader" title="ABNATION" />
    <RouterView />
  </div>
</template>

<style scoped></style>
