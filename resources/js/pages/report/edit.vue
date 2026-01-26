<template>
    <main class="w-full min-h-screen flex justify-center items-center bg-white">
        <section class="w-full max-w-6xl px-4 sm:px-6 py-6 sm:py-8">
            
            <!-- Header -->
            <div class="mb-8 sm:mb-12">
                <h1 class="text-xl sm:text-2xl font-light mb-2">Edit Report</h1>
                <Divider class="mt-2" />
            </div>

            <!-- Date and Barangay -->
            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-8 sm:mb-12">
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
                            input: { class: 'border-0 border-b border-gray-300 rounded-none focus:border-black px-0 text-sm sm:text-base' }
                        }"
                    />
                    <small v-if="errors.date" class="text-red-500 text-xs sm:text-sm">{{ errors.date }}</small>
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
                            input: { class: 'border-0 border-b border-gray-300 rounded-none focus:border-black px-0 text-sm sm:text-base' }
                        }"
                        filter
                    />
                    <small v-if="errors.barangay_id" class="text-red-500 text-xs sm:text-sm">{{ errors.barangay_id }}</small>
                </div>
            </div>

            <!-- Total Clients Section -->
            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-8 sm:mb-12">
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
                            input: { class: 'border-0 border-b border-gray-300 rounded-none focus:border-black px-0 w-full text-sm sm:text-base' }
                        }"
                    />
                    <small v-if="errors.total_returning_clients" class="text-red-500 text-xs sm:text-sm">{{ errors.total_returning_clients }}</small>
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
                            input: { class: 'border-0 border-b border-gray-300 rounded-none focus:border-black px-0 w-full text-sm sm:text-base' }
                        }"
                    />
                    <small v-if="errors.total_clients" class="text-red-500 text-xs sm:text-sm">{{ errors.total_clients }}</small>
                </div>
            </div>

            <!-- Indicators -->
            <div class="mb-8 sm:mb-12">
                <label class="block text-sm text-gray-600 mb-4 sm:mb-6">Indicators & Disaggregations</label>
                
                <div class="space-y-6 sm:space-y-8">
                    <div 
                        v-for="(ind, indIndex) in program_indicators" 
                        :key="ind.id"
                        class="border-l-2 border-gray-200 pl-4 sm:pl-6"
                    >
                        <!-- Indicator Name -->
                        <h3 class="text-sm sm:text-base font-medium mb-3 sm:mb-4 uppercase">{{ ind.name }}</h3>
                        
                        <!-- Disaggregations -->
                        <div class="space-y-3 sm:space-y-4">
                            <div 
                                v-for="(disagg, disaggIndex) in ind.disaggregations" 
                                :key="disagg.id"
                                class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4"
                            >
                                <span class="text-xs sm:text-sm text-gray-600 sm:w-48">{{ disagg.name }}</span>
                                <InputNumber 
                                    v-model="disagg.value"
                                    class="flex-1 w-full"
                                    placeholder="0"
                                    :min="0"
                                    :pt="{
                                        input: { class: 'border-0 border-b border-gray-200 rounded-none focus:border-gray-400 px-0 w-full text-sm sm:text-base' }
                                    }"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6">
                <Divider class="mb-4" />
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                    <Button 
                        label="Cancel"
                        @click="router.visit('/report')"
                        text
                        severity="secondary"
                        class="w-full sm:w-auto order-2 sm:order-1"
                    />
                    <Button 
                        label="Update"
                        @click="submitForm"
                        severity="contrast"
                        icon="pi pi-check"
                        class="w-full sm:w-auto order-1 sm:order-2"
                        :loading="isSubmitting"
                    />
                </div>
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
    import MainLayout from '@/layouts/mainLayout.vue';

    const props = defineProps({
        report: Object,
        program_indicators: Array,
        org_indicators: Array,
        barangays: Array
    })

    defineOptions({
        layout: MainLayout
    })

    const toast = useToast();
    const isSubmitting = ref(false);

    const form = ref({
        date: new Date(props.report.date),
        barangay_id: props.report.barangay_id,
        total_returning_clients: props.report.total_returning_clients,
        total_clients: props.report.total_clients
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

        isSubmitting.value = true;

        router.put(`/report/${props.report.id}`, payload, {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Report updated successfully',
                    life: 3000
                });
            },
            onError: (errors) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to update report. Please check your inputs.',
                    life: 3000
                });
                console.error('Validation errors:', errors);
            },
            onFinish: () => {
                isSubmitting.value = false;
            }
        });
    }
    
</script>