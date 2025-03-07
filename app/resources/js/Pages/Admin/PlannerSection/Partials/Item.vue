<script>
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {ColorPicker} from "vue3-colorpicker";
import WYSIWYG from "@/Components/Admin/WYSIWYG.vue";

export default {
    name: "Item",
    components: {WYSIWYG, ColorPicker, InputLabel, InputError, TextInput},
    props: {
        i: Number,
        item: Object,
        errors: Object
    }
}
</script>

<template>
    <div class="py-4 px-2 bg-gray-200 dark:bg-gray-700 flex flex-col gap-y-4 w-full mb-4 rounded">
        <div class="w-full flex flex-row gap-x-4">
            <div class="w-5/12">
                <InputLabel :for="'title-'.concat(item.uuid)" value="Title"/>
                <TextInput
                    :id="'title-'.concat(item.uuid)"
                    required
                    type="text"
                    class="mt-1 block w-full text-sm"
                    v-model="item.title"
                />
                <InputError class="mt-2" :message="errors[`items.${i}.title`]"/>
            </div>
            <div class="w-5/12">
                <InputLabel :for="'label-'.concat(item.uuid)" value="Label"/>
                <TextInput
                    :id="'label-'.concat(item.uuid)"
                    required
                    type="text"
                    class="mt-1 block w-full text-sm"
                    v-model="item.label"
                />
                <InputError class="mt-2" :message="errors[`items.${i}.label`]"/>
            </div>
            <div class="w-1/12">
                <InputLabel :for="'color-'.concat(item.uuid)" class="mb-2" value="Color"/>

                <ColorPicker v-model:pureColor="item.color"/>

                <InputError class="mt-2" :message="errors[`items.${i}.color`]"/>
            </div>
            <div class="w-1/12 cursor-pointer flex justify-end items-center" @click="$emit('removeItem', item)">
                <i class="fa fa-lg fa-trash text-red-800 hover:text-red-600"/>
            </div>
        </div>
        <div class="w-full">
            <WYSIWYG v-model="item.description" entity="planner_item"/>
        </div>
    </div>
</template>

<style scoped>

</style>
