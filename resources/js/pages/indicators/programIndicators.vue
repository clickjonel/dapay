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
                    <div class="col-span-2 flex flex-wrap items-start gap-1">
                        <span 
                            v-for="dis in indicator.disaggregations" 
                            :key="dis.id"
                            class="text-xs px-2 py-0.5 rounded-full flex items-center gap-1"
                            :class="dis.pivot?.totalable ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700'"
                        >
                            {{ dis.name }}
                            <i 
                                v-if="dis.pivot?.totalable" 
                                class="pi pi-calculator text-[10px]"
                                v-tooltip.top="'Included in totals'"
                            ></i>
                        </span>
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
            :style="{ width: '80vw' }"
        >
            <div v-if="selectedIndicator" class="flex flex-col gap-4">
                <div class="font-medium uppercase underline text-base">
                    Indicator: {{ selectedIndicator.name }}
                </div>

                <!-- Info Banner -->
                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="pi pi-info-circle text-blue-600 text-sm mt-0.5"></i>
                        <div class="text-xs text-blue-800">
                            <p class="font-semibold mb-1">Configure Disaggregations</p>
                            <p>Select disaggregations and mark which ones should be included in total calculations. For example: "Male" and "Female" should be included, but "4Ps Beneficiary" should not (to avoid double counting).</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col gap-4">
                    <div 
                            v-for="(disaggregationGroupArray, groupName) in disaggregations" 
                            :key="groupName"
                            class="w-full flex flex-col gap-2 rounded-lg border border-gray-200 p-2 shadow-md"
                        >
                            <span class="w-full px-2 text-base uppercase font-bold border-b border-gray-200">
                                {{ groupName }}
                            </span>

                            <div 
                            v-for="disaggregation in disaggregationGroupArray" 
                            :key="disaggregation.id"
                            class="w-full p-3 flex justify-between items-center bg-gray-100 rounded"
                        >
                            <div class="flex flex-col gap-1">
                                <span class="text-base font-medium">
                                    {{ disaggregation.name }}
                                </span>

                                <!-- TOTALABLE CHECKBOX -->
                                <div 
                                    v-if="selectedDisaggregations.includes(disaggregation.id)"
                                    class="flex items-center gap-2 text-xs text-blue-700"
                                >
                                    <Checkbox
                                        v-model="totalableDisaggregations"
                                        :value="disaggregation.id"
                                        inputId="`totalable-${disaggregation.id}`"
                                    />
                                    <label :for="`totalable-${disaggregation.id}`">
                                        Include in total
                                    </label>
                                </div>
                            </div>

                            <!-- ADD / REMOVE -->
                            <Button
                                :icon="selectedDisaggregations.includes(disaggregation.id) ? 'pi pi-minus' : 'pi pi-plus'"
                                rounded
                                size="small"
                                outlined
                                :severity="selectedDisaggregations.includes(disaggregation.id) ? 'warn' : 'help'"
                                @click="handleDisaggregationAction(disaggregation.id)"
                            />
                        </div>
                    </div>
                </div>
                
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
        disaggregations: Object,
        sub_programs: Array
    });

    defineOptions({
        layout: MainLayout
    });

    onMounted(() => {
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
    const totalableDisaggregations = ref([])

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
            sub_program_id: undefined
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
        //set the selected indicator
        selectedIndicator.value = indicator;

        //set selected disaggregations
        selectedDisaggregations.value = indicator.disaggregations?.map(disaggregation => disaggregation.id) || [];
        totalableDisaggregations.value = indicator.disaggregations?.filter(disaggregation => disaggregation.pivot?.totalable === 1).map(disaggregation => disaggregation.id) || [];

        disaggregationErrors.value = {};
        disaggregationDialog.value = true;

        // console.log(totalableDisaggregations.value);
    };

    const closeDisaggregationDialog = () => {
        disaggregationDialog.value = false;
        disaggregationErrors.value = {};
    };

    const saveDisaggregations = () => {
        // console.log(selectedDisaggregations.value);
        // console.log(totalableDisaggregations.value);
        disaggregationErrors.value = {};
        saving.value = true;
        
        // // Build array with disaggregation_id and totalable flag
        const disaggregationsData = selectedDisaggregations.value.map(id => ({
            disaggregation_id: id,
            totalable: totalableDisaggregations.value.includes(id),
            program_indicator_id:selectedIndicator.id
        }));

        router.post(`/indicator/disaggregations/set/${selectedIndicator.value.id}`, {
            disaggregations: disaggregationsData
        }, {
            preserveScroll: true,
            onSuccess: () => {
                disaggregationDialog.value = false;
                selectedIndicator.value = null;
                selectedDisaggregations.value = [];
                totalableDisaggregations.value = [];
                disaggregationErrors.value = {};
                saving.value = false;
            },
            onError: (error) => {
                disaggregationErrors.value = error;
                saving.value = false;
            }
        });
    };


    const handleDisaggregationAction = (disaggregationId) => {
        const index = selectedDisaggregations.value.indexOf(disaggregationId);

        if (index === -1) {
            // ADD
            selectedDisaggregations.value.push(disaggregationId);
        } else {
            // REMOVE
            selectedDisaggregations.value.splice(index, 1);
        }

    }
</script>