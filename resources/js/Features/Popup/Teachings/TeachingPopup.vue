<script setup lang="ts">
import Popup from '@/Components/Popup/Popup.vue';
import type { Teaching } from '@/types/models';

defineProps<{
    title: string;
    show: boolean;
    teaching?: Teaching;
}>();

const emit = defineEmits([
    'close',
    'updateTeachingName',
    'updateApogeeCode',
    'updateInitialCM',
    'updateContinuingCM',
    'updateInitialTD',
    'updateContinuingTD',
    'updateInitialTP',
    'updateContinuingTP',
]);
</script>

<template>
    <Popup
        :title
        :show
        @close="emit('close')"
    >
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <p class="text-lg font-medium">Intitulé de la ressource</p>
                <input
                    :value="teaching?.name"
                    class="border border-gray-300 rounded-lg p-2"
                    placeholder="ex : R1.01 - Développement web"
                    @input="emit('updateTeachingName', ($event.target as HTMLInputElement).value)"
                />
            </div>
            <div class="flex flex-col gap-2">
                <p class="text-lg font-medium">Code Apogée</p>
                <input
                    :value="teaching?.apogee_code"
                    class="border border-gray-300 rounded-lg p-2"
                    placeholder="ex : TIN13A1S"
                    @input="emit('updateApogeeCode', ($event.target as HTMLInputElement).value)"
                />
            </div>
            <table class="border-separate border-spacing-0">
                <tbody>
                    <tr>
                        <td />
                        <td class="border-y border-l border-gray-300 rounded-tl-lg text-center">Initial</td>
                        <td class="border border-gray-300 rounded-tr-lg text-center">Continue</td>
                    </tr>
                    <tr class="w-full">
                        <td class="bg-yellow-200 rounded-tl-lg text-center">CM</td>
                        <td class="p-0 border-b border-l border-gray-300 w-32">
                            <input
                                :value="teaching?.initial_cm"
                                type="number"
                                min="0"
                                class="p-2 bg-gray-200 text-center border-none w-full"
                                @input="emit('updateInitialCM', ($event.target as HTMLInputElement).value)"
                            />
                        </td>
                        <td class="p-0 border-b border-x border-gray-300 w-32">
                            <input
                                :value="teaching?.continuing_cm"
                                type="number"
                                min="0"
                                class="p-2 bg-gray-200 text-center border-none w-full"
                                @input="emit('updateContinuingCM', ($event.target as HTMLInputElement).value)"
                            />
                        </td>
                    </tr>
                    <tr class="w-full">
                        <td class="bg-red-400 text-center">TD</td>
                        <td class="p-0 border-b border-l border-gray-300 w-32">
                            <input
                                :value="teaching?.initial_td"
                                type="number"
                                min="0"
                                class="p-2 bg-gray-200 text-center border-none w-full"
                                @input="emit('updateInitialTD', ($event.target as HTMLInputElement).value)"
                            />
                        </td>
                        <td class="p-0 border-b border-x border-gray-300 w-32">
                            <input
                                :value="teaching?.continuing_td"
                                type="number"
                                min="0"
                                class="p-2 bg-gray-200 text-center border-none w-full"
                                @input="emit('updateContinuingTD', ($event.target as HTMLInputElement).value)"
                            />
                        </td>
                    </tr>
                    <tr class="w-full">
                        <td class="bg-blue-400 rounded-bl-lg text-center">TP</td>
                        <td class="p-0 border-b border-l border-gray-300 w-32">
                            <input
                                :value="teaching?.initial_tp"
                                type="number"
                                min="0"
                                class="p-2 bg-gray-200 text-center border-none w-full"
                                @input="emit('updateInitialTP', ($event.target as HTMLInputElement).value)"
                            />
                        </td>
                        <td class="p-0 border-b border-x border-gray-300 rounded-br-lg w-32">
                            <input
                                :value="teaching?.continuing_tp"
                                type="number"
                                min="0"
                                class="p-2 bg-gray-200 text-center border-none rounded-br-lg w-full"
                                @input="emit('updateContinuingTP', ($event.target as HTMLInputElement).value)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <slot />
    </Popup>
</template>
