<template>
    <div class="w-full h-full flex flex-col justify-between items-start gap-4 p-2 sm:p-4">

        <div class="w-full flex flex-col justify-start items-start gap-4">
                <section class="w-full flex justify-between items-center">
                    <h2 class="text-lg sm:text-xl uppercase font-semibold">Your Reports This Month</h2>
                    <Button 
                        label="Print Report" 
                        icon="pi pi-print"
                        @click="router.visit('/report/print')"
                        size="small"
                    />
                </section>
        </div>

        <section class="w-full h-full overflow-x-auto overflow-y-auto">
            <!-- Desktop Table View -->
            <div class="hidden lg:block min-w-full">
                <div class="w-full grid grid-cols-7 p-4 bg-gray-100 border-b-2 border-gray-300 font-semibold text-sm uppercase sticky top-0 z-10">
                    <span class="w-full">Date</span>
                    <span class="w-full">Barangay</span>
                    <span class="w-full">Municipality</span>
                    <span class="w-full">Total Clients</span>
                    <span class="w-full">Returning Clients</span>
                    <span class="w-full">Created By</span>
                    <span class="w-full">Action</span>
                </div>

                <div v-for="report in reports" :key="report.id" class="w-full grid grid-cols-7 p-4 border-b text-sm hover:bg-gray-50">
                    <span class="w-full">{{ report.date }}</span>
                    <span class="w-full">{{ report.barangay.name }}</span>
                    <span class="w-full">{{ report.barangay.municipality.name }}</span>
                    <span class="w-full">{{ report.total_clients }}</span>
                    <span class="w-full">{{ report.total_returning_clients }}</span>
                    <span class="w-full">{{ report.user?.full_name || 'N/A' }}</span>
                    <span class="w-full flex gap-2">
                        <Button 
                            icon="pi pi-eye" 
                            text 
                            rounded
                            size="small"
                            @click="router.visit(`/report/${report.id}`)"
                        />
                        <Button 
                            icon="pi pi-pencil" 
                            text 
                            rounded
                            size="small"
                            @click="router.visit(`/report/${report.id}/edit`)"
                        />
                        <Button 
                            icon="pi pi-trash" 
                            text 
                            rounded
                            size="small"
                            severity="danger"
                            @click="deleteReport(report.id)"
                        />
                    </span> 
                </div>
            </div>

            <!-- Mobile/Tablet Card View -->
            <div class="lg:hidden space-y-3">
                <div 
                    v-for="report in reports" 
                    :key="report.id" 
                    class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
                >
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Date</div>
                            <div class="font-semibold text-sm">{{ report.date }}</div>
                        </div>
                        <div class="flex gap-1">
                            <Button 
                                icon="pi pi-eye" 
                                text 
                                rounded
                                size="small"
                                @click="router.visit(`/report/${report.id}`)"
                            />
                            <Button 
                                icon="pi pi-pencil" 
                                text 
                                rounded
                                size="small"
                                @click="router.visit(`/report/${report.id}/edit`)"
                            />
                            <Button 
                                icon="pi pi-trash" 
                                text 
                                rounded
                                size="small"
                                severity="danger"
                                @click="deleteReport(report.id)"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Barangay</div>
                            <div class="font-medium">{{ report.barangay.name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Municipality</div>
                            <div class="font-medium">{{ report.barangay.municipality.name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Total Clients</div>
                            <div class="font-medium">{{ report.total_clients }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Returning Clients</div>
                            <div class="font-medium">{{ report.total_returning_clients }}</div>
                        </div>
                        <div class="col-span-2">
                            <div class="text-xs text-gray-500 mb-1">Created By</div>
                            <div class="font-medium">{{ report.user?.full_name || 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="reports.length === 0" class="w-full p-8 text-center text-gray-500">
                No reports found
            </div>
        </section>

    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { debounce } from 'lodash';
    import MainLayout from '@/layouts/mainLayout.vue';
    import { Button, InputText, DatePicker } from 'primevue';

    const props = defineProps({
        reports: Array,
        //filters: Object
    })

    defineOptions({
        layout: MainLayout
    })

    // const searchQuery = ref(props.filters?.search || '');

    // const search = debounce(() => {
    //     router.get('/report', 
    //         { 
    //             search: searchQuery.value,
    //         }, 
    //         { 
    //             preserveState: true,
    //             replace: true,
    //             only: ['reports']
    //         }
    //     );
    // }, 300);

    const navigatePage = (url) => {
        if (!url) return;
        
        router.get(url, {}, {
            preserveState: true,
            preserveScroll: true,
            only: ['reports']
        });
    };


</script>