<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { projectsApi } from '@/api/projects'
import { tasksApi } from '@/api/tasks'
import Badge from '@/components/common/Badge.vue'
import { formatDate } from '@/utils/date'

const route = useRoute()
const router = useRouter()
const id = route.params.id
const project = ref(null)
const tasks = ref([])
const loading = ref(false)
const error = ref('')

onMounted(async () => {
  loading.value = true
  try {
    const [pRes, tRes] = await Promise.all([projectsApi.get(id), tasksApi.list({ project_id: id })])
    project.value = pRes.data.data
    tasks.value = tRes.data.data || []
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal memuat detail proyek'
  } finally {
    loading.value = false
  }
})

async function removeTask(id) {
  if (!confirm('Hapus tugas ini?')) return
  try {
    await tasksApi.delete(id)
    tasks.value = tasks.value.filter((t) => t.id !== id)
  } catch (e) {
    alert(e.response?.data?.message || 'Gagal menghapus tugas')
  }
}
</script>

<template>
  <div v-if="loading" class="text-center py-8">Memuat...</div>

  <div v-else-if="error" class="text-center py-8 text-red-600">{{ error }}</div>

  <div v-else class="max-w-2xl">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ project.name }}</h1>
        <p v-if="project.description" class="text-gray-500">{{ project.description }}</p>
      </div>
      <router-link to="/tasks/create" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Tambah Tugas</router-link>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-white p-4 rounded border shadow-sm">
        <p class="text-sm text-gray-500">Status</p>
        <Badge :label="project.status" />
      </div>
    </div>

    <div class="mb-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-3">Daftar Tugas</h2>
      <table class="min-w-full divide-y border">
        <thead>
          <tr>
            <th class="px-4 py-2 text-left text-sm font-semibold">Judul</th>
            <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
            <th class="px-4 py-2 text-left text-sm font-semibold">Prioritas</th>
            <th class="px-4 py-2 text-right text-sm font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="t in tasks" :key="t.id" class="hover:bg-gray-50">
            <td class="px-4 py-3">
              <router-link :to="{ name: 'tasks.show', params: { id: t.id } }" class="text-indigo-600 hover:underline font-medium">{{ t.title }}</router-link>
            </td>
            <td class="px-4 py-3"><Badge :label="t.status" /></td>
            <td class="px-4 py-3 text-sm">{{ t.priority }}</td>
            <td class="px-4 py-3 text-right">
              <button class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700" @click="removeTask(t.id)">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
      <p v-if="tasks.length === 0" class="text-gray-500 text-sm">Belum ada tugas.</p>
    </div>

    <router-link to="/projects" class="text-sm text-gray-500">Kembali ke daftar proyek</router-link>
  </div>
</template>
