<script setup lang="ts">
import Sidebar from "@/Components/Navigation/Sidebar.vue";
import { sidebarMenuItems } from "@/config/navigation";
import { Head } from "@inertiajs/vue3";
import { useLabelsStore } from "@/stores/labelsStore";
import { onMounted } from "vue";

const labelsStore = useLabelsStore();

onMounted(async () => {
    await labelsStore.fetchLabels();
});
</script>

<template>
    <Head title="Calendrier prévisionnel" />
    <div class="main-layout w-screen h-screen bg-gray-100 flex">
        <Sidebar :items="sidebarMenuItems" />
        <div class="main-content m-10">
            <slot />
        </div>
    </div>
</template>

<style scoped>
.main-content {
    width: calc(100% - 80px);
    height: calc(100% - 80px);
}
</style>
