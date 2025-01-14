<script setup lang="ts">
defineProps<{
    name: string;
    selected?: boolean;
}>();
</script>

<template>
    <button
        :class="[
            'selectionnable-button rounded-2xl p-4 shadow-lg text-left border-2',
            selected
                ? 'bg-red-100 border-red-600 '
                : 'bg-gray-100 border-transparent',
        ]"
        draggable="true"
    >
        <p>{{ name }}</p>
    </button>
</template>
