<template>
  <div
    v-if="isVisible"
    class="toast show z-index-higher"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
  >
    <div class="toast-header">
      <strong class="me-auto">{{ title }}</strong>
      <small>{{ counterSec }} second</small>
      <button type="button" class="btn-close ms-2 mb-1" aria-label="Close" @click="closeToast">
        <span aria-hidden="true"></span>
      </button>
    </div>
    <div class="toast-body">{{ body }}</div>
  </div>
</template>

<script setup>
import { ref, onUnmounted } from 'vue'

const { title = 'Bootstrap', body = 'Hello, world! This is a toast message.' } = defineProps({
  title: String,
  body: String,
})

const counterSec = ref(5)
const isVisible = ref(false)
let countdownInterval

const closeToast = () => {
  isVisible.value = false
  clearInterval(countdownInterval)
}

const showToast = () => {
  clearInterval(countdownInterval)
  counterSec.value = 5
  isVisible.value = true

  countdownInterval = setInterval(() => {
    counterSec.value -= 1

    if (counterSec.value <= 0) {
      closeToast()
    }
  }, 1000)
}

defineExpose({ showToast })

onUnmounted(() => clearInterval(countdownInterval))
</script>

<style scoped>
.z-index-higher {
  position: absolute;
  top: -1rem;
  right: 0;
  z-index: 999;
}
</style>
