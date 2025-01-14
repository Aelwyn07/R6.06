<script setup lang="ts">
import Popup from '@/Components/Popup/Popup.vue';
import Button from '@/Components/Button.vue';
import { EditedItem, EditedItemStatus } from '@/types/models';

defineProps<{
    show?: boolean;
    modifications?: EditedItem[]
}>();
</script>

<template>
    <Popup title="Modifications non enregistrées" :show @close="$emit('cancel')">
        <div class="flex flex-col gap-4">
            <p>Voulez-vous enregistrer vos modifications ?</p>
            <p v-if="modifications">
                Modifications :
                <ul class="p-4 bg-gray-100 rounded-md border border-gray-300">
                    <li v-for="modification in modifications" :class="modification.editStatus === EditedItemStatus.ADDED ? 'text-green-500' : modification.editStatus === EditedItemStatus.DELETED ? 'text-red-500' : 'text-yellow-500'" :key="modification.id">
                        {{ modification.name }}
                    </li>
                </ul>
            </p>
            <div class="flex gap-4">
                <Button class="text-gray-500" @click="$emit('cancel')">Annuler</Button>
                <Button class="bg-red-500 text-white" @click="$emit('quitWithoutSave')">Continuer sans sauvegarder</Button>
                <Button class="bg-green-400 text-white" @click="$emit('save')">Sauvegarder</Button>
            </div>
        </div>
    </Popup>
</template>