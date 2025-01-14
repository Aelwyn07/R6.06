<script setup lang="ts">
import IconButton from '@/Components/IconButton.vue';

defineProps<{
    title: string;
    show?: boolean;
}>();

const emit = defineEmits(['close']);
</script>

<template>
<div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="rounded-2xl p-6 bg-white flex flex-col gap-4">
        <div class="flex justify-between gap-10 items-center">
            <h1 class="text-2xl font-bold">{{ title }}</h1>
            <IconButton 
                iconClass="X"
                bgColor="#FF3B30"
                iconColor="white" 
                small
                @click="emit('close')"
            />    
        </div>
        <slot />
    </div>
</div>
</template>