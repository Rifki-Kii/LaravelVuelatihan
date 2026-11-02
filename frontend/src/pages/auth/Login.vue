<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { ref } from 'vue'
import Input from '@/components/common/Input.vue'
import Button from '@/components/common/Button.vue'

const auth = useAuthStore()
const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

async function handleSubmit(e) {
  e.preventDefault()
  error.value = ''
  loading.value = true
  try {
    await auth.login({ email: email.value, password: password.value })
    router.push('/dashboard')
  } catch (e) {
    error.value = e.response?.data?.message || 'Email atau kata sandi salah'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow">
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Masuk</h1>
        <p class="text-gray-500 text-sm">Masuk ke akun Anda</p>
      </div>

      <form @submit="handleSubmit" class="space-y-4">
        <Input v-model="email" label="Email" type="email" placeholder="email@example.com" required />
        <Input v-model="password" label="Kata Sandi" type="password" required />

        <p v-if="error" class="text-sm text-red-600 text-center">{{ error }}</p>

        <Button type="submit" class="w-full">Masuk</Button>
      </form>

      <p class="mt-6 text-center text-sm text-gray-600">
        Belum punya akun?
        <router-link to="/register" class="text-indigo-600 font-medium hover:underline">Daftar di sini</router-link>
      </p>
    </div>
  </div>
</template>
