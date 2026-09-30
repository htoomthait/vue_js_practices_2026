<template>
  <div class="container d-flex flex-column justify-content-center login-container">
    <div class="login-box mx-auto d-flex flex-column">
      <div class="text-center"><h1>Contacts Management For CRUD Examplar</h1></div>

      <div class="text-center">
        <h2>Login here</h2>
      </div>

      <div class="login-form">
        <form action="#" @submit.prevent="handleLogin" novalidate>
          <div class="form-group">
            <label for="txtEmail" class="form-label mt-4"> Email:</label>
            <input
              type="text"
              name="email"
              id="txtEmail"
              class="form-control"
              placeholder="Enter Email"
              v-model="loginForm.email"
            />
            <small class="text-danger"></small>
          </div>
          <div class="form-group">
            <label for="txtEmail" class="form-label mt-4"> Password:</label>
            <input
              type="password"
              name="email"
              id="txtEmail"
              class="form-control"
              placeholder="Enter Email"
              v-model="loginForm.password"
            />
            <small class="text-danger"></small>
          </div>
          <div class="form-group mt-4 d-flex justify-content-center">
            <button class="btn btn-primary w-full" type="submit">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import api from '@/services/api'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const loginFormInit = {
  email: '',
  password: '',
}

const validateLoginForm = () => {
  let isFormValid = true

  return isFormValid
}

const loginForm = ref({ ...loginFormInit })
const isFormInit = ref(true)

const handleLogin = async () => {
  isFormInit.value = false
  if (!validateLoginForm()) {
    return
  }

  const dataToPost = { ...loginForm.value }

  try {
    const response = await api.post('/auth/login', dataToPost, {
      headers: { 'Content-Type': 'application/json' },
    })

    if (response.status === 200 && response.data.access_token) {
      const accessToken = response.data.access_token
      localStorage.setItem('access_token', accessToken)
      loginForm.value = { ...loginFormInit }
      isFormInit.value = true
      await router.push({ name: 'home' })
    }
  } catch (error) {
    if (error.response?.status === 500) {
      console.log(error.response.data.message || 'Server error cannot proceed this time.')
    }
  }
}
</script>

<style scoped></style>
