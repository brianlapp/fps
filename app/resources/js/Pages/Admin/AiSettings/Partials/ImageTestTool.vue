<template>
    <div class="relative">
        <div class="p-6 shadow-md rounded-md  mt-4 mb-6" v-if="response">
            <img :src="response.url"/>
            <p class="mt-1 p-0 text-xs italic text-gray-600 dark:text-gray-400 mb-4" v-if="response.caption">{{response.caption}}</p>
        </div>
        <Spinner v-else-if="isLoading"/>
        <div class="flex flex-col gap-y-2">
            <TextInput v-model="form.title" class="min-w-[50%]" placeholder="Enter Article Title"/>
            <TextareaInput rows="10" v-model="form.content" class="min-w-[50%]" placeholder="Enter Article Content"/>

            <PrimaryButton class="w-fit" :class="{ 'opacity-25': isLoading || !form.title || !form.content }" :disabled="isLoading || !form.title || !form.content" @click="test">TEST
            </PrimaryButton>
        </div>


    </div>

</template>
<script>

import LiveArticle from "@/Components/LiveArticle.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import TextareaInput from "@/Components/TextareaInput.vue";
import Spinner from "@/Components/Spinner.vue";

export default {
    name: "ImageTestTool",
    components: {Spinner, TextareaInput, PrimaryButton, TextInput, LiveArticle},
    props: {
        settings: Object
    },
    data() {
        return {
            response: null,
            form: {
                title: null,
                content: null
            },
            isLoading: false
        }
    },
    methods: {
        test() {
            this.isLoading = true;
            this.reset();
            axios.post(route('admin.ai-settings.test-image'), {
                ...this.form,
                ...this.settings
            }).then(({data}) => {
                this.response = data;
            }).finally(() => {
                this.isLoading = false;
            });
        },
        reset() {
            this.response = null;
        }
    }
}
</script>
