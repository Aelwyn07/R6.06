<script setup lang="ts">
import Popup from '@/Components/Popup/Popup.vue';
import { Group } from '@/types/models';
import { ref, computed, watch } from 'vue';
import CloseWithoutSaveConfirmationPopup from '../../../../Features/Popup/CloseWithoutSaveConfirmationPopup.vue';

const props = defineProps<{
    title: string;
    groupId?: number;
    groups?: Group[];
    show?: boolean;
}>();

const emit = defineEmits(['close']);

const groupName = ref<string | undefined>(props.groups?.find(group => group.id === props.groupId)?.name);
const isCopyingFromAnotherGroup = ref<boolean>(false);
const selectedGroupToCopyId = ref<number | undefined>();
const showCloseConfirmationPopup = ref<boolean>(false);

watch(isCopyingFromAnotherGroup, () => {
    selectedGroupToCopyId.value = undefined;
});

const handleClose = () => {
    console.log(groupName.value, props.groups?.find(group => group.id === props.groupId)?.name);
    if (
        groupName.value !== props.groups?.find(group => group.id === props.groupId)?.name
        || isCopyingFromAnotherGroup.value
    ) {
        showCloseConfirmationPopup.value = true;
    } else {
        showCloseConfirmationPopup.value = false;
        isCopyingFromAnotherGroup.value = false;
        selectedGroupToCopyId.value = undefined;
        emit('close');
    }
};

const handleCloseWithoutSaving = () => {
    showCloseConfirmationPopup.value = false;
    isCopyingFromAnotherGroup.value = false;
    selectedGroupToCopyId.value = undefined;
    emit('close');
};

const handleCancel = () => {
    showCloseConfirmationPopup.value = false;
};

const otherGroups = computed(() => props.groups?.filter(group => group.id !== props.groupId));
</script>

<template>
    <Popup
        :title
        :show
        @close="handleClose"
    >
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <p class="text-lg font-medium">Nom du groupe</p>
                <input
                    v-model="groupName"
                    class="border border-gray-300 rounded-lg p-2"
                    :placeholder="'ex : G1'"
                />
            </div>

            <div class="flex items-center gap-4">
                <input
                    type="checkbox"
                    v-model="isCopyingFromAnotherGroup"
                    class="rounded-md w-7 h-7 border-gray-300 text-gray-400"
                >
                <p>Copier un autre groupe</p>
            </div>

            <div v-if="isCopyingFromAnotherGroup" class="flex flex-col gap-2">
                <p class="text-lg font-medium">Groupe à copier</p>
                <select
                    v-model="selectedGroupToCopyId"
                    class="border border-gray-300 rounded-lg p-2"
                >
                    <option
                        v-for="group in otherGroups"
                        :key="group.id"
                        :value="group.id"
                    >
                        {{ group.name }}
                    </option>
                </select>
            </div>
            <slot />
        </div>
    </Popup>
    <CloseWithoutSaveConfirmationPopup
        :show="showCloseConfirmationPopup"
        @close="handleCloseWithoutSaving"
        @cancel="handleCancel"
    />
</template>