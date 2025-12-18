<template>
    <main class="w-full max-w-5xl mx-auto p-6">
        
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Create Program</h1>
            <p class="text-gray-600 text-sm">Define your program and add sub-programs</p>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <!-- Program Details Section -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="pi pi-folder text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Program Details</h2>
                        <p class="text-sm text-gray-500">Basic information about your program</p>
                    </div>
                </div>

                <div class="max-w-2xl">
                    <label for="program-name" class="block text-sm font-medium text-gray-700 mb-2">
                        Program Name <span class="text-red-500">*</span>
                    </label>
                    <InputText 
                        id="program-name"
                        v-model="form.name" 
                        placeholder="e.g., Education Support Program"
                        class="w-full"
                        :invalid="errors.name"
                        size="small"
                    />
                    <small v-if="errors.name" class="text-red-500 mt-1 block">{{ errors.name }}</small>
                    <small v-else class="text-gray-500 mt-1 block">Choose a clear, descriptive name for your program</small>
                </div>
            </div>

            <!-- Sub Programs Section -->
            <div class="p-8 bg-gray-50">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i class="pi pi-list text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Sub-Programs</h2>
                        <p class="text-sm text-gray-500">Add sub-programs if applicable, leave blank if otherwise.</p>
                    </div>
                </div>

                <!-- Add Sub Program Input -->
                <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200 shadow-sm">
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <InputText 
                                v-model="newSubProgram.name" 
                                placeholder="Enter sub-program name..."
                                class="w-full"
                                @keyup.enter="addSubProgram"
                                size="small"
                            />
                        </div>
                        <Button 
                            icon="pi pi-plus" 
                            label="Add"
                            @click="addSubProgram"
                            :disabled="!newSubProgram.name"
                            severity="secondary"
                            size="small"
                        />
                    </div>
                    <small class="text-gray-500 mt-2 block">Press Enter or click Add to create a sub-program</small>
                </div>

                <!-- Sub Programs List -->
                <div v-if="form.sub_programs.length > 0" class="space-y-3">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-700">
                            {{ form.sub_programs.length }} sub-program{{ form.sub_programs.length !== 1 ? 's' : '' }} added
                        </span>
                    </div>

                    <TransitionGroup name="list">
                        <div 
                            v-for="(subProgram, index) in form.sub_programs" 
                            :key="index"
                            class="bg-white rounded-lg p-4 border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all duration-200"
                        >
                            <div class="flex items-center gap-4">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-600 text-sm font-medium flex-shrink-0">
                                    {{ index + 1 }}
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <InputText 
                                        v-if="editingIndex === index"
                                        v-model="subProgram.name"
                                        class="w-full"
                                        @keyup.enter="editingIndex = null"
                                        @keyup.esc="editingIndex = null"
                                        autofocus
                                    />
                                    <p v-else class="text-gray-900 font-medium truncate">{{ subProgram.name }}</p>
                                </div>

                                <div class="flex gap-1 flex-shrink-0">
                                    <Button 
                                        v-if="editingIndex !== index"
                                        icon="pi pi-pencil" 
                                        text 
                                        rounded
                                        @click="editingIndex = index"
                                        severity="secondary"
                                        size="small"
                                    />
                                    <Button 
                                        v-else
                                        icon="pi pi-check" 
                                        text 
                                        rounded
                                        @click="editingIndex = null"
                                        severity="success"
                                        size="small"
                                    />
                                    <Button 
                                        icon="pi pi-trash" 
                                        text 
                                        rounded
                                        @click="removeSubProgram(index)"
                                        severity="danger"
                                        size="small"
                                    />
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white rounded-lg p-12 text-center border-2 border-dashed border-gray-300">
                    <div class="w-16 h-16 rounded-full bg-gray-100 mx-auto mb-4 flex items-center justify-center">
                        <i class="pi pi-inbox text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No sub-programs yet</h3>
                    <p class="text-gray-500 text-sm">Add your first sub-program using the form above</p>
                </div>
            </div>

            <!-- Action Footer -->
            <div class="p-6 bg-white border-t border-gray-200 flex justify-between items-center">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i class="pi pi-info-circle"></i>
                    <span>All fields marked with <span class="text-red-500">*</span> are required</span>
                </div>
                <div class="flex gap-3">
                    <Button 
                        label="Cancel" 
                        severity="secondary"
                        outlined
                        size="small"
                        @click="router.visit('/program')"
                    />
                    <Button 
                        label="Save Program" 
                        icon="pi pi-save" 
                        @click="saveAll"
                        :disabled="!form.name"
                        size="small"
                        :loading="saving"
                    />
                </div>
            </div>
        </div>

    </main>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { Link } from '@inertiajs/vue3'
    import MainLayout from '@/layouts/mainLayout.vue';
    import { router } from '@inertiajs/vue3';
    import { Button, InputText, Panel, Select, MultiSelect, DatePicker, Textarea, InputNumber } from 'primevue';
    import { Notify } from 'notiflix/build/notiflix-notify-aio';

    const form = ref({
        name: '',
        sub_programs: []
    });

    const newSubProgram = ref({
        name: ''
    });

    const editingIndex = ref(null);
    const saving = ref(false);

    const errors = ref({
        name: ''
    });

    const addSubProgram = () => {
        if (!newSubProgram.value.name.trim()) return;
        
        form.value.sub_programs.push({
            name: newSubProgram.value.name.trim()
        });
        
        newSubProgram.value.name = '';
    };

    const removeSubProgram = (index) => {
        form.value.sub_programs.splice(index, 1);
    };

    const saveAll = () => {
        errors.value.name = '';
        
        if (!form.value.name.trim()) {
            errors.value.name = 'Program name is required';
            return;
        }

        saving.value = true;

        router.post('/program', form.value, {
            onSuccess: () => {
                form.value.name = '';
                form.value.sub_programs = [];
                saving.value = false;
                Notify.success('Program Successfully Created',{
                    fontFamily:'Inter'
                });
                router.visit('/program')
            },
            onError: (err) => {
                errors.value = err;
                saving.value = false;
            },
            onFinish: () => {
                saving.value = false;
            }
        });
    };

</script>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.3s ease;
}

.list-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}

.list-leave-to {
    opacity: 0;
    transform: translateX(-20px);
}
</style>