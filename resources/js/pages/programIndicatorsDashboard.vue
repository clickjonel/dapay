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
                v-for="(indicators, programName) in programs" 
                :key="programName"
                class="hover:shadow-2xl transition-all duration-300 border-0 overflow-hidden"
            >
                <template #header>
                    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 p-3 text-white relative overflow-hidden">
                        <!-- Decorative Background Pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full -mr-16 -mt-16"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-full -ml-12 -mb-12"></div>
                        </div>
                        
                        <div class="relative z-10 flex items-center justify-between">
                            <div>
                                <h2 class="text-base font-bold mb-0.5">{{ programName }}</h2>
                                <p class="text-green-100 text-xs">
                                    {{ Object.keys(indicators).length }} Indicator{{ Object.keys(indicators).length !== 1 ? 's' : '' }}
                                </p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="pi pi-chart-bar text-xl"></i>
                            </div>
                        </div>
                    </div>
                </template>

                <template #content>
                    <div class="space-y-4">
                        <!-- Indicator Card -->
                        <div 
                            v-for="(provinces, indicatorName) in indicators" 
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
                                        <p class="text-xs text-gray-500">Provincial breakdown</p>
                                    </div>
                                </div>
                                
                                <!-- Total Badge -->
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-3 py-1.5 rounded-lg shadow-sm">
                                    <div class="text-xs font-medium uppercase tracking-wide">Total</div>
                                    <div class="text-lg font-bold">{{ provinces.total }}</div>
                                </div>
                            </div>

                            <!-- Provinces Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-2">
                                <div 
                                    v-for="(value, provinceName) in provinces" 
                                    :key="provinceName"
                                    v-show="provinceName !== 'total'"
                                    class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-2.5 border border-green-200 hover:border-emerald-400 hover:shadow-md transition-all duration-200 group"
                                >
                                    <div class="flex flex-col items-center text-center">
                                        <div class="bg-white rounded-full p-1.5 mb-1.5 shadow-sm group-hover:shadow-md transition-shadow">
                                            <i class="pi pi-map-marker text-base text-green-600"></i>
                                        </div>
                                        <div class="text-xl font-bold text-gray-800 mb-0.5">{{ value }}</div>
                                        <div class="text-xs font-medium text-gray-600 uppercase tracking-wide">{{ provinceName }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <template #footer>
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 -mx-6 -mb-6 px-4 py-2 mt-3">
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span class="flex items-center gap-1.5">
                                <i class="pi pi-info-circle text-xs"></i>
                                Data aggregated from all reporting periods
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

    onMounted(() => {
       console.log(props.programs);
    });
</script>

<style scoped>
    /* Custom scrollbar for better UX */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>