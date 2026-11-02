<script setup>
import { ref, onMounted } from 'vue'
import { useProjectsStore } from '@/stores/projects'
import { projectsApi } from '@/api/projects'
import Button from '@/components/common/Button.vue'
import Badge from '@/components/common/Badge.vue'
import { formatDate } from '@/utils/date'

const projectsStore = useProjectsStore()
const projects = ref([])
const loading = ref(false)
const error = ref('')

onMounted(load)

async function load() {
  loading.value = true
  try {
    const res = await projectsApi.list()
    projects.value = res.data.data || []
    projectsStore.setProjects(projects.value)
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal memuat proyek'
  } finally {
    loading.value = false
  }
}

async function remove(id) {
  if (!confirm('Hapus proyek ini?')) return
  try {
    await projectsApi.delete(id)
    projects.value = projects.value.filter((p) => p.id !== id)
    projectsStore.setProjects(projects.value)
  } catch (e) {
    alert(e.response?.data?.message || 'Gagal menghapus')
  }
}
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Proyek</h1>
      <router-link to="/projects/create" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Tambah Proyek</router-link>
    </div>

    <p v-if="error" class="text-sm text-red-600 mb-4">{{ error }}</p>

    <div v-if="loading" class="text-center py-8">Memuat...</div>

    <table v-else-if="projects.length > 0" class="min-w-full divide-y border">
      <thead>
        <tr>
          <th class="px-4 py-2 text-left text-sm font-semibold">Nama</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Dibuat</th>
          <th class="px-4 py-2 text-right text-sm font-semibold">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in projects" :key="p.id" class="hover:bg-gray-50">
          <td class="px-4 py-3">
            <router-link :to="{ name: 'projects.show', params: { id: p.id } }" class="text-indigo-600 hover:underline font-medium">{{ p.name }}</router-link>
          </td>
          <td class="px-4 py-3"><Badge :label="p.status" color="info" /></td>
          <td class="px-4 py-3 text-sm text-gray-500">{{ p.created_at ? formatDate(p.created_at) : '-' }}</td>
          <td class="px-4 py-3 text-right">
            <button class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700" @click="remove(p.id)">Hapus</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-else class="text-center py-8 text-gray-500">Belum ada proyek.</div>
  </div>
</template>
