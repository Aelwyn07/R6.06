<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TeachersListManager from '../Features/ListManager/TeachersListManager.vue';
import TeachingsListManager from '../Features/ListManager/TeachingsListManager.vue';
import { Teacher, Teaching, Period, EditedItem, EditedItemStatus } from '@/types/models';
import AddTeachingPopup from '@/Features/Popup/Teachings/AddTeachingPopup.vue';
import EditTeachingPopup from '@/Features/Popup/Teachings/EditTeachingPopup.vue';
import Button from '@/Components/Button.vue';
import ErrorPopup from '@/Features/Popup/ErrorPopup.vue';
import SaveConfirmationPopup from '@/Features/Popup/SaveConfirmationPopup.vue';

const teachers = ref<Teacher[]>([]);
const teachings = ref<Teaching[]>([]);
const periods = ref<Period[]>([
    {
        id: 1,
        name: 'Semestre 1'
    },
    {
        id: 2,
        name: 'Semestre 2'
    },
    {
        id: 3,
        name: 'Semestre 3'
    },
    {
        id: 4,
        name: 'Semestre 4'
    },
    {
        id: 5,
        name: 'Semestre 5'
    },
    {
        id: 6,
        name: 'Semestre 6'
    }
]);

const selectedTeacherIds = ref<number[]>([]);
const selectedTeachingIds = ref<number[]>([]);
const errorMessage = ref('');
const isErrorPopupVisible = ref(false);
const modifications = ref<EditedItem[] | undefined>();
const isSaveConfirmationPopupVisible = ref(false);

const fetchModifications = () => {
    modifications.value = [
        ...(teachers.value.find(teacher => selectedTeacherIds.value.includes(teacher.id))?.teachings || [])
            .filter(teaching => !selectedTeachingIds.value.includes(teaching.id))
            .map(teaching => ({
                ...teaching,
                editStatus: EditedItemStatus.DELETED
            })),
        ...selectedTeachingIds.value
            .filter(id => {
                const teaching = teachings.value.find(t => t.id === id);
                return teaching && !teachers.value
                    .find(teacher => selectedTeacherIds.value.includes(teacher.id))
                    ?.teachings?.some(t => t.id === id);
            })
            .map(id => {
                const teaching = teachings.value.find(t => t.id === id)!;
                return {
                    ...teaching,
                    editStatus: EditedItemStatus.ADDED
                };
            })
    ];
}

const handleTeacherSelect = (selectedItemId: number) => {
    if (!selectedTeacherIds.value.length) {
        if (selectedTeacherIds.value.includes(selectedItemId)) {
            selectedTeacherIds.value = selectedTeacherIds.value.filter(id => id !== selectedItemId);
            selectedTeachingIds.value = [];
        } else {
            selectedTeacherIds.value = [selectedItemId];
            selectedTeachingIds.value = teachers.value.find(teacher => teacher.id === selectedItemId)?.teachings.map(teaching => teaching.id) || [];
        }
    } else {
        if (!selectedTeacherIds.value.includes(selectedItemId)) {
            if (modifications.value?.length) {
                showSaveConfirmationPopup();
            } else {
                if (selectedTeacherIds.value.includes(selectedItemId)) {
                    selectedTeacherIds.value = selectedTeacherIds.value.filter(id => id !== selectedItemId);
                    selectedTeachingIds.value = [];
                } else {
                    selectedTeacherIds.value = [selectedItemId];
                    selectedTeachingIds.value = teachers.value.find(teacher => teacher.id === selectedItemId)?.teachings.map(teaching => teaching.id) || [];
                }
            }
        }
    }
}

const handleTeachingSelect = (selectedItemId: number) => {
    if (selectedTeacherIds.value.length) {
        if (selectedTeachingIds.value.includes(selectedItemId)) {
            selectedTeachingIds.value = selectedTeachingIds.value.filter(id => id !== selectedItemId);
        } else {
            selectedTeachingIds.value = [...selectedTeachingIds.value, selectedItemId];
        }
    }
}

const showSaveConfirmationPopup = () => {
    fetchModifications();
    isSaveConfirmationPopupVisible.value = true;
}

