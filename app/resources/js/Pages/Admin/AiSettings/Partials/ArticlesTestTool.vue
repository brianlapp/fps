<template>
    <div>
        <div class="flex flex-row gap-x-2">
            <TextInput v-model="form.search_query" class="min-w-[50%]" placeholder="Enter Search Query"/>

            <PrimaryButton class="w-fit" :class="{ 'opacity-25': isLoading || !form.search_query }" :disabled="isLoading || !form.search_query" @click="test">TEST
            </PrimaryButton>
        </div>

        <div class="p-6 shadow-md rounded-md  mt-4 mb-6" v-if="response">
            <div class="bg-white dark:bg-gray-600 dark:text-gray-300 article-content p-4 mb-8" v-for="article in response">
                <h1 class="text-2xl md:text-4xl font-extrabold mb-6 dark:text-gray-200">{{ article.title }}</h1>
                <div class="article-content" v-html="article.content"></div>
            </div>
        </div>
        <div class="relative mt-12" v-else-if="isLoading">
            <Spinner/>
        </div>
    </div>

</template>
<script>

import LiveArticle from "@/Components/LiveArticle.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import Spinner from "@/Components/Spinner.vue";

export default {
    name: "ArticlesTestTool",
    components: {Spinner, PrimaryButton, TextInput, LiveArticle},
    props: {
        settings: Object
    },
    data() {
        return {
            response: null,
            form: {
                search_query: null,
            },
            isLoading: false
        }
    },
    methods: {
        test() {
            this.isLoading = true;
            this.reset();
            axios.post(route('admin.ai-settings.test-articles'), {
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
