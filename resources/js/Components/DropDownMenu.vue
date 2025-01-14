<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { Teaching } from '@/types/models';
import SearchBar from '@/Components/SearchBar.vue';
import Filter from '@/Components/Filter.vue';

const teachings = ref<Teaching[]>([]);
const selectedTeaching = ref<string>('');
const searchValue = ref('');
const selectedPeriodId = ref(0);
const isOpen = ref(false);

const emit = defineEmits(['update:modelValue']);

onMounted(async () => {
  try {
    const response = await axios.get('/api/enseignements/1');
    teachings.value = response.data.map((teaching: any) => ({
      id: teaching.id,
      name: teaching.title,
      period: teaching.semester ? {
        id: teaching.semester,
        name: `Semestre ${teaching.semester}`
      } : null
    }));
  } catch (error) {
    console.error('Erreur lors de la récupération des enseignements:', error);
  }
});

const handleSelect = (event: Event) => {
  const value = (event.target as HTMLSelectElement).value;
  selectedTeaching.value = value;
  emit('update:modelValue', value);
  isOpen.value = false;
};

const handleSearch = (value: string) => {
  searchValue.value = value;
};

const visibleTeachings = computed(() => {
  if (!teachings.value) return [];
  
  return teachings.value
    .filter(teaching => {
      const matchesSearch = teaching.name.toLowerCase().includes(searchValue.value.toLowerCase());
      const matchesPeriod = selectedPeriodId.value === 0 || teaching.period?.id === selectedPeriodId.value;
      return matchesSearch && matchesPeriod;
    });
});

const periods = [
  { id: 0, name: 'Tous' },
  { id: 1, name: 'Semestre 1' },
  { id: 2, name: 'Semestre 2' },
  { id: 3, name: 'Semestre 3' },
  { id: 4, name: 'Semestre 4' },
  { id: 5, name: 'Semestre 5' },
  { id: 6, name: 'Semestre 6' },
];

const handleNextPeriod = () => {
  selectedPeriodId.value = selectedPeriodId.value === periods.length - 1 ? 0 : selectedPeriodId.value + 1;
};

const handlePreviousPeriod = () => {
  selectedPeriodId.value = selectedPeriodId.value === 0 ? periods.length - 1 : selectedPeriodId.value - 1;
};

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

const closeOnClickOutside = (event: MouseEvent) => {
  const target = event.target as HTMLElement;
  if (!target.closest('.dropdown-menu')) {
    isOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', closeOnClickOutside);
});
</script>

<template>
  <div class="dropdown-menu relative">
    <button 
      @click.stop="toggleDropdown"
      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-left"
    >
      {{ selectedTeaching ? teachings.find(t => t.id.toString() === selectedTeaching)?.name : 'Sélectionner un enseignement' }}
    </button>

    <div 
      v-if="isOpen"
      class="absolute top-full left-0 w-full mt-1 p-6 bg-white rounded-3xl shadow-lg z-50"
    >
      <h1 class="text-2xl font-bold mb-4">Enseignements</h1>
      
      <SearchBar
        placeholder="Rechercher..."
        class="mb-4"
        :modelValue="searchValue"
        @input="handleSearch"
      />

      <Filter
        class="w-52 mb-4"
        hasBorder
        :selectedItemName="periods[selectedPeriodId].name"
        @previous="handlePreviousPeriod"
        @next="handleNextPeriod"
      />

      <div class="max-h-60 overflow-y-auto">
        <div
          v-for="teaching in visibleTeachings"
          :key="teaching.id"
          @click="handleSelect({ target: { value: teaching.id.toString() } } as any)"
          class="px-3 py-2 hover:bg-gray-100 cursor-pointer rounded-md"
        >
          {{ teaching.name }}
        </div>
      </div>
    </div>
  </div>
</template>
