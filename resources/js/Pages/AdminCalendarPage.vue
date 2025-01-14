<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TeachersListManager from "@/Features/ListManager/TeachersListManager.vue";
import CalendarTable from "@/Calendar/CalendarTable.vue";
import { useCalendarStore } from "@/stores/calendar";
import AddCalendarPopup from "@/Features/Popup/Calendar/AddCalendarPopup.vue";

const teachers = ref([]);
const selectedTeacherIds = ref<number[]>([]);
const calendarStore = useCalendarStore();

// Watch store changes
watch(
    () => calendarStore.showingAddCalendarPopup,
    (showing) => {
        console.log('Popup visibility changed:', showing);
        console.log('Current popup data:', calendarStore.addCalendarPopupData);
    }
);

onMounted(async () => {
    try {
        const teachersResponse = await axios.get("/api/enseignants/1");
        teachers.value = teachersResponse.data
            .filter(
                (teacher: any) =>
                    teacher.teachings && teacher.teachings.length > 0
            )
            .map((teacher: any) => ({
                id: teacher.id,
                name: `${teacher.first_name} ${teacher.last_name}`,
            }));
    } catch (error) {
        console.error("Erreur lors de la récupération des enseignants:", error);
    }
});
</script>

<template>
    <AdminLayout>
        <div class="h-full w-full flex gap-4">
            <TeachersListManager
                :teachers="teachers"
                v-model:selectedTeacherIds="selectedTeacherIds"
                class="w-1/4"
            />
            <div class="w-3/4">
                <CalendarTable />
                <AddCalendarPopup
                    v-if="calendarStore.showingAddCalendarPopup"
                    v-model:show="calendarStore.showingAddCalendarPopup"
                    v-bind="calendarStore.addCalendarPopupData"
                    @close="calendarStore.hideAddCalendarPopup"
                />
            </div>
        </div>
    </AdminLayout>
</template>
