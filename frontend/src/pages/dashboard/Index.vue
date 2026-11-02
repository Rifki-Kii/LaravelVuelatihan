<script setup>
import { useAuthStore } from '@/stores/auth'
import { computed } from 'vue'

const auth = useAuthStore()
const user = computed(() => auth.user)
</script>

<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
      <p class="text-gray-500">Selamat datang, {{ user?.name }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-white p-6 rounded-lg shadow border">
        <p class="text-sm text-gray-500">Total Proyek</p>
        <p class="text-3xl font-bold text-gray-800">0</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow border">
        <p class="text-sm text-gray-500">Total Tugas</p>
        <p class="text-3xl font-bold text-gray-800">0</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow border">
        <p class="text-sm text-gray-500">Selesai</p>
        <p class="text-3xl font-bold text-green-600">0</p>
      </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border">
      <p class="text-gray-500 text-sm">Gunakan menu samping untuk mengelola proyek dan tugas.</p>
    </div>
  </div>
</template>
