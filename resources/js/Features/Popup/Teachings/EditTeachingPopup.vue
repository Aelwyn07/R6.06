<script setup lang="ts">
import TeachingPopup from './TeachingPopup.vue';
import Button from '@/Components/Button.vue';
import DeleteConfirmationPopup from '@/Components/DeleteConfirmationPopup.vue';
import CloseWithoutSaveConfirmationPopup from '@/Components/CloseWithoutSaveConfirmationPopup.vue';
import type { Teaching } from '@/types/models';
import { ref, watch } from 'vue';

const props = defineProps<{
  teaching?: Teaching;
  show: boolean;
}>();

const emit = defineEmits(['cancel', 'update', 'delete']);

const editedTeaching = ref<Teaching>({ ...props.teaching });
const isCloseWithoutSaveConfirmationPopupVisible = ref<boolean>(false);
const isDeleteConfirmationPopupVisible = ref<boolean>(false);

const showCloseWithoutSaveConfirmationPopup = () => {
    isCloseWithoutSaveConfirmationPopupVisible.value = true;
};

const hideCloseWithoutSaveConfirmationPopup = () => {
    isCloseWithoutSaveConfirmationPopupVisible.value = false;
};

const showDeleteConfirmationPopup = () => {
    isDeleteConfirmationPopupVisible.value = true;
};

const hideDeleteConfirmationPopup = () => {
    isDeleteConfirmationPopupVisible.value = false;
};

watch(() => props.show, () => {
    if (props.show) {
        editedTeaching.value = { ...props.teaching };
    }
});

const handleUpdateTeachingName = (name: string) => {
    editedTeaching.value.name = name;
};

const handleUpdateApogeeCode = (apogeeCode: string) => {
    editedTeaching.value.apogee_code = apogeeCode;
};

const handleUpdateInitialCM = (initialCM: number) => {
    editedTeaching.value.initial_cm = initialCM;
};

const handleUpdateInitialTD = (initialTD: number) => {
    editedTeaching.value.initial_td = initialTD;
};

const handleUpdateInitialTP = (initialTP: number) => {
    editedTeaching.value.initial_tp = initialTP;
};

const handleUpdateContinuingCM = (continuingCM: number) => {
    editedTeaching.value.continuing_cm = continuingCM;
};

const handleUpdateContinuingTD = (continuingTD: number) => {
    editedTeaching.value.continuing_td = continuingTD;
};

const handleUpdateContinuingTP = (continuingTP: number) => {
    editedTeaching.value.continuing_tp = continuingTP;
};

const handleClose = () => {
    if (
        editedTeaching.value.name !== props.teaching.name ||
        editedTeaching.value.apogee_code !== props.teaching.apogee_code ||
        editedTeaching.value.initial_cm !== props.teaching.initial_cm ||
        editedTeaching.value.initial_td !== props.teaching.initial_td ||
        editedTeaching.value.initial_tp !== props.teaching.initial_tp ||
        editedTeaching.value.continuing_cm !== props.teaching.continuing_cm ||
        editedTeaching.value.continuing_td !== props.teaching.continuing_td ||
        editedTeaching.value.continuing_tp !== props.teaching.continuing_tp
    ) {
        showCloseWithoutSaveConfirmationPopup();
    } else {
        emit('cancel');
    }
};

const handleCloseWithoutSaving = () => {
    hideCloseWithoutSaveConfirmationPopup();
    emit('cancel');
};

const handleSave = () => {
    // TODO : API CALL
};

const handleDelete = () => {
    // TODO : API CALL
};
</script>

<template>
    <TeachingPopup
        title="Modifier un enseignement"
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
        <div class="flex gap-4">
            <Button class="bg-green-500 text-white w-full" @click="handleSave">Sauvegarder</Button>
            <Button class="bg-red-500 text-white w-full" @click="showDeleteConfirmationPopup">Supprimer</Button>
        </div>
    </TeachingPopup>
    <DeleteConfirmationPopup
        :show="isDeleteConfirmationPopupVisible"
        @cancel="hideDeleteConfirmationPopup"
        @delete="handleDelete"
    />
    <CloseWithoutSaveConfirmationPopup
        :show="isCloseWithoutSaveConfirmationPopupVisible"
        @close="handleCloseWithoutSaving"
        @cancel="hideCloseWithoutSaveConfirmationPopup"
    />
</template>