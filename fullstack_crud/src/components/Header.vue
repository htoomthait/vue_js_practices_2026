<template>
  <div>
    <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">{{ title }}</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarColor01"
          aria-controls="navbarColor01"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor01">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <RouterLink to="/home" class="nav-link" active-class="active"> Home </RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink to="/add-contact" class="nav-link" active-class="active">
                Add Contact
              </RouterLink>
            </li>
          </ul>
          <form class="d-flex">
            <!-- <input class="form-control me-sm-2" type="search" placeholder="Search" />
            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button> -->
            <button type="button" class="btn btn-logout" @click="handleLogout">Logout</button>
          </form>
        </div>
      </div>
    </nav>
  </div>
</template>

<script setup>
import api from '@/services/api'
import { useRouter } from 'vue-router'

const router = useRouter()

const handleLogout = async () => {
  const accessToken = localStorage.getItem('access_token')

  try {
    await api.post('/auth/logout', null, {
      headers: accessToken ? { Authorization: `Bearer ${accessToken}` } : {},
    })
  } catch (error) {
    console.error('Logout request failed:', error)
  } finally {
    localStorage.removeItem('access_token')
    await router.push({ name: 'login_page' })
  }
}
</script>

<style scoped>
.btn-logout {
  color: white;
}
</style>
