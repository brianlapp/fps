<template>
    <Head title="Offer Category Editor"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Offer Category Editor</h2>
                <div class="flex gap-x-2">
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
                    <div class="mb-2 flex flex-col md:flex-row md:justify-between">
                        <div>
                            <InputLabel for="image" value="Image"/>

                            <ImageEditor v-model="form.image"/>

                            <InputError class="mt-2" :message="form.errors.image"/>
                        </div>
                        <div class="pt-5">
                            <Toggle v-model="form.is_active" label="Is Active"/>
                        </div>
                    </div>
                    <div>
                        <InputLabel for="description" value="Description"/>
                        <WYSIWYG v-model="form.description" entity="offer_category"/>

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

export default {
    name: "Offer Category Edit",
    components: {
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
                is_active: this.category ? this.category.is_active : true,
            })
        }
    },
    methods: {
        submit() {
            if (this.category?.id) {
                this.form.patch(route('admin.offer_categories.update', this.category.id), {});
            } else {
                this.form.post(route('admin.offer_categories.store'), {});
            }
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
