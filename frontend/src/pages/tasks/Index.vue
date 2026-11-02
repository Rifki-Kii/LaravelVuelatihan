<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { tasksApi } from '@/api/tasks'
import { tagsApi } from '@/api/tags'
import Badge from '@/components/common/Badge.vue'
import { formatDate } from '@/utils/date'

const route = useRoute()
const projectId = ref(route.query.project_id || '')
const tasks = ref([])
const tags = ref([])
const loading = ref(false)
const error = ref('')
const statusFilter = ref('')

const statusColors = {
  todo: 'bg-blue-100 text-blue-700',
  in_progress: 'bg-yellow-100 text-yellow-700',
  review: 'bg-purple-100 text-purple-700',
  done: 'bg-green-100 text-green-700',
}

onMounted(async () => {
  loading.value = true
  try {
    await tagsApi.list().then((r) => { tags.value = r.data.data || [] })
    await loadTasks()
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal memuat tugas'
  } finally {
    loading.value = false
  }
})

async function loadTasks() {
  const params = {}
  if (projectId.value) params.project_id = projectId.value
  if (statusFilter.value) params.status = statusFilter.value
  const res = await tasksApi.list(params)
  tasks.value = res.data.data || []
}

async function changeStatus(task, status) {
  try {
    await tasksApi.changeStatus(task.id, status)
    const idx = tasks.value.findIndex((t) => t.id === task.id)
    if (idx !== -1) tasks.value[idx].status = status
  } catch (e) {
    alert(e.response?.data?.message || 'Gagal mengubah status')
  }
}

async function removeTask(id) {
  if (!confirm('Hapus tugas ini?')) return
  try {
    await tasksApi.delete(id)
    tasks.value = tasks.value.filter((t) => t.id !== id)
  } catch (e) {
    alert(e.response?.data?.message || 'Gagal menghapus tugas')
  }
}

const filteredTasks = computed(() => {
  if (!statusFilter.value) return tasks.value
  return tasks.value.filter((t) => t.status === statusFilter.value)
})

const tagMap = computed(() => Object.fromEntries(tags.value.map((t) => [t.id, t])))
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Tugas</h1>
      <router-link to="/tasks/create" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Tambah Tugas</router-link>
    </div>

    <p v-if="error" class="text-sm text-red-600 mb-4">{{ error }}</p>

    <div class="flex gap-4 mb-6">
      <select
        v-model="statusFilter"
        @change="loadTasks"
        class="px-3 py-2 border border-gray-300 rounded text-sm focus:ring-indigo-500"
      >
        <option value="">Semua Status</option>
        <option value="todo">Todo</option>
        <option value="in_progress">In Progress</option>
        <option value="review">Review</option>
        <option value="done">Done</option>
      </select>
    </div>

    <div v-if="loading" class="text-center py-8">Memuat...</div>

    <table v-else-if="tasks.length > 0" class="min-w-full divide-y border">
      <thead>
        <tr>
          <th class="px-4 py-2 text-left text-sm font-semibold">Judul</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Proyek</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Prioritas</th>
          <th class="px-4 py-2 text-right text-sm font-semibold">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="t in filteredTasks" :key="t.id" class="hover:bg-gray-50">
          <td class="px-4 py-3">
            <router-link :to="{ name: 'tasks.show', params: { id: t.id } }" class="text-indigo-600 hover:underline font-medium">{{ t.title }}</router-link>
          </td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ t.project_id }}</td>
          <td class="px-4 py-3">
            <span :class="statusColors[t.status] || 'bg-gray-100 text-gray-700'">{{ t.status }}</span>
          </td>
          <td class="px-4 py-3 text-sm">{{ t.priority }}</td>
          <td class="px-4 py-3 text-right">
            <router-link :to="{ name: 'tasks.edit', params: { id: t.id } }" class="text-sm text-indigo-600 hover:underline mr-2">Edit</router-link>
            <button class="text-sm text-red-600 hover:underline" @click="() => removeTask(t.id)">Hapus</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-else class="text-center py-8 text-gray-500">Belum ada tugas.</div>
  </div>
</template>
