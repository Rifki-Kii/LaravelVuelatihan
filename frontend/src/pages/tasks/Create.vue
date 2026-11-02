<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { tasksApi } from '@/api/tasks'
import { projectsApi } from '@/api/projects'
import { tagsApi } from '@/api/tags'
import Input from '@/components/common/Input.vue'
import Button from '@/components/common/Button.vue'

const router = useRouter()
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
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal memuat data'
  }
})

function toggleTag(id) {
  const idx = selectedTags.value.indexOf(id)
  if (idx === -1) selectedTags.value.push(id)
  else selectedTags.value.splice(idx, 1)
}

async function handleSubmit(e) {
  e.preventDefault()
  error.value = ''
  loading.value = true
  try {
    await tasksApi.create({
      project_id: parseInt(project_id.value),
      title: title.value,
      description: description.value || null,
      status: status.value,
      priority: priority.value,
      due_date: due_date.value || null,
      tag_ids: selectedTags.value,
    })
    router.push('/tasks')
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal membuat tugas'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Buat Tugas</h1>

    <p v-if="error" class="text-sm text-red-600 mb-4">{{ error }}</p>

    <form @submit="handleSubmit" class="space-y-4 max-w-lg">
      <Input v-model="project_id" label="Proyek" type="number" required />

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Proyek</label>
        <select v-model="project_id" class="block w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-indigo-500 focus:border-indigo-500">
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

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Tag</label>
        <div class="flex flex-wrap gap-2">
          <button
            v-for="tag in tags"
            :key="tag.id"
            @click="toggleTag(tag.id)"
            :class="[
              'px-3 py-1.5 rounded text-sm border',
              selectedTags.value.includes(tag.id)
                ? 'bg-indigo-600 text-white border-indigo-600'
                : 'bg-white text-gray-700 border-gray-300 hover:border-indigo-400',
            ]"
          >
            {{ tag.name }}
          </button>
        </div>
      </div>

      <Button type="submit" class="w-full">Simpan Tugas</Button>
    </form>

    <router-link to="/tasks" class="text-sm text-gray-500">Kembali ke daftar tugas</router-link>
  </div>
</template>
