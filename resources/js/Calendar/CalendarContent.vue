<script setup lang="ts">
import CalendarCell from "@/Calendar/CalendarCell.vue";
import CalendarHeader from "@/Calendar/CalendarHeader.vue";
import { ref, onMounted } from "vue";

defineProps<{
    weeksData: any;
    biggestCM: number;
    biggestTD: number;
    biggestTP: number;
}>();

const headerRef = ref<HTMLElement | null>(null);
const contentRef = ref<HTMLElement | null>(null);

onMounted(() => {
    if (headerRef.value && contentRef.value) {
        contentRef.value.addEventListener("scroll", () => {
            if (headerRef.value) {
                headerRef.value.scrollLeft = contentRef.value!.scrollLeft;
            }
        });

        headerRef.value.addEventListener("scroll", () => {
            if (contentRef.value) {
                contentRef.value.scrollLeft = headerRef.value!.scrollLeft;
            }
        });
    }
});
</script>

<template>
    <div style="max-width: calc(100% - 3rem)">
        <!-- Header avec son propre scroll -->
        <div ref="headerRef" class="overflow-x-scroll hide-scrollbar">
            <CalendarHeader
                :biggestCM="biggestCM"
                :biggestTD="biggestTD"
                :biggestTP="biggestTP"
            />
        </div>
        <!-- Contenu scrollable -->
        <div ref="contentRef" class="overflow-x-scroll scrollbar-visible">
            <div v-for="week in weeksData" class="flex flex-col h-52">
                <div class="flex flex-col h-full">
                    <CalendarCell
                        class="h-52"
                        v-for="group in week.groups"
                        :group-data="group"
                        :biggestCM="biggestCM"
                        :biggestTD="biggestTD"
                        :biggestTP="biggestTP"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.scrollbar-visible {
    scrollbar-gutter: stable;
}

.scrollbar-visible::-webkit-scrollbar {
    height: 10px;
    display: block;
}

.scrollbar-visible::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.scrollbar-visible::-webkit-scrollbar-thumb {
    background: #9ca3af;
    border-radius: 5px;
}

.scrollbar-visible::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.hide-scrollbar {
    -ms-overflow-style: none; /* Pour Internet Explorer et Edge */
    scrollbar-width: none; /* Pour Firefox */
}

.hide-scrollbar::-webkit-scrollbar {
    display: none; /* Pour Chrome, Safari et Opera */
}
</style>
