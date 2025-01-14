<script setup lang="ts">
import { SlotType } from "@/types/models";
defineProps<{
    content: any;
    contentType: SlotType | null;
}>();
</script>

<template>
    <div
        :class="[
            ' h-full px-2 flex items-center justify-center border-r-2 border-r-gray-200',
            {
                'bg-yellow-200': contentType === SlotType.CM,
                'bg-red-200': contentType === SlotType.TD,
                'bg-blue-200': contentType === SlotType.TP,
                neutralized: content.isNeutralized,
            },
        ]"
        :style="{ width: `${content.hours * 96}px` }"
    >
        <p
            class="text-gray-500 text-ellipsis overflow-hidden text-nowrap text-sm font-semibold"
        >
            {{ content.teacherCode }} - {{ content.hours }}h
        </p>
    </div>
</template>

<style scoped>
.neutralized {
    filter: brightness(0.8);
    background-image: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 10px,
        rgba(255, 150, 150, 1) 10px,
        rgba(255, 150, 150, 1) 12px
    );
}
</style>
