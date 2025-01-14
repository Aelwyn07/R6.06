<script setup lang="ts">
import { ref, watch } from "vue";
import axios from "axios";

import { Class } from "@/types/models";
import { API_ENDPOINTS, MESSAGES } from "@/constants";

import Button from "@/Components/Button.vue";
import ClassPopup from "./ClassPopup.vue";
import DeleteConfirmationPopup from "@/Features/Popup/DeleteConfirmationPopup.vue";
import CloseWithoutSaveConfirmationPopup from "@/Features/Popup/CloseWithoutSaveConfirmationPopup.vue";

const props = defineProps<{ classToEditId?: number; show?: boolean }>();
const emit = defineEmits(["cancel", "delete", "save", "error"]);

const actualClass = ref<Class | undefined>();
const editedClass = ref<Class | undefined>();

const isCloseWithoutSaveConfirmationPopupVisible = ref<boolean>(false);
const isDeleteConfirmationPopupVisible = ref<boolean>(false);

const cloneActualClass = async () => {
    const response = await axios.get(
        `${API_ENDPOINTS.PROMOTION}/${props.classToEditId}`
    );
    actualClass.value = response.data;
    actualClass.value && (editedClass.value = { ...actualClass.value });
};

watch(
    () => props.show,
    () => props.show && cloneActualClass()
);

const showCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = true);

const hideCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = false);

const showDeleteConfirmationPopup = () =>
    (isDeleteConfirmationPopupVisible.value = true);

const hideDeleteConfirmationPopup = () =>
    (isDeleteConfirmationPopupVisible.value = false);

const updateClassName = (groupName: string) =>
    (editedClass.value!.name = groupName);

const handleCloseWithoutSaving = () => {
    hideCloseWithoutSaveConfirmationPopup();
    emit("cancel");
};

const handleCancel = () =>
    editedClass.value?.name !== actualClass.value?.name
        ? showCloseWithoutSaveConfirmationPopup()
        : emit("cancel");

const handleDelete = async () => {
    try {
        const response = await axios.delete(
            `${API_ENDPOINTS.PROMOTION}/${actualClass.value!.id}`
        );
        hideDeleteConfirmationPopup();
        emit("delete", response.data.promotion);
    } catch (error: any) {
        error.response?.data?.error
            ? emit("error", error.response.data.error)
            : emit("error", MESSAGES.DEFAULT_ERROR_MESSAGE);
    }
};

const handleSave = async () => {
    if (editedClass.value?.name === "") {
        emit("error", MESSAGES.EMPTY_GROUP_NAME_ERROR_MESSAGE);
        return;
    }
    try {
        const response = await axios.put(
            `${API_ENDPOINTS.PROMOTION}/${editedClass.value!.id}`,
            editedClass.value
        );
        emit("save", response.data.promotion);
    } catch (error: any) {
        error.response?.data?.error
            ? emit("error", error.response.data.error)
            : emit("error", MESSAGES.DEFAULT_ERROR_MESSAGE);
    }
};
</script>

<template>
    <ClassPopup
        :classe="editedClass"
        :show
        title="Modifier une promotion"
        @updateClassName="updateClassName"
        @close="handleCancel"
    >
        <div class="flex gap-4">
            <Button class="bg-green-500 text-white w-full" @click="handleSave"
                >Sauvegarder</Button
            >
            <Button
                class="bg-red-500 text-white w-full"
                @click="showDeleteConfirmationPopup"
                >Supprimer</Button
            >
        </div>
    </ClassPopup>
    <CloseWithoutSaveConfirmationPopup
        :show="isCloseWithoutSaveConfirmationPopupVisible"
        @close="handleCloseWithoutSaving"
        @cancel="hideCloseWithoutSaveConfirmationPopup"
    />
    <DeleteConfirmationPopup
        :show="isDeleteConfirmationPopupVisible"
        @delete="handleDelete"
        @cancel="hideDeleteConfirmationPopup"
    />
</template>
