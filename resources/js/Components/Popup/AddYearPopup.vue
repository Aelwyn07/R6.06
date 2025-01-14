<script setup lang="ts">
import Popup from './Popup.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

interface Props {
  show?: boolean;
}

interface Year {
  id: number;
  name: string;
  periodicity: string;
}

defineProps<Props>();

const emit = defineEmits(['close', 'add']);

const periodicity = ref('');
const duplicateFromPrevious = ref(false);
const previousYear = ref('');
const yearName = ref('');
const years = ref<Year[]>([]);

onMounted(async () => {
  try {
    const response = await axios.get('/api/years');
    years.value = response.data;
  } catch (error) {
    console.error('Erreur lors de la récupération des années:', error);
  }
});

const handleSubmit = async () => {
  try {
    if (duplicateFromPrevious.value) {
      await axios.post(`/api/years/${previousYear.value}/clone`, {
        name: yearName.value
      });
    } else {
      await axios.post('/api/years', {
        name: yearName.value,
        periodicity: periodicity.value
      });
    }
    emit('close');
  } catch (error) {
    console.error('Erreur lors de la création de l\'année:', error);
  }
};
</script>

<template>
  <Popup
    title="Nouvelle année"
    :show="show"
    @close="$emit('close')"
  >
    <div class="flex flex-col gap-6">
      <div class="flex items-center gap-4">
        <label class="text-lg font-medium">Dupliquer une année précédente</label>
        <label class="flex items-center gap-2">
          <input
            type="radio"
            v-model="duplicateFromPrevious"
            :value="true"
            class="w-4 h-4"
          >
          Oui
        </label>
        <label class="flex items-center gap-2">
          <input
            type="radio"
            v-model="duplicateFromPrevious"
            :value="false"
            class="w-4 h-4"
          >
          Non
        </label>
      </div>

      <div v-if="duplicateFromPrevious" class="flex flex-col gap-2">
        <label class="text-lg font-medium">Calendriers précédents</label>
        <select
          v-model="previousYear"
          class="border border-gray-300 rounded-lg p-2"
        >
          <option value="" disabled selected>Sélectionner une année</option>
          <option 
            v-for="year in years" 
            :key="year.id" 
            :value="year.id"
          >
            {{ year.name }}
          </option>
        </select>
      </div>

      <div v-if="!duplicateFromPrevious" class="flex flex-col gap-2">
        <label class="text-lg font-medium">Périodicité</label>
        <select
          v-model="periodicity"
          class="border border-gray-300 rounded-lg p-2"
        >
          <option value="" disabled selected>Choisir une périodicité</option>
          <option value="Semestrial">Semestre</option>
          <option value="Trimestrial">Trimestre</option>
        </select>
      </div>

      <div class="flex flex-col gap-2">
        <label class="text-lg font-medium">Nom de l'année</label>
        <input
          v-model="yearName"
          type="text"
          class="border border-gray-300 rounded-lg p-2"
          placeholder="ex: 2024-2025"
        />
      </div>

      <button
        @click="handleSubmit"
        class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition-colors"
      >
        Ajouter
      </button>
    </div>
  </Popup>
</template>