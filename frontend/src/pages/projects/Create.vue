<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { projectsApi } from '@/api/projects'
import Input from '@/components/common/Input.vue'
import Button from '@/components/common/Button.vue'

const router = useRouter()
const auth = useAuthStore()
const name = ref('')
const description = ref('')
const status = ref('active')
const error = ref('')
const loading = ref(false)

async function handleSubmit(e) {
  e.preventDefault()
  error.value = ''
  loading.value = true
  try {
    await projectsApi.create({
      name: name.value,
      description: description.value || null,
      status: status.value,
    })
    router.push('/projects')
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal membuat proyek'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Buat Proyek</h1>

    <p v-if="error" class="text-sm text-red-600 mb-4">{{ error }}</p>

    <form @submit="handleSubmit" class="space-y-4 max-w-md">
      <Input v-model="name" label="Nama Proyek" placeholder="Nama proyek" required />
      <Input v-model="description" label="Deskripsi" placeholder="Deskripsi proyek" />

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select
          v-model="status"
          class="block w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option value="active">Aktif</option>
          <option value="arsip">Arsip</option>
        </select>
      </div>

      <Button type="submit" class="w-full">Simpan Proyek</Button>
    </form>

    <router-link to="/projects" class="text-sm text-gray-500">Kembali ke daftar proyek</router-link>
  </div>
</template>
