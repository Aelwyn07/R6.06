<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue';
import { ref } from 'vue';
import ProvisionnalCalendarSlot, { ProvisionnalCalendarSlot as ProvisionnalCalendarSlotType } from '@/Components/ProvisionnalCalendar/ProvisionnalCalendarSlot.vue';


const props = defineProps<{
    message?: string;
    role?: string;
}>();

console.log(props.role);
console.log(props.message);

const slot = ref<ProvisionnalCalendarSlotType>({ items: [
    { teacher: { name: 'John Doe', firstname: 'John', lastname: 'Doe', code: '123456', id: 1 }, substitute: null, time: 1 },
    { teacher: { name: 'Jane Doe', firstname: 'Jane', lastname: 'Doe', code: '123457', id: 2 }, substitute: null, time: 1 },
    { teacher: { name: 'John Doe', firstname: 'John', lastname: 'Doe', code: '123456', id: 1 }, substitute: null, time: 2 },
], subslot: null });
</script>

<template>
    <MainLayout>
        <div v-if="message" class="welcome-message">
            {{ message }}
        </div>
        <h1>Calendrier Prévisionnel Lecteur</h1>
        <ProvisionnalCalendarSlot :slot="slot" :slot-width="100" />
    </MainLayout>
</template>

<style scoped>
.welcome-message {
    background-color: #f3f4f6;
    padding: 1rem;
    margin: 1rem;
    border-radius: 0.5rem;
    text-align: center;
    font-size: 1.1rem;
    color: #374151;
}
</style>