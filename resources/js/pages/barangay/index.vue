<template>
    <div class="w-full h-full flex flex-col justify-between items-start gap-4 p-2">

        <div class="w-full flex flex-col justify-start items-start gap-4">
                <section class="w-full flex justify-between items-center">
                    <h2 class="text-xl uppercase font-semibold">Barangays</h2>
                </section>

                <section>
                    <InputText 
                        v-model="searchQuery"
                        placeholder="Search Keyword" 
                        size="small" 
                        class="w-[400px]"
                        @update:modelValue="search"
                    />
                </section>
        </div>

        <section class="w-full h-full overflow-y-auto">
            <div class="w-full grid grid-cols-6 p-4 bg-gray-100 border-b-2 border-gray-300 font-semibold text-sm uppercase sticky top-0 z-10">
                <span class="w-full">Name</span>
                <span class="w-full">Municipality</span>
                <span class="w-full">Province</span>
                <span class="w-full">PK Status</span>
                <span class="w-full">PK Site?</span>
                <span class="w-full">Action</span>
            </div>

            <div v-for="brgy in barangays.data" :key="brgy.id" class="w-full grid grid-cols-6 p-4 border-b text-sm">
                <span class="w-full">{{ brgy.name }}</span>
                <span class="w-full">{{ brgy.municipality.name }}</span>
                <span class="w-full">{{ brgy.province.name }}</span>
                <span class="w-full">{{ brgy.pk_status }}</span>
                <span class="w-full">{{ brgy.is_pk_site ? 'Yes' : 'No' }}</span>
                <span class="w-full">
                    <Button 
                        icon="pi pi-pencil" 
                        text 
                        rounded
                        size="small"
                        @click="openEditModal(brgy)"
                    />
                    <Button 
                        icon="pi pi-file-edit" 
                        text 
                        rounded
                        size="small"
                        severity="help"
                        @click="openSetBarangayIndicatorsModal(brgy)"
                    />
                </span>
            </div>

            <div v-if="barangays.data.length === 0" class="w-full p-8 text-center text-gray-500">
                No barangays found
            </div>
        </section>

        <!-- Pagination -->
        <section class="w-full flex justify-between items-center px-4 py-2 border-t">
            <div class="text-sm text-gray-600">
                Showing {{ barangays.from ?? 0 }} to {{ barangays.to ?? 0 }} results
            </div>
            
            <div class="flex items-center gap-2">
                <Button 
                    icon="pi pi-chevron-left"
                    :disabled="!barangays.prev_page_url"
                    @click="navigatePage(barangays.prev_page_url)"
                    size="small"
                    rounded
                />
                
                <span class="text-sm font-medium px-3">
                    Page {{ barangays.current_page }}
                </span>
                
                <Button 
                    icon="pi pi-chevron-right"
                    :disabled="!barangays.next_page_url"
                    @click="navigatePage(barangays.next_page_url)"
                    size="small"
                    rounded
                />
            </div>
        </section>

    </div>

    <!-- Edit Modal -->
    <Dialog 
        v-model:visible="editModal.show" 
        modal 
        header="Edit Barangay"
        class="w-[1000px]"
    >
        <form @submit.prevent="updateBarangay" class="w-full">
            <div class="w-full flex flex-col justify-start items-start gap-6">
                
                <!-- Basic Information Section -->
                <div class="w-full">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 pb-2 border-b border-gray-200">Basic Information</h3>

                    <!-- Barangay Name -->
                    <div class="flex flex-col gap-1.5 mb-4">
                        <label class="text-xs font-semibold text-gray-700">Barangay Name</label>
                        <InputText 
                            v-model="editModal.data.name" 
                            placeholder="Enter barangay name"
                            class="text-sm"
                        />
                    </div>

                    <div class="grid grid-cols-3 gap-4">  

                        <!-- PK Status -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-700">PK Status</label>
                            <Select 
                                v-model="editModal.data.pk_status"
                                :options="pk_statuses"
                                optionLabel="label"
                                optionValue="label"
                                class="w-full"
                            />
                        </div>

                        <!-- Is PK Site -->
                        <div class="flex items-center gap-2 pt-6">
                            <Checkbox 
                                v-model="editModal.data.is_pk_site" 
                                :binary="true"
                                inputId="is_pk_site"
                            />
                            <label for="is_pk_site" class="text-xs font-semibold text-gray-700">Is PK Site?</label>
                        </div>

                        <!-- Is GIDA -->
                        <div class="flex items-center gap-2 pt-6">
                            <Checkbox 
                                v-model="editModal.data.is_gida" 
                                :binary="true"
                                inputId="is_gida"
                            />
                 
                            <label for="is_gida" class="text-xs font-semibold text-gray-700">Is GIDA?</label>
                        </div>
                    </div>
                </div>

                <!-- Resource Information Section -->
                <div class="w-full">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 pb-2 border-b border-gray-200">Resource Information</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Deployed HRH -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-700">Deployed HRH</label>
                            <InputNumber 
                                v-model="editModal.data.deployed_hrh"
                                placeholder="0"
                                class="text-sm"
                            />
                        </div>

                        <!-- Total Purok -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-700">Total Purok</label>
                            <InputNumber 
                                v-model="editModal.data.total_purok"
                                placeholder="0"
                                class="text-sm"
                            />
                        </div>

                        <!-- Target Purok -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-700">Target Purok</label>
                            <InputNumber 
                                v-model="editModal.data.target_purok"
                                placeholder="0"
                                class="text-sm"
                            />
                        </div>
                    </div>
                </div>

                <!-- Target Information Section -->
                <div class="w-full">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 pb-2 border-b border-gray-200">Target Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Target Households -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-700">Target Households</label>
                            <InputNumber 
                                v-model="editModal.data.target_households"
                                placeholder="0"
                                class="text-sm"
                            />
                        </div>

                        <!-- Target Individuals -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-700">Target Individuals</label>
                            <InputNumber 
                                v-model="editModal.data.target_individuals"
                                placeholder="0"
                                class="text-sm"
                            />
                        </div>
                    </div>
                </div>

                <!-- Location Coordinates Section -->
                <div class="w-full">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 pb-2 border-b border-gray-200">Location Coordinates</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Latitude -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-700">Latitude</label>
                            <InputNumber 
                                v-model="editModal.data.latitude"
                                placeholder="0.000000"
                                :minFractionDigits="6"
                                :maxFractionDigits="6"
                                class="text-sm"
                            />
                        </div>

                        <!-- Longitude -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-700">Longitude</label>
                            <InputNumber 
                                v-model="editModal.data.longitude"
                                placeholder="0.000000"
                                :minFractionDigits="6"
                                :maxFractionDigits="6"
                                class="text-sm"
                            />
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="w-full flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <Button 
                        label="Cancel" 
                        severity="secondary" 
                        @click="editModal.show = false"
                        type="button"
                        size="small"
                    />
                    <Button 
                        label="Update Barangay" 
                        severity="success" 
                        type="submit"
                        size="small"
                    />
                </div>

            </div>
        </form>
    </Dialog>

    <!-- Barangay Organizational Indicators Modal -->
    <Dialog 
        v-model:visible="brgyOrgIndicatorModal.show" 
        modal 
        :header="`Set Indicators - ${brgyOrgIndicatorModal.barangay?.name || ''}`"
        class="w-[1200px]"
    >
        <form @submit.prevent="setBarangayIndicators" class="w-full">
            <div class="w-full flex flex-col justify-start items-start gap-4">
                
                <!-- Info Banner -->
                <div class="w-full p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="pi pi-info-circle text-blue-600 text-sm mt-0.5"></i>
                        <div class="text-xs text-blue-800">
                            <p class="font-semibold mb-1">Set Organizational Indicators</p>
                            <p>Enter/update the values and optional remarks for each organizational indicator for this barangay.</p>
                        </div>
                    </div>
                </div>

                <!-- Indicators List Header -->
                <div class="w-full grid grid-cols-12 gap-3 p-3 bg-gray-100 rounded-lg border border-gray-200">
                    <span class="col-span-1 text-xs font-bold text-gray-700 text-center">#</span>
                    <span class="col-span-5 text-xs font-bold text-gray-700">Indicator Name</span>
                    <span class="col-span-2 text-xs font-bold text-gray-700 text-center">Value</span>
                    <span class="col-span-4 text-xs font-bold text-gray-700">Remarks</span>
                </div>

                <!-- Indicators List -->
                <div class="w-full flex flex-col justify-start items-start gap-2">
                    <div 
                        v-for="indicator in brgyOrgIndicatorModal.indicators" 
                        :key="indicator.id"
                        class="w-full flex gap-4 bg-white border border-gray-200 rounded-lg hover:border-green-300 hover:shadow-sm transition-all p-1"
                    >

                        <!-- Indicator Name -->
                        <div class="w-[30%] flex flex-col justify-center">
                            <span class="text-sm font-medium text-gray-800">{{ indicator.name }}</span>
                        </div>

                        <!-- Value Input -->
                        <div class="w-[20%] flex items-center">
                            <InputNumber
                                v-model="indicator.value"
                                placeholder="Enter value"
                                class="w-full text-sm"
                                size="small"
                            />
                        </div>

                        <!-- Remarks Input -->
                        <div class="w-[50%] flex items-center">
                            <InputText
                                v-model="indicator.remarks"
                                placeholder="Optional remarks"
                                class="w-full text-sm"
                                size="small"
                            />
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="w-full flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <Button 
                        label="Cancel" 
                        severity="secondary" 
                        @click="brgyOrgIndicatorModal.show = false"
                        type="button"
                        size="small"
                        icon="pi pi-times"
                    />
                    <Button 
                        label="Save Indicators" 
                        severity="success" 
                        type="submit"
                        size="small"
                        icon="pi pi-check"
                    />
                </div>

            </div>
        </form>
    </Dialog>

