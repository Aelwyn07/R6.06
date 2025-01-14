import { defineStore } from 'pinia';

interface AddCalendarPopupData {
    teacherId?: number;
    type?: string;
    promotionId?: number;
    groupId?: number;
    subgroupId?: number;
}

export const useCalendarStore = defineStore('calendar', {
    state: () => ({
        showingAddCalendarPopup: false as boolean,
        addCalendarPopupData: null as AddCalendarPopupData | null,
    }),
    
    actions: {
        showAddCalendarPopup(data: AddCalendarPopupData) {
            console.log('Store: showing popup with data:', data);
            this.addCalendarPopupData = data;
            this.showingAddCalendarPopup = true;
            console.log('Store state after update:', {
                showing: this.showingAddCalendarPopup,
                data: this.addCalendarPopupData
            });
        },
        
        hideAddCalendarPopup() {
            this.showingAddCalendarPopup = false;
            this.addCalendarPopupData = null;
        }
    }
});
