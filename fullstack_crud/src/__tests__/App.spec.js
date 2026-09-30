import { afterEach, beforeEach, describe, expect, it } from 'vitest'
import { mount } from '@vue/test-utils'
import LoginPage from '../components/LoginPage.vue'
import App from '../App.vue'
import router from '../router'

describe('App', () => {
  beforeEach(() => {
    localStorage.clear()
  })

  afterEach(() => {
    localStorage.clear()
  })

  it('shows the login page without the header when no access token exists', async () => {
    await router.push('/login')
    const wrapper = mount(App, { global: { plugins: [router] } })

    expect(wrapper.find('nav').exists()).toBe(false)
    expect(wrapper.findComponent(LoginPage).exists()).toBe(true)
  })

  it('redirects visitors with an access token away from the login page', async () => {
    localStorage.setItem('access_token', 'test-token')
    await router.push('/')
    await router.push('/login')

    expect(router.currentRoute.value.name).toBe('home')
  })
})
