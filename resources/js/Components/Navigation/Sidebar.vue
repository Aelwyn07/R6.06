<script setup lang="ts">
import { ref } from 'vue';
import MenuItem from './MenuItem.vue';
import IconButton from '../IconButton.vue';
import { MenuItem as MenuItemType } from '@/types/models';

defineProps<{
    items: MenuItemType[],
}>();

const closed = ref(false);

function toggleSidebar() {
    closed.value = !closed.value;
}

const currentPath = ref(window.location.pathname.split('/')[1]);

</script>

<template>
    <div :class="['sidebar bg-white shadow-lg p-5 ml-4 my-auto rounded-xl relative transition-all duration-300 w-min flex flex-col gap-40', { closed }]">
        <ul class="flex flex-col justify-center gap-8 h-min">
            <MenuItem
                v-for="item in items"
                :item="item"
                :active="currentPath === item.route"
                @click="$inertia.visit('/' + item.route)"
            />
        </ul>
        <MenuItem
            :item="{ iconClass: 'Power', label: 'Déconnexion', route: '/logout' }"
            :active="false"
            @click="$inertia.visit('/logout')"
        />
        <IconButton
            class="toggle-button bg-gray-100 absolute right-0 top-1/2 translate-x-1/2 -translate-y-1/2 shadow-sm"
            :iconClass="closed ? 'ChevronRight' : 'ChevronLeft'"
            small
            @click="toggleSidebar"
        />
    </div>
</template>

<style scoped>
.sidebar.closed {
    margin-left: -110px;
}
</style>