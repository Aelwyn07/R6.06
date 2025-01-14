<script setup lang="ts">
import Popup from "@/Components/Popup/Popup.vue";

const emit = defineEmits(["close"]);

defineProps<{
    message: string;
    show?: boolean;
}>();
</script>

<template>
    <Popup title="Erreur" :show="show" @close="emit('close')">
        <p>{{ message }}</p>
    </Popup>
</template>