</template>

<script setup>
    import { ref, computed } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { debounce } from 'lodash';
    import MainLayout from '@/layouts/mainLayout.vue';
    import { Button, InputText, Dialog, Select, Checkbox, InputNumber } from 'primevue';
    import { useToast } from 'primevue/usetoast';

    const props = defineProps({
        barangays: Object,
        filters: Object,
        organizational_indicators: Array
    })

    defineOptions({
        layout: MainLayout
    })

    const toast = useToast();
    const searchQuery = ref(props.filters?.search || '');
    const editModal = ref({
        show:false,
        data:null
    });
    const brgyOrgIndicatorModal = ref({
        show: false,
        barangay: null,
        indicators: []
    });
    const pk_statuses = [
        { label: 'Preparation' },
        { label: 'Implementing PK(without Ordinance/Resolution)' },
        { label: 'Implementing PK(with Ordinance/Resolution)' },
        { label: 'Monitoring PK Implementation' },
        { label: 'PK Status Not Set' },
    ];

    const search = debounce((value) => {
        router.get('/barangay', 
            { search: value }, 
            { 
                preserveState: true,
                replace: true,
                only: ['barangays']
            }
        );
    }, 300);

    const navigatePage = (url) => {
        if (!url) return;
        
        router.get(url, {}, {
            preserveState: true,
            preserveScroll: true,
            only: ['barangays']
        });
    };

    const openEditModal = (barangay) => {
        editModal.value = {
            show: true,
            data: { ...barangay }
        };
    };

    const openSetBarangayIndicatorsModal = (barangay) => {
        const indicators = props.organizational_indicators.map(indicator => ({
            id: indicator.id,
            name: indicator.name,
            value: 0,
            remarks: ''
        }));

        brgyOrgIndicatorModal.value = {
            show: true,
            barangay: barangay,
            indicators: indicators
        };

        if(barangay.organizational_indicators){
            barangay.organizational_indicators.forEach(i => {
                const indicator = brgyOrgIndicatorModal.value.indicators.find(ind => ind.id === i.id);
                if(indicator){
                    indicator.value = i.pivot.value;
                    indicator.remarks = i.pivot.remarks;
                }
            });
        }
    };

    const updateBarangay = () => {
        router.put(`/barangay/${editModal.value.data.id}`, editModal.value.data, {
            onSuccess: () => {
                editModal.value = {
                    show: false,
                    data: null
                };
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Barangay updated successfully',
                    life: 3000
                });
            },
            onError: (errors) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to update barangay. Please check your inputs.',
                    life: 3000
                });
                console.error('Validation errors:', errors);
            }
        });
    };

    const setBarangayIndicators = () => {
        const data = {
            barangay_id: brgyOrgIndicatorModal.value.barangay.id,
            indicators: brgyOrgIndicatorModal.value.indicators.map(ind => ({
                organizational_indicator_id: ind.id,
                value: ind.value || 0,
                remarks: ind.remarks || ''
            }))
        };
        
        router.post('/barangay-set-org_indicators', data, {
            onSuccess: () => {
                brgyOrgIndicatorModal.value = {
                    show: false,
                    barangay: null,
                    indicators: []
                };
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Indicators saved successfully',
                    life: 3000
                });
            },
            onError: (errors) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to save indicators. Please check your inputs.',
                    life: 3000
                });
                console.error('Validation errors:', errors);
            }
        });
    };

</script>