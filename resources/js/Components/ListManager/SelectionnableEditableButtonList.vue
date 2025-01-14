<script setup lang="ts">
import SelectionnableEditableButton from './SelectionnableEditableButton.vue';
import { Item } from '@/types/models';

const emit = defineEmits(['select', 'edit']);

defineProps<{
    items: Item[];
    selectedItemsId?: number[];
}>();
</script>

<template>
    <div class="flex flex-col flex-end gap-3">
        <SelectionnableEditableButton
            v-for="item in items"
            :item="item"
            :selected="selectedItemsId?.includes(item.id)"
            @select="emit('select', item.id)"
            @edit="emit('edit', item.id)"
        />
    </div>
</template>