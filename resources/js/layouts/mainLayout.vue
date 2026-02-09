<template>

    <div class="main-container w-full min-h-screen h-screen flex justify-start items-start gap-2 p-2 bg-sky-100">

        <!-- Mobile Menu Toggle Button -->
        <button 
            @click="toggleSidebar"
            class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-md hover:bg-gray-50 transition-colors"
        >
            <i :class="sidebarOpen ? 'pi pi-times' : 'pi pi-bars'" class="text-xl text-gray-700"></i>
        </button>

        <!-- Overlay for mobile -->
        <div 
            v-if="sidebarOpen" 
            @click="closeSidebar"
            class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-30 transition-opacity"
        ></div>

        <!-- sidebar -->
        <aside 
            :class="[
                'h-full bg-white p-4 rounded-lg overflow-auto flex flex-col transition-all duration-300 ease-in-out',
                // Mobile styles
                'fixed lg:relative top-0 left-0 z-40',
                'w-[280px] lg:w-[220px] xl:w-[15%]',
                // Mobile transform
                sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
            ]"
        >
            <nav class="w-full flex flex-col justify-start items-start flex-1">
                <div class="w-full flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-800">Navigation</h2>
                    <!-- Close button for mobile -->
                    <button 
                        @click="closeSidebar"
                        class="lg:hidden p-1 hover:bg-gray-100 rounded"
                    >
                        <i class="pi pi-times text-gray-600"></i>
                    </button>
                </div>
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

            <!-- User Actions Section -->
            <div class="w-full border-t border-gray-200 pt-4 mt-4 space-y-2">
                <button 
                    @click="showPasswordModal = true"
                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200"
                >
                    <i class="pi pi-key text-base"></i>
                    <span>Change Password</span>
                </button>
                
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
        <section 
            :class="[
                'min-h-full h-full overflow-y-auto p-2 sm:p-4 bg-white rounded-lg transition-all duration-300',
                'w-full lg:w-[calc(100%-220px-0.5rem)] xl:w-[85%]',
                'lg:ml-0'
            ]"
        >
    
           <slot/>
           
        </section>

        <!-- Change Password Modal -->
        <Dialog 
            v-model:visible="showPasswordModal" 
            modal 
            header="Change Password" 
            :style="{ width: '400px' }"
            :dismissableMask="true"
        >
            <div class="space-y-4 py-4">
                <!-- New Password -->
                <div class="flex flex-col gap-2">
                    <label for="new_password" class="text-sm font-medium text-gray-700">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <Password 
                        id="new_password"
                        v-model="newPassword"
                        toggleMask
                        placeholder="Enter new password"
                        class="w-full"
                        input-class="w-full"
                    />
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button 
                        label="Cancel" 
                        severity="secondary" 
                        @click="closePasswordModal"
                        outlined
                        :disabled="isSubmitting"
                    />
                    <Button 
                        label="Change Password" 
                        severity="success" 
                        @click="submitPasswordChange"
                        :loading="isSubmitting"
                    />
                </div>
            </template>
        </Dialog>

        <Toast position="top-right"/>
    </div>

</template>

<script setup>
    import { onMounted, ref, computed, watch } from 'vue';
    import { PanelMenu } from 'primevue';
    import { router, usePage } from '@inertiajs/vue3';
    import Toast from 'primevue/toast';
    import Dialog from 'primevue/dialog';
    import Password from 'primevue/password';
    import Button from 'primevue/button';
    import { useToast } from 'primevue/usetoast';

    const toast = useToast();

    // Get the authenticated user from Inertia
    const page = usePage();
    const user = computed(() => page.props.auth?.user);

    // Sidebar state for mobile
    const sidebarOpen = ref(false);

    // Password modal state
    const showPasswordModal = ref(false);
    const isSubmitting = ref(false);
    const newPassword = ref('');

    const toggleSidebar = () => {
        sidebarOpen.value = !sidebarOpen.value;
    };

    const closeSidebar = () => {
        sidebarOpen.value = false;
    };

    const closePasswordModal = () => {
        showPasswordModal.value = false;
        newPassword.value = '';
    };

    const submitPasswordChange = () => {
        if (!newPassword.value) {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Please enter a new password',
                life: 3000
            });
            return;
        }

        isSubmitting.value = true;

        router.put(`/user/${page.props.auth.user.id}`, { new_password: newPassword.value }, {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Password changed successfully!',
                    life: 3000
                });
                closePasswordModal();
            },
            onError: (errors) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to change password.',
                    life: 3000
                });
                console.log(errors)
            },
            onFinish: () => {
                isSubmitting.value = false;
            }
        });
    };

    // Close sidebar when route changes (mobile)
    watch(() => page.url, () => {
        if (window.innerWidth < 1024) {
            closeSidebar();
        }
    });

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
            access_levels: [1,2,4], // Only admin can see
            items:[
                {
                    label: 'Programs List',
                    command: () => router.visit('/program'),
                    access_levels: [1,2,4]
                },
                {
                    label: 'Create Program',
                    command: () => router.visit('/program/create'),
                    access_levels: [1,2,4]
                },
            ]
        },

        {
            label: 'Barangays',
            key:'Barangays',
            access_levels: [1,2,3,4],
            items:[
                {
                    label: 'Master List',
                    command: () => router.visit('/barangay'),
                    access_levels: [1,2,3,4]
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
            access_levels: [1,2,4],
            items:[
                {
                    label: 'Organizational',
                    command: () => router.visit('/indicator/organizational'),
                    access_levels: [1,2,4]
                },
                {
                    label: 'Program',
                    command: () => router.visit('/indicator/program'),
                    access_levels: [1,2,4]
                },
            ]
        },

        {
            label: 'Disaggregations',
            key:'Disaggregations',
            access_levels: [1,2,4],
            items:[
                {
                    label: 'Master List',
                    command: () => router.visit('/disaggregation'),
                    access_levels: [1,2,4]
                },
            ]
        },

        {
            label: 'Reports',
            key:'Reports',
            access_levels: [1,2,3,4,5],
            items:[
                {
                    label: 'All Reports',
                    command: () => router.visit('/report'),
                    access_levels: [1,2,3,4,5]
                },
                {
                    label: 'This Month',
                    command: () => router.visit('/report/monthly-user-report'),
                    access_levels: [1,2,3,4,5]
                },
                {
                    label: 'Create Report',
                    command: () => router.visit('/report/create'),
                    access_levels: [1,2,3,4,5]
                },
            ]
        },

        {
            label: 'Users',
            key:'Users',
            access_levels: [1,2,3,4],
            items:[
                {
                    label: 'Users',
                    command: () => router.visit('/user'),
                    access_levels: [1,2,3,4]
                },
                // {
                //     label: 'Register User',
                //     command: () => router.visit('/user/create'),
                //     access_levels: [1,2,3,4]
                // },
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
        'Reports':true,
        'Users':true
    });

    const handleLogout = () => {
        router.post('/logout');
    }

</script>