<template>

    <div class="main-container w-full min-h-screen h-screen flex justify-start items-start gap-2 p-2 bg-sky-100">

        <!-- sidebar -->
        <aside class="w-[15%] h-full bg-white p-4 rounded-lg overflow-auto flex flex-col">
            <nav class="w-full flex flex-col justify-start items-start flex-1">
                <h2 class="font-bold mb-4 text-gray-800">Navigation</h2>
                <PanelMenu :model="filteredLinks" 
                    class="w-full text-sm"
                    v-model:expanded-keys="expandedKeys"
                    :pt="{
                        panel: { 
                            class: '!bg-transparent' 
                        },
                        submenuIcon:{
                            class:'!text-[#2C2E2E]'     
                        },
                        headerContent:{
                            class:'!text-black'     
                        },
                        headerIcon:{
                            class:'!text-[#2C2E2E]'     
                        },
                    }"
                />
            </nav>

            <!-- Logout Section -->
            <div class="w-full border-t border-gray-200 pt-4 mt-4">
                <button 
                    @click="handleLogout"
                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                >
                    <i class="pi pi-sign-out text-base"></i>
                    <span>Logout</span>
                </button>
            </div>
        </aside>

        <!-- content -->
        <section class="w-[85%] min-h-full h-full overflow-y-auto p-2 bg-white rounded-lg">
    
           <slot/>
           
        </section>

        <Toast position="top-right"/>
    </div>

</template>

<script setup>
    import { onMounted, ref, computed } from 'vue';
    import { PanelMenu } from 'primevue';
    import { router, usePage } from '@inertiajs/vue3';
    import Toast from 'primevue/toast';

    // Get the authenticated user from Inertia
    const page = usePage();
    const user = computed(() => page.props.auth?.user);

    const links = [
        {
            label: 'Dashboard',
            key:'Dashboard',
            access_levels: [1,2],
            items:[
                {
                    label: 'Geographic Dashboard',
                    command: () => router.visit('/dashboard-geographic'),
                    access_levels: [1,2]
                },
                {
                    label: 'Program Indicators',
                    command: () => router.visit('/dashboard-program-indicators'),
                    access_levels: [1,2]
                },
            ]
        },

        {
            label: 'Programs',
            key:'Programs',
            access_levels: [1,2,7], // Only admin can see
            items:[
                {
                    label: 'Programs List',
                    command: () => router.visit('/program'),
                    access_levels: [1,2,7]
                },
                {
                    label: 'Create Program',
                    command: () => router.visit('/program/create'),
                    access_levels: [1,2,7]
                },
            ]
        },

        {
            label: 'Barangays',
            key:'Barangays',
            access_levels: [1,2,3,4,5,6],
            items:[
                {
                    label: 'Master List',
                    command: () => router.visit('/barangay'),
                    access_levels: [1,2,3,4,5,6]
                },
                // {
                //     label: 'PK Sites',
                //     access_levels: [1,2,3,4,5,6]
                //     //command: () => router.visit('/program/create'),
                // },
                // {
                //     label: 'UUC',
                //     access_levels: ['admin']
                //     //command: () => router.visit('/program/create'),
                // },
            ]
        },

        {
            label: 'Indicators',
            key:'Indicators',
            access_levels: [1,2,7],
            items:[
                {
                    label: 'Organizational',
                    command: () => router.visit('/indicator/organizational'),
                    access_levels: [1,2,7]
                },
                {
                    label: 'Program',
                    command: () => router.visit('/indicator/program'),
                    access_levels: [1,2,7]
                },
            ]
        },

        {
            label: 'Disaggregations',
            key:'Disaggregations',
            access_levels: [1,2,7],
            items:[
                {
                    label: 'Master List',
                    command: () => router.visit('/disaggregation'),
                    access_levels: [1,2,7]
                },
            ]
        },

        {
            label: 'Reports',
            key:'Reports',
            access_levels: [1,2,3,4,5,6],
            items:[
                {
                    label: 'All Reports',
                    command: () => router.visit('/report'),
                    access_levels: [1,2,3,4,5,6]
                },
                {
                    label: 'Create Report',
                    command: () => router.visit('/report/create'),
                    access_levels: [1,2,3,4,5,6]
                },
            ]
        },

    ]

    // Filter links based on user role
    const filteredLinks = computed(() => {
        const access_level = user.value?.user_level; 
        
        if (!access_level) return [];

        return links
            .filter(link => link.access_levels?.includes(access_level))
            .map(link => ({
                ...link,
                items: link.items?.filter(item => item.access_levels?.includes(access_level))
            }))
            .filter(link => link.items && link.items.length > 0);
    });

    const expandedKeys = ref({
        'Dashboard':true,
        'Programs': true,
        'Barangays': true,
        'Provinces': true,
        'Indicators':true,
        'Disaggregations':true,
        'Reports':true
    });

    const handleLogout = () => {
        router.post('/logout');
    }

</script>