<template>
    <Head>
        <title>{{page.seo_title || page.title}}</title>
        <meta head-key="description" name="description" :content="page.seo_headline || page.headline">
        <meta head-key="og:title" property="og:title" :content="page.seo_title || page.title">
        <meta head-key="og:description" property="og:description" :content="page.seo_headline || page.headline">
        <meta head-key="og:image" property="og:image" :content="page.image"/>
        <meta head-key="og:url" property="og:url" :content=" page.link ">

        <meta head-key="twitter:title" property="twitter:title" :content="page.seo_title || page.title">
        <meta head-key="twitter:description" property="twitter:description" :content="page.seo_headline || page.headline">
        <meta head-key="twitter:image" property="twitter:image" :content="page.image">
    </Head>
    <div class="container mx-auto mt-8 p-4 flex flex-col md:flex-row md: gap-x-4">
        <div class="w-full">
            <article class="bg-white p-8 shadow-md rounded-md dark:bg-gray-800">
                <!-- Page Title -->
                <h1 class="text-2xl md:text-4xl font-extrabold mb-6 dark:text-gray-200">{{ page.title }}</h1>
                
                <!-- Page Image -->
                <img v-if="page.image" class="w-full h-64 object-cover mb-8 rounded-lg" :src="page.image" :alt="page.title"/>

                <!-- Page Content -->
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed mb-8 article-content"
                     v-html="page.content">
                </div>

                <!-- Team Section (placeholder) -->
                <div class="mt-12">
                    <h2 class="text-2xl font-bold mb-6 dark:text-gray-200">Our Team</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="i in 3" :key="i" class="flex flex-col items-center">
                            <div class="w-32 h-32 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden mb-4 flex items-center justify-center">
                                <i class="fas fa-user text-4xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <h3 class="text-lg font-semibold dark:text-gray-200">Team Member {{ i }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm text-center mt-2">Coming soon - this section will contain information about our team members.</p>
                        </div>
                    </div>
                </div>

                <!-- Mission Section -->
                <div class="mt-12 p-6 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <h2 class="text-2xl font-bold mb-4 dark:text-gray-200">Our Mission</h2>
                    <p class="text-gray-700 dark:text-gray-300">At Free Parent Search, our mission is to provide parents with free, accessible resources and tools to support them through every stage of their parenting journey. We believe that quality parenting information should be available to everyone, regardless of their circumstances.</p>
                </div>

                <!-- Tags List -->
                <div class="flex flex-wrap gap-2 mt-8" v-if="page.tags && page.tags.length > 0">
                    <span
                        class="inline-block bg-blue-200 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-700 dark:text-blue-100"
                        v-for="(tag, i) in page.tags" :key="'tag-'.concat(i)">{{ tag }}</span>
                </div>
            </article>
        </div>
    </div>
</template>

<script>
import {Head, Link} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    name: "About",
    components: {Head, Link},
    layout: MainLayout,
    props: {
        page: Object,
    },
}
</script>

<style scoped>
.article-content :deep(h2) {
    font-size: 1.5rem;
    font-weight: 700;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    color: #1a202c;
}

.article-content :deep(p) {
    margin-bottom: 1rem;
}

.dark .article-content :deep(h2) {
    color: #e2e8f0;
}
</style>
