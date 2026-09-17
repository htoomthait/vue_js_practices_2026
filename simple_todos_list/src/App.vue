<script setup>
import { ref, onMounted, computed, watch } from 'vue'

const todos = ref([])
const name = ref('')

const input_content = ref('')
const input_category = ref('')

const todos_asc = computed(() =>
  todos.value.sort((a, b) => {
    return a.created_at - b.created_at
  }),
)

watch(todos, (newTodos) => {
  localStorage.setItem('todos', JSON.stringify(newTodos))
})

watch(
  name,
  (newName) => {
    localStorage.setItem('name', newName)
  },
  {
    deep: true,
  },
)

const addTodo = () => {
  if (input_content.value.trim() === '' || input_category.value === null) {
    return
  }

  const newTodo = {
    id: Date.now(),
    content: input_content.value,
    category: input_category.value,
    completed: false,
    created_at: Date.now(),
  }

  todos.value.push(newTodo)
  input_content.value = ''
  input_category.value = ''
}

const removeTodo = (id) => {
  // console.log('Removing todo with id:', id)
  todos.value = todos.value.filter((todo) => todo.id !== id)
  // console.log('Remaining todos:', todos.value)
}

onMounted(() => {
  const storedTodos = localStorage.getItem('todos')
  if (storedTodos) {
    todos.value = JSON.parse(storedTodos)
  }

  const storedName = localStorage.getItem('name')
  if (storedName) {
    name.value = storedName
  }
})
</script>

<template>
  <!-- Greeting block -->
  <main class="app">
    <section class="greeting">
      <h2 class="title">What's up, <input v-model="name" placeholder="Enter your name" /></h2>
    </section>
    <!-- todo form block -->
    <section class="create-todo">
      <form class="new-todo-form" @submit.prevent="addTodo">
        <h4>What's on your todo list?</h4>
        <input
          type="text"
          name="content"
          id="content"
          placeholder="e.g. make a video"
          v-model="input_content"
        />

        <h4>Pick a category</h4>
        <div class="options">
          <label>
            <input
              type="radio"
              name="category"
              id="category1"
              value="business"
              v-model="input_category"
            />
            <span class="bubble business"></span>
            <div>Business</div>
          </label>

          <label>
            <input
              type="radio"
              name="category"
              id="category2"
              value="personal"
              v-model="input_category"
            />
            <span class="bubble personal"></span>
            <div>Personal</div>
          </label>
        </div>

        <input type="submit" value="Add todo" />
      </form>
    </section>

    <!-- to do list block -->
    <section class="todo-list">
      <h3>TODO LIST</h3>
      <div class="list" id="todo-list">
        <div v-for="todo in todos_asc" :class="`todo-item ${todo.done && 'done'}`" :key="todo.id">
          <label>
            <input type="checkbox" v-model="todo.done" />
            <span :class="`bubble ${todo.category == 'business' ? 'business' : 'personal'}`"></span>
          </label>

          <div class="todo-content">
            <input type="text" v-model="todo.content" />
          </div>

          <div class="actions">
            <button class="delete" @click="removeTodo(todo.id)">Delete</button>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>
