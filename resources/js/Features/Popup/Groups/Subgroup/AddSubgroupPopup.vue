<script setup lang="ts">
import { ref, watch } from "vue";
import axios from "axios";

import { Subgroup } from "@/types/models";
import { API_ENDPOINTS, MESSAGES } from "@/constants";

import Button from "@/Components/Button.vue";
import SubgroupPopup from "./SubgroupPopup.vue";
import CloseWithoutSaveConfirmationPopup from "@/Features/Popup/CloseWithoutSaveConfirmationPopup.vue";

const emit = defineEmits(["cancel", "add", "error"]);

const props = defineProps<{ show?: boolean; groupId?: number }>();

const subgroup = ref<Subgroup>({ id: 0, name: "" });
const isCloseWithoutSaveConfirmationPopupVisible = ref<boolean>(false);

const resetSubgroup = () => (subgroup.value = { id: 0, name: "" });

watch(
    () => props.show,
    () => props.show && resetSubgroup()
);

const showCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = true);

const hideCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = false);

const updateSubgroupName = (newSubgroupName: string) =>
    (subgroup.value.name = newSubgroupName);

const handleCancel = () =>
    subgroup.value.name !== ""
        ? showCloseWithoutSaveConfirmationPopup()
        : emit("cancel");

const handleCloseWithoutSaving = () => {
    hideCloseWithoutSaveConfirmationPopup();
    emit("cancel");
};

const handleAdd = async () => {
    if (subgroup.value.name === "") {
        emit("error", MESSAGES.EMPTY_GROUP_NAME_ERROR_MESSAGE);
        return;
    }
    try {
        const response = await axios.post(
            `${API_ENDPOINTS.SUBGROUP}/${props.groupId}`,
            subgroup.value
        );
        emit("add", response.data.subgroup);
    } catch (error: any) {
        error.response?.data?.error
            ? emit("error", error.response.data.error)
            : emit("error", MESSAGES.DEFAULT_ERROR_MESSAGE);
    }
};
</script>

<template>
    <SubgroupPopup
        :show
        :subgroup
        title="Ajouter un sous-groupe"
        @updateSubgroupName="updateSubgroupName"
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
    </SubgroupPopup>
</template>
