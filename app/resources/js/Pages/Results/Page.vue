<template>
        <Head title="Search Results"/>
        <main class="container mx-auto mt-2 p-4">
            <AuthorBanner class="max-w-2xl mx-auto"/>
            <div
                class="container mx-auto p-4 flex flex-col md:flex-row justify-between items-center bg-white shadow-md dark:bg-gray-800 rounded-md">
               <SearchInput :query="articleRequest.query"/>
            </div>

            <div class="w-full flex flex-col md:flex-row md:gap-x-8">
                <div class="w-full md:w-8/12 flex flex-col">
                    <div class="bg-white p-6 shadow-md rounded-md dark:bg-gray-800 dark:text-gray-300 mt-4">
                        <div class="mt-4 space-y-6">
                            <LiveArticle :url="liveUrl" :payload="livePayload" :request-id="articleRequest.id"
                                         :article="liveArticle" @done="liveDone = true;"/>
                        </div>
                    </div>
                    <div class="bg-white p-6 shadow-md rounded-md dark:bg-gray-800 dark:text-gray-300 mt-4"
                         v-if="localArticles.length > 0 && liveDone">
                        <p class="text-gray-500 dark:text-gray-400 text-lg mb-2">Suggested Articles</p>
                        <div class="mt-4 space-y-6">
                            <div v-for="article in localArticles" :key="'aal-'.concat(article.uuid)">
                                <a :href="article.link"
                                   class="text-blue-700 text-xl font-medium hover:underline dark:text-blue-400">{{
                                        article.title
                                    }}</a>
                                <p class="text-gray-700 dark:text-gray-400">{{ article.headline }}...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-4/12 flex flex-col">
                    <div class="bg-white p-6 shadow-md rounded-md dark:bg-gray-800 dark:text-gray-300 mt-4">
                        <p class="text-gray-500 dark:text-gray-400 text-lg mb-2">Google Results</p>
                        <div class="mt-4 space-y-6">
                            <div v-for="(article, i) in results" :key="'aac-'.concat(i)">
                                <a :href="article.link" target="_blank"
                                   class="text-blue-700 text-xl font-medium hover:underline dark:text-blue-400">{{
                                        article.title
                                    }}</a>
                                <p class="text-green-700 dark:text-green-500">{{ article.displayLink }}</p>
                                <p class="text-gray-700 dark:text-gray-400">{{ article.snippet }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
</template>

<script>
import {Head, Link} from '@inertiajs/vue3';
import GeneratedArticles from "@/Pages/Results/partials/_GeneratedArticles.vue";
import Logo from "@/Components/Logo.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import LiveArticle from "@/Components/LiveArticle.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthorBanner from "@/Pages/Home/partials/AuthorBanner.vue";
import Search from "@/Components/Admin/Table/Search.vue";
import SearchInput from "@/Pages/Results/partials/SearchInput.vue";

export default {
    components: {SearchInput, Search, AuthorBanner, PrimaryButton, LiveArticle, Logo, GeneratedArticles, Head, Link},
    layout: MainLayout,
    props: {
        results: {
            type: Array,
            default: () => [],
        },
        localArticles: {
            type: Array,
            default: () => [],
        },
        articleRequest: {
            type: Object,
            default: () => null,
        },
        livePayload: {
            type: Object,
            default: () => null,
        },
        liveUrl: {
            type: String,
            default: null
        },
        liveArticle: {
            type: Object,
            default: () => null,
        },
    },
    data() {
        return {
            liveDone: false
        }
    }
}
</script>
