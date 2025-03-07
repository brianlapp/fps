<template>
    <Head title="Article Editor"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Article Editor</h2>
                <div class="flex flex-nowrap flex-row gap-x-4">
                    <Toggle v-model="form.is_featured" label="Featured"/>
                    <Toggle v-model="form.is_page" label="Page"/>
                    <PrimaryButton @click="publish" v-if="showPublishButton">
                        PUBLISH
                    </PrimaryButton>
                    <SecondaryButton @click="draft" v-if="showDraftButton">
                        DRAFT
                    </SecondaryButton>
                    <PrimaryButton @click="submit" v-if="showSaveButton">
                        SAVE
                    </PrimaryButton>
                    <DangerButton @click="draft" v-if="showUnpublishButton">
                        Unpublish
                    </DangerButton>
                </div>
            </div>

        </template>

        <div class="">
            <loading v-model:active="isImproving"
                     :is-full-page="true"/>
            <div class="w-full mx-auto px-6 lg:px-8 flex md:gap-x-6 flex-col md:flex-row flex-wrap md:flex-nowrap">
                <div class="w-full md:w-8/12 py-8">
                    <div class="mb-2">
                        <InputLabel for="title" value="Title"/>

                        <TextInput
                            id="title"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.title"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.title"/>
                    </div>

                    <div>
                        <div class="flex-col md:flex-row flex justify-between items-center">
                            <InputLabel for="content" value="Content"/>
                            <div class="flex-col md:flex-row flex gap-x-2">
                                <WarningButton @click="openCompareModal" v-if="isImproved" class="flex w-fit mb-3">
                                    Compare original text with improved one
                                </WarningButton>
                                <PrimaryButton @click="improve" class="flex w-fit mb-3" :disabled="isImproving">
                                    Improve Content with AI
                                </PrimaryButton>
                            </div>
                        </div>
                        <WYSIWYG v-model="form.content" entity="article"/>

                        <InputError class="mt-2" :message="form.errors.content"/>
                    </div>
                </div>
                <div class="w-full md:w-4/12 py-4 md:py-8 border-t md:border-t-0 md:border-l dark:border-gray-700">
                    <div class="mb-2 md:px-4">
                        <InputLabel for="slug" value="Slug"/>

                        <TextInput
                            id="slug"
                            type="text"
                            class="mt-1 block w-full text-sm"
                            placeholder="Leave blank and slug will be generated automatically"
                            v-model="form.slug"
                        />

                        <InputError class="mt-2" :message="form.errors.slug"/>
                    </div>
                    <div class="mb-2 md:px-4">
                        <InputLabel for="topics" value="Topics"/>

                        <multiselect v-model="form.topics" :options="topics" :multiple="true" :close-on-select="false"
                                     :clear-on-select="false"
                                     :preserve-search="true" placeholder="Select Topics" label="title" track-by="id"
                                     :preselect-first="false">
                            <template #selection="{ values, search, isOpen }">
                                <div class="multiselect__single"
                                     v-if="values.length"
                                     v-show="!isOpen">{{ values.map(i => i.title).join(' | ') }}
                                </div>
                            </template>
                        </multiselect>

                        <InputError class="mt-2" :message="form.errors.topics"/>
                    </div>
                    <div class="mb-2 md:px-4">
                        <InputLabel for="tags" value="Tags"/>

                        <TagsInput
                            id="title"
                            class="mt-1 block w-full text-sm"
                            v-model="form.tags"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.tags"/>
                    </div>
                    <div class="mb-2 md:px-4 w-full flex flex-col justify-start relative" v-if="authors.length > 0">
                        <InputLabel for="authorDropdownButton" value="Author"/>
                        <button id="authorDropdownButton" data-dropdown-toggle="authorDropdown"
                                ref="authorDropdown"
                                class="w-full flex items-center p-2 justify-between border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                type="button">
                            <div class="flex items-center gap-x-2">
                                <img :src="form.author.avatar" class="w-6 h-6 rounded-full">
                                {{ form.author.name }}
                            </div>
                            <i class="fa-solid fa-chevron-down w-4 ml-2 text-gray-700 dark:text-gray-400"></i>
                        </button>
                        <div id="authorDropdown"
                             class="z-10 hidden w-full p-3 bg-white rounded-lg shadow dark:bg-gray-900">
                            <ul class="space-y-2 text-sm max-h-[300px] md:max-h-[500px] overflow-auto"
                                aria-labelledby="authorDropdownButton">
                                <li class="flex items-center cursor-pointer hover:bg-white hover:dark:bg-gray-700"
                                    v-for="author in authors" @click="selectAuthor(author)"
                                    :key="'a-'.concat(author.id)">
                                    <div class="flex items-center gap-x-2">
                                        <img :src="author.avatar" class="w-6 h-6 rounded-full">
                                        <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"> {{
                                                author.name
                                            }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <InputError class="mt-2" :message="form.errors.created_by"/>
                    </div>
                    <div class="mb-2 md:px-4">
                        <InputLabel for="image" value="Image"/>

                        <ImageEditor v-model="form.image"
                                     :generate-url="article?.id ? route('admin.articles.generateImage', article?.id) : null"
                                     @setCaption="setCaption"/>

                    </div>
                    <div class="mb-2 md:px-4">
                        <InputLabel for="image_caption" value="Image Caption"/>

                        <TextareaInput
                            id="image_caption"
                            type="text"
                            rows="4"
                            class="mt-1 block w-full text-sm"
                            v-model="form.image_caption"
                        />

                        <InputError class="mt-2" :message="form.errors.image_caption"/>
                    </div>

                    <h2 class="text-2xl mb-4 mt-6 md:px-4 text-gray-800 dark:text-gray-200 leading-tight">SEO</h2>
                    <div class="mb-2 md:px-4">
                        <InputLabel for="seo_title" value="Title"/>

                        <TextInput
                            id="seo_title"
                            type="text"
                            class="mt-1 block w-full text-sm"
                            placeholder="Leave blank to use Article's title as SEO title"
                            v-model="form.seo_title"
                        />

                        <InputError class="mt-2" :message="form.errors.seo_title"/>
                    </div>

                    <div class="mb-2 md:px-4">
                        <InputLabel for="seo_headline" value="Description"/>

                        <TextareaInput
                            id="seo_headline"
                            type="text"
                            rows="4"
                            placeholder="Leave blank to use Article's first parargraph as SEO description"
                            class="mt-1 block w-full text-sm"
                            v-model="form.seo_headline"
                        />

                        <InputError class="mt-2" :message="form.errors.seo_headline"/>
                    </div>


                </div>
            </div>
            <CompareModal v-if="showCompareModal" :original-content="article.content" :improved-content="form.content" @close="closeCompareModal"/>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import WYSIWYG from "@/Components/Admin/WYSIWYG.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TagsInput from "@/Components/Admin/TagsInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import ImageEditor from "@/Components/Admin/ImageEditor.vue";
