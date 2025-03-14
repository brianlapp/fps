<template>
    <MainLayout>
        <Head :title="author.name"/>
        <section class="mt-5 ">
            <div class="">
                <div class=" px-4 pt-24 pb-10 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="block font-bold text-gray-800 text-4xl md:text-5xl lg:text-6xl dark:text-neutral-200">
                            Meet
                            <span class="bg-clip-text bg-gradient-to-tl from-blue-600 text-transparent to-blue-400"
                                  data-ai-key="content">
            {{author.firstName}}!
          </span>
                        </h1>
                        <img
                            class="mx-auto max-w-sm w-1/2 sm:max-w-sm rounded-full mt-8 dark:ring-gray-500 ring-8 ring-white shadow-md"
                            :src="author.fullAvatar"
                            :alt="author.name"
                            height="400"
                            width="400"/>
                    </div>
                    <div class="mt-10 text-center mx-auto">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-200">Name: {{ author.name }}</h2>
                        </div>
                        <div class="p-4" v-if="author.bio">
                            <div
                                class="text-lg text-gray-600 dark:text-neutral-400 bg-white dark:bg-gray-800 rounded-md shadow-sm p-4 whitespace-pre-wrap text-left">
                                {{author.bio}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="mt-5">
            <h2 class="text-3xl text-center p-6 text-gray-800 dark:text-neutral-200">My Articles</h2>
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

            <div v-else class="min-h-[400px] flex flex-col justify-center items-center p-4 rounded dark:text-gray-400 text-gray-600 gap-y-8">
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
        author: Object,
        articles: Object
    },
    data() {
        return {
            feedUrl: route('prompt_author.feed', this.author.id)
        }
    },
}
</script>
