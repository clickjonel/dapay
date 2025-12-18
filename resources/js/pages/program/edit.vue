<template>
    <main class="w-full min-h-screen flex justify-center items-center bg-white">
        <section class="w-full max-w-2xl px-6">
            
            <!-- Header -->
            <div class="mb-12">
                <h1 class="text-2xl font-light mb-2">Edit Program</h1>
                <Divider class="mt-2" />
            </div>

            <!-- Program Name -->
            <div class="mb-12">
                <label class="block text-sm text-gray-600 mb-3">Program Name</label>
                <InputText 
                    v-model="form.name"
                    class="w-full"
                    variant="outlined"
                    placeholder="Enter program name"
                    :pt="{
                        root: { class: 'border-0 border-b border-gray-300 rounded-none focus:border-black px-0' }
                    }"
                />
            </div>

            <!-- Sub Programs -->
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <label class="text-sm text-gray-600">Sub Programs</label>
                    <Button 
                        label="Add"
                        icon="pi pi-plus"
                        @click="addSubProgram"
                        text
                        size="small"
                        class="text-sm"
                    />
                </div>

                <div class="space-y-6">
                    <div 
                        v-for="(sub, index) in form.sub_programs" 
                        :key="sub.id || index"
                        class="flex items-center gap-4 group"
                    >
                        <span class="text-sm text-gray-400 w-6">{{ index + 1 }}</span>
                        <InputText 
                            v-model="sub.name"
                            class="flex-1"
                            variant="outlined"
                            placeholder="Sub program name"
                            :pt="{
                                root: { class: 'border-0 border-b border-gray-200 rounded-none focus:border-gray-400 px-0' }
                            }"
                        />
                        <Button 
                            icon="pi pi-times"
                            @click="removeSubProgram(index)"
                            text
                            rounded
                            size="small"
                            severity="secondary"
                            class="opacity-0 group-hover:opacity-100 transition-opacity"
                        />
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-6">
                <Divider class="mb-4" />
                <Button 
                    label="Cancel"
                    @click="router.visit('/program')"
                    text
                    severity="secondary"
                />
                <Button 
                    label="Save"
                    @click="submitForm"
                    severity="contrast"
                />
            </div>

        </section>
    </main>
</template>

<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3'
import { Button, InputText, Divider } from 'primevue';

const props = defineProps({
    program: Object
})

const form = reactive({
    name: props.program.name || '',
    sub_programs: props.program.sub_programs ? [...props.program.sub_programs] : []
})

const addSubProgram = () => {
    form.sub_programs.push({ name: '' })
}

const removeSubProgram = (index) => {
    form.sub_programs.splice(index, 1)
}

const submitForm = () => {
    router.put(`/program/${props.program.id}`, form)
}
</script>