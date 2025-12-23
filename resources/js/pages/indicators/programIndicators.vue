<template>
    <div class="w-full h-full flex flex-col justify-between items-start gap-4 p-2">

        <div class="w-full flex flex-col justify-start items-start gap-4">
            <section class="w-full flex justify-between items-center">
                <h2 class="text-xl uppercase font-semibold">Program Indicators</h2>
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
                <div class="col-span-4">Indicator Name</div>
                <div class="col-span-2">Program</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-2">Disaggregations</div>
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
                    <div class="col-span-4 flex items-center gap-2">
                        <span class="text-sm">{{ indicator.name }}</span>
                    </div>

                    <!-- subProgram -->
                    <div class="col-span-2 flex items-center gap-2">
                        <span class="text-sm">{{ indicator.sub_program?.name }}</span>
                    </div>

                    <!-- Status -->
                    <div class="col-span-2">
                        <Tag 
                            :value="indicator.active ? 'Active' : 'Inactive'" 
                            :severity="indicator.active ? 'success' : 'danger'"
                            class="text-xs"
                        />
                    </div>

                    <!-- Disaggregations -->
                    <div class="col-span-2 flex flex-col items-start gap-1">
                        <span v-for="dis in indicator.disaggregations" class="text-sm bg-gray-200 px-4 rounded-full">{{ dis.name }}</span>
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
                            icon="pi pi-cog" 
                            text 
                            rounded
                            size="small"
                            severity="info"
                            v-tooltip.top="'Set Disaggregations'"
                            @click="openDisaggregationDialog(indicator)"
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
                <div class="flex flex-col gap-2">
                    <label class="font-medium text-sm">Program <span class="text-red-500">*</span></label>
                    <Select v-model="form.sub_program_id" :options="props.sub_programs" optionLabel="name" optionValue="id" placeholder="Select Associated Program"/>
                    <small v-if="errors.sub_program_id" class="text-red-500">{{ errors.sub_program_id }}</small>
                </div>
            </div>

            <template #footer>
                <Button label="Cancel" text severity="secondary" @click="closeEditDialog" />
                <Button label="Save" @click="saveIndicator" :loading="saving" />
            </template>
        </Dialog>

        <!-- Set Disaggregations Dialog -->
        <Dialog 
            v-model:visible="disaggregationDialog" 
            modal 
            header="Set Disaggregations"
            :style="{ width: '70vw' }"
        >
            <div v-if="selectedIndicator" class="flex flex-col gap-4">
                <div class="font-medium uppercase underline text-base">
                    Indicator: {{ selectedIndicator.name }}
                </div>
                
                <div class="flex flex-col gap-4 max-h-[60vh] overflow-y-auto pr-2">
                    <div 
                        v-for="(items, type) in disaggregations" 
                        :key="type"
                        class="flex flex-col gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200"
                    >
                        <div class="font-semibold text-sm text-gray-800 uppercase tracking-wide">
                            {{ type }}
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 ml-2">
                            <div 
                                v-for="item in items" 
                                :key="item.id"
                                class="flex items-center gap-2 hover:bg-gray-100 p-2 rounded transition-colors"
                            >
                                <Checkbox 
                                    v-model="selectedDisaggregations"
                                    :inputId="`disagg-${item.id}`"
                                    :value="item.id"
                                />
                                <label 
                                    :for="`disagg-${item.id}`" 
                                    class="text-sm cursor-pointer select-none"
                                >
                                    {{ item.name }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <small v-if="disaggregationErrors.disaggregations" class="text-red-500 mt-2">
                    {{ disaggregationErrors.disaggregations }}
                </small>
            </div>

            <template #footer>
                <Button label="Cancel" text severity="secondary" @click="closeDisaggregationDialog" />
                <Button label="Save" @click="saveDisaggregations" :loading="saving" />
            </template>
        </Dialog>

    </div>
</template>

<script setup>
    import { ref, computed, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';
    import MainLayout from '@/layouts/mainLayout.vue';
    import { 
        Button, 
        InputText, 
        Tag,
        Dialog,
        Select,
        Checkbox 
    } from 'primevue';

    const props = defineProps({
        program_indicators: Array,
        disaggregations:Object,
        sub_programs:Array
    });

    defineOptions({
        layout: MainLayout
    });

    onMounted(()=>{
        console.log(props.program_indicators)
    })

    const searchQuery = ref('');
    const editDialog = ref(false);
    const disaggregationDialog = ref(false);
    const selectedIndicator = ref(null);
    const isEditMode = ref(false);
    const saving = ref(false);
    const errors = ref({});
    const disaggregationErrors = ref({});
    const selectedDisaggregations = ref([]);

    const form = ref({
        name: ''
    });

    const filteredIndicators = computed(() => {
        if (!searchQuery.value) return props.program_indicators;
        
        const query = searchQuery.value.toLowerCase();
        return props.program_indicators.filter(indicator => 
            indicator.name.toLowerCase().includes(query)
        );
    });

    const openAddDialog = () => {
        isEditMode.value = false;
        form.value = {
            name: '',
            sub_program_id:undefined
        };
        errors.value = {};
        editDialog.value = true;
    };

    const openEditDialog = (indicator) => {
        isEditMode.value = true;
        selectedIndicator.value = indicator;
        form.value = {
            name: indicator.name,
            sub_program_id: indicator.sub_program_id
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
            ? `/indicator/program/${selectedIndicator.value.id}`
            : '/indicator/program/create';
        
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
            router.delete(`/indicator/program/${indicator.id}`, {}, {
                preserveScroll: true
            });
        }
    };

    const openDisaggregationDialog = (indicator) => {
        selectedIndicator.value = indicator;
        selectedDisaggregations.value = indicator.disaggregations?.map(d => d.id) || [];
        disaggregationErrors.value = {};
        disaggregationDialog.value = true;
    };

    const closeDisaggregationDialog = () => {
        disaggregationDialog.value = false;
        disaggregationErrors.value = {};
    };

    const saveDisaggregations = () => {
        disaggregationErrors.value = {};
        saving.value = true;
        router.post(`/indicator/disaggregations/set/${selectedIndicator.value.id}`, {
            disaggregations: selectedDisaggregations.value
        }, {
            preserveScroll: true,
            onSuccess: () => {
                disaggregationDialog.value = false;
                selectedIndicator.value = null;
                selectedDisaggregations.value = [];
                disaggregationErrors.value = {};
                saving.value = false;
            },
            onError: (error) => {
                disaggregationErrors.value = error;
                saving.value = false;
            }
        });
    };

</script>