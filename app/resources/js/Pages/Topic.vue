<template>
    <MainLayout>
        <Head>
            <title>{{topic.title}}</title>
            <meta head-key="description" name="description" :content="topic.headline">
            <meta head-key="og:title" property="og:title" :content=" topic.title ">
            <meta head-key="og:description" property="og:description" :content="topic.headline ">
            <meta head-key="og:image" property="og:image" :content="topic.image"/>
            <meta head-key="og:url" property="og:url" :content=" topic.link ">

            <meta head-key="twitter:title" property="twitter:title" :content="topic.title">
            <meta head-key="twitter:description" property="twitter:description" :content="topic.headline">
            <meta head-key="twitter:image" property="twitter:image" :content="topic.image ">
        </Head>
        <section class="mt-5 ">
            <div class="border-b dark:border-gray-600 mb-8">
                <h1 class="text-6xl font-extrabold mb-6 dark:text-gray-200 text-center w-full my-8">
                    {{ topic.title }}
                </h1>
                <img :src="topic.image" v-if="topic.image" :srcset="topic.imageSrcSet"
                     class="rounded w-auto mx-auto mb-3" :alt="topic.title" width="1280" height="731"/>
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed article-content  mb-8"
                     v-html="topic.description">
                </div>
            </div>
        </section>
        <section class="mt-5">
            <h2 class="text-3xl text-center p-6 text-gray-800 dark:text-neutral-200">Topic Articles</h2>
            <div v-if="articles?.data?.length > 0">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 py-4 px-2 sm:px-0 rounded"
                    v-if="view === 'grid'">
                    <template v-for="article in articles.data"
                              :key="article.id">
                        <GridItem :article="article"/>
                    </template>
                </div>
                <div class="flex flex-col gap-y-4 py-4 px-2 sm:px-0 rounded" v-else-if="view === 'list'">
                    <template v-for="article in articles.data"
                              :key="article.id">
                        <ListItem :article="article"/>
                    </template>
                </div>
                <div class="w-full text-center mt-4 p-4">
                    <button @click="loadMore" v-if="hasNextPage" :disabled="isLoading"
                            class="text-center inline-block text-white rounded-lg text-2xl py-3 bg-primary-500 font-bold mt-4 hover:bg-primary-400 w-fit px-12">
                        {{ isLoading ? 'Loading...' : 'Load More' }}
                    </button>
                </div>
            </div>

            <div v-else
                 class="min-h-[400px] flex flex-col justify-center items-center p-4 rounded dark:text-gray-400 text-gray-600 gap-y-8">
                <i class="fa-solid fa-2xl fa-face-frown"></i>
                <h3 class="text-2xl">Nothing Found</h3>
            </div>
        </section>
    </MainLayout>
</template>

<script>
import {Head} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import GridItem from "@/Pages/Feed/partials/GridItem.vue";
import ListItem from "@/Pages/Feed/partials/ListItem.vue";
import FeedMixin from "@/Mixins/FeedMixin.vue";

export default {
    name: 'Author',
    components: {ListItem, GridItem, MainLayout, Head},
    mixins: [FeedMixin],
    props: {
        topic: Object,
        articles: Object
    },
    data() {
        return {
            feedUrl: route('topics.feed', this.topic.slug)
        }
    },
}
</script>
