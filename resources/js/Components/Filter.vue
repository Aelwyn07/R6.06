<!--
/**
 * @component Filter
 * @description Composant de filtrage permettant de naviguer entre différentes valeurs avec des boutons précédent/suivant
 * 
 * @example Avec bordure
 * <Filter 
 *   :items="['Semestre 1', 'Semestre 2', 'Semestre 3']"
 *   hasBorder
 *   @change="handleFilterChange"
 * />
 * 
 * @example Sans bordure
 * <Filter 
 *   :items="['2023', '2024', '2025']"
 *   @change="handleFilterChange"
 * />
 */
-->
<script setup lang="ts">
import IconButton from './IconButton.vue';

const emit = defineEmits(['previous', 'next']);

const props = defineProps<{
    selectedItemName: string,
    hasBorder: boolean
}>()
</script>

<template>
    <div :class="['flex items-center justify-between gap-2', { 'border border-black rounded-xl p-1 shadow-lg bg-gray-100': props.hasBorder }]">
        <IconButton iconClass="ChevronLeft" bgColor="#FFD8E4" small @click="emit('previous')" />
        <p>{{ selectedItemName }}</p>
        <IconButton iconClass="ChevronRight" bgColor="#FFD8E4" small @click="emit('next')" />
    </div>
</template>
