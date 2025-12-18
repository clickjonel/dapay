<template>
    <div class="w-full min-h-screen flex justify-center items-center bg-gradient-to-br from-blue-50 via-sky-50 to-slate-100">
        <Card class="w-full max-w-md shadow-xl">
            <template #content>
                <!-- Header -->
                <div class="w-full flex flex-col justify-center items-center mb-8">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mb-4">
                        <i class="pi pi-box text-white text-2xl"></i>
                    </div>
                    <h1 class="text-xl font-bold text-gray-800 text-center">
                        Dap-ay Reporting System (Purokalusugan)
                    </h1>
                    <p class="text-sm text-gray-600 text-center mt-2">
                        Center for Health Development - Cordillera
                    </p>
                    <p class="text-sm text-gray-500 text-center">
                        Department of Health
                    </p>
                </div>

                <!-- Login Form -->
                <form @submit.prevent="login" class="w-full flex flex-col gap-6">
                    <div class="w-full">
                        <p class="text-base font-semibold text-gray-700 mb-4">
                            Login to enter system
                        </p>
                        
                        <!-- Username Field -->
                        <div class="flex flex-col gap-2 mb-4">
                            <label for="username" class="text-sm font-medium text-gray-700">
                                Username
                            </label>
                            <InputText 
                                id="username"
                                v-model="form.username"
                                placeholder="Enter your username"
                                class="w-full"
                                :class="{ 'p-invalid': errors.username }"
                                required
                            />
                            <small v-if="errors.username" class="text-red-500">
                                {{ errors.username }}
                            </small>
                        </div>

                        <!-- Password Field -->
                        <div class="flex flex-col gap-2">
                            <label for="password" class="text-sm font-medium text-gray-700">
                                Password
                            </label>
                            <Password 
                                id="password"
                                v-model="form.password"
                                :feedback="false" 
                                toggleMask 
                                placeholder="Enter your password"
                                class="w-full"
                                :class="{ 'p-invalid': errors.password }"
                                :pt="{
                                    pcInputText: {
                                        root: { class: 'w-full' }
                                    }
                                }"
                                required
                            />
                            <small v-if="errors.password" class="text-red-500">
                                {{ errors.password }}
                            </small>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <Button 
                        type="submit"
                        label="Login to LIMS" 
                        icon="pi pi-sign-in"
                        class="w-full"
                        :loading="processing"
                    />

                    <!-- Error Message -->
                    <Message 
                        v-if="errors.general" 
                        severity="error" 
                        :closable="false"
                    >
                        {{ errors.general }}
                    </Message>
                </form>

                <!-- Footer -->
                <div class="text-center mt-6 pt-4 border-t border-gray-200">
                    <!-- <a href="#" class="text-sm text-blue-600 hover:underline">
                        Forgot your password?
                    </a> -->
                </div>
            </template>
        </Card>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import { InputText, Card, Password, Button, Message } from 'primevue';
    import { useForm } from '@inertiajs/vue3';

    // Using Inertia's useForm for better form handling
    const form = useForm({
        username: '',
        password: ''
    });

    const processing = ref(false);
    const errors = ref({});

    // Login function using useForm (recommended)
    const login = () => {
        form.post('/login', {
            onSuccess: (response) => {
                // Redirect is handled automatically by Inertia
                //console.log(response)
            },
            onError: (err) => {
                errors.value = err;
            }
        });
    };

</script>