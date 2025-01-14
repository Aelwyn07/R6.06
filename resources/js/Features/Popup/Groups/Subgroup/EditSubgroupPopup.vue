<script setup lang="ts">
import { ref, watch } from "vue";
import axios from "axios";

import { Subgroup } from "@/types/models";
import { API_ENDPOINTS, MESSAGES } from "@/constants";

import Button from "@/Components/Button.vue";
import SubgroupPopup from "./SubgroupPopup.vue";
import DeleteConfirmationPopup from "@/Features/Popup/DeleteConfirmationPopup.vue";
import CloseWithoutSaveConfirmationPopup from "@/Features/Popup/CloseWithoutSaveConfirmationPopup.vue";

const props = defineProps<{ subgroupToEditId?: number; show?: boolean }>();
const emit = defineEmits(["cancel", "delete", "save", "error"]);

const actualSubgroup = ref<Subgroup | undefined>();
const editedSubgroup = ref<Subgroup | undefined>();

const isCloseWithoutSaveConfirmationPopupVisible = ref<boolean>(false);
const isDeleteConfirmationPopupVisible = ref<boolean>(false);

const cloneActualSubgroup = async () => {
    const response = await axios.get(
        `${API_ENDPOINTS.SUBGROUP}/${props.subgroupToEditId}`
    );
    actualSubgroup.value = response.data;
    actualSubgroup.value &&
        (editedSubgroup.value = { ...actualSubgroup.value });
};

watch(
    () => props.show,
    () => props.show && cloneActualSubgroup()
);

const showCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = true);

const hideCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = false);

const showDeleteConfirmationPopup = () =>
    (isDeleteConfirmationPopupVisible.value = true);

const hideDeleteConfirmationPopup = () =>
    (isDeleteConfirmationPopupVisible.value = false);

const updateSubgroupName = (subgroupName: string) =>
    (editedSubgroup.value!.name = subgroupName);

const handleCloseWithoutSaving = () => {
    hideCloseWithoutSaveConfirmationPopup();
    emit("cancel");
};

const handleCancel = () =>
    editedSubgroup.value?.name !== actualSubgroup.value?.name
        ? showCloseWithoutSaveConfirmationPopup()
        : emit("cancel");

const handleDelete = async () => {
    try {
        const response = await axios.delete(
            `${API_ENDPOINTS.SUBGROUP}/${actualSubgroup.value!.id}`
        );
        hideDeleteConfirmationPopup();
        emit("delete", response.data.subgroup);
    } catch (error: any) {
        error.response?.data?.error
            ? emit("error", error.response.data.error)
            : emit("error", MESSAGES.DEFAULT_ERROR_MESSAGE);
    }
};

const handleSave = async () => {
    if (editedSubgroup.value?.name === "") {
        emit("error", MESSAGES.EMPTY_GROUP_NAME_ERROR_MESSAGE);
        return;
    }
    try {
        const response = await axios.put(
            `${API_ENDPOINTS.SUBGROUP}/${editedSubgroup.value!.id}`,
            editedSubgroup.value
        );
        emit("save", response.data.subgroup);
    } catch (error: any) {
        error.response?.data?.error
            ? emit("error", error.response.data.error)
            : emit("error", MESSAGES.DEFAULT_ERROR_MESSAGE);
    }
};
</script>

<template>
    <SubgroupPopup
        :subgroup="editedSubgroup"
        :show
        title="Modifier un sous-groupe"
        @updateSubgroupName="updateSubgroupName"
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
    </SubgroupPopup>
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
