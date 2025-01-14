<template>
    <ConfigLayout>
        <div class="config-page-container">
            <div class="config-page grid grid-cols-2 gap-8 p-8">
                <div class="config-section">
                    <h2 class="text-xl font-bold mb-4">Groupes</h2>
                    <div class="flex flex-col gap-4">
                        <div
                            v-for="label in groupLabels"
                            :key="label.id"
                            class="flex justify-between items-center"
                        >
                            <span>{{ label.original_name }}</span>
                            <input
                                type="text"
                                v-model="labelValues[label.id]"
                                class="border p-2 rounded"
                            />
                        </div>
                    </div>
                </div>

                <div class="config-section">
                    <h2 class="text-xl font-bold mb-4">
                        Enseignants/Enseignements
                    </h2>
                    <div class="flex flex-col gap-4">
                        <div
                            v-for="label in teachingLabels"
                            :key="label.id"
                            class="flex justify-between items-center"
                        >
                            <span>{{ label.original_name }}</span>
                            <input
                                type="text"
                                v-model="labelValues[label.id]"
                                class="border p-2 rounded"
                            />
                        </div>
                    </div>
                </div>

                <div class="config-section">
                    <h2 class="text-xl font-bold mb-4">
                        Calendrier Prévisionnel
                    </h2>
                    <div class="flex flex-col gap-4">
                        <div
                            v-for="label in calendarLabels"
                            :key="label.id"
                            class="flex justify-between items-center"
                        >
                            <span>{{ label.original_name }}</span>
                            <input
                                type="text"
                                v-model="labelValues[label.id]"
                                class="border p-2 rounded"
                            />
                        </div>
                    </div>
                </div>

                <div class="config-section">
                    <h2 class="text-xl font-bold mb-4">Menu Latéral</h2>
                    <div class="flex flex-col gap-4">
                        <div
                            v-for="label in sidebarLabels"
                            :key="label.id"
                            class="flex justify-between items-center"
                        >
                            <span>{{ label.original_name }}</span>
                            <input
                                type="text"
                                v-model="labelValues[label.id]"
                                class="border p-2 rounded"
                            />
                        </div>
                    </div>
                </div>

                <div class="config-section">
                    <h2 class="text-xl font-bold mb-4">Menu du haut</h2>
                    <div class="flex flex-col gap-4">
                        <div
                            v-for="label in topMenuLabels"
                            :key="label.id"
                            class="flex justify-between items-center"
                        >
                            <span>{{ label.original_name }}</span>
                            <input
                                type="text"
                                v-model="labelValues[label.id]"
                                class="border p-2 rounded"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="validate-button-container">
                <button class="validate-button" @click="handleSave">
                    Valider
                </button>
            </div>
        </div>
    </ConfigLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import ConfigLayout from "@/Layouts/ConfigLayout.vue";
import { useLabelsStore } from "@/Stores/labelsStore";
import axios from "axios";

interface Label {
    id: number;
    original_name: string;
    name: string | null;
}

const labelsStore = useLabelsStore();
const labelValues = ref<{ [key: number]: string }>({});

// Grouper les labels par catégorie
const groupLabels = computed(() => {
    return labelsStore.labels.filter((label) =>
        ["Promotion", "Groupe", "Demi-groupe"].includes(label.original_name)
    );
});

const teachingLabels = computed(() => {
    return labelsStore.labels.filter((label) =>
        [
            "Enseignants",
            "Enseignements",
            "Mode Enseignants",
            "Mode Enseignements",
        ].includes(label.original_name)
    );
});

const calendarLabels = computed(() => {
    return labelsStore.labels.filter((label) =>
        ["S", "CM", "TD", "TP"].includes(label.original_name)
    );
});

const sidebarLabels = computed(() => {
    return labelsStore.labels.filter((label) =>
        [
            "Calendrier Prévisionnel",
            "EDT",
            "Service",
            "Configurations",
            "Déconnexion",
        ].includes(label.original_name)
    );
});

const topMenuLabels = computed(() => {
    return labelsStore.labels.filter((label) =>
        [
            "Groupes",
            "Enseignants/Enseignements",
            "Calendrier Prévisionnels",
        ].includes(label.original_name)
    );
});

onMounted(async () => {
    await labelsStore.fetchLabels();
    // Initialiser les valeurs des labels
    for (const label of labelsStore.labels) {
        labelValues.value[label.id] = label.name || label.original_name;
    }
});

const handleSave = async () => {
    try {
        let hasChanges = false;
        for (const label of labelsStore.labels) {
            if (labelValues.value[label.id] !== label.name) {
                await axios.put(`/api/labels/${label.id}`, {
                    name: labelValues.value[label.id],
                });
                hasChanges = true;
            }
        }
        if (hasChanges) {
            alert("Les modifications ont été enregistrées avec succès");
            // Rafraîchir les données
            await labelsStore.fetchLabels();
        }
    } catch (error) {
        console.error("Erreur lors de la sauvegarde des labels:", error);
        alert(
            "Une erreur est survenue lors de la sauvegarde des modifications"
        );
    }
};
</script>

<style scoped>
.config-page-container {
    display: flex;
    flex-direction: column;
    height: 100%;
    max-height: calc(100vh - 2rem);
}

.config-page {
    flex: 1;
    overflow-y: auto;
    min-height: 0;
}

.validate-button-container {
    padding: 1rem;
    display: flex;
    justify-content: flex-end;
}

.validate-button {
    background-color: #4caf50;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.validate-button:hover {
    background-color: #45a049;
}

.config-section {
    background-color: #ffffff;
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style>
