<script setup lang="ts">
import { Class } from "@/types/models";
import Popup from "@/Components/Popup/Popup.vue";

defineProps<{
    title: string;
    classe?: Class;
    show?: boolean;
}>();
</script>

<template>
    <Popup :title :show @close="$emit('close')">
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <p class="text-lg font-medium">Nom du groupe</p>
                <input
                    :value="classe?.name"
                    class="border border-gray-300 rounded-lg p-2"
                    :placeholder="'ex : G1'"
                    @input="
                        $emit(
                            'updateClassName',
                            ($event.target as HTMLInputElement).value
                        )
                    "
                />
            </div>
            <slot />
        </div>
    </Popup>
</template>
