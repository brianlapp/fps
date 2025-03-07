<template>
    <Head title="Planner Section Editor"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Section for `{{category.title}}`
                    Editor</h2>
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
                    <div class="mb-2 flex flex-col sm:flex-row gap-y-2 sm:gap-x-2">
                        <div class="w-full sm:w-1/2">
                            <InputLabel for="color" value="Color" class="mb-2"/>

                            <ColorPicker v-model:pureColor="form.color"/>

                            <InputError class="mt-2" :message="form.errors.color"/>
                        </div>

                    </div>
                    <div>
                        <InputLabel for="description" value="Explanation"/>
                        <WYSIWYG v-model="form.description" entity="planner_section"/>

                        <InputError class="mt-2" :message="form.errors.description"/>
                    </div>
                    <div class="py-6">
                        <h2 class="text-2xl text-gray-800 dark:text-gray-200 leading-tight text-center mb-8">
                            Items</h2>
                        <Item v-for="(item, i) in form.items" :key="item.uuid" :item="item" :errors="form.errors" :i="i" @removeItem="removeItem"/>
                        <PrimaryButton @click="addItem" class="max-w-3xl mx-auto text-xl">+ Add Item
                        </PrimaryButton>
                        <InputError class="mt-2 text-center" :message="form.errors.items"/>
                    </div>
                    <div class="py-6">
                        <h2 class="text-2xl text-gray-800 dark:text-gray-200 leading-tight text-center mb-8">
                            Bottom Section</h2>
                        <WYSIWYG v-model="form.links" entity="planner_section"/>
                        <InputError class="mt-2 text-center" :message="form.errors.links"/>
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
import {ColorPicker} from "vue3-colorpicker";
import {v4} from 'uuid';
import TextareaInput from "@/Components/TextareaInput.vue";
import Item from "@/Pages/Admin/PlannerSection/Partials/Item.vue";

export default {
    name: "Planner Section Edit",
    components: {
        Item,
        TextareaInput,
        Toggle,
        Checkbox,
        ImageEditor,
        DangerButton,
        TagsInput,
        SecondaryButton, PrimaryButton, WYSIWYG, Head, AuthenticatedLayout, InputError, InputLabel, TextInput,
        ColorPicker
    },
    props: {
        section: {
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
    },
    data() {
        return {
            form: useForm({
                planner_category_id: this.category.id,
                title: this.section?.title || null,
                description: this.section?.description || null,
                slug: this.section?.slug || null,
                image: this.section?.image || null,
                color: this.section?.color || 'rgba(0, 0, 0, 0)',
                items: this.section?.items || [],
                links: this.section?.links || null,
            })
        }
    },
    methods: {
        submit() {
            if (this.section?.id) {
                this.form.patch(route('admin.planner_sections.update', this.section.id), {});
            } else {
                this.form.post(route('admin.planner_sections.store'), {});
            }
        },
        addItem() {
            this.form.items.push({
                id: null,
                uuid: v4(),
                title: null,
                label: null,
                color: 'rgba(0, 0, 0, 0)',
                description: null
            })
        },
        removeItem(item) {
            this.form.items = this.form.items.filter(i => i.uuid !== item.uuid);
        },
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
