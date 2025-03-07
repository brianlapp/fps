<script>
import StringMixin from "@/Mixins/StringMixin.vue";

export default {
    name: "FeaturedArticles",
    mixins: [StringMixin],
    props: {
        trendingArticles: {
            type: Array
        }
    },
}
</script>

<template>
    <section class="w-full px-4 lg:px-8 py-12">
        <div class="max-w-7xl mx-auto">

            <!-- Section Title -->
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-blue-600 mb-8 text-center">Featured
                Articles</h2>

            <!-- Articles Grid -->
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Article 1 -->
                <div v-for="(article, i) in trendingArticles" :key="article.id"
                     class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-md transition-shadow">
                    <img :src="article.thumbnail" :alt="article.title" class="w-full h-auto rounded-lg mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white"> {{ article.title }}</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300 line-clamp-3">
                        {{ article.headline }}
                    </p>
                    <a :href="article.link" class="block mt-4 text-green-500 hover:text-green-700 transition-colors">
                        Read More
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>
