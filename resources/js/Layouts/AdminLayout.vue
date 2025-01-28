<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from "vue";
import HeaderMenu from "@/Components/Navigation/HeaderMenu.vue";
import { sidebarMenuItems } from "@/config/navigation";
import MainLayout from "./MainLayout.vue";
import Button from "@/Components/Button.vue";
import AddYearPopup from "@/Components/Popup/AddYearPopup.vue";
import IconButton from "@/Components/IconButton.vue";
import Filter from "@/Components/Filter.vue";
import axios from "axios";
import { useLabelsStore } from "@/stores/labelsStore";

interface Year {
    id: number;
    name: string;
}

const contentHeight = ref<number>(0);
const mainLayout = ref<HTMLElement | null>(null);
const headerMenu = ref<HTMLElement | null>(null);
const observer = ref<MutationObserver | null>(null);
const currentPath = ref(window.location.pathname.split("/")[1]);
const showAddYearPopup = ref(false);
const years = ref<Year[]>([]);
const selectedYearIndex = ref(0);
const labelsStore = useLabelsStore();

const selectedYear = computed(() => {
    return years.value[selectedYearIndex.value]?.name || "Aucune année";
});

const handlePreviousYear = () => {
    if (selectedYearIndex.value > 0) {
        selectedYearIndex.value--;
    }
};

const handleNextYear = () => {
    if (selectedYearIndex.value < years.value.length - 1) {
        selectedYearIndex.value++;
    }
};

const getCurrentSubmenu = () => {
    const currentItem = sidebarMenuItems.find(
        (item) => item.route === currentPath.value
    );
    return currentItem?.submenu;
};

const updateMainContentHeight = () => {
    requestAnimationFrame(() => {
        const headerMenuHeight =
            headerMenu.value?.getBoundingClientRect().bottom ?? 0;
        contentHeight.value = window.innerHeight - headerMenuHeight - 80;
    });
};

const handleAddYear = () => {
    showAddYearPopup.value = true;
};

onMounted(async () => {
    await labelsStore.fetchLabels();
    headerMenu.value = mainLayout.value!.querySelector(".header-menu");
    window.addEventListener("resize", updateMainContentHeight);

    observer.value = new MutationObserver(() => {
        setTimeout(updateMainContentHeight, 1);
    });

    observer.value.observe(mainLayout.value!, {
        attributes: true,
        subtree: true,
    });

    updateMainContentHeight();

    try {
        const response = await axios.get("/api/years");
        years.value = response.data;
    } catch (error) {
        console.error("Erreur lors de la récupération des années:", error);
    }
});

onUnmounted(() => {
    observer.value?.disconnect();
    window.removeEventListener("resize", updateMainContentHeight);
});
</script>

<template>
    <MainLayout>
        <div ref="mainLayout" class="flex flex-col gap-10 -mt-10">
            <div class="flex justify-between items-center px-6">
                <HeaderMenu :items="getCurrentSubmenu()!" />
                <div class="flex items-center gap-4">
                    <Filter
                        :selectedItemName="selectedYear"
                        :hasBorder="true"
                        @previous="handlePreviousYear"
                        @next="handleNextYear"
                    />
                    <IconButton
                        @click="handleAddYear"
                        icon-class="Plus"
                        bg-color="#FFD8E4"
                        icon-color="black"
                        :small="false"
                        :style="{
                            borderRadius: '8px',
                            height: '42px',
                            width: '42px',
                            padding: '0',
                        }"
                    />
                </div>
            </div>
            <div
                class="content w-full"
                :style="{ '--header-menu-height': contentHeight + 'px' }"
            >
                <slot />
            </div>
        </div>
    </MainLayout>

    <AddYearPopup
        v-if="showAddYearPopup"
        :show="showAddYearPopup"
        @close="showAddYearPopup = false"
    />
</template>

<style scoped>
.content {
    height: var(--header-menu-height);
}
</style>
