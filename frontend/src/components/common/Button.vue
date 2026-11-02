<template>
  <button
    type="button"
    :class="[
      'inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded transition',
      variant === 'primary'
        ? 'bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50 disabled:pointer-events-none'
        : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50',
    ]"
    :disabled="disabled"
  >
    <slot />
  </button>
</template>

<script>
export default {
  props: {
    variant: { type: String, default: 'primary' },
    disabled: { type: Boolean, default: false },
  },
}
</script>