const hideSaveConfirmationPopup = () => {
    isSaveConfirmationPopupVisible.value = false;
}

const handleSave = async () => {
    fetchModifications();
    if (modifications.value) {  
        for (const modification of modifications.value) {
            if (modification.editStatus === EditedItemStatus.ADDED) {
                const response = await axios.post(`/api/enseignant/enseignement/${teachers.value.find(teacher => selectedTeacherIds.value.includes(teacher.id))?.id}/${modification.id}`)
            } else if (modification.editStatus === EditedItemStatus.DELETED) {
                const response = await axios.delete(`/api/enseignant/enseignement/${teachers.value.find(teacher => selectedTeacherIds.value.includes(teacher.id))?.id}/${modification.id}`);
            }
        }
    }
}

const handleQuitWithoutSave = () => {
    console.log('quitWithoutSave');
}

const handleCancel = () => {
    selectedTeachingIds.value = teachers.value.find(teacher => teacher.id === selectedTeacherIds.value[0])?.teachings.map(teaching => teaching.id) || [];
}

onMounted(async () => {
    try {
        const teachersResponse = await axios.get('/api/enseignants/1');
        teachers.value = teachersResponse.data.map((teacher: any) => {
            return {
                id: teacher.id,
                name: `${teacher.first_name} ${teacher.last_name}`,
                acronym: teacher.acronym,
                teachings: teacher.teachings.map((teaching: any) => ({
                    id: teaching.id,
                    name: teaching.title,
                    apogee_code: teaching.apogee_code
                }))
            };
        });
    } catch (error: any) {
        if (error?.response?.data?.message) {
            showErrorPopup(errorMessage.value);
        } else {
            showErrorPopup('Une erreur est survenue');
        }
    }

    try {
        const teachingsResponse = await axios.get('/api/enseignements/1');
        teachings.value = teachingsResponse.data.map((teaching: any) => {
            return {
                id: teaching.id,
                name: teaching.title,
                period: periods.value.find((period) => period.id === teaching.semester || teaching.trimester),
                year: teaching.year_id,
                semester: teaching.semester,
                trimester: teaching.trimester,
                apogee_code: teaching.apogee_code,
                initial_cm: teaching.tp_hours_initial,
                initial_td: teaching.td_hours_intial,
                initial_tp: teaching.tp_hours_initial,
                continuing_cm: teaching.cm_hours_continued,
                continuing_td: teaching.td_hours_continued,
                continuing_tp: teaching.tp_hours_continued
            };
        });
    } catch (error: any) {
        if (error?.response?.data?.message) {
            showErrorPopup(errorMessage.value);
        } else {
            showErrorPopup('Une erreur est survenue');
        }
    }
});

const showErrorPopup = (message: string) => {
    errorMessage.value = message;
    isErrorPopupVisible.value = true;
}

const hideErrorPopup = () => {
    isErrorPopupVisible.value = false;
}
</script>

<template>
    <AdminLayout>
        <div class="flex flex-col h-full gap-8 items-end">
            <div class="flex gap-8 flex-1 w-full min-h-0">
                <TeachersListManager
                    class="h-full w-1/3"
                    :teachers
                    :selectedTeacherIds
                    @select="handleTeacherSelect"
                />
                <TeachingsListManager
                    class="h-full w-2/3"
                    :teachings
                    :periods
                    :selectedTeachingIds
                    @select="handleTeachingSelect"
                />
            </div>
            <div class="flex gap-4 min-h-0 h-min">
                <Button class="text-gray-500 underline" @click="handleCancel">Réinitialiser les modifications</Button>
                <Button class="bg-green-400 text-white" @click="handleSave">Valider</Button>
            </div>
        </div>
    </AdminLayout>
    <AddTeachingPopup
        :show="false"
    />
    <EditTeachingPopup
        :show="false"
    />
    <SaveConfirmationPopup
        :show="isSaveConfirmationPopupVisible"
        :modifications="modifications"
        @save="handleSave"
        @quitWithoutSave="handleQuitWithoutSave"
        @cancel="handleCancel"
    />
    <ErrorPopup
        :message="errorMessage"
        :show="isErrorPopupVisible"
        @close="hideErrorPopup"
    />
</template>
