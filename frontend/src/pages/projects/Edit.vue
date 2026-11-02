<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { projectsApi } from '@/api/projects'
import Input from '@/components/common/Input.vue'
import Button from '@/components/common/Button.vue'

const route = useRoute()
const router = useRouter()
const id = route.params.id
const name = ref('')
const description = ref('')
const status = ref('active')
const error = ref('')
const loading = ref(false)

onMounted(async () => {
  loading.value = true
  try {
    const res = await projectsApi.get(id)
    const p = res.data.data
    name.value = p.name
    description.value = p.description || ''
    status.value = p.status
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal memuat proyek'
  } finally {
    loading.value = false
  }
})

async function handleSubmit(e) {
  e.preventDefault()
  error.value = ''
  loading.value = true
  try {
    await projectsApi.update(id, {
      name: name.value,
      description: description.value || null,
      status: status.value,
    })
    router.push('/projects')
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal memperbarui proyek'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Proyek</h1>

    <p v-if="error" class="text-sm text-red-600 mb-4">{{ error }}</p>

    <form @submit="handleSubmit" class="space-y-4 max-w-md">
      <Input v-model="name" label="Nama Proyek" required />
      <Input v-model="description" label="Deskripsi" />

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

      <Button type="submit" class="w-full">Perbarui Proyek</Button>
    </form>

    <router-link :to="{ name: 'projects.show', params: { id } }" class="text-sm text-gray-500">Kembali ke detail</router-link>
  </div>
</template>
