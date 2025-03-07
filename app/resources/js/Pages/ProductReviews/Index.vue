<template>
    <Head>
        <title>{{page.title}}</title>
        <meta head-key="description" name="description" :content="page.description">
        <meta head-key="og:title" property="og:title" :content=" page.title ">
        <meta head-key="og:description" property="og:description" :content="page.description ">
        <meta head-key="og:image" property="og:image" :content="page.image"/>
        <meta head-key="og:url" property="og:url" :content=" page.link ">

        <meta head-key="twitter:title" property="twitter:title" :content="page.title">
        <meta head-key="twitter:description" property="twitter:description" :content="page.description">
        <meta head-key="twitter:image" property="twitter:image" :content="page.image ">
    </Head>
    <div class="container mx-auto mt-8 p-4 flex flex-col">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-6">
            <div class="md:flex">
                <!-- Page Details -->
                <div class=" md:mt-0 md:w-full" >
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ page.title }}</h1>
                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed article-content  mb-8"
                         v-html="page.description">
                    </div>
                </div>
            </div>
        </div>
        <div v-if="categories.length > 0"
             class="flex flex-col gap-y-4 md:grid md:grid-cols-2 md:gap-4 lg:grid lg:grid-cols-3 lg:gap-4">
            <template v-for="category in categories" :key="category.id">
                <CategoryTile :category="category" :category-slug="category.slug"/>
            </template>
        </div>
        <div class="w-full" v-else>
            <h2 class="text-4xl uppercase font-black text-white text-center">No Categories so far ...</h2>
        </div>

    </div>
</template>


<script>
import DataMixin from "@/Mixins/DataMixin.vue";
import {Head, Link, router} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import CategoryTile from "@/Pages/Products/partials/CategoryTile.vue";

export default {
    name: "Product Category",
    components: {CategoryTile,  Head, Link},
    layout: MainLayout,
    mixins: [DataMixin],
    props: {
        page: Object,
        categories: Object
    },
}
</script>

<style scoped>

</style>
