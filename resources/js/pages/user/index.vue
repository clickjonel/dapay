<template>
    <div class="w-full h-full flex flex-col justify-between items-start gap-4 p-2 sm:p-4">

        <div class="w-full flex flex-col justify-start items-start gap-4">
            <section class="w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h2 class="text-lg sm:text-xl uppercase font-semibold">Users</h2>
                <Button 
                    label="Create User" 
                    icon="pi pi-plus"
                    @click="router.visit('/user/create')"
                    size="small"
                />
            </section>

            <section class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <InputText 
                    v-model="searchQuery"
                    placeholder="Search by name or username" 
                    size="small" 
                    class="w-full sm:w-[300px]"
                    @update:modelValue="search"
                />
            </section>
        </div>

        <section class="w-full h-full overflow-x-auto overflow-y-auto">
            <!-- Desktop Table View -->
            <div class="hidden lg:block min-w-full">
                <div class="w-full grid grid-cols-6 p-4 bg-gray-100 border-b-2 border-gray-300 font-semibold text-sm uppercase sticky top-0 z-10">
                    <span class="w-full">Full Name</span>
                    <span class="w-full">Username</span>
                    <span class="w-full">Nickname</span>
                    <span class="w-full">User Level</span>
                    <span class="w-full">Status</span>
                    <span class="w-full">Action</span>
                </div>

                <div v-for="user in users.data" :key="user.id" class="w-full grid grid-cols-6 p-4 border-b text-sm hover:bg-gray-50">
                    <span class="w-full">{{ user.full_name }}</span>
                    <span class="w-full">{{ user.username }}</span>
                    <span class="w-full">{{ user.nickname || 'N/A' }}</span>
                    <span class="w-full">
                        <span :class="getUserLevelBadgeClass(user.user_level)" class="px-2 py-1 rounded text-xs">
                            {{ getUserLevelText(user.user_level) }}
                        </span>
                    </span>
                    <span class="w-full">
                        <span :class="getStatusBadgeClass(user.account_status)" class="px-2 py-1 rounded text-xs">
                            {{ user.account_status }}
                        </span>
                    </span>
                    <span class="w-full flex gap-2">
                        <!-- <Button 
                            icon="pi pi-pencil" 
                            text 
                            rounded
                            size="small"
                        />
                        <Button 
                            icon="pi pi-trash" 
                            text 
                            rounded
                            size="small"
                            severity="danger"
                        /> -->
                    </span> 
                </div>
            </div>

            <!-- Mobile/Tablet Card View -->
            <div class="lg:hidden space-y-3">
                <div 
                    v-for="user in users.data" 
                    :key="user.id" 
                    class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
                >
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="font-semibold text-sm mb-1">{{ user.full_name }}</div>
                            <div class="text-xs text-gray-500">@{{ user.username }}</div>
                        </div>
                        <div class="flex gap-1">
                            <Button 
                                icon="pi pi-pencil" 
                                text 
                                rounded
                                size="small"
                                @click="router.visit(`/user/${user.id}/edit`)"
                            />
                            <Button 
                                icon="pi pi-trash" 
                                text 
                                rounded
                                size="small"
                                severity="danger"
                                @click="confirmDelete(user.id)"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Nickname</div>
                            <div class="font-medium">{{ user.nickname || 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">User Level</div>
                            <span :class="getUserLevelBadgeClass(user.user_level)" class="px-2 py-1 rounded text-xs inline-block">
                                {{ getUserLevelText(user.user_level) }}
                            </span>
                        </div>
                        <div class="col-span-2">
                            <div class="text-xs text-gray-500 mb-1">Status</div>
                            <span :class="getStatusBadgeClass(user.account_status)" class="px-2 py-1 rounded text-xs inline-block">
                                {{ user.account_status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="users.data.length === 0" class="w-full p-8 text-center text-gray-500">
                No users found
            </div>
        </section>

        <!-- Pagination -->
        <section class="w-full flex flex-col sm:flex-row justify-between items-center gap-3 px-2 sm:px-4 py-2 border-t">
            <div class="text-xs sm:text-sm text-gray-600 order-2 sm:order-1">
                Showing {{ users.from ?? 0 }} to {{ users.to ?? 0 }} of {{ users.total ?? 0 }} results
            </div>
            
            <div class="flex items-center gap-2 order-1 sm:order-2">
                <Button 
                    icon="pi pi-chevron-left"
                    :disabled="!users.prev_page_url"
                    @click="navigatePage(users.prev_page_url)"
                    size="small"
                    text
                />
                
                <span class="text-xs sm:text-sm font-medium px-2 sm:px-3">
                    Page {{ users.current_page }}
                </span>
                
                <Button 
                    icon="pi pi-chevron-right"
                    :disabled="!users.next_page_url"
                    @click="navigatePage(users.next_page_url)"
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
    import { Button, InputText, Dialog, Toast } from 'primevue';
    import { useToast } from 'primevue/usetoast';

    const props = defineProps({
        users: Object,
        filters: Object
    })

    defineOptions({
        layout: MainLayout
    })

    const toast = useToast();
    const searchQuery = ref(props.filters?.search || '');

    const search = debounce(() => {
        router.get('/user', 
            { 
                search: searchQuery.value,
            }, 
            { 
                preserveState: true,
                replace: true,
                only: ['users']
            }
        );
    }, 300);

    const navigatePage = (url) => {
        if (!url) return;
        
        router.get(url, {}, {
            preserveState: true,
            preserveScroll: true,
            only: ['users']
        });
    };

    const getUserLevelText = (level) => {
        const levels = {
            1: 'Admin',
            2: 'Secretariat',
            3: 'PDOHO',
            4: 'Program',
            5: 'HRH'
        };
        return levels[level] || 'Unknown';
    };

    const getUserLevelBadgeClass = (level) => {
        const classes = {
            1: 'bg-purple-100 text-purple-800',
            2: 'bg-blue-100 text-blue-800',
            3: 'bg-green-100 text-green-800',
            4: 'bg-gray-100 text-gray-800'
        };
        return classes[level] || 'bg-gray-100 text-gray-800';
    };

    const getStatusBadgeClass = (status) => {
        return status === 'active' 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    };

</script>