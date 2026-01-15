<template>
    <div class="w-full p-4 bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 min-h-screen">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="text-xl font-bold text-green-800">Programs Dashboard</h1>
            <p class="text-xs text-gray-600 mt-1">Overview of program indicators across all provinces</p>
        </div>

        <!-- Programs Grid -->
        <div class="w-full flex flex-col gap-4">
            <Card 
                v-for="(program, programName) in programs" 
                :key="programName"
                class="hover:shadow-2xl transition-all duration-300 border-0 overflow-hidden"
            >
                <template #header>
                    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 p-3 text-white relative overflow-hidden">
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full -mr-16 -mt-16"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-full -ml-12 -mb-12"></div>
                        </div>
                        
                        <div class="relative z-10 flex items-center justify-between">
                            <div>
                                <h2 class="text-base font-bold mb-0.5">{{ programName }}</h2>
                                <p class="text-green-100 text-xs">
                                    {{ Object.keys(program.indicators || {}).length }} Indicator{{ Object.keys(program.indicators || {}).length !== 1 ? 's' : '' }}
                                </p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="pi pi-chart-bar text-xl"></i>
                            </div>
                        </div>
                    </div>
                </template>

                <template #content>
                    <div v-if="program.indicators && Object.keys(program.indicators).length > 0" class="space-y-4">
                        <!-- Indicator Card -->
                        <div 
                            v-for="(indicator, indicatorName) in program.indicators" 
                            :key="indicatorName"
                            class="border border-green-200 rounded-lg p-3 bg-white hover:shadow-md transition-shadow"
                        >
                            <!-- Indicator Header -->
                            <div class="flex items-start justify-between mb-3 pb-2 border-b border-green-100">
                                <div class="flex items-center gap-2">
                                    <div class="bg-green-100 rounded-lg p-1.5">
                                        <i class="pi pi-chart-line text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-800">{{ indicatorName }}</h3>
                                        <p class="text-xs text-gray-500">
                                            Provincial breakdown • 
                                            <span class="text-blue-600">{{ (indicator.disaggregations || []).length }} disaggregation(s)</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Total Badge -->
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-3 py-1.5 rounded-lg shadow-sm">
                                    <div class="text-xs font-medium uppercase tracking-wide">Grand Total</div>
                                    <div class="text-lg font-bold">{{ indicator.total }}</div>
                                </div>
                            </div>

                            <!-- Provinces Grid -->
                            <div v-if="indicator.provinces && Object.keys(indicator.provinces).length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <div 
                                    v-for="(provinceData, provinceName) in indicator.provinces" 
                                    :key="provinceName"
                                    class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-3 border border-green-200 hover:border-emerald-400 hover:shadow-lg transition-all duration-200"
                                >
                                    <!-- Province Header -->
                                    <div class="flex items-center justify-between mb-2 pb-2 border-b border-green-300">
                                        <div class="flex items-center gap-2">
                                            <div class="bg-white rounded-full p-1 shadow-sm">
                                                <i class="pi pi-map-marker text-xs text-green-600"></i>
                                            </div>
                                            <span class="text-sm font-bold text-gray-800">{{ provinceName }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <i class="pi pi-calculator text-xs text-green-600"></i>
                                            <span class="text-base font-bold text-green-700">{{ provinceData.total }}</span>
                                        </div>
                                    </div>

                                    <!-- Disaggregations List -->
                                    <div class="space-y-1 max-h-48 overflow-y-auto pr-1">
                                        <div 
                                            v-for="(disagg, index) in (provinceData.disaggregations || [])"
                                            :key="index"
                                            class="flex items-center justify-between text-xs py-1.5 px-2 rounded transition-all"
                                            :class="disagg.totalable 
                                                ? 'bg-blue-50 hover:bg-blue-100 border border-blue-200' 
                                                : 'bg-white hover:bg-gray-50 border border-gray-200'"
                                        >
                                            <div class="flex items-center gap-1.5 flex-1 min-w-0">
                                                <i 
                                                    v-if="disagg.totalable" 
                                                    class="pi pi-check-circle text-[10px] text-blue-600 flex-shrink-0"
                                                    v-tooltip.top="'Included in total calculation'"
                                                ></i>
                                                <i 
                                                    v-else 
                                                    class="pi pi-info-circle text-[10px] text-gray-400 flex-shrink-0"
                                                    v-tooltip.top="'Reference only - not in total'"
                                                ></i>
                                                <span 
                                                    class="font-medium truncate"
                                                    :class="disagg.totalable ? 'text-blue-700' : 'text-gray-600'"
                                                >
                                                    {{ disagg.name }}
                                                </span>
                                            </div>
                                            <span 
                                                class="font-bold ml-2 flex-shrink-0"
                                                :class="disagg.totalable ? 'text-blue-800' : 'text-gray-700'"
                                            >
                                                {{ disagg.value }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Province Total Info -->
                                    <div class="mt-2 pt-2 border-t border-green-300">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-gray-600 flex items-center gap-1">
                                                <i class="pi pi-info-circle text-[10px]"></i>
                                                Totalable only
                                            </span>
                                            <span class="font-bold text-green-700">{{ provinceData.total }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- No provinces message -->
                            <div v-else class="text-center py-6 text-gray-500 text-sm">
                                <i class="pi pi-info-circle mb-2"></i>
                                <p>No provincial data available for this indicator</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- No indicators message -->
                    <div v-else class="text-center py-8 text-gray-500">
                        <i class="pi pi-info-circle text-2xl mb-2"></i>
                        <p class="text-sm">No indicators available for this program</p>
                    </div>
                </template>

                <template #footer>
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 -mx-6 -mb-6 px-4 py-2 mt-3">
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span class="flex items-center gap-1.5">
                                <i class="pi pi-info-circle text-xs"></i>
                                Totals include only disaggregations marked as "totalable"
                            </span>
                            <span class="text-gray-500">
                                {{ new Date().toLocaleDateString() }}
                            </span>
                        </div>
                    </div>
                </template>
            </Card>
        </div>

        <!-- Empty State -->
        <div v-if="!programs || Object.keys(programs).length === 0" class="text-center py-12">
            <div class="bg-white rounded-lg shadow-md p-8 max-w-md mx-auto border border-green-200">
                <i class="pi pi-inbox text-5xl text-green-300 mb-3"></i>
                <h3 class="text-base font-semibold text-gray-700 mb-1">No Data Available</h3>
                <p class="text-xs text-gray-500">There are no programs to display at this time.</p>
            </div>
        </div>

    </div>
</template>

<script setup>
    import { onMounted } from 'vue';
    import MainLayout from '@/layouts/mainLayout.vue';
    import { Card } from 'primevue';

    defineOptions({
        layout: MainLayout
    })

    const props = defineProps({
        programs: Object,
    })

    // onMounted(() => {
    //    console.log('Programs data:', props.programs);
    // });
</script>

<style scoped>
    /* Custom scrollbar for disaggregations list */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>