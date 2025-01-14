<script setup lang="ts">
import { ref, watch } from "vue";
import axios from "axios";

import { Group } from "@/types/models";
import { API_ENDPOINTS, MESSAGES } from "@/constants";

import Button from "@/Components/Button.vue";
import GroupPopup from "./GroupPopup.vue";
import DeleteConfirmationPopup from "@/Features/Popup/DeleteConfirmationPopup.vue";
import CloseWithoutSaveConfirmationPopup from "@/Features/Popup/CloseWithoutSaveConfirmationPopup.vue";

const props = defineProps<{ groupToEditId?: number; show?: boolean }>();
const emit = defineEmits(["cancel", "delete", "save", "error"]);

const actualGroup = ref<Group | undefined>();
const editedGroup = ref<Group | undefined>();

const isCloseWithoutSaveConfirmationPopupVisible = ref<boolean>(false);
const isDeleteConfirmationPopupVisible = ref<boolean>(false);

const cloneActualGroup = async () => {
    const response = await axios.get(
        `${API_ENDPOINTS.GROUP}/${props.groupToEditId}`
    );
    actualGroup.value = response.data;
    actualGroup.value && (editedGroup.value = { ...actualGroup.value });
};

watch(
    () => props.show,
    () => props.show && cloneActualGroup()
);

const showCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = true);

const hideCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = false);

const showDeleteConfirmationPopup = () =>
    (isDeleteConfirmationPopupVisible.value = true);

const hideDeleteConfirmationPopup = () =>
    (isDeleteConfirmationPopupVisible.value = false);

const updateGroupName = (groupName: string) =>
    (editedGroup.value!.name = groupName);

const handleCloseWithoutSaving = () => {
    hideCloseWithoutSaveConfirmationPopup();
    emit("cancel");
};

const handleCancel = () =>
    editedGroup.value?.name !== actualGroup.value?.name
        ? showCloseWithoutSaveConfirmationPopup()
        : emit("cancel");

const handleDelete = async () => {
    try {
        const response = await axios.delete(
            `${API_ENDPOINTS.GROUP}/${actualGroup.value!.id}`
        );
        hideDeleteConfirmationPopup();
        emit("delete", response.data.group);
    } catch (error: any) {
        error.response?.data?.error
            ? emit("error", error.response.data.error)
            : emit("error", MESSAGES.DEFAULT_ERROR_MESSAGE);
    }
};

const handleSave = async () => {
    if (editedGroup.value?.name === "") {
        emit("error", MESSAGES.EMPTY_GROUP_NAME_ERROR_MESSAGE);
        return;
    }
    try {
        const response = await axios.put(
            `${API_ENDPOINTS.GROUP}/${editedGroup.value!.id}`,
            editedGroup.value
        );
        emit("save", response.data.group);
    } catch (error: any) {
        error.response?.data?.error
            ? emit("error", error.response.data.error)
            : emit("error", MESSAGES.DEFAULT_ERROR_MESSAGE);
    }
};
</script>

<template>
    <GroupPopup
        :group="editedGroup"
        :show
        title="Modifier un groupe"
        @updateGroupName="updateGroupName"
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
    </GroupPopup>
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
