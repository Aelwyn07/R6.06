<script setup lang="ts">
import Button from '@/Components/Button.vue';
import CloseWithoutSaveConfirmationPopup from '@/Components/CloseWithoutSaveConfirmationPopup.vue';
import type { Teaching } from '@/types/models';
import { ref } from 'vue';
import TeachingPopup from './TeachingPopup.vue';

const props = defineProps<{ show: boolean }>();
const emit = defineEmits(['close', 'cancel']);

const teaching = ref<Teaching>({
  id: 0,
  semester: 0,
  trimester: 0,
  teachers: [],
  name: '',
  apogee_code: '',
  initial_cm: 0,
  initial_td: 0,
  initial_tp: 0,
  continuing_cm: 0,
  continuing_td: 0,
  continuing_tp: 0
});

const isCloseWithoutSaveConfirmationPopupVisible = ref(false);

const showCloseWithoutSaveConfirmationPopup = () => {
    isCloseWithoutSaveConfirmationPopupVisible.value = true;
};

const hideCloseWithoutSaveConfirmationPopup = () => {
    isCloseWithoutSaveConfirmationPopupVisible.value = false;
};

const handleUpdateTeachingName = (name: string) => {
    teaching.value.name = name;
};

const handleUpdateApogeeCode = (apogeeCode: string) => {
    teaching.value.apogee_code = apogeeCode;
};

const handleUpdateInitialCM = (initialCM: number) => {
    teaching.value.initial_cm = initialCM;
};

const handleUpdateInitialTD = (initialTD: number) => {
    teaching.value.initial_td = initialTD;
};

const handleUpdateInitialTP = (initialTP: number) => {
    teaching.value.initial_tp = initialTP;
};

const handleUpdateContinuingCM = (continuingCM: number) => {
    teaching.value.continuing_cm = continuingCM;
};

const handleUpdateContinuingTD = (continuingTD: number) => {
    teaching.value.continuing_td = continuingTD;
};

const handleUpdateContinuingTP = (continuingTP: number) => {
    teaching.value.continuing_tp = continuingTP;
};

const handleClose = () => {
    if (
        teaching.value.name !== '' ||
        teaching.value.apogee_code !== '' ||
        teaching.value.initial_cm !== 0 ||
        teaching.value.initial_td !== 0 ||
        teaching.value.initial_tp !== 0 ||
        teaching.value.continuing_cm !== 0 ||
        teaching.value.continuing_td !== 0 ||
        teaching.value.continuing_tp !== 0
    ) {
        showCloseWithoutSaveConfirmationPopup();
    } else {
        emit('close');
    }
};

const handleCloseWithoutSaving = () => {
    hideCloseWithoutSaveConfirmationPopup();
    emit('cancel');
};

const handleAdd = () => {
    // TODO : API CALL
};
</script>

<template>
    <TeachingPopup
        title="Ajouter un enseignement"
        :teaching
        :show
        @updateTeachingName="handleUpdateTeachingName"
        @updateApogeeCode="handleUpdateApogeeCode"
        @updateInitialCM="handleUpdateInitialCM"
        @updateInitialTD="handleUpdateInitialTD"
        @updateInitialTP="handleUpdateInitialTP"
        @updateContinuingCM="handleUpdateContinuingCM"
        @updateContinuingTD="handleUpdateContinuingTD"
        @updateContinuingTP="handleUpdateContinuingTP"
        @close="handleClose"
    >
        <Button class="bg-green-500 text-white" @click="handleAdd">Ajouter</Button>
    </TeachingPopup>
    <CloseWithoutSaveConfirmationPopup
        :show="isCloseWithoutSaveConfirmationPopupVisible"
        @close="handleCloseWithoutSaving"
        @cancel="hideCloseWithoutSaveConfirmationPopup"
    />
</template>