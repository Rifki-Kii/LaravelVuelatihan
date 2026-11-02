<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { computed } from 'vue'

const router = useRouter()
const auth = useAuthStore()
const user = computed(() => auth.user)
</script>

<template>
  <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
    <div class="p-6 border-b">
      <div class="flex items-center gap-2">
        <span class="text-indigo-400">▲</span>
        <span class="font-semibold">TaskFlow</span>
      </div>
    </div>

    <nav class="p-4 space-y-1">
      <a
        v-for="item in menu"
        :key="item.name"
        :class="[
          'block px-4 py-3 rounded text-sm',
          $route.name === item.name ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800',
        ]"
        @click="router.push({ name: item.name })"
      >
        {{ item.label }}
      </a>
    </nav>
  </aside>
</template>

<script>
export default {
  computed: {
    menu() {
      const base = [
        { name: 'dashboard', label: 'Dashboard' },
        { name: 'projects.index', label: 'Proyek' },
        { name: 'tasks.index', label: 'Tugas' },
      ]
      return base
    },
  },
}
</script>
