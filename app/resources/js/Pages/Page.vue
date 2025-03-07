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
                <img v-if="page.image" class="w-full mb-4" :src="page.image"/>

                <!-- Page Content -->
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed mb-8 article-content"
                     v-html="page.content">
                </div>

                <!-- Tags List -->
                <div class="flex flex-wrap gap-2 mb-6" v-if="article?.tags.length > 0">
                    <span
                        class="inline-block bg-blue-200 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-700 dark:text-blue-100"
                        v-for="(tag, i) in page.tags" :key="'tag-'.concat(i)">{{ tag }}</span>
                </div>
            </article>
        </div>
    </div>
</template>


<script>
import DataMixin from "@/Mixins/DataMixin.vue";
import {Head, Link} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    name: "Page",
    components: {Head, Link},
    layout: MainLayout,
    mixins: [DataMixin],
    props: {
        page: Object,
    },
}
</script>

<style scoped>

</style>
