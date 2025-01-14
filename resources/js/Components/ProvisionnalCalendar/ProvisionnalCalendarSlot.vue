<script setup lang="ts">
import { Teacher } from '@/types/models';
import { computed, defineProps, ref } from 'vue';

export interface ProvisionnalCalendarItem {
    teacher: Teacher;
    substitute: Teacher | null;
    time: number;
}

export interface ProvisionnalCalendarSlot {
    items: ProvisionnalCalendarItem[];
    subslot: ProvisionnalCalendarSlot | null;
}

const props = defineProps<{
    slot: ProvisionnalCalendarSlot;
}>();

const slotItemWidth = computed(() => {
    return props.slot.items.reduce((sum, item) => sum + item.time, 0) * 100;
});
</script>

<template>
    <div class="bg-white border-r-2 h-full flex" :style="{ '--slot-item-width': `${slotItemWidth}px` }">
        <div class="flex">
            <div v-for="item in slot.items" class="slot-item flex flex-col items-center justify-center">
                <p>{{ item.teacher.name }}</p>
                <p v-if="item.substitute">{{ item.substitute.name }}</p>
                <p>{{ item.time }}h</p>
            </div>
        </div>
        <ProvisionnalCalendarSlot v-if="slot.subslot" :slot="slot.subslot" :slot-width="100" />
    </div>
</template>

<style scoped>
.slot-item {
    width: var(--slot-item-width);
}
</style>
