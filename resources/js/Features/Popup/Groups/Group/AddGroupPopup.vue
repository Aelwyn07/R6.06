<script setup lang="ts">
import { ref, watch } from "vue";
import axios from "axios";

import { Group } from "@/types/models";
import { API_ENDPOINTS, MESSAGES } from "@/constants";

import Button from "@/Components/Button.vue";
import GroupPopup from "./GroupPopup.vue";
import CloseWithoutSaveConfirmationPopup from "@/Features/Popup/CloseWithoutSaveConfirmationPopup.vue";

const props = defineProps<{ show?: boolean; classId?: number }>();
const emit = defineEmits(["cancel", "add", "error"]);

const group = ref<Group>({ id: 0, name: "", subgroups: [] });
const isCloseWithoutSaveConfirmationPopupVisible = ref<boolean>(false);

const resetGroup = () => (group.value = { id: 0, name: "", subgroups: [] });

watch(
    () => props.show,
    () => props.show && resetGroup()
);

const showCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = true);

const hideCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = false);

const handleUpdateGroupName = (newGroupName: string) =>
    (group.value.name = newGroupName);

const handleCancel = () =>
    group.value.name !== ""
        ? showCloseWithoutSaveConfirmationPopup()
        : emit("cancel");

const handleCloseWithoutSaving = () => {
    hideCloseWithoutSaveConfirmationPopup();
    emit("cancel");
};

const handleAdd = async () => {
    if (group.value.name === "") {
        emit("error", MESSAGES.EMPTY_GROUP_NAME_ERROR_MESSAGE);
        return;
    }
    try {
        const response = await axios.post(
            `${API_ENDPOINTS.GROUP}/${props.classId}`,
            group.value
        );
        emit("add", response.data.group);
    } catch (error: any) {
        error.response?.data?.error
            ? emit("error", error.response.data.error)
            : emit("error", MESSAGES.DEFAULT_ERROR_MESSAGE);
    }
};
</script>

<template>
    <GroupPopup
        :show
        :group
        title="Ajouter un groupe"
        @updateGroupName="handleUpdateGroupName"
        @close="handleCancel"
    >
        <div class="flex gap-4">
            <Button class="bg-green-500 text-white w-full" @click="handleAdd"
                >Ajouter</Button
            >
        </div>
        <CloseWithoutSaveConfirmationPopup
            :show="isCloseWithoutSaveConfirmationPopupVisible"
            @close="handleCloseWithoutSaving"
            @cancel="hideCloseWithoutSaveConfirmationPopup"
        />
    </GroupPopup>
</template>
