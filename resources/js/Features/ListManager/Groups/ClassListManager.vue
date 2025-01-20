<script setup lang="ts">
import ListManager from "@/Components/ListManager/ListManager.vue";
import { defineProps, defineEmits, onMounted, computed } from "vue";
import { Class } from "@/types/models";
import { useLabelsStore } from "@/stores/labelsStore";

const labelsStore = useLabelsStore();

defineProps<{
    classes: Class[];
    selectedClassId?: number;
}>();

const emit = defineEmits(["select", "edit", "add"]);

const title = computed(() => {
    return labelsStore.getLabel("Promotion");
});

onMounted(async () => {
    await labelsStore.fetchLabels();
});

const handleSelect = (item: Class) => {
    emit("select", item);
};

const handleEdit = (item: Class) => {
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
        :items="classes"
        :selectedItemsId="selectedClassId ? [selectedClassId] : undefined"
        @select="handleSelect"
        @edit="handleEdit"
        @add="handleAdd"
    />
</template>
