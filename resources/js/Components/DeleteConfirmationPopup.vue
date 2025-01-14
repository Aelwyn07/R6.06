<script setup lang="ts">
import Popup from '@/Components/Popup/Popup.vue';
import Button from '@/Components/Button.vue';
defineProps<{
    show?: boolean;
}>();
</script>

<template>
    <Popup title="Suppression" :show @close="$emit('cancel')">
        <div class="flex flex-col gap-4">
            <p>Etes-vous sûr de vouloir supprimer cet élément ?</p>
            <div class="flex gap-4">
                <Button class="bg-red-500 text-white" @click="$emit('delete')">Supprimer</Button>
                <Button @click="$emit('cancel')">Annuler</Button>
            </div>
        </div>
    </Popup>
</template>