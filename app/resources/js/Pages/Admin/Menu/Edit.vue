<template>
    <Head :title="title"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"> Menu Item </h2>
                <div class="flex gap-x-2">
                    <PrimaryButton @click="submit">
                        SAVE
                    </PrimaryButton>
                </div>
            </div>

        </template>

        <div class="">
            <div class="w-full max-w-7xl px-6 lg:px-8 flex md:gap-x-6 flex-col flex-wrap">
                <div class="w-full py-8">
                    <div class="mb-2">
                        <InputLabel for="name" value="Name"/>

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.name"/>
                    </div>
                    <div class="mb-2">
                        <InputLabel for="link" value="Link"/>

                        <TextInput
                            id="link"
                            type="text"
                            class="mt-1 block w-full text-sm"
                            placeholder="Enter an absolute (external) or relative (internal) URL. Internal must start with '/' "
                            v-model="form.link"
                        />

                        <InputError class="mt-2" :message="form.errors.link"/>
                    </div>
                </div>
                <div class="w-full flex flex-col sm:flex-row sm:gap-x-2 sm:items-end justify-between py-2 flex-wrap gap-y-4">
                    <div class="w-full sm:w-1/4">
                        <InputLabel for="list" value="Placement"/>
                        <select v-model="form.list"
                                class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option v-for="option in listOptions" :key="option" :value="option">
                                {{ option }}
                            </option>
                        </select>

                        <InputError class="mt-2" :message="form.errors.list"/>
                    </div>
                    <Toggle v-model="form.is_new" label="NEW!"/>
                    <Toggle v-model="form.is_coming_soon" label="Coming Soon!"/>
                    <Toggle v-model="form.is_beta" label="Beta"/>
                    <Toggle v-model="form.auth_only" label="Logged In only"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import ImageEditor from "@/Components/Admin/ImageEditor.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Toggle from "@/Components/Toggle.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

export default {
    name: "Menu Edit",
    components: {
        TextareaInput,
        Toggle,
        Checkbox,
        ImageEditor,
        Head,
        PrimaryButton,
        DangerButton,
        AuthenticatedLayout, InputError, InputLabel, TextInput
    },
    props: {
        menu: {
            type: Object,
            default() {
                return null;
            }
        },
    },
    data() {
        return {
            form: useForm({
                name: this.menu?.name || null,
                link: this.menu?.link || null,
                is_new: this.menu?.is_new || false,
                is_coming_soon: this.menu?.is_coming_soon || false,
                auth_only: this.menu?.auth_only || false,
                is_beta: this.menu?.is_beta || false,
                list: this.menu?.list || 'main',
            }),
            listOptions: [
                'main',
                'sidebar'
            ]
        }
    },
    methods: {
        submit() {
            if (this.menu?.id) {
                this.form.put(route('admin.menu.update', this.menu.id), {});
            } else {
                this.form.post(route('admin.menu.store'), {});
            }
        }
    },
}

</script>
