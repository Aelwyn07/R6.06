<script setup lang="ts">
import ListManager from "@/Components/ListManager/ListManager.vue";
import { Teaching, Period } from "@/types/models";
import { defineProps, computed } from "vue";
import AddTeachingPopup from "@/Features/Popup/Teachings/AddTeachingPopup.vue";
import EditTeachingPopup from "@/Features/Popup/Teachings/EditTeachingPopup.vue";
import { ref } from "vue";
import { useLabelsStore } from "@/stores/labelsStore";

const labelsStore = useLabelsStore();

defineProps<{
    periods: Period[];
    teachings: Teaching[];
    selectedTeachingIds: number[];
}>();

const title = computed(() => {
    return labelsStore.getLabel("Enseignements");
});

const emit = defineEmits(["select"]);

const showPopup = ref(false);
const showPopupEdit = ref(false);
const openPopup = () => {
    showPopup.value = true;
};

const handleEdit = (teaching: Teaching) => {
    showPopupEdit.value = true;
};

const handleSelect = (teaching: Teaching) => {
    emit("select", teaching);
};
</script>

<template>
    <div class="teachings-list-manager h-full w-full">
        <ListManager
            :title="title"
            hasAdd
            hasImport
            :periods
            :items="teachings"
            :selectedItemsId="selectedTeachingIds"
            @add="openPopup"
            @edit="handleEdit"
            @select="handleSelect"
        />

        <!--<AddTeachingPopup
            class="z-50"
            v-if="showPopup"
            :is-editing="false"
            @close="showPopup = false"
        />

        <EditTeachingPopup
            class="z-50"
            v-if="showPopupEdit"
            :is-editing="true"
            @close="showPopupEdit = false"
            />-->
    </div>
</template>
