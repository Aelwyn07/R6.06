<script setup lang="ts">
import { Subgroup } from "@/types/models";
import Popup from "@/Components/Popup/Popup.vue";

defineProps<{
    title: string;
    subgroup?: Subgroup;
    show?: boolean;
}>();
</script>

<template>
    <Popup :title :show @close="$emit('close')">
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <p class="text-lg font-medium">Nom du groupe</p>
                <input
                    :value="subgroup?.name"
                    class="border border-gray-300 rounded-lg p-2"
                    :placeholder="'ex : G1'"
                    @input="
                        $emit(
                            'updateSubgroupName',
                            ($event.target as HTMLInputElement).value
                        )
                    "
                />
            </div>
            <slot />
        </div>
    </Popup>
</template>
