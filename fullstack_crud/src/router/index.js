import AddContact from '@/components/AddContact.vue'
import ContactList from '@/components/ContactList.vue'
import EditContact from '@/components/EditContact.vue'
import LoginPage from '@/components/LoginPage.vue'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/home',
      name: 'home',
      component: ContactList,
    },
    {
      path: '/',
      name: 'landing_login',
      component: LoginPage,
    },
    {
      path: '/login',
      name: 'login_page',
      component: LoginPage,
    },
    {
      path: '/add-contact',
      name: 'add_contact',
      component: AddContact,
    },
    {
      path: '/edit-contact/:id',
      name: 'edit_contact',
      component: EditContact,
    },
  ],
})

router.beforeEach((to) => {
  if (['landing_login', 'login_page'].includes(to.name) && localStorage.getItem('access_token')) {
    return { name: 'home' }
  }
})

export default router
