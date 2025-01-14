<script setup lang="ts">
import CalendarLeftSidebar from "./CalendarLeftSidebar.vue";
import CalendarContent from "./CalendarContent.vue";
import CalendarRightSidebar from "./CalendarRightSidebar.vue";
import RightSidebarHeader from "./RightSidebarHeader.vue";
import { computed, onMounted, ref } from "vue";
import { Calendar } from "@/types/models";
import axios from "axios";

const weeksData = ref<Calendar>();
const teachingId = ref(1);

onMounted(async () => {
    weeksData.value = await axios
        .get(`/api/calendrier/${teachingId.value}`)
        .then((response) => {
            return response.data;
        });
});

const biggestCM = computed(() => {
    let biggest = 0;
    if (!weeksData.value) return biggest;

    weeksData.value.forEach((week) => {
        week.groups.forEach((promotion) => {
            let contentSize = 0;
            promotion.contents.forEach((content) => {
                contentSize += content.hours;
            });
            if (contentSize > biggest) {
                biggest = contentSize;
            }
        });
    });
    return biggest;
});

const biggestTD = computed(() => {
    let biggest = 0;
    if (!weeksData.value) return biggest;

    weeksData.value.forEach((week) => {
        week.groups.forEach((promotion) => {
            if (!promotion.groups) return;
            promotion.groups.forEach((tdGroup) => {
                let contentSize = 0;
                tdGroup.contents.forEach((content) => {
                    contentSize += content.hours;
                });
                if (contentSize > biggest) {
                    biggest = contentSize;
                }
            });
        });
    });
    return biggest;
});

const biggestTP = computed(() => {
    let biggest = 0;
    if (!weeksData.value) return biggest;

    weeksData.value.forEach((week) => {
        week.groups.forEach((promotion) => {
            if (!promotion.groups) return;
            promotion.groups.forEach((tdGroup) => {
                if (!tdGroup.groups) return;
                tdGroup.groups.forEach((tpGroup) => {
                    let contentSize = 0;
                    tpGroup.contents.forEach((content) => {
                        contentSize += content.hours;
                    });
                    if (contentSize > biggest) {
                        biggest = contentSize;
                    }
                });
            });
        });
    });
    return biggest;
});
</script>

<template>
    <div class="h-full flex flex-col">
        <!-- Contenu défilant -->
        <div class="flex-1 relative overflow-y-auto">
            <CalendarLeftSidebar
                :weeks-data="weeksData"
                class="absolute left-0 top-0 bottom-0 pt-12"
            />
            <div class="relative flex-1">
                <CalendarContent
                    :weeks-data="weeksData"
                    :biggestCM="biggestCM"
                    :biggestTD="biggestTD"
                    :biggestTP="biggestTP"
                    class="absolute left-12 right-36"
                >
                    <template v-for="week in weeksData" :key="week.week">
                        <div class="flex flex-col">
                            <CalendarCell
                                v-for="group in week.groups"
                                :key="group.id"
                                :groupData="group"
                                :biggestCM="biggestCM"
                                :biggestTD="biggestTD"
                                :biggestTP="biggestTP"
                            >
                                <template v-for="subgroup in group.groups" :key="subgroup.id">
                                    <CalendarCell
                                        :groupData="subgroup"
                                        :biggestCM="biggestCM"
                                        :biggestTD="biggestTD"
                                        :biggestTP="biggestTP"
                                    >
                                        <template v-for="finalGroup in subgroup.groups" :key="finalGroup.id">
                                            <CalendarCell
                                                :groupData="finalGroup"
                                                :biggestCM="biggestCM"
                                                :biggestTD="biggestTD"
                                                :biggestTP="biggestTP"
                                            />
                                        </template>
                                    </CalendarCell>
                                </template>
                            </CalendarCell>
                        </div>
                    </template>
                </CalendarContent>
                <div class="absolute right-0 top-0 bottom-0">
                    <RightSidebarHeader />
                    <CalendarRightSidebar
                        :weeks-data="weeksData"
                        class="absolute right-0 top-0 bottom-0 pt-12"
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
    width: 15px;
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
</style>
