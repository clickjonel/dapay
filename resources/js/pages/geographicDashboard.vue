<template>
    <div class="w-full bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 min-h-screen h-screen">
        <!-- Header with Summary Stats -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-xl font-bold text-green-800">Geographic Coverage</h1>
                    <p class="text-xs text-gray-600 mt-1">Cordillera Administrative Region</p>
                </div>
                <!-- Quick Summary -->
                <div class="flex gap-3">
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm border border-green-200">
                        <div class="text-xs text-gray-600 mb-0.5">Provinces</div>
                        <div class="text-2xl font-bold text-green-600">{{ provinces.length }}</div>
                    </div>
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm border border-emerald-200">
                        <div class="text-xs text-gray-600 mb-0.5">Barangays</div>
                        <div class="text-2xl font-bold text-emerald-600">{{ totalBarangays }}</div>
                    </div>
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm border border-teal-200">
                        <div class="text-xs text-gray-600 mb-0.5">PK Sites</div>
                        <div class="text-2xl font-bold text-teal-600">{{ totalPKSites }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Province Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <Card 
                v-for="province in provinces" 
                :key="province.id"
                class="hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-0 bg-white"
            >
                <template #header>
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 text-white relative overflow-hidden">
                        <!-- Decorative Elements -->
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-white/10 rounded-full"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/10 rounded-full"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-start justify-between mb-3">
                                <div class="p-1">
                                    <i class="pi pi-map-marker text-2xl"></i>
                                </div>
                                <!-- Coverage Badge -->
                                <div class="bg-white/90 text-green-700 px-2 py-1 rounded-full text-xs font-medium">
                                    {{ getCoveragePercentage(province) }}% PK
                                </div>
                            </div>
                            <h3 class="text-base font-semibold uppercase">{{ province.name }}</h3>
                        </div>
                    </div>
                </template>

                <template #content>
                    <!-- Main Statistics Grid -->
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div class="text-center p-3 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg border border-emerald-200 hover:shadow-md transition-shadow">
                            <!-- <i class="pi pi-building text-emerald-500 text-lg mb-1"></i> -->
                            <div class="text-2xl font-bold text-emerald-600">{{ province.municipalities.length }}</div>
                            <div class="text-xs font-medium text-emerald-700">Municipalities</div>
                        </div>
                        <div class="text-center p-3 bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg border border-teal-200 hover:shadow-md transition-shadow">
                            <!-- <i class="pi pi-home text-teal-500 text-lg mb-1"></i> -->
                            <div class="text-2xl font-bold text-teal-600">{{ province.barangays.length }}</div>
                            <div class="text-xs font-medium text-teal-700">Barangays</div>
                        </div>
                    </div>

                    <!-- PK Sites Breakdown with Progress Bar -->
                    <div class="space-y-2">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-2.5 border border-green-200">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-semibold text-gray-700 flex items-center gap-1.5">
                                    <i class="pi pi-check-circle text-green-500 text-sm"></i>
                                    PK Sites
                                </span>
                                <span class="font-bold text-green-600 text-base">{{ getProvincePKSites(province) }}</span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full bg-green-200 rounded-full h-1.5">
                                <div 
                                    class="bg-gradient-to-r from-green-500 to-emerald-500 h-1.5 rounded-full transition-all duration-500"
                                    :style="{ width: getCoveragePercentage(province) + '%' }"
                                ></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-700 flex items-center gap-1.5">
                                    <i class="pi pi-times-circle text-gray-400 text-sm"></i>
                                    Non-PK Sites
                                </span>
                                <span class="font-bold text-gray-600 text-base">{{ getProvinceNonPKSites(province) }}</span>
                            </div>
                        </div>
                    </div>
                </template>

                <template #footer>
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 -mx-6 -mb-6 px-4 py-2 flex items-center justify-between">
                        <span class="text-xs text-gray-600">Coverage Status</span>
                        <div class="flex items-center gap-1">
                            <div 
                                v-for="n in 5" 
                                :key="n"
                                class="w-1.5 h-1.5 rounded-full transition-all"
                                :class="n <= Math.ceil(getCoveragePercentage(province) / 20) ? 'bg-green-500' : 'bg-gray-300'"
                            ></div>
                        </div>
                    </div>
                </template>
            </Card>
        </div>

        <!-- Empty State -->
        <div v-if="!provinces || provinces.length === 0" class="text-center py-12">
            <div class="bg-white rounded-lg shadow-md p-8 max-w-md mx-auto border border-green-200">
                <i class="pi pi-map text-5xl text-green-300 mb-3"></i>
                <h3 class="text-base font-semibold text-gray-700 mb-1">No Provinces Found</h3>
                <p class="text-xs text-gray-500">There are no provinces to display at this time.</p>
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
        provinces: Array
    })

    // Calculate PK sites count for each province
    const getProvincePKSites = (province) => {
        return province.barangays.filter(barangay => barangay.is_pk_site === true).length;
    }

    // Calculate Non-PK sites count for each province
    const getProvinceNonPKSites = (province) => {
        return province.barangays.filter(barangay => barangay.is_pk_site === false).length;
    }

    // Calculate coverage percentage
    const getCoveragePercentage = (province) => {
        const total = province.barangays.length;
        if (total === 0) return 0;
        const pkSites = getProvincePKSites(province);
        return Math.round((pkSites / total) * 100);
    }

    // Total barangays across all provinces
    const totalBarangays = computed(() => {
        return props.provinces.reduce((sum, province) => sum + province.barangays.length, 0);
    })

    // Total PK sites across all provinces
    const totalPKSites = computed(() => {
        return props.provinces.reduce((sum, province) => sum + getProvincePKSites(province), 0);
    })

    // onMounted(() => {
    //     console.log(getProvincePKSites(props.provinces[0]));
    // })
</script>

<style scoped>
    /* Smooth animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .grid > * {
        animation: slideIn 0.3s ease-out;
    }
</style>