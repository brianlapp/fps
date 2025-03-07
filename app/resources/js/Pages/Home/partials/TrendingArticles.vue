<script>
import StringMixin from "@/Mixins/StringMixin.vue";

export default {
    name: "TrendingArticles",
    mixins: [StringMixin],
    props: {
        trendingArticles: {
            type: Array
        }
    },
}
</script>

<template>
    <div class="py-8">
        <div class="container mx-auto px-4">
            <div class="md:w-5/6 md:mx-auto md:max-w-2xl text-slate-900 dark:text-white">
                <h2 class="text-3xl text-center font-bold sm:text-4xl">Trending
                    articles</h2>
                <p class="text-center text-lg mt-2">
                    Stay updated with the latest articles.
                </p>
            </div>
        </div>
        <div
            class="grid grid-cols-1 gap-3 p-4 mt-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:max-w-7xl xl:mx-auto">
            <div v-for="(article, i) in trendingArticles" :key="article.id">
                <div class="relative p-4 rounded"
                     :class="{'dark:bg-gray-800 bg-blue-50' : !isOdd(i), 'dark:bg-gray-700 bg-green-50': isOdd(i)}">
                    <div class="">
                        <span class="bg-primary-500 text-white tracking-wide text-sm uppercase px-2 py-1 rounded-md">Trending</span>
                        <a :href="article.link"
                           class="flex flex-col left-3 bottom-3 right-3 bg-white dark:bg-gray-900 dark:text-white text-black p-4 mt-3 rounded dark:hover:bg-gray-700 hover:bg-blue-100">
                            <img class="w-full mb-4" :src="article.thumbnail" :alt="article.title"/>
                            <h3 class="text-xl font-semibold  mb-4 mt-2 2xl:text-2xl line-clamp-3 md:min-h-[6rem]">
                                {{ article.title }}
                            </h3>
                            <span class="flex items-center gap-x-1.5">
                                    Read more
                                    <i class="fa-solid fa-chevron-right fa-xs font-bold"></i>
                                </span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
