<template>
    <div class="w-full h-full flex flex-col justify-between items-start gap-4 p-2">

        <div class="w-full flex flex-col justify-start items-start gap-4">
                <section class="w-full flex justify-between items-center">
                    <h2 class="text-xl uppercase font-semibold">Programs</h2>
                </section>

                <section>
                    <InputText 
                        v-model="searchQuery"
                        placeholder="Search Keyword" 
                        size="small" 
                        class="w-[400px]"
                    />
                </section>
        </div>

        <section class="w-full h-full overflow-y-auto">
            <div v-for="prog in filteredPrograms" :key="prog.id" class="w-full flex justify-start items-stretch font-light text-sm border-b bg-white hover:bg-[#F0FCFA]">
                    <Accordion value="0" class="w-full">
                        <AccordionPanel>
                            <AccordionHeader class="!uppercase">
                                <div class="w-full flex justify-between items-center pr-4">
                                    <span>{{ prog.name }}</span>
                                    <div>
                                        <Button 
                                            icon="pi pi-pencil" 
                                            text 
                                            rounded
                                            size="small"
                                            severity="secondary"
                                            @click.stop="$inertia.visit(`/program/${prog.id}/edit`)"
                                        />
                                        <Button 
                                            :icon="prog.active ? 'pi pi-check' : 'pi pi-ban'" 
                                            text 
                                            rounded
                                            size="small"
                                            :severity="prog.active ? 'warning' : 'warning'"
                                            @click.stop="toggleDisable(prog)"
                                        />
                                    </div>
                                </div>
                            </AccordionHeader>
                            <AccordionContent>
                                <div class="w-full flex flex-col justify-start items-start ml-4">
                                    <span class="font-medium text-sm">Sub Programs</span>
                                    <li v-for="sub in prog.sub_programs" :key="sub.id" class="ml-4 text-sm">{{ sub.name }}</li>
                                </div>
                            </AccordionContent>
                        </AccordionPanel>
                    </Accordion>
                </div>
        </section>

    </div>
</template>

<script setup>
    import { ref, computed } from 'vue';
    import { router } from '@inertiajs/vue3'
    import MainLayout from '@/layouts/mainLayout.vue';
    import { Button, InputText, Accordion, AccordionContent, AccordionPanel, AccordionHeader } from 'primevue';

    const props = defineProps({
        programs: Array
    })

    defineOptions({
        layout: MainLayout
    })

    const searchQuery = ref('');

    const filteredPrograms = computed(() => {
        if (!searchQuery.value) return props.programs;
        
        const query = searchQuery.value.toLowerCase();
        return props.programs.filter(prog => 
            prog.name.toLowerCase().includes(query) ||
            prog.sub_programs?.some(sub => sub.name.toLowerCase().includes(query))
        );
    });

    const toggleDisable = (program) => {
        router.delete(`/program/${program.id}`, {}, {
            preserveScroll: true,
            onSuccess: () => {
                // Optional: show toast notification
            }
        });
    }
</script>