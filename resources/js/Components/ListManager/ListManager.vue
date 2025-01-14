<script setup lang="ts">
import { ref, computed, defineEmits, onMounted, onUnmounted, watch } from 'vue';
import Filter from '@/Components/Filter.vue';
import SearchBar from '@/Components/SearchBar.vue';
import SelectionnableEditableButtonList from './SelectionnableEditableButtonList.vue';
import { Period, Item } from '@/types/models';

const props = defineProps<{
  title: string;
  periods?: Period[];
  items: Item[];
  selectedItemsId?: number[];
  hasFilter?: boolean;
  hasAdd?: boolean;
  hasImport?: boolean;
}>();

const emit = defineEmits(['select', 'edit', 'add']);

const selectedPeriodId = ref(0);

const searchValue = ref('');

const listManagerItemsHeight = ref('0px');
const visibleItems = computed(() => {
    if (!props.items) return [];
    
    if (props.periods) 
        return props.items
            .filter(item => item.period?.id! - 1 === selectedPeriodId.value)
            .filter(item => item.name.toLowerCase().includes(searchValue.value.toLowerCase()));
    else
        return props.items
            .filter(item => item.name.toLowerCase().includes(searchValue.value.toLowerCase()));
});

const handleNextPeriod = () => {
    selectedPeriodId.value = selectedPeriodId.value < (props.periods?.length! - 1) ? (selectedPeriodId.value + 1) : 0;
}

const handlePreviousPeriod = () => {
    selectedPeriodId.value = selectedPeriodId.value === 0 ? (props.periods?.length! - 1) : (selectedPeriodId.value - 1);
}

const handleSearch = (event: Event) => {
    searchValue.value = (event.target as HTMLInputElement).value;
}

const listManager = ref<HTMLElement | null>(null)

const updateHeight = () => {
  if (!listManager.value) return;
  
  requestAnimationFrame(() => {
    const elements = listManager.value?.querySelectorAll(':scope > :not(.list-manager-items)');
    const elementsHeight = Array.from(elements || []).reduce((acc, el) => acc + el.clientHeight, 0);
    listManagerItemsHeight.value = `${listManager.value?.clientHeight! - elementsHeight - (props.periods ? 96 : 80)}px`;
  });
}

const resizeObserver = new ResizeObserver(() => {
    updateHeight();
});

onMounted(() => {
    if (listManager.value) {
        resizeObserver.observe(listManager.value);
    }
    updateHeight();
});

onUnmounted(() => {
  resizeObserver.disconnect();
});
</script>

<template>
    <div ref="listManager" class="list-manager h-full flex flex-col p-6 bg-white rounded-3xl shadow-lg">
        <h1 class="text-2xl font-bold mb-4">{{ title }}</h1>
        <SearchBar
            placeholder="Rechercher..." 
            :hasAdd
            :hasImport
            class="mb-4"
            @input="handleSearch"
            @addClick="emit('add')"
        />

        <Filter
            v-if="periods"
            class="w-52 mb-4"
            hasBorder
            :selectedItemName="periods[selectedPeriodId].name"
            @previous="handlePreviousPeriod"
            @next="handleNextPeriod"
        />

        <div class="flex-1 min-h-0 overflow-y-auto">
            <SelectionnableEditableButtonList
                v-if="visibleItems.length > 0"
                class="w-full"
                :items="visibleItems"
                :selectedItemsId
                @select="emit('select', $event)"
                @edit="emit('edit', $event)"
            />
            <div v-else class="flex items-center justify-center h-full">
                <p>Aucun élément trouvé</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.list-manager-items {
    height: var(--list-manager-items-height);
}
</style>