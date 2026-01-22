<template>
    <main class="w-full min-h-screen flex justify-center items-center bg-gradient-to-br from-sky-50 to-blue-100 p-4">
        <section class="w-full max-w-4xl bg-white rounded-xl shadow-lg p-6 sm:p-8">
            
            <!-- Header -->
            <div class="mb-4 sm:mb-6">
                <h1 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">User Registration</h1>
                <p class="text-xs sm:text-sm text-gray-600">Create a new user account</p>
                <Divider class="mt-3" />
            </div>

            <!-- Personal Information Section -->
            <div class="mb-6">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Personal Information</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                    <!-- Prefix -->
                    <div class="sm:col-span-1">
                        <label class="block text-xs text-gray-600 mb-1.5">
                            Prefix
                        </label>
                        <Select 
                            v-model="form.prefix"
                            :options="prefixes"
                            placeholder="Select"
                            class="w-full text-xs"
                            :pt="{
                                input: { class: 'border border-gray-300 rounded-md focus:border-blue-500 px-2 py-1.5 text-xs' },
                                panel: { class: 'text-xs' }
                            }"
                        />
                    </div>

                    <!-- First Name -->
                    <div class="sm:col-span-1 lg:col-span-3">
                        <label class="block text-xs text-gray-600 mb-1.5">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            v-model="form.first_name"
                            placeholder="Enter first name"
                            class="w-full text-xs px-2 py-1.5"
                            :class="{ 'border-red-500': errors.first_name }"
                        />
                        <small v-if="errors.first_name" class="text-red-500 text-xs">{{ errors.first_name }}</small>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                    <!-- Middle Name -->
                    <div>
                        <label class="block text-xs text-gray-600 mb-1.5">
                            Middle Name
                        </label>
                        <InputText 
                            v-model="form.middle_name"
                            placeholder="Enter middle name"
                            class="w-full text-xs px-2 py-1.5"
                        />
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-xs text-gray-600 mb-1.5">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            v-model="form.last_name"
                            placeholder="Enter last name"
                            class="w-full text-xs px-2 py-1.5"
                            :class="{ 'border-red-500': errors.last_name }"
                        />
                        <small v-if="errors.last_name" class="text-red-500 text-xs">{{ errors.last_name }}</small>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Suffix -->
                    <div>
                        <label class="block text-xs text-gray-600 mb-1.5">
                            Suffix
                        </label>
                        <Select 
                            v-model="form.suffix"
                            :options="suffixes"
                            placeholder="Select"
                            class="w-full text-xs"
                            :pt="{
                                input: { class: 'border border-gray-300 rounded-md focus:border-blue-500 px-2 py-1.5 text-xs' },
                                panel: { class: 'text-xs' }
                            }"
                        />
                    </div>

                    <!-- Nickname -->
                    <div>
                        <label class="block text-xs text-gray-600 mb-1.5">
                            Nickname
                        </label>
                        <InputText 
                            v-model="form.nickname"
                            placeholder="Enter nickname"
                            class="w-full text-xs px-2 py-1.5"
                        />
                    </div>
                </div>
            </div>

            <Divider />

            <!-- Account Information Section -->
            <div class="mb-6 mt-4">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Account Information</h2>
                
                <!-- Username -->
                <div>
                    <label class="block text-xs text-gray-600 mb-1.5">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <InputText 
                        v-model="form.username"
                        placeholder="Enter username"
                        class="w-full text-xs px-2 py-1.5"
                        :class="{ 'border-red-500': errors.username }"
                    />
                    <small v-if="errors.username" class="text-red-500 text-xs">{{ errors.username }}</small>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 pt-4 border-t">
                <Button 
                    label="Cancel"
                    @click="handleCancel"
                    text
                    severity="secondary"
                    size="small"
                    class="w-full sm:w-auto order-2 sm:order-1 text-xs"
                />
                <Button 
                    label="Register User"
                    @click="submitForm"
                    severity="contrast"
                    icon="pi pi-user-plus"
                    size="small"
                    class="w-full sm:w-auto order-1 sm:order-2 text-xs"
                    :loading="isSubmitting"
                />
            </div>

        </section>

        <Toast position="top-right"/>

    </main>
</template>

<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { Button, InputText, Select, Divider, Toast } from 'primevue';
    import { useToast } from 'primevue/usetoast';

    const props = defineProps({
        // No props needed since assignment fields removed
    })

    const toast = useToast();
    const isSubmitting = ref(false);

    const prefixes = ['Mr.', 'Ms.', 'Mrs.', 'Dr.', 'Engr.', 'Atty.'];
    const suffixes = ['Jr.', 'Sr.', 'II', 'III', 'IV'];

    const form = ref({
        prefix: null,
        first_name: '',
        middle_name: '',
        last_name: '',
        suffix: null,
        nickname: '',
        username: ''
    })

    const errors = ref({
        first_name: null,
        last_name: null,
        username: null
    })

    const validateForm = () => {
        // Reset errors
        errors.value = {
            first_name: null,
            last_name: null,
            username: null
        };

        let isValid = true;

        // Required fields validation
        if (!form.value.first_name || form.value.first_name.trim() === '') {
            errors.value.first_name = 'First name is required';
            isValid = false;
        }

        if (!form.value.last_name || form.value.last_name.trim() === '') {
            errors.value.last_name = 'Last name is required';
            isValid = false;
        }

        if (!form.value.username || form.value.username.trim() === '') {
            errors.value.username = 'Username is required';
            isValid = false;
        } else if (form.value.username.length < 4) {
            errors.value.username = 'Username must be at least 4 characters';
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

        isSubmitting.value = true;

        router.post('/user', form.value, {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'User registered successfully',
                    life: 3000
                });
                // Reset form
                form.value = {
                    prefix: null,
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    suffix: null,
                    nickname: '',
                    username: ''
                };
            },
            onError: (serverErrors) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to register user. Please check your inputs.',
                    life: 3000
                });
                // Map server errors to form errors
                Object.keys(serverErrors).forEach(key => {
                    if (errors.value.hasOwnProperty(key)) {
                        errors.value[key] = serverErrors[key];
                    }
                });
            },
            onFinish: () => {
                isSubmitting.value = false;
            }
        });
    }

    const handleCancel = () => {
        router.visit('/users'); // Adjust the route as needed
    }
    
</script>