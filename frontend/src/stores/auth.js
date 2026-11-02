import { defineStore } from 'pinia'
import api from '@/api/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('access_token') || null,
    isAuthenticated: !!localStorage.getItem('access_token') || false,
  }),

  getters: {
    getUser: (state) => state.user,
  },

  actions: {
    async register({ name, email, password, password_confirmation }) {
      const response = await api.post('/auth/register', {
        name,
        email,
        password,
        password_confirmation,
      })
      this.user = response.data.data
      this.isAuthenticated = true
      return response
    },

    async login({ email, password }) {
      const response = await api.post('/auth/login', { email, password })
      this.user = response.data.data
      this.token = response.data.access_token || null
      this.isAuthenticated = true
      return response
    },

    async logout() {
      try {
        await api.post('/auth/logout')
      } catch (e) {
        // ignore network errors on logout
      }
      this.user = null
      this.token = null
      this.isAuthenticated = false
      localStorage.removeItem('access_token')
    },

    async me() {
      const response = await api.get('/auth/user')
      this.user = response.data.data
      this.isAuthenticated = true
      return response
    },

    async check() {
      try {
        await this.me()
        return true
      } catch (e) {
        this.user = null
        this.isAuthenticated = false
        return false
      }
    },
  },
})
