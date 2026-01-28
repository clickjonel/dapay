<template>
    <div class="w-full p-4 bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 min-h-screen">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="text-xl font-bold text-green-800">Programs Dashboard</h1>
            <p class="text-xs text-gray-600 mt-1">Overview of program indicators across all provinces</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div class="bg-white rounded-lg p-4 shadow-sm border border-green-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-600 mb-1">Total Programs</div>
                        <div class="text-2xl font-bold text-green-700">{{ programs.length }}</div>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="pi pi-folder text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border border-emerald-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-600 mb-1">Total Indicators</div>
                        <div class="text-2xl font-bold text-emerald-700">{{ totalIndicators }}</div>
                    </div>
                    <div class="bg-emerald-100 rounded-full p-3">
                        <i class="pi pi-chart-line text-emerald-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border border-teal-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-600 mb-1">Total Provinces</div>
                        <div class="text-2xl font-bold text-teal-700">{{ totalProvinces }}</div>
                    </div>
                    <div class="bg-teal-100 rounded-full p-3">
                        <i class="pi pi-map-marker text-teal-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-600 mb-1">Grand Total</div>
                        <div class="text-2xl font-bold text-blue-700">{{ grandTotal }}</div>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="pi pi-calculator text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="mb-4 bg-white rounded-lg p-4 shadow-sm border border-green-200">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative">
                    <i class="pi pi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="Search programs, indicators, or provinces..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-green-300 focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                    />
                </div>
                <select v-model="sortBy" class="px-4 py-2 rounded-lg border border-green-300 focus:ring-2 focus:ring-green-500 text-sm">
                    <option value="name">Sort by Name</option>
                    <option value="total_desc">Sort by Total (High to Low)</option>
                    <option value="total_asc">Sort by Total (Low to High)</option>
                </select>
                <button 
                    @click="toggleAllIndicators"
                    class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors text-sm font-medium flex items-center gap-2"
                >
                    <i :class="allExpanded ? 'pi pi-minus' : 'pi pi-plus'" class="text-xs"></i>
                    {{ allExpanded ? 'Collapse All' : 'Expand All' }}
                </button>
            </div>
        </div>

        <!-- Programs Grid -->
        <div class="w-full flex flex-col gap-4">
            <Card 
                v-for="program in filteredAndSortedPrograms" 
                :key="program.id"
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
                                <h2 class="text-base font-bold mb-0.5">{{ program.name }}</h2>
                                <p class="text-green-100 text-xs">
                                    {{ (program.indicators || []).length }} Indicator{{ (program.indicators || []).length !== 1 ? 's' : '' }} • 
                                    Total: <span class="font-bold">{{ program.total_value || 0 }}</span>
                                </p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="pi pi-chart-bar text-xl"></i>
                            </div>
                        </div>
                    </div>
                </template>

                <template #content>
                    <div v-if="program.indicators && program.indicators.length > 0" class="space-y-4">
                        <!-- Indicator Card -->
                        <div 
                            v-for="indicator in program.indicators" 
                            :key="indicator.indicator_name"
                            class="border border-green-200 rounded-lg bg-white hover:shadow-md transition-shadow overflow-hidden"
                        >
                            <!-- Indicator Header - Clickable -->
                            <div 
                                @click="toggleIndicator(program.id + '-' + indicator.indicator_name)"
                                class="flex items-start justify-between p-3 pb-2 border-b border-green-100 cursor-pointer hover:bg-green-50 transition-colors"
                            >
                                <div class="flex items-center gap-2">
                                    <i 
                                        :class="isIndicatorExpanded(program.id + '-' + indicator.indicator_name) ? 'pi pi-chevron-down' : 'pi pi-chevron-right'" 
                                        class="text-sm text-gray-600 transition-transform"
                                    ></i>
                                    <div class="bg-green-100 rounded-lg p-1.5">
                                        <i class="pi pi-chart-line text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-800">{{ indicator.indicator_name }}</h3>
                                        <p class="text-xs text-gray-500">
                                            {{ (indicator.provinces || []).length }} Province{{ (indicator.provinces || []).length !== 1 ? 's' : '' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Total Badge -->
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-3 py-1.5 rounded-lg shadow-sm">
                                    <div class="text-xs font-medium uppercase tracking-wide">Indicator Total</div>
                                    <div class="text-lg font-bold">{{ indicator.total_value || 0 }}</div>
                                </div>
                            </div>

                            <!-- Collapsible Content -->
                            <div 
                                v-show="isIndicatorExpanded(program.id + '-' + indicator.indicator_name)"
                                class="p-3"
                            >
                                <!-- Provincial Distribution Chart -->
                                <div v-if="indicator.provinces && indicator.provinces.length > 0" class="mb-3 p-3 bg-gradient-to-r from-gray-50 to-green-50 rounded-lg border border-green-100">
                                    <div class="text-xs font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                        <i class="pi pi-chart-bar text-green-600"></i>
                                        Provincial Distribution
                                    </div>
                                    <div class="space-y-2">
                                        <div v-for="province in indicator.provinces" :key="province.province_name" class="flex items-center gap-2">
                                            <span class="text-xs text-gray-700 w-32 truncate font-medium">{{ province.province_name }}</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-5 overflow-hidden relative">
                                                <div 
                                                    class="bg-gradient-to-r from-green-500 to-emerald-500 h-full rounded-full transition-all duration-500 flex items-center justify-end pr-2"
                                                    :style="{ width: `${(province.total_value / indicator.total_value * 100)}%` }"
                                                >
                                                    <span 
                                                        v-if="(province.total_value / indicator.total_value * 100) > 15"
                                                        class="text-[10px] font-bold text-white"
                                                    >
                                                        {{ Math.round(province.total_value / indicator.total_value * 100) }}%
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="text-xs font-bold text-gray-700 w-20 text-right">{{ province.total_value }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Provinces Grid -->
                                <div v-if="indicator.provinces && indicator.provinces.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    <div 
                                        v-for="province in indicator.provinces" 
                                        :key="province.province_name"
                                        class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-3 border border-green-200 hover:border-emerald-400 hover:shadow-lg transition-all duration-200"
                                    >
                                        <!-- Province Header -->
                                        <div class="flex items-center justify-between mb-2 pb-2 border-b border-green-300">
                                            <div class="flex items-center gap-2">
                                                <div class="bg-white rounded-full p-1 shadow-sm">
                                                    <i class="pi pi-map-marker text-xs text-green-600"></i>
                                                </div>
                                                <span class="text-sm font-bold text-gray-800">{{ province.province_name }}</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <i class="pi pi-calculator text-xs text-green-600"></i>
                                                <span class="text-base font-bold text-green-700">{{ province.total_value || 0 }}</span>
                                            </div>
                                        </div>

                                        <!-- Disaggregations List -->
                                        <div v-if="province.disaggregations && province.disaggregations.length > 0" class="space-y-1 max-h-48 overflow-y-auto pr-1">
                                            <div 
                                                v-for="(disagg, index) in province.disaggregations"
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

                                        <!-- No disaggregations message -->
                                        <div v-else class="text-center py-3 text-gray-400 text-xs">
                                            <i class="pi pi-info-circle text-sm mb-1"></i>
                                            <p>No disaggregations available</p>
                                        </div>

                                        <!-- Province Total Info -->
                                        <div class="mt-2 pt-2 border-t border-green-300">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="text-gray-600 flex items-center gap-1">
                                                    <i class="pi pi-info-circle text-[10px]"></i>
                                                    Report Count
                                                </span>
                                                <span class="font-bold text-gray-700">{{ province.count || 0 }}</span>
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
        <div v-if="!programs || programs.length === 0" class="text-center py-12">
            <div class="bg-white rounded-lg shadow-md p-8 max-w-md mx-auto border border-green-200">
                <i class="pi pi-inbox text-5xl text-green-300 mb-3"></i>
                <h3 class="text-base font-semibold text-gray-700 mb-1">No Data Available</h3>
                <p class="text-xs text-gray-500">There are no programs to display at this time.</p>
            </div>
        </div>

        <!-- No Search Results -->
        <div v-if="programs.length > 0 && filteredAndSortedPrograms.length === 0" class="text-center py-12">
            <div class="bg-white rounded-lg shadow-md p-8 max-w-md mx-auto border border-green-200">
                <i class="pi pi-search text-5xl text-gray-300 mb-3"></i>
                <h3 class="text-base font-semibold text-gray-700 mb-1">No Results Found</h3>
                <p class="text-xs text-gray-500">Try adjusting your search query.</p>
                <button 
                    @click="searchQuery = ''"
                    class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition-colors"
                >
                    Clear Search
                </button>
            </div>
        </div>

    </div>
</template>

<script setup>
    import { ref, computed, onMounted } from 'vue';
    import MainLayout from '@/layouts/mainLayout.vue';
    import { Card } from 'primevue';

    defineOptions({
        layout: MainLayout
    })

    const props = defineProps({
        programs: {
            type: Array,
            default: () => []
        }
    })

    // State
    const searchQuery = ref('');
    const sortBy = ref('name');
    const expandedIndicators = ref(new Set());
    const allExpanded = ref(false);

    // Computed properties for summary cards
    const totalIndicators = computed(() => {
        return props.programs.reduce((sum, program) => {
            return sum + (program.indicators?.length || 0);
        }, 0);
    });

    const totalProvinces = computed(() => {
        const provincesSet = new Set();
        props.programs.forEach(program => {
            program.indicators?.forEach(indicator => {
                indicator.provinces?.forEach(province => {
                    provincesSet.add(province.province_name);
                });
            });
        });
        return provincesSet.size;
    });

    const grandTotal = computed(() => {
        return props.programs.reduce((sum, program) => {
            return sum + (program.total_value || 0);
        }, 0);
    });

    // Filtered and sorted programs
    const filteredAndSortedPrograms = computed(() => {
        let filtered = props.programs;

        // Search filter
        if (searchQuery.value) {
            const query = searchQuery.value.toLowerCase();
            filtered = filtered.filter(program => {
                // Search in program name
                if (program.name?.toLowerCase().includes(query)) return true;
                
                // Search in indicators
                return program.indicators?.some(indicator => {
                    if (indicator.indicator_name?.toLowerCase().includes(query)) return true;
                    
                    // Search in provinces
                    return indicator.provinces?.some(province => 
                        province.province_name?.toLowerCase().includes(query)
                    );
                });
            });
        }

        // Sort
        const sorted = [...filtered];
        if (sortBy.value === 'total_desc') {
            sorted.sort((a, b) => (b.total_value || 0) - (a.total_value || 0));
        } else if (sortBy.value === 'total_asc') {
            sorted.sort((a, b) => (a.total_value || 0) - (b.total_value || 0));
        } else {
            sorted.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
        }

        return sorted;
    });

    // Toggle functions
    const toggleIndicator = (key) => {
        if (expandedIndicators.value.has(key)) {
            expandedIndicators.value.delete(key);
        } else {
            expandedIndicators.value.add(key);
        }
    };

    const isIndicatorExpanded = (key) => {
        return expandedIndicators.value.has(key);
    };

    const toggleAllIndicators = () => {
        if (allExpanded.value) {
            // Collapse all
            expandedIndicators.value.clear();
            allExpanded.value = false;
        } else {
            // Expand all
            props.programs.forEach(program => {
                program.indicators?.forEach(indicator => {
                    expandedIndicators.value.add(program.id + '-' + indicator.indicator_name);
                });
            });
            allExpanded.value = true;
        }
    };

    onMounted(() => {
        console.log('Programs data:', props.programs);
        
        // Expand first indicator by default
        if (props.programs.length > 0 && props.programs[0].indicators?.length > 0) {
            const firstKey = props.programs[0].id + '-' + props.programs[0].indicators[0].indicator_name;
            expandedIndicators.value.add(firstKey);
        }
    });
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

    /* Smooth transitions */
    .pi {
        transition: transform 0.2s ease;
    }
</style>