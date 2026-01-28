<template>
    <div class="w-full min-h-screen bg-gray-50">
        <!-- Print Button (hidden when printing) -->
        <div class="no-print w-full p-6 bg-white shadow-sm flex justify-between items-center sticky top-0 z-10">
            <Button 
                label="Back" 
                icon="pi pi-arrow-left"
                @click="router.visit('/report/monthly-user-report')"
                outlined
                class="text-gray-700"
            />
            <Button 
                label="Print Report" 
                icon="pi pi-print"
                @click="handlePrint"
                severity="success"
            />
        </div>

        <!-- Printable Content -->
        <div class="printable-content p-8 md:p-12">
            <!-- Header with Logo -->
            <div class="w-full bg-white shadow-sm rounded-lg p-8 mb-8">
                <div class="flex items-start gap-6 pb-6 border-b-2 border-blue-600">
                    <img 
                        src="/images/logo.png" 
                        alt="Organization Logo" 
                        class="h-24 w-24 object-contain flex-shrink-0"
                        @error="handleImageError"
                    />
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Monthly Activity Report</h1>
                        <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-600">
                            <p><span class="font-semibold text-gray-700">Generated:</span> {{ currentDate }}</p>
                            <p><span class="font-semibold text-gray-700">Period:</span> {{ reportMonth }}</p>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-600">
                        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">Total Reports</p>
                        <p class="text-3xl font-bold text-gray-900">{{ reports.length }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-600">
                        <p class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-1">Total Clients</p>
                        <p class="text-3xl font-bold text-gray-900">{{ totalClients }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-600">
                        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wide mb-1">Returning Clients</p>
                        <p class="text-3xl font-bold text-gray-900">{{ totalReturningClients }}</p>
                    </div>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Detailed Activity Records</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Report Details
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Program
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Indicator
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Values
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="(report, index) in reports" :key="report.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="space-y-1.5 text-sm">
                                        <div class="flex items-start">
                                            <span class="font-semibold text-gray-700 min-w-[140px]">Barangay:</span>
                                            <span class="text-gray-900">{{ report.barangay.name }}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-semibold text-gray-700 min-w-[140px]">Municipality:</span>
                                            <span class="text-gray-900">{{ report.barangay.municipality.name }}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-semibold text-gray-700 min-w-[140px]">Province:</span>
                                            <span class="text-gray-900">{{ report.barangay.municipality.province || 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-start pt-2 border-t border-gray-100">
                                            <span class="font-semibold text-gray-700 min-w-[140px]">Total Clients:</span>
                                            <span class="text-gray-900 font-medium">{{ report.total_clients }}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-semibold text-gray-700 min-w-[140px]">Returning Clients:</span>
                                            <span class="text-gray-900 font-medium">{{ report.total_returning_clients }}</span>
                                        </div>
                                        <div class="flex items-start pt-2 border-t border-gray-100">
                                            <span class="font-semibold text-gray-700 min-w-[140px]">Date:</span>
                                            <span class="text-gray-900">{{ formatDate(report.date) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ report.program || 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ report.indicator || 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <!-- {{ report.values || 'N/A' }} -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Signature Section -->
            <div class="bg-white shadow-sm rounded-lg p-8 mt-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-4">Prepared by:</p>
                        <div class="border-b-2 border-gray-300 pb-2">
                            <input 
                                type="text" 
                                class="w-full outline-none text-sm text-gray-900 uppercase tracking-wide placeholder-gray-400" 
                                placeholder="Enter name"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-2 italic">Signature over printed name</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-4">Reviewed by:</p>
                        <div class="border-b-2 border-gray-300 pb-2">
                            <input 
                                type="text" 
                                class="w-full outline-none text-sm text-gray-900 uppercase tracking-wide placeholder-gray-400" 
                                placeholder="Enter name"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-2 italic">Signature over printed name</p>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="mt-6 text-center text-xs text-gray-500">
                <p>This is a system-generated report. For inquiries, please contact the appropriate department.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import { router, usePage } from '@inertiajs/vue3';
    import { Button } from 'primevue';

    const props = defineProps({
        reports: Array,
    });

    const page = usePage();
    const user = computed(() => page.props.auth?.user);

    const handlePrint = () => {
        window.print();
    };

    const totalClients = computed(() => {
        return props.reports.reduce((sum, report) => sum + report.total_clients, 0);
    });

    const totalReturningClients = computed(() => {
        return props.reports.reduce((sum, report) => sum + report.total_returning_clients, 0);
    });

    const currentDate = new Date().toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });

    const reportMonth = computed(() => {
        const now = new Date();
        return now.toLocaleString('default', { month: 'long', year: 'numeric' });
    });

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    };

    const handleImageError = (event) => {
        event.target.style.display = 'none';
    };
</script>

<style scoped>
    @media print {
        .no-print {
            display: none !important;
        }
        
        .printable-content {
            padding: 40px !important;
            background: white !important;
        }
        
        body {
            background: white;
        }
        
        @page {
            margin: 1.5cm;
            size: A4;
        }
        
        /* Ensure cards and sections don't break across pages */
        .bg-white {
            page-break-inside: avoid;
        }
        
        /* Remove shadows and rounded corners for print */
        .shadow-sm,
        .rounded-lg {
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        
        /* Enhance borders for print */
        .border-b-2 {
            border-bottom-width: 2px !important;
        }
        
        /* Keep table structure intact */
        table {
            page-break-inside: auto;
        }
        
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        thead {
            display: table-header-group;
        }
        
        /* Adjust colors for print */
        .bg-blue-50,
        .bg-green-50,
        .bg-purple-50 {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
    }

    /* Screen view styles */
    .printable-content {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Smooth transitions */
    tr {
        transition: background-color 0.15s ease;
    }

    /* Input styling */
    input::placeholder {
        opacity: 0.5;
    }

    input:focus {
        border-color: #3b82f6;
    }
</style>