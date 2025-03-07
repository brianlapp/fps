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
                            <InputLabel value="Header"/>
                            <codemirror :model-value="form.header"
                                        :options="cmOptions"
                                        style="min-height: 500px"
                                        :tab-size="cmOptions.tabSize"
                                        :extensions="cmOptions.extensions"
                                        @change="onHeaderChange"/>
                        </div>

                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <InputLabel value="Footer"/>
                            <codemirror :value="form.footer"
                                        :options="cmOptions"
                                        style="min-height: 500px"
                                        :tab-size="cmOptions.tabSize"
                                        :extensions="cmOptions.extensions"
                                        @change="onFooterChange"/>
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

export default {
    name: "Code Injections",
    components: {
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
        header: {
            type: String,
        },
        footer: {
            type: String,
        },
    },
    data() {
        return {
            form: useForm({
                header: this.header ?? '',
                footer: this.footer ?? '',
            }),
            cmOptions: {
                // codemirror options
                tabSize: 4,
                mode: 'text/html',
                lineNumbers: true,
                line: true,
                extensions: [javascript(), oneDark, html(), json()]
            }
        }
    },
    methods: {
        submit() {
            this.form.patch(route('admin.code-injections.update'), {});
        },
        onHeaderChange(newCode) {
            this.form.header = newCode
        },
        onFooterChange(newCode) {
            this.form.footer = newCode
        },
    },
    computed: {
        title() {
            return 'Code Injections';
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
