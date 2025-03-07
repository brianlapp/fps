<template>
    <div class="bg-white p-6 shadow-md rounded-md dark:bg-gray-800 dark:text-gray-300 mt-4">
        <p class="text-gray-500 dark:text-gray-400 text-lg mb-2" v-if="currentRequest.isFailed || error">Error!</p>
        <p class="text-gray-500 dark:text-gray-400 text-lg mb-2" v-else>Suggested Articles</p>
        <div class="mt-4 space-y-6 relative" v-if="currentRequest.isRunning && !error">
            <Spinner/>
            <div class="blur" v-for="i in currentRequest.qty" :key="'aa-'.concat(i)">
                <a href="#" class="text-blue-700 text-xl font-medium hover:underline dark:text-blue-400">Result Title {{i }}</a>
                <p class="text-gray-700 dark:text-gray-400">This is a brief description of the search result, providing a summary of the content found at the link.</p>
            </div>
        </div>
        <div class="mt-4 space-y-6" v-else-if="currentRequest.isFailed || error">
                We are sorry! Something went wrong! Please try again
        </div>
        <div class="mt-4 space-y-6"  v-else-if="currentRequest.isSuccessful">
            <div v-for="article in currentRequest.articles" :key="'aa-'.concat(article.uuid)">
                <a :href="article.link" class="text-blue-700 text-xl font-medium hover:underline dark:text-blue-400">{{article.title}}</a>
                <p class="text-gray-700 dark:text-gray-400">{{article.headline}}...</p>
            </div>
        </div>
    </div>
</template>

<script>
import {Head, Link} from '@inertiajs/vue3';
import Spinner from "@/Components/Spinner.vue";

export default {
    components: {Spinner, Head, Link},
    props: {
        articleRequest: {
            type: Object,
            default: () => null,
        },
    },
    data() {
        return {
            currentRequest: this.articleRequest,
            error: false,
            interval: setInterval(this.checkRequest, 2000)
        }
    },
    methods: {
        checkRequest() {
            axios.get(route('requestStatus', this.articleRequest.id)).then(({data}) => {
                this.currentRequest = data.request;
                if (this.currentRequest.isSuccessful || this.currentRequest.isFailed) {
                    this.stopChecking();
                }
                this.error = this.currentRequest.isFailed;
            }).catch(() => {
                this.error = true;
                this.stopChecking();
            });
        },
        stopChecking() {
            clearInterval(this.interval);
        }
    }
}
</script>
