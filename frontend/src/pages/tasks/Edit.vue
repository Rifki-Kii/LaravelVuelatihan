<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { tasksApi } from '@/api/tasks'
import { projectsApi } from '@/api/projects'
import { tagsApi } from '@/api/tags'
import Input from '@/components/common/Input.vue'
import Button from '@/components/common/Button.vue'

const route = useRoute()
const router = useRouter()
const id = route.params.id
const project_id = ref('')
const title = ref('')
const description = ref('')
const status = ref('todo')
const priority = ref('medium')
const due_date = ref('')
const selectedTags = ref([])
const projects = ref([])
const tags = ref([])
const error = ref('')
const loading = ref(false)

onMounted(async () => {
  try {
    const [pRes, tRes] = await Promise.all([projectsApi.list(), tagsApi.list()])
    projects.value = pRes.data.data || []
    tags.value = tRes.data.data || []
    const res = await tasksApi.get(id)
    const t = res.data.data
    project_id.value = t.project_id
    title.value = t.title
    description.value = t.description || ''
    status.value = t.status
    priority.value = t.priority
    due_date.value = t.due_date || ''
    selectedTags.value = (t.tags || []).map((tag) => tag.id)
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal memuat tugas'
  }
})

function toggleTag(tagId) {
  const idx = selectedTags.value.indexOf(tagId)
  if (idx === -1) selectedTags.value.push(tagId)
  else selectedTags.value.splice(idx, 1)
}

async function handleSubmit(e) {
  e.preventDefault()
  error.value = ''
  loading.value = true
  try {
    await tasksApi.update(id, {
      title: title.value,
      description: description.value || null,
      status: status.value,
      priority: priority.value,
      due_date: due_date.value || null,
    })
    router.push('/tasks')
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal memperbarui tugas'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Tugas</h1>

    <p v-if="error" class="text-sm text-red-600 mb-4">{{ error }}</p>

    <form @submit="handleSubmit" class="space-y-4 max-w-lg">
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Proyek</label>
        <select v-model="project_id" class="block w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-indigo-500">
          <option value="">Pilih proyek</option>
          <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.name }}</option>
        </select>
      </div>

      <Input v-model="title" label="Judul Tugas" required />
      <Input v-model="description" label="Deskripsi" />

      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <select v-model="status" class="block w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-indigo-500">
            <option value="todo">Todo</option>
            <option value="in_progress">In Progress</option>
            <option value="review">Review</option>
            <option value="done">Done</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
          <select v-model="priority" class="block w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-indigo-500">
            <option value="low">Rendah</option>
            <option value="medium">Sedang</option>
            <option value="high">Tinggi</option>
            <option value="urgent">Mendesak</option>
          </select>
        </div>
      </div>

      <Input v-model="due_date" label="Tanggal Jatuh Tempo" type="date" />

      <Button type="submit" class="w-full">Perbarui Tugas</Button>
    </form>

    <router-link :to="{ name: 'tasks.show', params: { id } }" class="text-sm text-gray-500">Kembali ke detail</router-link>
  </div>
</template>
