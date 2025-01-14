 <script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    biggestCM: number;
    biggestTD: number;
    biggestTP: number;
}>();

const cmWidth = computed(() => `${props.biggestCM * 96}px`);
const tdWidth = computed(() => `${props.biggestTD * 96}px`);
const tpWidth = computed(() => `${props.biggestTP * 96}px`);
</script>

<template>
    <div class="flex w-max">
        <!-- Headers container -->
        <div class="flex">
            <!-- CM Header -->
            <div
                class="flex items-center justify-center font-semibold text-gray-700 bg-yellow-100 border-r-2 border-b-2 border-gray-200 h-12 min-w-96 rounded-tl-xl"
                :style="{ width: cmWidth }"
            >
                CM
            </div>
            <!-- TD Header -->
            <div
                class="flex items-center justify-center font-semibold text-gray-700 bg-red-100 border-r-2 border-b-2 border-gray-200 h-12 min-w-96"
                :style="{ width: tdWidth }"
            >
                TD
            </div>
            <!-- TP Header -->
            <div
                class="flex items-center justify-center font-semibold text-gray-700 bg-blue-100 border-r-2 border-b-2 border-gray-200 h-12 min-w-96 rounded-tr-xl"
                :style="{ width: tpWidth }"
            >
                TP
            </div>
        </div>
    </div>
</template>

<style scoped>
.scrollbar-visible {
    scrollbar-gutter: stable;
}

.scrollbar-visible::-webkit-scrollbar {
    height: 10px;
    display: block;
}

.scrollbar-visible::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.scrollbar-visible::-webkit-scrollbar-thumb {
    background: #9ca3af;
    border-radius: 5px;
}

.scrollbar-visible::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
