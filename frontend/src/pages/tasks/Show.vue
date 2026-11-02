<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { tasksApi } from '@/api/tasks'
import { commentsApi } from '@/api/comments'
import { formatDateTime } from '@/utils/date'
import Input from '@/components/common/Input.vue'
import Button from '@/components/common/Button.vue'

const route = useRoute()
const router = useRouter()
const id = route.params.id
const task = ref(null)
const comments = ref([])
const newComment = ref('')
const loading = ref(false)
const error = ref('')

const statusColors = {
  todo: 'bg-blue-100 text-blue-700',
  in_progress: 'bg-yellow-100 text-yellow-700',
  review: 'bg-purple-100 text-purple-700',
  done: 'bg-green-100 text-green-700',
}

onMounted(async () => {
  loading.value = true
  try {
    const res = await tasksApi.get(id)
    task.value = res.data.data
    await loadComments()
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal memuat tugas'
  } finally {
    loading.value = false
  }
})

async function loadComments() {
  const res = await commentsApi.listByTask(id)
  comments.value = res.data.data || []
}

async function changeStatus(status) {
  try {
    await tasksApi.changeStatus(id, status)
    task.value.status = status
  } catch (e) {
    alert(e.response?.data?.message || 'Gagal mengubah status')
  }
}

async function addComment() {
  if (!newComment.value.trim()) return
  try {
    await commentsApi.create({ task_id: parseInt(id), body: newComment.value })
    newComment.value = ''
    await loadComments()
  } catch (e) {
    alert(e.response?.data?.message || 'Gagal menambahkan komentar')
  }
}

async function removeComment(commentId) {
  if (!confirm('Hapus komentar?')) return
  try {
    await commentsApi.delete(commentId)
    comments.value = comments.value.filter((c) => c.id !== commentId)
  } catch (e) {
    alert(e.response?.data?.message || 'Gagal menghapus komentar')
  }
}
</script>

<template>
  <div v-if="loading" class="text-center py-8">Memuat...</div>

  <div v-else-if="error" class="text-center py-8 text-red-600">{{ error }}</div>

  <div v-else class="max-w-3xl">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ task.title }}</h1>
        <p class="text-gray-500 text-sm">{{ task.description || '-' }}</p>
      </div>
      <div class="flex gap-2">
        <button
          v-for="s in ['todo', 'in_progress', 'review', 'done']"
          :key="s"
          @click="changeStatus(s)"
          :class="[
            'px-3 py-1 rounded text-xs font-medium',
            task.status === s ? 'bg-indigo-600 text-white' : statusColors[s],
          ]"
        >
          {{ s }}
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-white p-4 rounded border shadow-sm">
        <p class="text-sm text-gray-500">Prioritas</p>
        <p class="font-medium">{{ task.priority }}</p>
      </div>
      <div class="bg-white p-4 rounded border shadow-sm">
        <p class="text-sm text-gray-500">Jatuh Tempo</p>
        <p class="font-medium">{{ task.due_date || '-' }}</p>
      </div>
      <div class="bg-white p-4 rounded border shadow-sm">
        <p class="text-sm text-gray-500">Dibuat</p>
        <p class="font-medium text-sm">{{ task.created_at ? formatDateTime(task.created_at) : '-' }}</p>
      </div>
    </div>

    <div class="mb-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-3">Komentar</h2>
      <form @submit.prevent="addComment" class="flex gap-2 mb-4">
        <Input v-model="newComment" label="" placeholder="Ketik komentar..." class="flex-1" />
        <Button type="submit">Kirim</Button>
      </form>
      <ul class="space-y-3">
        <li v-for="c in comments" :key="c.id" class="p-3 border rounded bg-gray-50">
          <p class="text-sm">{{ c.body }}</p>
          <p class="text-xs text-gray-500 mt-1">{{ c.created_at ? formatDateTime(c.created_at) : '-' }}</p>
          <button class="text-xs text-red-600 hover:underline mt-1" @click="removeComment(c.id)">Hapus</button>
        </li>
        <li v-if="comments.length === 0" class="text-gray-400 text-sm italic">Belum ada komentar.</li>
      </ul>
    </div>

    <div class="flex gap-2 mt-6">
      <router-link to="/tasks" class="px-4 py-2 text-gray-600 hover:underline text-sm">Kembali</router-link>
      <router-link :to="{ name: 'tasks.edit', params: { id } }" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Edit</router-link>
    </div>
  </div>
</template>
