import { defineStore } from 'pinia';
import axios from 'axios';

interface Label {
    id: number;
    original_name: string;
    name: string | null;
}

export const useLabelsStore = defineStore('labels', {
    state: () => ({
        labels: [] as Label[],
    }),
    getters: {
        getLabel: (state) => (originalName: string) => {
            console.log('Searching for label:', originalName);
            console.log('Available labels:', state.labels);
            const label = state.labels.find(l => l.original_name === originalName);
            console.log('Found label:', label);
            return label?.name || label?.original_name || originalName;
        }
    },
    actions: {
        async fetchLabels() {
            try {
                const response = await axios.get('/api/labels');
                this.labels = response.data;
                console.log('Fetched labels:', this.labels);
                console.log('Labels disponibles:', this.labels.map(l => l.original_name));
            } catch (error) {
                console.error('Erreur lors de la récupération des labels:', error);
            }
        }
    }
});
