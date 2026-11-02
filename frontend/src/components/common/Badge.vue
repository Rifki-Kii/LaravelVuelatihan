<template>
  <span
    :class="[
      'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
      color === 'success' ? 'bg-green-100 text-green-700' : '',
      color === 'error' ? 'bg-red-100 text-red-700' : '',
      color === 'info' ? 'bg-blue-100 text-blue-700' : '',
      color === 'warning' ? 'bg-yellow-100 text-yellow-700' : '',
      'bg-gray-100 text-gray-700',
    ]"
  >
    {{ label }}
  </span>
</template>

<script>
export default {
  props: {
    label: { type: String, required: true },
    color: { type: String, default: 'gray' },
  },
}
</script>
