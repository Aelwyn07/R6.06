<script setup lang="ts">
import Icon from '@/Components/Icon.vue';
import IconButton from '@/Components/IconButton.vue';

/**
 * @component SearchBar
 * @description Barre de recherche réutilisable avec une icône de recherche et des options pour ajouter des boutons d'import et d'ajout
 * 
 * @example
 * ```vue
 * <SearchBar 
 *   placeholder="Rechercher un élément..."
 *   hasImport
 *   hasAdd
 *   @importClick="handleImport"
 *   @addClick="handleAdd"
 * />
 * ```
 * 
 * @example Version compacte
 * ```vue
 * <SearchBar 
 *   placeholder="Rechercher..."
 *   small
 * />
 * ```
 */

defineProps<{
    /** Placeholder de la barre de recherche*/
    placeholder: string,
    /** Indique si la barre de recherche doit avoir un bouton d'import */
    hasImport?: boolean,
    /** Indique si la barre de recherche doit avoir un bouton d'ajout */
    hasAdd?: boolean,
    /** Indique si la barre de recherche doit être en version compacte */
    small?: boolean
}>()

const emit = defineEmits(['importClick', 'addClick', 'input']);
</script>

<template>
    <div v-if="!small" class="search-bar flex items-center gap-2 border border-black rounded-2xl p-1 pl-5 bg-gray-100 shadow-lg">
        <Icon name="Search" :strokeWidth="2" />
        <input class="border-none focus:ring-0 w-full bg-transparent" type="text" :placeholder="placeholder" @input="emit('input', $event)" />
        <IconButton v-if="hasImport" iconClass="Import" bgColor="#E8DEF8" @click="emit('importClick')" />
        <IconButton v-if="hasAdd" iconClass="Plus" bgColor="#FFD8E4" @click="emit('addClick')" />
    </div>
    <div v-else class="search-bar small flex items-center gap-1 border border-black rounded-2xl p-1 pl-2 bg-gray-100 shadow-lg">
        <Icon name="Search" :strokeWidth="2" />
        <input class="border-none focus:ring-0 w-full bg-transparent py-0" type="text" :placeholder="placeholder" @input="emit('input', $event)" />
    </div>
</template>