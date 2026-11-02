<template>
  <transition name="modal">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
      <div
        class="bg-white rounded-lg shadow-lg w-full max-w-md p-6"
        @click.self="close"
      >
        <h3 class="text-lg font-semibold mb-2">{{ title }}</h3>
        <div class="space-y-4">
          <slot />
        </div>
        <div class="mt-6 flex justify-end gap-3">
          <Button variant="outline" @click="close">Batal</Button>
          <slot name="confirm">
            <Button @click="confirm">{{ confirmLabel }}</Button>
          </slot>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import Button from '@/components/common/Button.vue'

export default {
  components: { Button },
  props: {
    show: { type: Boolean, default: false },
    title: { type: String, default: 'Konfirmasi' },
    confirmLabel: { type: String, default: 'Ya' },
  },
  emits: ['update:show', 'confirm'],
  methods: {
    close() {
      this.$emit('update:show', false)
    },
    confirm() {
      this.$emit('confirm')
      this.close()
    },
  },
}
</script>

<style scoped>
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}
</style>
