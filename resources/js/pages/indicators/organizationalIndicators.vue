<template>
    <div class="w-full h-full flex flex-col justify-between items-start gap-4 p-2">

        <div class="w-full flex flex-col justify-start items-start gap-4">
            <section class="w-full flex justify-between items-center">
                <h2 class="text-xl uppercase font-semibold">Organizational Indicators</h2>
                <Button 
                    label="Add Indicator" 
                    icon="pi pi-plus" 
                    size="small"
                    @click="openAddDialog"
                />
            </section>

            <section>
                <InputText 
                    v-model="searchQuery"
                    placeholder="Search Indicator" 
                    size="small" 
                    class="w-[400px]"
                />
            </section>
        </div>

        <section class="w-full h-full overflow-y-auto">
            <!-- Header Row -->
            <div class="w-full grid grid-cols-12 gap-4 p-4 bg-gray-100 border-b-2 border-gray-300 font-semibold text-sm uppercase sticky top-0 z-10">
                <div class="col-span-8">Indicator Name</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-2 text-center">Actions</div>
            </div>

            <!-- Data Rows -->
            <div v-if="filteredIndicators.length > 0">
                <div 
                    v-for="indicator in filteredIndicators" 
                    :key="indicator.id" 
                    class="w-full grid grid-cols-12 gap-4 p-4 border-b hover:bg-[#F0FCFA] transition-colors items-center"
                >
                    <!-- Indicator Name -->
                    <div class="col-span-8 flex items-center gap-2">
                        <span class="text-sm">{{ indicator.name }}</span>
                    </div>

                    <!-- Status -->
                    <div class="col-span-2">
                        <Tag 
                            :value="indicator.active ? 'Active' : 'Inactive'" 
                            :severity="indicator.active ? 'success' : 'danger'"
                            class="text-xs"
                        />
                    </div>

                    <!-- Actions -->
                    <div class="col-span-2 flex justify-center gap-1">
                        <Button 
                            icon="pi pi-pencil" 
                            text 
                            rounded
                            size="small"
                            severity="secondary"
                            v-tooltip.top="'Edit Indicator'"
                            @click="openEditDialog(indicator)"
                        />
                        <Button 
                            :icon="indicator.active ? 'pi pi-ban' : 'pi pi-check'" 
                            text 
                            rounded
                            size="small"
                            :severity="indicator.active ? 'danger' : 'success'"
                            v-tooltip.top="indicator.active ? 'Disable' : 'Enable'"
                            @click="toggleStatus(indicator)"
                        />
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="w-full text-center py-12 text-gray-500">
                No indicators found
            </div>
        </section>

        <!-- Add/Edit Indicator Dialog -->
        <Dialog 
            v-model:visible="editDialog" 
            modal 
            :header="isEditMode ? 'Edit Indicator' : 'Add Indicator'"
            :style="{ width: '30vw' }"
        >
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <label class="font-medium text-sm">Indicator Name <span class="text-red-500">*</span></label>
                    <InputText 
                        v-model="form.name"
                        placeholder="Enter indicator name"
                        class="w-full"
                        :class="{ 'p-invalid': errors.name }"
                    />
                    <small v-if="errors.name" class="text-red-500">{{ errors.name }}</small>
                </div>
            </div>

            <template #footer>
                <Button label="Cancel" text severity="secondary" @click="closeEditDialog" />
                <Button label="Save" @click="saveIndicator" :loading="saving" />
            </template>
        </Dialog>

    </div>
</template>

<script setup>
    import { ref, computed } from 'vue';
    import { router } from '@inertiajs/vue3';
    import MainLayout from '@/layouts/mainLayout.vue';
    import { 
        Button, 
        InputText, 
        Tag,
        Dialog
    } from 'primevue';

    const props = defineProps({
        org_indicators: Array,
    });

    defineOptions({
        layout: MainLayout
    });

    const searchQuery = ref('');
    const editDialog = ref(false);
    const selectedIndicator = ref(null);
    const isEditMode = ref(false);
    const saving = ref(false);
    const errors = ref({});

    const form = ref({
        name: ''
    });

    const filteredIndicators = computed(() => {
        if (!searchQuery.value) return props.org_indicators;
        
        const query = searchQuery.value.toLowerCase();
        return props.org_indicators.filter(indicator => 
            indicator.name.toLowerCase().includes(query)
        );
    });

    const openAddDialog = () => {
        isEditMode.value = false;
        form.value = {
            name: ''
        };
        errors.value = {};
        editDialog.value = true;
    };

    const openEditDialog = (indicator) => {
        isEditMode.value = true;
        selectedIndicator.value = indicator;
        form.value = {
            name: indicator.name
        };
        errors.value = {};
        editDialog.value = true;
    };

    const closeEditDialog = () => {
        editDialog.value = false;
        errors.value = {};
    };

    const saveIndicator = () => {
        errors.value = {};
        saving.value = true;
        
        const url = isEditMode.value 
            ? `/indicator/organizational/${selectedIndicator.value.id}`
            : '/indicator/organizational/create';
        
        const method = isEditMode.value ? 'patch' : 'post';

        router[method](url, form.value, {
            preserveScroll: true,
            onSuccess: () => {
                editDialog.value = false;
                saving.value = false;
                errors.value = {};
            },
            onError: (error) => {
                errors.value = error;
                saving.value = false;
            }
        });
    };

    const toggleStatus = (indicator) => {
        const action = indicator.active ? 'disable' : 'enable';
        const message = `Are you sure you want to ${action} "${indicator.name}"?`;
        
        if (confirm(message)) {
            router.delete(`/indicator/organizational/${indicator.id}`, {}, {
                preserveScroll: true
            });
        }
    };
</script>