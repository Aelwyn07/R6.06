<script setup lang="ts">
import ListManager from "@/Components/ListManager/ListManager.vue";
import { defineProps, onMounted, computed } from "vue";
import { Subgroup } from "@/types/models";
import { useLabelsStore } from "@/stores/labelsStore";

const labelsStore = useLabelsStore();

defineProps<{
    subgroups: Subgroup[];
}>();

const title = computed(() => {
    return labelsStore.getLabel("Demi-groupe");
});

onMounted(async () => {
    await labelsStore.fetchLabels();
});
</script>

<template>
    <ListManager
        :title="title"
        hasAdd
        :items="subgroups"
        @select="$emit('select', $event)"
        @edit="$emit('edit', $event)"
        @add="$emit('add')"
    />
</template>
