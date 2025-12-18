<template>

    <div class="main-container w-full min-h-screen h-screen flex justify-start items-start gap-2 p-2 bg-sky-100">

        <!-- sidebar -->
        <aside class="w-[15%] h-full bg-white p-4 rounded-lg overflow-auto">
            <nav class="w-full flex flex-col justify-start items-start">
                <h2 class="font-bold mb-4 text-gray-800">Navigation</h2>
                <PanelMenu :model="links" 
                    class="w-full text-sm"
                    :expanded-keys="expandedKeys"
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
        </aside>

        <!-- content -->
        <section class="w-[85%] min-h-full h-full overflow-y-auto p-2 bg-white rounded-lg">

           <slot/>
           
        </section>

    </div>

</template>

<script setup>
    import { onMounted,ref } from 'vue';
    import { PanelMenu } from 'primevue';
    import { router } from '@inertiajs/vue3';

    const links = [
        {
            label: 'Dashboard',
            command: () => router.visit('/dashboard'),
        },

        {
            label: 'Programs',
            key:'Programs',
            items:[
                {
                    label: 'Programs List',
                    command: () => router.visit('/program'),
                },
                {
                    label: 'Create Program',
                    command: () => router.visit('/program/create'),
                },
            ]
        },

        {
            label: 'Provinces',
            key:'Provinces',
            items:[
                {
                    label: 'Provinces List',
                    command: () => router.visit('/provinces'),
                },
            ]
        },

        {
            label: 'Barangays',
            key:'Barangays',
            items:[
                {
                    label: 'Master List',
                    //command: () => router.visit('/program'),
                },
                {
                    label: 'PK Sites',
                    command: () => router.visit('/program/create'),
                },
                {
                    label: 'UUC',
                    command: () => router.visit('/program/create'),
                },
            ]
        },
    ]

    const expandedKeys = ref({
        'Programs': true,
        'Barangays': true,
        'Provinces': true
    });


</script>
