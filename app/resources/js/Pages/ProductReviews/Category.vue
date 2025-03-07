<template>
    <Head>
        <title>{{category.seo_title}}</title>
        <meta head-key="description" name="description" :content="category.headline">
        <meta head-key="og:title" property="og:title" :content=" category.seo_title ">
        <meta head-key="og:description" property="og:description" :content="category.headline ">
        <meta head-key="og:image" property="og:image" :content="category.image"/>
        <meta head-key="og:url" property="og:url" :content=" category.link ">

        <meta head-key="twitter:title" property="twitter:title" :content="category.seo_title">
        <meta head-key="twitter:description" property="twitter:description" :content="category.headline">
        <meta head-key="twitter:image" property="twitter:image" :content="category.image ">
    </Head>
    <div class="container mx-auto mt-8 p-4 flex flex-col">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-6">
            <div class="md:flex">
                <!-- Product Image -->
                <div class="md:w-1/2" v-if="category.image">
                    <img :src="category.image"  :srcset="category.imageSrcSet" :alt=" category.title"
                         class="rounded-lg shadow-lg w-full h-auto"/>
                </div>

                <!-- Product Details -->
                <div class=" md:mt-0" :class="{'md:w-1/2 md:ml-8 mt-6': category.image, 'md:w-full': !category.image}">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ category.title }}</h1>
                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed article-content  mb-8"
                         v-html="category.description">
                    </div>
                </div>
            </div>
        </div>
        <div v-if="products.data.length > 0"
             class="flex flex-col gap-y-4 md:grid md:grid-cols-2 md:gap-4 lg:grid lg:grid-cols-3 lg:gap-4">
            <template v-for="product in products.data" :key="product.id">
                <ProductTile :product="product" :category-slug="category.slug"/>
            </template>
        </div>
        <div class="w-full" v-else>
            <h2 class="text-4xl uppercase font-black text-white text-center">No Products so far ...</h2>
        </div>
        <div class="flex flex-row justify-between mt-4" v-if="products.prev_page_url || products.next_page_url">
            <PrimaryLink class="w-fit" :class="{'opacity-0' : !products.prev_page_url}" :href="products.prev_page_url">
                Prev
            </PrimaryLink>
            <PrimaryLink class="w-fit justify-self-end" :class="{'opacity-0' : !products.next_page_url}"
                         :href="products.next_page_url">
                Next
            </PrimaryLink>
        </div>

    </div>
</template>


<script>
import DataMixin from "@/Mixins/DataMixin.vue";
import {Head, Link, router} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import ProductTile from "@/Pages/Products/partials/ProductTile.vue";

export default {
    name: "Product Category",
    components: {ProductTile, PrimaryLink, PrimaryButton, Head, Link},
    layout: MainLayout,
    mixins: [DataMixin],
    props: {
        category: Object,
        products: Object
    },
}
</script>

<style scoped>

</style>
