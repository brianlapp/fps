<template>
    <Head :title="title"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"> {{ title }}</h2>
                <div class="flex gap-x-2">
                    <PrimaryButton @click="submit">
                        SAVE
                    </PrimaryButton>
                </div>
            </div>

        </template>

        <div class="">
            <div class="w-full mx-auto px-6 lg:px-8 flex md:gap-x-6 flex-col md:flex-row flex-wrap md:flex-nowrap">
                <div class="w-full mx-auto px-6 lg:px-8 flex md:gap-x-6 flex-col md:flex-row flex-wrap md:flex-nowrap">
                    <div class="w-full md:w-8/12 py-8">
                        <div class="mb-2">
                            <InputLabel for="title" value="Title"/>

                            <TextInput
                                id="title"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.title"
                                maxlength="255"
                                required
                            />
                            <InputHint value="255 chars max"/>
                            <InputError class="mt-2" :message="form.errors.title"/>
                        </div>
                        <div class="mb-2">
                            <InputLabel for="slug" value="Slug"/>

                            <TextInput
                                id="slug"
                                type="text"
                                class="mt-1 block w-full text-sm"
                                placeholder="Leave blank and slug will be generated automatically"
                                maxlength="255"
                                v-model="form.slug"
                            />
                            <InputHint value="255 chars max"/>
                            <InputError class="mt-2" :message="form.errors.slug"/>
                        </div>
                        <div class="mb-2">
                            <InputLabel for="link" value="Link"/>

                            <TextInput
                                id="link"
                                type="text"
                                class="mt-1 block w-full text-sm"
                                maxlength="255"
                                v-model="form.link"
                            />
                            <InputHint value="255 chars max"/>
                            <InputError class="mt-2" :message="form.errors.link"/>
                        </div>

                        <div>
                            <InputLabel for="description" value="Description"/>
                            <TextareaInput v-model="form.description" class="w-1/2" rows="4"/>

                            <InputError class="mt-2" :message="form.errors.description"/>
                        </div>
                    </div>

                    <div class="w-full md:w-4/12 py-4 md:py-8 border-t md:border-t-0 md:border-l dark:border-gray-700">
                        <div class="mb-2 md:px-4">
                            <Toggle v-model="form.is_active" label="Is Active"/>
                        </div>
                        <div class="mb-2 md:px-4 flex flex-col md:flex-row md:justify-between">
                            <div>
                                <InputLabel for="image" value="Image"/>

                                <ImageEditor v-model="form.image" :aspect-ratio="6/5"/>
                                <InputHint value="Recommended Image Size: 450x375"/>
                                <InputError class="mt-2" :message="form.errors.image"/>
                            </div>
                        </div>
                        <div class="mb-2 md:px-4">
                            <InputLabel for="countries" value="Countries"/>

                            <multiselect v-model="form.countries" :options="countries" :multiple="true"
                                         :close-on-select="false"
                                         :clear-on-select="false"
                                         :preserve-search="true" placeholder="Select Countries" label="name"
                                         track-by="id"
                                         :preselect-first="false">
                                <template #selection="{ values, search, isOpen }">
                                    <div class="multiselect__single"
                                         v-if="values.length"
                                         v-show="!isOpen">
                                        <div class="flex gap-x-2 flex-wrap gap-y-2">
                                            <div class="py-1 px-3 bg-blue-600 text-white text-sm rounded-full" v-for="value in values">
                                                {{value.name}}
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </multiselect>

                            <InputError class="mt-2" :message="form.errors.countries"/>
                        </div>
                    </div>
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
import WYSIWYG from "@/Components/Admin/WYSIWYG.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TagsInput from "@/Components/Admin/TagsInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import ImageEditor from "@/Components/Admin/ImageEditor.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Toggle from "@/Components/Toggle.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import Multiselect from "vue-multiselect";
import InputHint from "@/Components/InputHint.vue";

export default {
    name: "Offer Edit",
    components: {
        InputHint,
        Multiselect,
        TextareaInput,
        Toggle,
        Checkbox,
        ImageEditor,
        DangerButton,
        TagsInput,
        SecondaryButton, PrimaryButton, WYSIWYG, Head, AuthenticatedLayout, InputError, InputLabel, TextInput
    },
    props: {
        offer: {
            type: Object,
            default() {
                return null;
            }
        },
        category: {
            type: Object,
            default() {
                return null;
            }
        },
        countries: {
            type: Array,
            default() {
                return [];
            }
        }
    },
    data() {
        return {
            form: useForm({
                title: this.offer?.title || null,
                description: this.offer?.description || null,
                slug: this.offer?.slug || null,
                link: this.offer?.link || null,
                image: this.offer?.image || null,
                is_active: this.offer ? this.offer.is_active : true,
                offer_category_id: this.category.id,
                countries: this.offer?.countries || this.countries
            })
        }
    },
    methods: {
        submit() {
            if (this.offer?.id) {
                this.form.patch(route('admin.offers.update', this.offer.id), {});
            } else {
                this.form.post(route('admin.offers.store'), {});
            }
        }
    },
    computed: {
        title() {
            return this.category.single_title.concat(' Editor');
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
