<script setup lang="ts">
import { computed } from 'vue';
import * as icons from "lucide-vue-next";

const props = withDefaults(defineProps<{
  name: string,
  size?: number,
  color?: string,
  strokeWidth?: number
}>(), {
  size: 24,
  color: 'black',
  strokeWidth: 2,
})

const icon = computed(() => icons[props.name as keyof typeof icons]);
</script>

<template>
  <component
    :is="icon"
    :size
    :color
    :strokeWidth
  />
</template>