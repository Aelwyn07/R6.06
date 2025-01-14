<script setup lang="ts">
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ClassListManager from "@/Features/ListManager/Groups/ClassListManager.vue";
import GroupListManager from "@/Features/ListManager/Groups/GroupListManager.vue";
import SubgroupListManager from "@/Features/ListManager/Groups/SubgroupListManager.vue";
import { ref, computed, onMounted } from "vue";
import { Class, Group, Subgroup } from "@/types/models";
import AddClassPopup from "@/Features/Popup/Groups/Class/AddClassPopup.vue";
import EditClassPopup from "@/Features/Popup/Groups/Class/EditClassPopup.vue";
import AddGroupPopup from "@/Features/Popup/Groups/Group/AddGroupPopup.vue";
import EditGroupPopup from "@/Features/Popup/Groups/Group/EditGroupPopup.vue";
import AddSubgroupPopup from "@/Features/Popup/Groups/Subgroup/AddSubgroupPopup.vue";
import EditSubgroupPopup from "@/Features/Popup/Groups/Subgroup/EditSubgroupPopup.vue";
import axios from "axios";
import ErrorPopup from "@/Features/Popup/ErrorPopup.vue";

onMounted(async () => {
    const response = await axios.get("/api/groupes/1");
    classes.value = response.data;
});

const classes = ref<Class[]>([]);

const selectedClassId = ref<number | undefined>();
const selectedGroupId = ref<number | undefined>();

const isAddClassPopupVisible = ref<boolean>(false);
const isEditClassPopupVisible = ref<boolean>(false);
const isAddGroupPopupVisible = ref<boolean>(false);
const isEditGroupPopupVisible = ref<boolean>(false);
const isAddSubgroupPopupVisible = ref<boolean>(false);
const isEditSubgroupPopupVisible = ref<boolean>(false);
const isErrorPopupVisible = ref<boolean>(false);

const classToEditId = ref<number | undefined>();
const groupToEditId = ref<number | undefined>();
const subgroupToEditId = ref<number | undefined>();

const errorMessage = ref<string>("");

const groups = computed(
    () =>
        classes.value.find((c) => c.id === selectedClassId.value)?.groups ?? []
);
const subgroups = computed(
    () =>
        groups.value?.find((g) => g.id === selectedGroupId.value)?.subgroups ??
        []
);

const showAddClassPopup = () => (isAddClassPopupVisible.value = true);
const hideAddClassPopup = () => (isAddClassPopupVisible.value = false);
const showEditClassPopup = () => (isEditClassPopupVisible.value = true);
const hideEditClassPopup = () => (isEditClassPopupVisible.value = false);

const showAddGroupPopup = () =>
    selectedClassId.value && (isAddGroupPopupVisible.value = true);
const hideAddGroupPopup = () => (isAddGroupPopupVisible.value = false);
const showEditGroupPopup = () => (isEditGroupPopupVisible.value = true);
const hideEditGroupPopup = () => (isEditGroupPopupVisible.value = false);

const showAddSubgroupPopup = () =>
    selectedGroupId.value && (isAddSubgroupPopupVisible.value = true);
const hideAddSubgroupPopup = () => (isAddSubgroupPopupVisible.value = false);
const showEditSubgroupPopup = () => (isEditSubgroupPopupVisible.value = true);
const hideEditSubgroupPopup = () => (isEditSubgroupPopupVisible.value = false);

const showErrorPopup = () => (isErrorPopupVisible.value = true);
const hideErrorPopup = () => (isErrorPopupVisible.value = false);

const handleClassSelect = (id: number) => {
    if (id === selectedClassId.value) {
        selectedClassId.value = undefined;
        selectedGroupId.value = undefined;
    } else {
        selectedClassId.value = id;
        selectedGroupId.value = undefined;
    }
};

const handleGroupSelect = (id: number) =>
    id === selectedGroupId.value
        ? (selectedGroupId.value = undefined)
        : (selectedGroupId.value = id);

const handleAddClass = async (classe: Class) => {
    hideAddClassPopup();
    classes.value = [...classes.value, classe];
};

const handleEditClass = async (id: number) => {
    classToEditId.value = id;
    showEditClassPopup();
};

const handleSaveEditedClass = async (classe: Class) => {
    hideEditClassPopup();
    classes.value = classes.value.map((c) =>
        c.id === classe.id ? { ...c, name: classe.name } : c
    );
};

const handleDeleteClass = async (classe: Class) => {
    hideEditClassPopup();
    classes.value = classes.value.filter((c) => c.id !== classe.id);
    if (selectedClassId.value === classe.id) {
        selectedClassId.value = undefined;
        selectedGroupId.value = undefined;
    }
};

const handleAddGroup = async (group: Group) => {
    hideAddGroupPopup();
    classes.value = classes.value.map((classe) => {
        if (classe.id === selectedClassId.value) {
            return {
                ...classe,
                groups: [...classe.groups, group],
            };
        }
        return classe;
    });
};

