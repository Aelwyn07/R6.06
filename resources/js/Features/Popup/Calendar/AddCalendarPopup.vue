<script setup lang="ts">
import { ref, computed } from "vue";
import Popup from "@/Components/Popup/Popup.vue";
import CloseWithoutSaveConfirmationPopup from "@/Components/CloseWithoutSaveConfirmationPopup.vue";

defineProps<{
    show?: boolean;
}>();

const professors = ref([
    { label: "POURSAT Anaïs", value: "poursat" },
    { label: "DUBREUIL Laurent", value: "laurent" },
]);

const initialState = {
    selectedProfessor: "",
    replacementProfessor: "",
    hours: "",
    evaluation: false,
    replaced: false,
    neutralized: false,
};

const selectedProfessor = ref(initialState.selectedProfessor);
const replacementProfessor = ref(initialState.replacementProfessor);
const hours = ref(initialState.hours);
const evaluation = ref(initialState.evaluation);
const replaced = ref(initialState.replaced);
const neutralized = ref(initialState.neutralized);
const showConfirmation = ref(false);

const hasChanges = computed(() => {
    return (
        selectedProfessor.value !== initialState.selectedProfessor ||
        replacementProfessor.value !== initialState.replacementProfessor ||
        hours.value !== initialState.hours ||
        evaluation.value !== initialState.evaluation ||
        replaced.value !== initialState.replaced ||
        neutralized.value !== initialState.neutralized
    );
});

const confirmClose = () => {
    showConfirmation.value = false;
};

const cancelClose = () => {
    showConfirmation.value = false;
};

const modifyCalendar = () => {
    // Logic to modify the calendar
};
</script>

<template>
    <Popup
        title="Ajouter calendrier prévisionnel"
        :show
        @close="$emit('cancel')"
    >
        <div class="space-y-6">
            <div>
                <label class="block text-lg mb-2">Professeur</label>
                <select
                    v-model="selectedProfessor"
                    class="w-full p-3 border rounded-lg bg-white appearance-none pr-10 relative"
                >
                    <option
                        v-for="prof in professors"
                        :key="prof.value"
                        :value="prof.value"
                    >
                        {{ prof.label }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-lg mb-2">Heures</label>
                <input
                    type="text"
                    v-model="hours"
                    placeholder="ex : 10.5"
                    class="w-full p-3 border rounded-lg"
                />
            </div>

            <div class="space-y-3">
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="evaluation"
                        v-model="evaluation"
                        class="w-5 h-5 mr-3"
                    />
                    <label for="evaluation" class="text-lg">Évaluation</label>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="replaced"
                            v-model="replaced"
                            class="w-5 h-5 mr-3"
                        />
                        <label for="replaced" class="text-lg">Remplacé</label>
                    </div>
                    <div v-if="replaced" class="ml-8">
                        <label class="block text-lg mb-2"
                            >Professeur remplaçant</label
                        >
                        <select
                            v-model="replacementProfessor"
                            class="w-full p-3 border rounded-lg bg-white appearance-none pr-10 relative"
                        >
                            <option
                                v-for="prof in professors"
                                :key="prof.value"
                                :value="prof.value"
                                :disabled="prof.value === selectedProfessor"
                            >
                                {{ prof.label }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="neutralized"
                        v-model="neutralized"
                        class="w-5 h-5 mr-3"
                    />
                    <label for="neutralized" class="text-lg">Neutralisé</label>
                </div>
            </div>

            <button
                @click="modifyCalendar"
                class="w-full bg-[#72C489] hover:bg-[#5A8B6F] text-white font-semibold py-4 px-6 rounded-full text-lg"
            >
                Ajouter
            </button>
        </div>
    </Popup>

    <CloseWithoutSaveConfirmationPopup
        :show="showConfirmation"
        @close="confirmClose"
        @cancel="cancelClose"
    />
</template>
