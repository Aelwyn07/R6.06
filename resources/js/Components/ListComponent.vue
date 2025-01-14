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
    default: () => `select-${Math.random().toString(36).substr(2, 9)}`
  },
  showLabel: {
    type: Boolean,
    default: false
  },
  showError: {
    type: Boolean,
    default: false
  },
  options: {
    type: Array,
    required: true
  },
  optionLabel: {
    type: String,
    default: 'label'
  },
  optionValue: {
    type: String,
    default: 'value'
  }
})

defineEmits(['update:modelValue'])
</script>

<template>
  <div class="select-wrapper max-w-xs">
    <label 
      v-if="label" 
      :for="id" 
      class="block text-sm font-medium text-black font-bold mb-1"
    >
      {{ label }}
    </label>
    
    <select
    :id="id"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    :class="[
      'px-3 py-2 border border-black rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8',
      modelValue === '' ? 'text-gray-500' : 'text-black'
    ]"
    v-bind="$attrs"
  >
    <option value="">Sélectionnez une option</option>
    <option 
      v-for="option in options" 
      :key="option[optionValue]" 
      :value="option[optionValue]"
    >
      {{ option[optionLabel] }}
    </option>
  </select>

    <p v-if="error" class="mt-1 text-sm text-red-600 font-bold">
      {{ error }}
    </p>
  </div>
</template> 