const handleEditGroup = async (id: number) => {
    groupToEditId.value = id;
    showEditGroupPopup();
};

const handleSaveEditedGroup = async (group: Group) => {
    hideEditGroupPopup();
    classes.value = classes.value.map((classe) => {
        if (classe.id === selectedClassId.value) {
            return {
                ...classe,
                groups: classe.groups.map((g) =>
                    g.id === group.id ? { ...g, name: group.name } : g
                ),
            };
        }
        return classe;
    });
};

const handleDeleteGroup = async (group: Group) => {
    hideEditGroupPopup();
    classes.value = classes.value.map((classe) => {
        if (classe.id === selectedClassId.value) {
            return {
                ...classe,
                groups: classe.groups.filter((g) => g.id !== group.id),
            };
        }
        return classe;
    });
    selectedGroupId.value === group.id && (selectedGroupId.value = undefined);
};

const handleAddSubgroup = async (subgroup: Subgroup) => {
    hideAddSubgroupPopup();
    classes.value = classes.value.map((classe) => {
        if (classe.id === selectedClassId.value) {
            return {
                ...classe,
                groups: classe.groups.map((group) =>
                    group.id === selectedGroupId.value
                        ? {
                              ...group,
                              subgroups: [...group.subgroups, subgroup],
                          }
                        : group
                ),
            };
        }
        return classe;
    });
};

const handleEditSubgroup = async (id: number) => {
    subgroupToEditId.value = id;
    showEditSubgroupPopup();
};

const handleSaveEditedSubgroup = async (subgroup: Subgroup) => {
    hideEditSubgroupPopup();
    classes.value = classes.value.map((classe) => {
        if (classe.id === selectedClassId.value) {
            return {
                ...classe,
                groups: classe.groups.map((group) =>
                    group.id === selectedGroupId.value
                        ? {
                              ...group,
                              subgroups: group.subgroups.map((s) =>
                                  s.id === subgroup.id
                                      ? {
                                            ...s,
                                            name: subgroup.name,
                                        }
                                      : s
                              ),
                          }
                        : group
                ),
            };
        }
        return classe;
    });
};

const handleDeleteSubgroup = async (subgroup: Subgroup) => {
    hideEditSubgroupPopup();
    classes.value = classes.value.map((classe) => {
        if (classe.id === selectedClassId.value) {
            return {
                ...classe,
                groups: classe.groups.map((group) =>
                    group.id === selectedGroupId.value
                        ? {
                              ...group,
                              subgroups: group.subgroups.filter(
                                  (s) => s.id !== subgroup.id
                              ),
                          }
                        : group
                ),
            };
        }
        return classe;
    });
};

const handleError = (error: string) => {
    errorMessage.value = error;
    showErrorPopup();
};
</script>

<template>
    <AdminLayout>
        <div class="flex gap-10 w-full h-full">
            <ClassListManager
                class="w-full h-full"
                :classes
                :selectedClassId
                @select="handleClassSelect"
                @add="showAddClassPopup"
                @edit="handleEditClass"
            />
            <GroupListManager
                class="w-full h-full"
                :groups
                :selectedGroupId
                @select="handleGroupSelect"
                @add="showAddGroupPopup"
                @edit="handleEditGroup"
            />
            <SubgroupListManager
                class="w-full h-full"
                :subgroups
                @add="showAddSubgroupPopup"
                @edit="handleEditSubgroup"
            />
        </div>
    </AdminLayout>
    <AddClassPopup
        :show="isAddClassPopupVisible"
        :yearId="1"
        @cancel="hideAddClassPopup"
        @add="handleAddClass"
        @error="handleError"
    />
    <EditClassPopup
        :classToEditId
        :show="isEditClassPopupVisible"
        @cancel="hideEditClassPopup"
        @delete="handleDeleteClass"
        @save="handleSaveEditedClass"
        @error="handleError"
    />
    <AddGroupPopup
        :show="isAddGroupPopupVisible"
        :classId="selectedClassId"
        @cancel="hideAddGroupPopup"
        @add="handleAddGroup"
        @error="handleError"
    />
    <EditGroupPopup
        :groupToEditId
        :show="isEditGroupPopupVisible"
        @cancel="hideEditGroupPopup"
        @delete="handleDeleteGroup"
        @save="handleSaveEditedGroup"
        @error="handleError"
    />
    <AddSubgroupPopup
        :show="isAddSubgroupPopupVisible"
        :groupId="selectedGroupId"
        @cancel="hideAddSubgroupPopup"
        @add="handleAddSubgroup"
        @error="handleError"
    />
    <EditSubgroupPopup
        :subgroupToEditId
        :show="isEditSubgroupPopupVisible"
        @cancel="hideEditSubgroupPopup"
        @delete="handleDeleteSubgroup"
        @save="handleSaveEditedSubgroup"
        @error="handleError"
    />
    <ErrorPopup
        :message="errorMessage"
        :show="isErrorPopupVisible"
        @close="hideErrorPopup"
    />
</template>
