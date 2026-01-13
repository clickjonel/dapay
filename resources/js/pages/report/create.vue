<template>
    <main class="w-full min-h-screen flex justify-center items-center bg-white">
        <section class="w-full px-6 py-8">
            
            <!-- Header -->
            <div class="mb-12">
                <h1 class="text-2xl font-light mb-2">Create Report</h1>
                <Divider class="mt-2" />
            </div>

            <!-- Date and Barangay -->
            <div class="w-full grid grid-cols-2 gap-4 mb-12">
                <!-- Date -->
                <div>
                    <label class="block text-sm text-gray-600 mb-3">
                        Report Date <span class="text-red-500">*</span>
                    </label>
                    <DatePicker 
                        v-model="form.date"
                        dateFormat="yy-mm-dd"
                        showIcon
                        placeholder="Select date"
                        class="w-full"
                        input-class="w-full"
                        :class="{ 'border-red-500': errors.date }"
                        :pt="{
                            input: { class: 'border-0 border-b border-gray-300 rounded-none focus:border-black px-0' }
                        }"
                    />
                    <small v-if="errors.date" class="text-red-500">{{ errors.date }}</small>
                </div>

                <!-- Barangay -->
                <div>
                    <label class="block text-sm text-gray-600 mb-3">
                        Barangay <span class="text-red-500">*</span>
                    </label>
                    <Select 
                        v-model="form.barangay_id"
                        :options="barangays"
                        optionLabel="name"
                        optionValue="id"
                        placeholder="Select barangay"
                        class="w-full"
                        :class="{ 'border-red-500': errors.barangay_id }"
                        :pt="{
                            input: { class: 'border-0 border-b border-gray-300 rounded-none focus:border-black px-0' }
                        }"
                        filter
                    />
                    <small v-if="errors.barangay_id" class="text-red-500">{{ errors.barangay_id }}</small>
                </div>
            </div>

            <!-- Total Clients Section -->
            <div class="w-full grid grid-cols-2 gap-4 mb-12">
                <!-- Total Returning Clients -->
                <div>
                    <label class="block text-sm text-gray-600 mb-3">
                        Total Returning Clients <span class="text-red-500">*</span>
                    </label>
                    <InputNumber 
                        v-model="form.total_returning_clients"
                        class="w-full"
                        :class="{ 'border-red-500': errors.total_returning_clients }"
                        placeholder="0"
                        :pt="{
                            input: { class: 'border-0 border-b border-gray-300 rounded-none focus:border-black px-0 w-full' }
                        }"
                    />
                    <small v-if="errors.total_returning_clients" class="text-red-500">{{ errors.total_returning_clients }}</small>
                </div>

                <!-- Total Individual Clients -->
                <div>
                    <label class="block text-sm text-gray-600 mb-3">
                        Total Individual Clients <span class="text-red-500">*</span>
                    </label>
                    <InputNumber 
                        v-model="form.total_clients"
                        class="w-full"
                        :class="{ 'border-red-500': errors.total_clients }"
                        placeholder="0"
                        :pt="{
                            input: { class: 'border-0 border-b border-gray-300 rounded-none focus:border-black px-0 w-full' }
                        }"
                    />
                    <small v-if="errors.total_clients" class="text-red-500">{{ errors.total_clients }}</small>
                </div>
            </div>

            <!-- Indicators -->
            <div class="mb-12">
                <label class="block text-sm text-gray-600 mb-6">Indicators & Disaggregations</label>
                
                <div class="space-y-8">
                    <div 
                        v-for="(ind, indIndex) in program_indicators" 
                        :key="ind.id"
                        class="border-l-2 border-gray-200 pl-6"
                    >
                        <!-- Indicator Name -->
                        <h3 class="text-base font-medium mb-4 uppercase">{{ ind.name }}</h3>
                        
                        <!-- Disaggregations -->
                        <div class="space-y-4">
                            <div 
                                v-for="(disagg, disaggIndex) in ind.disaggregations" 
                                :key="disagg.id"
                                class="flex items-center gap-4"
                            >
                                <span class="text-sm text-gray-600 w-48">{{ disagg.name }}</span>
                                <InputNumber 
                                    v-model="disagg.value"
                                    class="flex-1"
                                    placeholder="0"
                                    :min="0"
                                    :pt="{
                                        input: { class: 'border-0 border-b border-gray-200 rounded-none focus:border-gray-400 px-0 w-full' }
                                    }"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-6">
                <Divider class="mb-4" />
                <Button 
                    label="Cancel"
                    @click="router.visit('/report')"
                    text
                    severity="secondary"
                />
                <Button 
                    label="Save"
                    @click="submitForm"
                    severity="contrast"
                    icon="pi pi-check"
                />
            </div>

        </section>

        <Toast position="top-right"/>

    </main>
</template>

<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { Button, InputNumber, Divider, DatePicker, Select, Toast } from 'primevue';
    import { useToast } from 'primevue/usetoast';

    const props = defineProps({
        program_indicators: Array,
        org_indicators: Array,
        barangays: Array
    })

    const toast = useToast();
    const form = ref({
        date: null,
        barangay_id: null,
        total_returning_clients: null,
        total_clients: null
    })

    const errors = ref({
        date: null,
        barangay_id: null,
        total_returning_clients: null,
        total_clients: null
    })

    const validateForm = () => {
        errors.value = {
            date: null,
            barangay_id: null,
            total_returning_clients: null,
            total_clients: null
        };

        let isValid = true;

        if (!form.value.date) {
            errors.value.date = 'Report date is required';
            isValid = false;
        }

        if (!form.value.barangay_id) {
            errors.value.barangay_id = 'Barangay is required';
            isValid = false;
        }

        if (!form.value.total_returning_clients || form.value.total_returning_clients < 1) {
            errors.value.total_returning_clients = 'Total returning clients must be at least 1';
            isValid = false;
        }

        if (!form.value.total_clients || form.value.total_clients < 1) {
            errors.value.total_clients = 'Total individual clients must be at least 1';
            isValid = false;
        }

        return isValid;
    }

    const submitForm = () => {
        if (!validateForm()) {
            toast.add({
                severity: 'warn',
                summary: 'Validation Error',
                detail: 'Please fill in all required fields correctly',
                life: 3000
            });
            return;
        }

        const programValues = props.program_indicators.flatMap(indicator => 
            indicator.disaggregations.map(disagg => ({
                sub_program_id: indicator.sub_program_id || null,
                program_indicator_id: indicator.id,
                organizational_indicator_id: null,
                disaggregation_id: disagg.id,
                indicator_type: 'program',
                value: disagg.value || 0
            }))
        );

        const payload = {
            date: form.value.date.toISOString().split('T')[0],
            barangay_id: form.value.barangay_id,
            total_returning_clients: form.value.total_returning_clients,
            total_clients: form.value.total_clients,
            values: programValues
        };

        router.post('/report', payload, {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Report created successfully',
                    life: 3000
                });
            },
            onError: (errors) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to create report. Please check your inputs.',
                    life: 3000
                });
                console.error('Validation errors:', errors);
            }
        });
    }
    
</script>