import AddContact from '@/components/AddContact.vue'
import ContactList from '@/components/ContactList.vue'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: ContactList,
    },
    {
      path: '/add-contact',
      name: 'add_contact',
      component: AddContact,
    },
  ],
})

export default router
