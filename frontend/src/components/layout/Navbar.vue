<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { computed } from 'vue'

const auth = useAuthStore()
const router = useRouter()
const user = computed(() => auth.user)

function handleLogout() {
  auth.logout()
  router.push('/login')
}
</script>

<template>
  <nav class="sticky top-0 z-20 flex items-center justify-between px-6 py-3 bg-white border-b">
    <div class="flex items-center gap-3">
      <span class="font-bold text-lg text-indigo-600">TaskFlow</span>
    </div>

    <div class="flex items-center gap-4">
      <span v-if="user" class="text-sm text-gray-600">{{ user.name }}</span>
      <button @click="handleLogout" class="px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded">
        Keluar
      </button>
    </div>
  </nav>
</template>
