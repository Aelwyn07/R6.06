<script setup lang="ts">
import ListManager from "@/Components/ListManager/ListManager.vue";
import { defineProps, defineEmits, onMounted, computed } from "vue";
import { Group } from "@/types/models";
import { useLabelsStore } from "@/stores/labelsStore";

const labelsStore = useLabelsStore();

defineProps<{
    groups: Group[];
    selectedGroupId?: number;
}>();

const emit = defineEmits(["select", "edit", "add"]);

const title = computed(() => {
    return labelsStore.getLabel("Groupe");
});

onMounted(async () => {
    await labelsStore.fetchLabels();
});

const handleSelect = (item: Group) => {
    emit("select", item);
};

const handleEdit = (item: Group) => {
    emit("edit", item);
};

const handleAdd = () => {
    emit("add");
};
</script>

<template>
    <ListManager
        :title="title"
        hasAdd
        :items="groups"
        :selectedItemsId="selectedGroupId ? [selectedGroupId] : undefined"
        @select="handleSelect"
        @edit="handleEdit"
        @add="handleAdd"
    />
</template>
