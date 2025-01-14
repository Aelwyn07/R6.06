<script setup lang="ts">
import Popup from '../Popup/Popup.vue';
import Button from '@/Components/Button.vue';
import DeleteConfirmationPopup from '@/Components/DeleteConfirmationPopup.vue';
import CloseWithoutSaveConfirmationPopup from '@/Components/CloseWithoutSaveConfirmationPopup.vue';
import { ref, watch } from 'vue';
import { Teacher } from '@/types/models';

const props = withDefaults(defineProps<{
  isEditing?: boolean;
  initialValues?: Teacher;
}>(), {
  isEditing: false,
  initialValues: () => ({ firstname: '', lastname: '' })
});

const emit = defineEmits(['close', 'add', 'update', 'delete']);

const formData = ref({ ...props.initialValues });
const showDeleteConfirmation = ref(false);
const showCloseConfirmation = ref(false);
const hasChanges = ref(false);

watch(() => formData.value, () => {
  hasChanges.value = Object.keys(formData.value).some(
    key => formData.value[key] !== props.initialValues[key]
  );
}, { deep: true });

const handleClose = () => hasChanges.value ? showCloseConfirmation.value = true : emit('close');
const handleDelete = () => showCloseConfirmation.value = true;
const confirmDelete = () => (showDeleteConfirmation.value = false, emit('delete'));
const confirmClose = () => (showCloseConfirmation.value = false, emit('close'));
const cancelConfirmation = () => {
  showDeleteConfirmation.value = showCloseConfirmation.value = false;
};
</script>

<template>
  <Popup
    :title="`${isEditing ? 'Modifier' : 'Ajouter'} un enseignant`"
    :show="true"
    @close="handleClose"
  >
    <div class="flex flex-col gap-6">
      <!-- Prénom -->
      <div class="flex flex-col gap-2">
        <label class="text-lg font-medium">Prénom</label>
        <input 
          v-model="formData.firstname"
          type="text" 
          class="border border-gray-300 rounded-lg p-2"
          placeholder="ex : Jean"
        />
      </div>

      <!-- Nom -->
      <div class="flex flex-col gap-2">
        <label class="text-lg font-medium">Nom</label>
        <input 
          v-model="formData.lastname"
          type="text" 
          class="border border-gray-300 rounded-lg p-2"
          placeholder="ex : Dupont"
        />
      </div>

      <!-- Boutons d'action -->
      <div class="flex gap-4 justify-end">
        <Button 
          v-if="isEditing"
          class="bg-red-500 text-white" 
          @click="handleDelete"
        >
          Supprimer
        </Button>
        <Button 
          class="bg-green-500 text-white"
          @click="handleSubmit"
        >
          {{ isEditing ? 'Sauvegarder' : 'Ajouter' }}
        </Button>
      </div>
    </div>
  </Popup>

  <!-- Popups de confirmation -->
  <DeleteConfirmationPopup
    v-if="showDeleteConfirmation"
    :show="showDeleteConfirmation"
    @delete="confirmDelete"
    @cancel="cancelConfirmation"
  />

  <CloseWithoutSaveConfirmationPopup
    v-if="showCloseConfirmation"
    :show="showCloseConfirmation"
    @close="confirmClose"
    @cancel="cancelConfirmation"
  />
</template>