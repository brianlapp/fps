<template>
    <div>
        <div class="flex flex-row gap-x-2">
            <TextInput v-model="form.search_query" class="min-w-[50%]" placeholder="Enter Search Query"/>

            <PrimaryButton class="w-fit" :class="{ 'opacity-25': isLoading || !form.search_query }" :disabled="isLoading || !form.search_query" @click="test">TEST
            </PrimaryButton>
        </div>

        <div class="bg-white p-6 shadow-md rounded-md dark:bg-gray-600 dark:text-gray-300 mt-4 mb-6" v-if="liveUrl">
            <div class="mt-4 space-y-6">
                <LiveArticle :url="liveUrl" :payload="livePayload" :request-id="null"
                             :article="null"/>
            </div>
        </div>
    </div>

</template>
<script>

import LiveArticle from "@/Components/LiveArticle.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";

export default {
    name: "LiveArticleTestTool",
    components: {PrimaryButton, TextInput, LiveArticle},
    props: {
        settings: Object
    },
    data() {
        return {
            livePayload: null,
            liveUrl: null,
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
            axios.post(route('admin.ai-settings.test-live-article'), {
                ...this.form,
                ...this.settings
            }).then(({data}) => {
                this.livePayload = data.livePayload;
                this.liveUrl = data.liveUrl;
            }).finally(() => {
                this.isLoading = false;
            });
        },
        reset() {
            this.liveUrl = null;
            this.livePayload = null;
        }
    }
}
</script>
