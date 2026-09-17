<script setup>
import { ref } from 'vue'
const count = ref(0)

const increment = () => {
  count.value++
}

const decrement = () => {
  count.value--
}
</script>

<template>
  <h1>You did it!</h1>
  <p>
    Visit <a href="https://vuejs.org/" target="_blank" rel="noopener">vuejs.org</a> to read the
    documentation
  </p>
  <div class="counter_block">
    <div class="counter_display">
      <h2>Counter</h2>
      <p>Count: {{ count }}</p>
    </div>
    <div class="counter_actions">
      <button @click="increment">Increment</button>
      <button @click="decrement">Decrement</button>
    </div>
  </div>
</template>

<style scoped>
.counter_block {
  display: flex;
  flex-direction: column;
  padding: 16px;
  border: 1px solid #ccc;
  width: 300px;
}
.counter_block > .counter_display {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.counter_block > .counter_actions {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-around;
}

.counter_block > .counter_actions > button {
  padding: 8px 16px;
  font-size: 16px;
  cursor: pointer;
}
.counter_block > .counter_actions > button:hover {
  background-color: #f0f0f0;
}
</style>
