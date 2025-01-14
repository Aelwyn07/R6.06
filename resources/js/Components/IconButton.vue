<!--
/**
 * @component IconButton
 * @description Bouton personnalisable avec une icône intégrée
 * 
 * @example
 * // Bouton standard
 * <IconButton 
 *   icon-class="Plus"
 *   bg-color="#FF3B30"
 *   icon-color="white"
 * />
 * 
 * // Petit bouton avec ombre
 * <IconButton 
 *   icon-class="X"
 *   bg-color="#4CAF50"
 *   icon-color="white"
 *   small
 *   has-shadow
 * />
 */
  -->

<script setup lang="ts">
import Icon from '@/Components/Icon.vue';

defineProps<{
  iconClass: string,
  bgColor?: string,
  iconColor?: string,
  small?: boolean,
  hasShadow?: boolean
}>()
</script>

<template>
  <!-- 
    Classes:
    - icon-button: classe de base
    - flex justify-center items-center: centrage de l'icône
    - cursor-pointer: curseur au survol
    - hover:brightness-75: effet de survol
    - small ? 'p-2 rounded-full' : 'p-4 rounded-xl': taille et bordures selon mode
    - shadow-md: ombre conditionnelle
  -->
  <button 
    :class="[
      'icon-button flex justify-center items-center cursor-pointer text-black brightness-100 transition-brightness duration-300 hover:brightness-75',
      small ? 'p-2 rounded-full' : 'p-4 rounded-xl',
      { 'shadow-md': hasShadow }
    ]"
    :style="{ 'background-color': bgColor }"
  >
    <Icon :name="iconClass" :size="small ? 16 : 24" :color="iconColor ?? 'black'" :strokeWidth="2" />
  </button>
</template>