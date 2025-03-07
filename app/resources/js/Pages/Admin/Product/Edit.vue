<template>
    <Head title="Product Editor"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Product
                    Editor</h2>
                <div class="flex flex-nowrap flex-row gap-x-4">
                    <Toggle v-model="form.is_active" label="Is Active"/>
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
                                required
                            />

                            <InputError class="mt-2" :message="form.errors.title"/>
                        </div>
                        <div class="mb-2 flex flex-col md:flex-row gap-y-4 gap-x-4">

                            <div class="w-full md:w-2/3">

                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="description" value="Short Description"/>
                            <WYSIWYG v-model="form.description" entity="product"/>

                            <InputError class="mt-2" :message="form.errors.description"/>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="description" value="Full Description"/>
                            <WYSIWYG v-model="form.long_description" entity="product"/>

                            <InputError class="mt-2" :message="form.errors.long_description"/>
                        </div>
                        <div class="mt-6 flex flex-col md:flex-row gap-x-4 gap-y-4">
                            <div class="flex flex-row gap-x-4">
                                <div>
                                    <InputLabel for="price_from" value="Price From"/>

                                    <TextInput
                                        id="price_from"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        class="mt-1 block w-full text-sm"
                                        v-model="form.price_from"
                                    />

                                    <InputError class="mt-2" :message="form.errors.price_from"/>
                                </div>
                                <div>
                                    <InputLabel for="price_to" value="Price To"/>

                                    <TextInput
                                        id="price_to"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        class="mt-1 block w-full text-sm"
                                        v-model="form.price_to"
                                    />

                                    <InputError class="mt-2" :message="form.errors.price_to"/>
                                </div>
                            </div>
                            <div class="w-full md:w-2/4">
                                <InputLabel for="price_range_string" value="Price Range in words"/>

                                <TextInput
                                    id="price_range_string"
                                    type="text"
                                    class="mt-1 block w-full text-sm"
                                    placeholder="Price Range in words e.g 'Free' or 'Various'. NOTE: will override Price From and Price To fields!"
                                    v-model="form.price_range_string"
                                />

                                <InputError class="mt-2" :message="form.errors.price_range_string"/>
                            </div>

                        </div>
                        <div class="mt-6">
                            <InputLabel for="description" value="Deals"/>
                            <WYSIWYG v-model="form.deals" entity="product"/>

                            <InputError class="mt-2" :message="form.errors.deals"/>
                        </div>
                        <div class="mt-6">
                            <div class="flex flex-row justify-between">
                                <InputLabel for="affiliate_links" value="Affiliate Links"/>
                                <PrimaryButton @click="createAffiliateLink" class="w-fit">
                                    <i class="fa-solid fa-plus w-4 mr-2"></i>Add Affiliate Link
                                </PrimaryButton>
                            </div>

                            <div class="flex flex-col gap-y-4 min-h-[5vh]">
                                <div class="flex flex-col md:flex-row gap-x-4 py-4 gap-y-2 border-b border-gray-500 md:border-none" v-for="link in form.affiliate_links"
                                     :key="link.uuid">
                                    <div class="md:w-3/12">
                                        <TextInput
                                            :id="'link-title-'.concat(link.uuid)"
                                            type="text"
                                            class="mt-1 block w-full text-sm"
                                            placeholder="Link Title"
                                            v-model="link.title"
                                        />
                                    </div>
                                    <div class="md:w-8/12">
                                        <TextInput
                                            :id="'link-url-'.concat(link.uuid)"
                                            type="text"
                                            class="mt-1 block w-full text-sm"
                                            placeholder="Link URL"
                                            v-model="link.url"
                                        />
                                    </div>
                                    <div class="md:w-3/12 flex justify-between">
                                        <Toggle v-model="link.isPrimary" label="Primary"/>
                                        <button
                                            class="flex items-center h-full cursor-pointer text-red-500 py-2 px-4 hover:text-red-700 dark:text-red-500 dark:hover:text-red-400"
                                            @click="removeAffiliateLink(link)">
                                            <i class="fa fa-trash fa-xl"/>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <InputError class="mt-2" :message="form.errors.affiliate_links"/>
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
                            <InputLabel for="categories" value="Categories"/>

                            <multiselect v-model="form.categories" :options="categories" :multiple="true"
                                         :close-on-select="false"
                                         :clear-on-select="false"
                                         :preserve-search="true" placeholder="Select Categories" label="title"
                                         track-by="id"
                                         :preselect-first="false">
                                <template #selection="{ values, search, isOpen }">
                                    <div class="multiselect__single"
                                         v-if="values.length"
                                         v-show="!isOpen">
                                        <div class="flex gap-x-2 flex-wrap gap-y-2">
                                            <div class="py-1 px-3 bg-blue-600 text-white text-sm rounded-full" v-for="value in values">
                                                {{value.title}}
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </multiselect>

                            <InputError class="mt-2" :message="form.errors.categories"/>
                        </div>

                        <div class="mb-2  md:px-4">
                            <InputLabel for="seo_title" value="SEO Title"/>

                            <TextInput
                                id="seo_title"
                                type="text"
                                class="mt-1 block w-full text-sm"
                                placeholder="Leave blank to use title as SEO title"
                                v-model="form.seo_title"
                            />

                            <InputError class="mt-2" :message="form.errors.seo_title"/>
                        </div>

                        <div class="mb-2 md:px-4">
                            <InputLabel for="seo_description" value="SEO Headline"/>

                            <TextareaInput
                                id="seo_description"
                                type="text"
                                rows="4"
                                placeholder="Leave blank to use description as SEO headline"
                                class="mt-1 block w-full text-sm"
                                v-model="form.seo_description"
                            />

                            <InputError class="mt-2" :message="form.errors.seo_description"/>
                        </div>
                        <div class="mb-2 md:px-4 w-full">
                            <InputLabel for="image" class="mb-2" value="Image"/>

                            <ImageEditor v-model="form.image" :aspect-ratio="6/5"/>

                            <InputError class="mt-2" :message="form.errors.image"/>
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
import {v4} from "uuid";
import {filter} from "lodash";
import Multiselect from "vue-multiselect";

