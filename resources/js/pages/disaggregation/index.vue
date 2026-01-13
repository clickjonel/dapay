<template>
    <div class="w-full h-full flex flex-col justify-between items-start gap-4 p-2">

        <div class="w-full flex flex-col justify-start items-start gap-4">
            <section class="w-full flex justify-between items-center">
                <h2 class="text-xl uppercase font-semibold">Disaggregations</h2>
                <Button 
                    label="Add Disaggregation" 
                    icon="pi pi-plus" 
                    size="small"
                    @click="openAddModal"
                />
            </section>

            <section>
                <InputText 
                    v-model="searchQuery"
                    placeholder="Search Disaggregation" 
                    size="small" 
                    class="w-[400px]"
                />
            </section>
        </div>

        <section class="w-full h-full overflow-y-auto">
            <!-- Header Row -->
            <div class="w-full grid grid-cols-12 gap-4 p-4 bg-gray-100 border-b-2 border-gray-300 font-semibold text-sm uppercase sticky top-0 z-10">
                <div class="col-span-4">Disaggregation Name</div>
                <div class="col-span-3">Type</div>
                <div class="col-span-3">Status</div>
                <div class="col-span-2 text-center">Actions</div>
            </div>

            <!-- Data Rows -->
            <div v-if="filteredDisaggregations.length > 0">
                <div 
                    v-for="disaggregation in filteredDisaggregations" 
                    :key="disaggregation.id" 
                    class="w-full grid grid-cols-12 gap-4 p-4 border-b hover:bg-[#F0FCFA] transition-colors items-center"
                >
                    <!-- Disaggregation Name -->
                    <div class="col-span-4 flex items-center gap-2">
                        <span class="text-sm">{{ disaggregation.name }}</span>
                    </div>

                    <!-- Type -->
                    <div class="col-span-3 flex items-center gap-2">
                        <span class="text-sm">{{ disaggregation.type }}</span>
                    </div>

                    <!-- Status -->
                    <div class="col-span-3">
                        <Tag 
                            :value="disaggregation.active ? 'Active' : 'Inactive'" 
                            :severity="disaggregation.active ? 'success' : 'danger'"
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
                            v-tooltip.top="'Edit'"
                            @click="openEditModal(disaggregation)"
                        />
                        <Button 
                            :icon="disaggregation.active ? 'pi pi-ban' : 'pi pi-check'" 
                            text 
                            rounded
                            size="small"
                            :severity="disaggregation.active ? 'danger' : 'success'"
                            v-tooltip.top="disaggregation.active ? 'Disable' : 'Enable'"
                            @click="toggleStatus(disaggregation)"
                        />
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="w-full text-center py-12 text-gray-500">
                No disaggregations found
            </div>
        </section>

    </div>

    <!-- Add/Edit Disaggregation Modal -->
    <Dialog 
        v-model:visible="modal.show" 
        modal 
        :header="modal.isEdit ? 'Edit Disaggregation' : 'Add Disaggregation'"
        class="w-[500px]"
    >
        <form @submit.prevent="saveDisaggregation" class="w-full">
            <div class="w-full flex flex-col justify-start items-start gap-4">
                
                <!-- Disaggregation Name -->
                <div class="w-full flex flex-col gap-2">
                    <label class="text-sm font-semibold text-gray-700">
                        Disaggregation Name <span class="text-red-500">*</span>
                    </label>
                    <InputText 
                        v-model="modal.data.name" 
                        placeholder="Enter disaggregation name"
                        class="w-full"
                        required
                    />
                </div>

                <!-- Type -->
                <div class="w-full flex flex-col gap-2">
                    <label class="text-sm font-semibold text-gray-700">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <InputText 
                        v-model="modal.data.type"
                        placeholder="Enter type"
                        class="w-full"
                        required
                    />
                </div>

                <!-- Form Actions -->
                <div class="w-full flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <Button 
                        label="Cancel" 
                        severity="secondary" 
                        @click="modal.show = false"
                        type="button"
                        size="small"
                        icon="pi pi-times"
                    />
                    <Button 
                        :label="modal.isEdit ? 'Update' : 'Create'" 
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
    import { ref, computed, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';
    import MainLayout from '@/layouts/mainLayout.vue';
    import { 
        Button, 
        InputText, 
        Dialog,
        Tag
    } from 'primevue';
    import { useToast } from 'primevue/usetoast';

    const props = defineProps({
        disaggregations: Array,
    });

    defineOptions({
        layout: MainLayout
    });

    const toast = useToast();
    const searchQuery = ref('');

    const modal = ref({
        show: false,
        isEdit: false,
        data: {
            id: null,
            name: '',
            type: ''
        }
    });

    onMounted(() => {
        console.log(props.disaggregations);
    });

    const filteredDisaggregations = computed(() => {
        if (!searchQuery.value) return props.disaggregations;
        
        const query = searchQuery.value.toLowerCase();
        return props.disaggregations.filter(disaggregation => 
            disaggregation.name.toLowerCase().includes(query) ||
            disaggregation.type.toLowerCase().includes(query)
        );
    });

    const openAddModal = () => {
        modal.value = {
            show: true,
            isEdit: false,
            data: {
                id: null,
                name: '',
                type: ''
            }
        };
    };

    const openEditModal = (disaggregation) => {
        modal.value = {
            show: true,
            isEdit: true,
            data: {
                id: disaggregation.id,
                name: disaggregation.name,
                type: disaggregation.type
            }
        };
    };

    const saveDisaggregation = () => {
        if (modal.value.isEdit) {
            // Update
            router.put(`/disaggregation/${modal.value.data.id}`, modal.value.data, {
                onSuccess: () => {
                    modal.value.show = false;
                    toast.add({
                        severity: 'success',
                        summary: 'Success',
                        detail: 'Disaggregation updated successfully',
                        life: 3000
                    });
                },
                onError: (errors) => {
                    toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to update disaggregation',
                        life: 3000
                    });
                    console.error('Validation errors:', errors);
                }
            });
        } 
        else {
            // Create
            router.post('/disaggregation', modal.value.data, {
                onSuccess: () => {
                    modal.value.show = false;
                    toast.add({
                        severity: 'success',
                        summary: 'Success',
                        detail: 'Disaggregation created successfully',
                        life: 3000
                    });
                },
                onError: (errors) => {
                    toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to create disaggregation',
                        life: 3000
                    });
                    console.error('Validation errors:', errors);
                }
            });
        }
    };

    const toggleStatus = (disaggregation) => {
        const action = disaggregation.active ? 'deactivate' : 'activate';
        if (confirm(`Are you sure you want to ${action} "${disaggregation.name}"?`)) {
            router.delete(`/disaggregation/${disaggregation.id}`, 
                {
                    onSuccess: () => {
                        toast.add({
                            severity: 'success',
                            summary: 'Success',
                            detail: `Disaggregation ${action}d successfully`,
                            life: 3000
                        });
                    },
                    onError: () => {
                        toast.add({
                            severity: 'error',
                            summary: 'Error',
                            detail: `Failed to ${action} disaggregation`,
                            life: 3000
                        });
                    }
                }
            );
        }
    };

</script>