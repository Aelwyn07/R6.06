<script setup lang="ts">
defineProps<{
    weeksData: any;
}>();
</script>

<template>
    <div class="flex flex-col">
        <div
            v-for="week in weeksData"
            class="border-2 border-r-gray-200 border-b-gray-200 rounded-l-xl flex items-center justify-center w-12 min-h-52 bg-[#FFE5DA]"
        >
            S {{ week.week }}
        </div>
    </div>
</template>
