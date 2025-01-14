<script setup lang="ts">
import { ref, watch } from "vue";
import axios from "axios";

import { Class } from "@/types/models";
import { API_ENDPOINTS, MESSAGES } from "@/constants";

import Button from "@/Components/Button.vue";
import ClassPopup from "./ClassPopup.vue";
import CloseWithoutSaveConfirmationPopup from "@/Features/Popup/CloseWithoutSaveConfirmationPopup.vue";

const props = defineProps<{ show?: boolean; yearId?: number }>();
const emit = defineEmits(["cancel", "add", "error"]);

const classe = ref<Class>({ id: 0, name: "", groups: [] });
const isCloseWithoutSaveConfirmationPopupVisible = ref<boolean>(false);

const resetClass = () => (classe.value = { id: 0, name: "", groups: [] });

watch(
    () => props.show,
    () => props.show && resetClass()
);

const showCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = true);

const hideCloseWithoutSaveConfirmationPopup = () =>
    (isCloseWithoutSaveConfirmationPopupVisible.value = false);

const handleUpdateClassName = (newClassName: string) =>
    (classe.value.name = newClassName);

const handleCancel = () =>
    classe.value.name !== ""
        ? showCloseWithoutSaveConfirmationPopup()
        : emit("cancel");

const handleCloseWithoutSaving = () => {
    hideCloseWithoutSaveConfirmationPopup();
    emit("cancel");
};

const handleAdd = async () => {
    if (classe.value.name === "") {
        emit("error", MESSAGES.EMPTY_GROUP_NAME_ERROR_MESSAGE);
        return;
    }
    try {
        const response = await axios.post(
            `${API_ENDPOINTS.PROMOTION}/1`,
            classe.value
        );
        emit("add", response.data.promotion);
    } catch (error: any) {
        error.response?.data?.error
            ? emit("error", error.response.data.error)
            : emit("error", MESSAGES.DEFAULT_ERROR_MESSAGE);
    }
};
</script>

<template>
    <ClassPopup
        :show
        :classe
        title="Ajouter une promotion"
        @updateClassName="handleUpdateClassName"
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
    </ClassPopup>
</template>
