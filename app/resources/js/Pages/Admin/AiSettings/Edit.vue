<template>
    <Head :title="title"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"> {{ title }}</h2>
                <div class="flex flex-row gap-x-2">
                    <SetDefaultsModal :url="route('admin.ai-settings.set-defaults', settings.function)" @updated="reload"/>
                    <PrimaryButton class="w-fit" @click="submit">
                        SAVE
                    </PrimaryButton>
                </div>
            </div>

        </template>

        <div class="">
            <div class="w-full mx-auto px-6 lg:px-8 flex md:gap-x-6 flex-col flex-wrap">
                <div class="w-full py-8">
                    <div class="mb-4 flex flex-col md:flex-row gap-x-2">
                        <div class="w-full md:w-1/2">
                            <InputLabel for="prompt" value="Prompt (readonly)"/>
                            <TextareaInput v-model="form.prompt" class="w-full" rows="20" readonly="readonly"/>

                            <InputError class="mt-2" :message="form.errors.prompt"/>
                        </div>
                        <div class="w-full md:w-1/2">
                            <InputLabel for="instructions" value="instructions"/>
                            <TextareaInput v-model="form.instructions" class="w-full" rows="20"/>

                            <InputError class="mt-2" :message="form.errors.instructions"/>
                        </div>
                    </div>
                    <div class="mb-4 flex flex-row gap-x-2">
                        <div class="w-1/2" v-if="models.length > 1">
                            <InputLabel for="model" value="Model"/>
                            <select v-model="form.model"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option v-for="model in models" :key="model" :value="model">
                                    {{ model }}
                                </option>
                            </select>

                            <InputError class="mt-2" :message="form.errors.model"/>
                        </div>
                        <div class="w-1/2" v-if="settings.function !== 'image_for_article'">
                            <InputLabel for="temperature" value="Temperature"/>
                            <TextInput
                                id="temperature"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.temperature"
                                :min="0" :max="0.9" :step="0.1"
                                required
                            />

                            <InputError class="mt-2" :message="form.errors.temperature"/>
                        </div>
                    </div>
                </div>

                <div class="w-full mt-8">
                    <h2 class="text-4xl my-8 pt-8 text-gray-700 dark:text-gray-300 text-center">Testing Tool</h2>
                    <LiveArticleTestTool v-if="settings.function === 'live_article'" :settings="form.data()"/>
                    <ArticlesTestTool v-else-if="settings.function === 'articles'" :settings="form.data()"/>
                    <ImageTestTool v-else-if="settings.function === 'image_for_article'" :settings="form.data()"/>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import {Head, useForm, router} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import SetDefaultsModal from "@/Components/Admin/SetDefaultsModal.vue";
import LiveArticleTestTool from "@/Pages/Admin/AiSettings/Partials/LiveArticleTestTool.vue";
import ArticlesTestTool from "@/Pages/Admin/AiSettings/Partials/ArticlesTestTool.vue";
import ImageTestTool from "@/Pages/Admin/AiSettings/Partials/ImageTestTool.vue";

export default {
    name: "Offer Edit",
    components: {
        ImageTestTool,
        ArticlesTestTool,
        LiveArticleTestTool,
        SetDefaultsModal,
        TextareaInput,
        Checkbox,
        DangerButton,
        SecondaryButton, PrimaryButton, Head, AuthenticatedLayout, InputError, InputLabel, TextInput
    },
    props: {
        settings: {
            type: Object,
            default() {
                return null;
            }
        },
        models: {
            type: Array,
            default() {
                return [];
            }
        },
    },
    data() {
        return {
            form: useForm({
                prompt: this.settings?.prompt || null,
                instructions: this.settings?.instructions || null,
                model: this.settings?.model || null,
                temperature: this.settings?.temperature || null,
            })
        }
    },
    methods: {
        submit() {
            this.form.patch(route('admin.ai-settings.update', this.settings.function), {});
        },
        reload() {
            window.location.reload();
        }
    },
    computed: {
        title() {
            return 'AI Settings Editor';
        }
    }
}

</script>

<style>
:root {
    .ck-editor__editable_inline:not(.ck-comment__input *) {
        height: 50vh;
        overflow-y: auto;
    }
}
</style>