export default {
    name: "Product Edit",
    components: {
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
        product: {
            type: Object,
            default() {
                return null;
            }
        },
        categories: {
            type: Array,
            default() {
                return [];
            }
        },
    },
    data() {
        return {
            form: useForm({
                title: this.product?.title || null,
                description: this.product?.description || null,
                long_description: this.product?.long_description || null,
                slug: this.product?.slug || null,
                image: this.product?.image || null,
                seo_title: this.product?.seo_title || null,
                seo_description: this.product?.seo_description || null,
                is_active: this.product ? this.product.is_active : true,
                deals: this.product?.deals,
                price_from: this.product?.price_from || null,
                price_to: this.product?.price_to || null,
                price_range_string: this.product?.price_range_string || null,
                affiliate_links: this.product?.affiliate_links || [],
                categories: this.product?.categories || [],
            })
        }
    },
    methods: {
        submit() {
            if (this.product?.id) {
                this.form.patch(route('admin.products.update', this.product.id), {});
            } else {
                this.form.post(route('admin.products.store'), {});
            }
        },
        createAffiliateLink() {
            const uuid = v4();
            this.form.affiliate_links.push({
                uuid: uuid,
                title: null,
                url: null,
                isPrimary: false
            })
            this.$nextTick(() => {
                const input = document.getElementById('link-title-'.concat(uuid));
                if (input) {
                    input.focus();
                    input.scrollIntoView({behavior: 'smooth'});
                }
            });
        },
        removeAffiliateLink(link) {
            this.form.affiliate_links = filter(this.form.affiliate_links, l => l.uuid !== link.uuid);
        }
    }
}

</script>

<style>
:root {
    .ck-editor__editable_inline:not(.ck-comment__input *) {
        height: 20vh;
        overflow-y: auto;
    }
}
</style>
