<script setup lang="ts">
import Popup from '@/Components/Popup/Popup.vue';
import Button from '@/Components/Button.vue';
defineProps<{
    show?: boolean;
}>();
</script>

<template>
    <Popup title="Modifications non enregistrées" :show @close="$emit('cancel')">
        <div class="flex flex-col gap-4">
            <p>Etes-vous sûr de vouloir quitter sans sauvegarder ?</p>
            <div class="flex gap-4">
                <Button class="bg-red-500 text-white" @click="$emit('close')">Quitter sans sauvegarder</Button>
                <Button @click="$emit('cancel')">Annuler</Button>
            </div>
        </div>
    </Popup>
</template>