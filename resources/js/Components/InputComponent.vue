<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  label: {
    type: String,
    default: ''
  },
  modelValue: {
    type: [String, Number],
    default: ''
  },
  error: {
    type: String,
    default: ''
  },
  id: {
    type: String,
    default: () => `input-${Math.random().toString(36).substr(2, 9)}`
  },
  showLabel: {
    type: Boolean,
    default: false
  },
  showError: {
    type: Boolean,
    default: false
  }
})

defineEmits(['update:modelValue'])
</script>

<template>
  <div class="input-wrapper max-w-xs">
    <label v-if="label" :for="id" class="block text-sm font-medium text-black mb-1">
      {{ label }}
    </label>
    
    <input
      :id="id"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      class="w-full px-3 py-2 border border-black-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
      v-bind="$attrs"
    >

    <p v-if="error" class="mt-1 text-sm text-red-600 font-bold">
      {{ error }}
    </p>
  </div>
</template>

