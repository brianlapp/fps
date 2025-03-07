<template>
    <Head title="Product Category Editor"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Product Category
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
                <div class="w-full py-8">
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
                    <div class="mb-2">
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
                    <div class="mb-2 flex flex-col md:flex-row gap-y-4 gap-x-4">
                        <div class="w-f md:w-1/3">
                            <InputLabel for="image" class="mb-2" value="Image"/>

                            <ImageEditor v-model="form.image" :aspect-ratio="6/5"/>

                            <InputError class="mt-2" :message="form.errors.image"/>
                        </div>
                        <div class="w-full md:w-2/3">
                            <div class="mb-2">
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

                            <div class="mb-2">
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
                        </div>
                    </div>
                    <div>
                        <InputLabel for="description" value="Description"/>
                        <WYSIWYG v-model="form.description" entity="product_category"/>

                        <InputError class="mt-2" :message="form.errors.description"/>
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

export default {
    name: "Product Category Edit",
    components: {
        TextareaInput,
        Toggle,
        Checkbox,
        ImageEditor,
        DangerButton,
        TagsInput,
        SecondaryButton, PrimaryButton, WYSIWYG, Head, AuthenticatedLayout, InputError, InputLabel, TextInput
    },
    props: {
        category: {
            type: Object,
            default() {
                return null;
            }
        },
    },
    data() {
        return {
            form: useForm({
                title: this.category?.title || null,
                description: this.category?.description || null,
                slug: this.category?.slug || null,
                image: this.category?.image || null,
                seo_title: this.category?.seo_title || null,
                seo_description: this.category?.seo_description || null,
                is_active: this.category ? this.category.is_active : true,
            })
        }
    },
    methods: {
        submit() {
            if (this.category?.id) {
                this.form.patch(route('admin.product_categories.update', this.category.id), {});
            } else {
                this.form.post(route('admin.product_categories.store'), {});
            }
        }
    }
}

</script>

<style>
:root {
    .ck-editor__editable_inline:not(.ck-comment__input *) {
        height: 30vh;
        overflow-y: auto;
    }
}
</style>