import Toggle from "@/Components/Toggle.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import Multiselect from 'vue-multiselect';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
import WarningButton from "@/Components/WarningButton.vue";
import CompareModal from "@/Pages/Admin/Articles/Partials/CompareModal.vue";

export default {
    name: "Article Edit",
    components: {
        CompareModal,
        WarningButton,
        TextareaInput,
        Toggle,
        ImageEditor,
        DangerButton,
        TagsInput,
        SecondaryButton, PrimaryButton, WYSIWYG, Head, AuthenticatedLayout, InputError, InputLabel, TextInput,
        Multiselect,
        Loading
    },
    props: {
        article: {
            type: Object,
            default() {
                return null;
            }
        },
        authors: {
            type: Array,
            default: () => []
        },
        topics: {
            type: Array,
            default: () => []
        },
    },
    computed: {
        showPublishButton() {
            return !this.article || this.article.status === 'draft';
        },
        showDraftButton() {
            return !this.article || this.article.status === 'draft';
        },
        showUnpublishButton() {
            return this.article?.status === 'published';
        },
        showSaveButton() {
            return this.article?.status === 'published';
        }
    },
    data() {
        return {
            form: useForm({
                title: this.article?.title || null,
                content: this.article?.content || null,
                image: this.article?.image || null,
                slug: this.article?.slug || null,
                author: this.article?.author || this.$page.props.auth.user,
                created_by: this.article?.author?.id || this.$page.props.auth.user.id,
                tags: this.article?.tags || [],
                status: this.article?.status || 'draft',
                is_page: this.article?.is_page || false,
                image_caption: this.article?.image_caption || null,
                topics: this.article?.topics || [],
                was_improved: this.article?.was_improved || false,
                seo_title: this.article?.seo_title || null,
                seo_headline: this.article?.seo_headline || null,
                is_featured: this.article?.is_featured || false,
            }),
            isImproving: false,
            isImproved: false,
            showCompareModal: false
        }
    },
    methods: {
        selectAuthor(author) {
            this.form.author = author;
            this.form.created_by = author.id;
            this.$refs.authorDropdown.click();
        },
        publish() {
            this.form.status = 'published';
            this.submit();
        },
        draft() {
            this.form.status = 'draft';
            this.submit();
        },
        submit() {
            if (this.article?.id) {
                this.form.patch(route('admin.articles.update', this.article.id), {});
            } else {
                this.form.post(route('admin.articles.store'), {});
            }

        },
        setCaption(caption) {
            if (caption) {
                this.form.image_caption = caption;
            }
        },
        improve() {
            this.isImproving = true;
            axios.get(route('admin.articles.improve', this.article?.id)).then(({data}) => {
                this.form.content = data.content;
                this.form.was_improved = true;
                this.isImproved = true;
            }).catch(e => {
                this.$page.props.flash.error = e?.response?.data?.message || e;
            }).finally(() => {
                this.isImproving = false;
            });
        },
        openCompareModal() {
            this.showCompareModal = true;
        },
        closeCompareModal() {
            this.showCompareModal = false;
        }
    }
}

</script>

<style>
:root {
    .ck-editor__editable_inline:not(.ck-comment__input *) {
        height: 70vh;
        overflow-y: auto;
    }
}
</style>
