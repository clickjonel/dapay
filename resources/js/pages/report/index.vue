<template>
    <div class="w-full h-full flex flex-col justify-between items-start gap-4 p-2">

        <div class="w-full flex flex-col justify-start items-start gap-4">
                <section class="w-full flex justify-between items-center">
                    <h2 class="text-xl uppercase font-semibold">Reports</h2>
                    <!-- <Button 
                        label="Create Report" 
                        icon="pi pi-plus"
                        @click="router.visit('/report/create')"
                        size="small"
                    /> -->
                </section>

                <section class="flex gap-2">
                    <InputText 
                        v-model="searchQuery"
                        placeholder="Search by barangay" 
                        size="small" 
                        class="w-[300px]"
                        @update:modelValue="search"
                    />
                    <!-- <DatePicker 
                        v-model="dateFilter"
                        placeholder="Filter by date"
                        dateFormat="yy-mm-dd"
                        showIcon
                        size="small"
                        class="w-[200px]"
                        @update:modelValue="search"
                    /> -->
                </section>
        </div>

        <section class="w-full h-full overflow-y-auto">
            <div class="w-full grid grid-cols-7 p-4 bg-gray-100 border-b-2 border-gray-300 font-semibold text-sm uppercase sticky top-0 z-10">
                <span class="w-full">Date</span>
                <span class="w-full">Barangay</span>
                <span class="w-full">Municipality</span>
                <span class="w-full">Total Clients</span>
                <span class="w-full">Returning Clients</span>
                <span class="w-full">Created By</span>
                <span class="w-full">Action</span>
            </div>

            <div v-for="report in reports.data" :key="report.id" class="w-full grid grid-cols-7 p-4 border-b text-sm">
                <span class="w-full">{{ report.date }}</span>
                <span class="w-full">{{ report.barangay.name }}</span>
                <span class="w-full">{{ report.barangay.municipality.name }}</span>
                <span class="w-full">{{ report.total_clients }}</span>
                <span class="w-full">{{ report.total_returning_clients }}</span>
                <span class="w-full">{{ report.user?.full_name || 'N/A' }}</span>
                <span class="w-full flex gap-2">
                    <!-- <Button 
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
                    /> -->
                    <!-- <Button 
                        icon="pi pi-trash" 
                        text 
                        rounded
                        size="small"
                        severity="danger"
                        @click="deleteReport(report.id)"
                    /> -->
                </span> 
            </div>

            <div v-if="reports.data.length === 0" class="w-full p-8 text-center text-gray-500">
                No reports found
            </div>
        </section>

        <!-- Pagination -->
        <section class="w-full flex justify-between items-center px-4 py-2 border-t">
            <div class="text-sm text-gray-600">
                Showing {{ reports.from ?? 0 }} to {{ reports.to ?? 0 }} results
            </div>
            
            <div class="flex items-center gap-2">
                <Button 
                    icon="pi pi-chevron-left"
                    :disabled="!reports.prev_page_url"
                    @click="navigatePage(reports.prev_page_url)"
                    size="small"
                    text
                />
                
                <span class="text-sm font-medium px-3">
                    Page {{ reports.current_page }}
                </span>
                
                <Button 
                    icon="pi pi-chevron-right"
                    :disabled="!reports.next_page_url"
                    @click="navigatePage(reports.next_page_url)"
                    size="small"
                    text
                />
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
        reports: Object,
        filters: Object
    })

    defineOptions({
        layout: MainLayout
    })

    const searchQuery = ref(props.filters?.search || '');

    const search = debounce(() => {
        router.get('/report', 
            { 
                search: searchQuery.value,
            }, 
            { 
                preserveState: true,
                replace: true,
                only: ['reports']
            }
        );
    }, 300);

    const navigatePage = (url) => {
        if (!url) return;
        
        router.get(url, {}, {
            preserveState: true,
            preserveScroll: true,
            only: ['reports']
        });
    };


</script>