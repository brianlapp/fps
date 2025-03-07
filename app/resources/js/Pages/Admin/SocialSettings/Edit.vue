<template>
    <Head :title="title"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"> {{ title }}</h2>
                <div class="flex flex-row gap-x-2">
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
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <h2 class="text-2xl mb-6 text-gray-700 dark:text-gray-300">Site SEO</h2>
                            <div class="w-full mb-2">
                                <InputLabel for="seo_title" value="Title"/>
                                <TextInput id="seo_title" v-model="form.seo.title" class="w-full"/>

                                <InputError class="mt-2" :message="form.errors.seo_title"/>
                            </div>
                            <div class="w-full mb-2">
                                <InputLabel for="seo_headline" value="Headline"/>
                                <TextareaInput id="seo_headline" v-model="form.seo.headline" class="w-full" rows="5"/>

                                <InputError class="mt-2" :message="form.errors.seo_headline"/>
                            </div>
                            <div class="mb-2 w-full">
                                <InputLabel for="seo_image" value="Image"/>

                                <ImageEditor v-model="form.seo.image" :aspect-ratio="null"/>

                                <InputError class="mt-2" :message="form.errors.seo_image"/>
                            </div>
                        </div>

                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <h2 class="text-2xl mb-6 text-gray-700 dark:text-gray-300">Social Networks</h2>
                            <template v-for="key in Object.keys(networks)" :key="key">
                                <div class="w-full mb-2">
                                    <InputLabel :for="'networks_'.concat(key)" :value="key.capitalize()"/>
                                    <TextInput :id="'networks_'.concat(key)" v-model="form.networks[key]" class="w-full"/>

                                    <InputError class="mt-2" :message="form.errors['networks_'.concat(key)]"/>
                                </div>
                            </template>
                        </div>
                    </div>
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
import {Codemirror} from "vue-codemirror";
import { javascript } from '@codemirror/lang-javascript'
import { html } from '@codemirror/lang-html'
import { json } from '@codemirror/lang-json'
import { oneDark } from '@codemirror/theme-one-dark'
import ImageEditor from "@/Components/Admin/ImageEditor.vue";

export default {
    name: "Code Injections",
    components: {
        ImageEditor,
        Codemirror,
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
        seo: {
            type: Object,
        },
        networks: {
            type: String,
        },
    },
    data() {
        return {
            form: useForm({
               seo: this.seo,
                networks: this.networks,
            }),
        }
    },
    methods: {
        submit() {
            this.form.patch(route('admin.social-settings.update'), {});
        },
    },
    computed: {
        title() {
            return 'SEO & Socials';
        }
    }
}

</script>
