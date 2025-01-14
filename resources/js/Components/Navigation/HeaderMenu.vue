<script setup lang="ts">
import { ref, defineProps } from 'vue';
import IconButton from '../IconButton.vue';
import MenuItem from './MenuItem.vue';
import { MenuItem as MenuItemType } from '@/types/models';

defineProps<{
    items: MenuItemType[]
}>();

const closed = ref(false);

function toggleMenu() {
    closed.value = !closed.value;
}

const currentRoute = ref(window.location.pathname.split('/')[1]);
const currentPath = ref(window.location.pathname.split('/')[2]);
</script>

<template>
    <div :class="['header-menu bg-white rounded-xl shadow-lg p-5 mt-4 relative w-max mx-auto transition-all duration-300', { closed }]">
        <ul class="flex justify-center gap-8">
            <MenuItem
                v-for="item in items"
                :item="item"
                :active="currentPath === item.route"
                @click="$inertia.visit('/' + currentRoute + '/' + item.route)"
            />
        </ul>
        <IconButton
            class="toggle-button bg-gray-100 absolute left-1/2 bottom-0 -translate-x-1/2 translate-y-1/2 shadow-sm"
            :iconClass="closed ? 'ChevronDown' : 'ChevronUp'"
            small
            @click="toggleMenu"
        />
    </div>
  </template>

<style scoped>
.header-menu.closed {
    margin-top: -85px;
}
</style>