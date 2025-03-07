<template>
    <Head>
        <title>{{product.seo_title}}</title>
        <meta head-key="description" name="description" :content="product.headline">
        <meta head-key="og:title" property="og:title" :content=" product.seo_title ">
        <meta head-key="og:description" property="og:description" :content="product.headline ">
        <meta head-key="og:image" property="og:image" :content="product.image"/>
        <meta head-key="og:url" property="og:url" :content=" product.link ">

        <meta head-key="twitter:title" property="twitter:title" :content="product.seo_title">
        <meta head-key="twitter:description" property="twitter:description" :content="product.headline">
        <meta head-key="twitter:image" property="twitter:image" :content="product.image ">
    </Head>
    <div class="container mx-auto mt-8 p-4 flex flex-col">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <div class="md:flex">
                <!-- Product Image -->
                <div class="md:w-1/2">
                    <img :src="product.image" v-if="product.image" :srcset="product.imageSrcSet" :alt=" product.title"
                         class="rounded-lg shadow-lg w-full h-auto"/>
                </div>

                <!-- Product Details -->
                <div class="md:w-1/2 md:ml-8 mt-6 md:mt-0">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ product.title }}</h1>
                    <div class="flex items-center mb-4" v-if="product.reviews_count > 0">
                        <span class="text-yellow-400 text-2xl">{{ getStars(product.rating) }}</span>
                        <span class="ml-2 text-gray-600 dark:text-gray-400 text-xl">({{ product.rating }}/{{
                                maxRate
                            }})</span>
                        <span class="ml-4 text-gray-600 dark:text-gray-400">{{ product.reviews_count }} Reviews</span>
                    </div>
                    <div class=" mb-4" v-else>
                        <b class="text-gray-600 dark:text-gray-400">No Reviews so far</b>
                    </div>

                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed article-content  mb-8"
                         v-html="product.description">
                    </div>

                    <!-- Price Range -->
                    <p class="text-2xl flex font-bold text-gray-900 dark:text-gray-100 mb-4 gap-x-2">
                        <div>Price Range:</div>
                        <div v-if="product.price_range_string">
                            {{ product.price_range_string }}
                        </div>
                        <div v-else-if="product.price_from">
                            ${{ product.price_from }}
                            {{ product.price_to ? ' - $'.concat(product.price_to) : '' }}
                        </div>

                    </p>

                    <!-- Buy & Deal Buttons -->
                    <div class="flex gap-x-4 gap-y-4 flex-wrap" v-if="product.affiliate_links?.length > 0">
                        <a :href="link.url" target="_blank" v-for="link in product.affiliate_links"
                           :class="link.isPrimary ? ['bg-green-500 hover:bg-green-600 text-white'] : ['text-blue-500 border border-blue-500 hover:bg-blue-500 hover:text-white']"
                           class="px-6 py-3 rounded-lg transition duration-300">
                            {{ link.title }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deals Section -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Deals & Discounts</h2>
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 ">
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed article-content" v-html="product.deals"
                     v-if="product.deals"/>
                <p class="text-gray-600 dark:text-gray-400" v-else>No current deals available.</p>
            </div>
        </div>

        <!-- User Reviews Section -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100  mb-6">User Reviews</h2>

            <!-- Review Form -->
            <ReviewForm :product-id="product.slug" @reviewSubmitted="refreshFeed"/>

            <ReviewsFeed :product-id="product.slug" :key="feedKey"/>
        </div>
        <div v-if="relatedProducts?.length > 0" class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Related Products</h2>
            <div
                class="flex flex-col gap-y-4 md:grid md:grid-cols-2 md:gap-4 lg:grid lg:grid-cols-3 lg:gap-4">
                <template v-for="product in relatedProducts" :key="product.id">
                    <ProductTile :product="product" :category-slug="category.slug"/>
                </template>
            </div>
        </div>


    </div>
</template>


<script>
import DataMixin from "@/Mixins/DataMixin.vue";
import {Head, Link, router} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import ReviewForm from "@/Pages/Products/partials/ReviewForm.vue";
import ReviewsFeed from "@/Pages/Products/partials/ReviewsFeed.vue";
import {v4} from "uuid";
import ProductReviewsMixin from "@/Mixins/ProductReviewsMixin.vue";
import ProductTile from "@/Pages/Products/partials/ProductTile.vue";

export default {
    name: "Product",
    components: {ProductTile, ReviewsFeed, ReviewForm, PrimaryLink, PrimaryButton, Head, Link},
    layout: MainLayout,
    mixins: [DataMixin, ProductReviewsMixin],
    props: {
        product: Object,
        category: Object,
        relatedProducts: Array
    },
    data() {
        return {
            feedKey: v4()
        }
    },
    methods: {
        refreshFeed() {
            this.feedKey = v4();
        }
    }
}
</script>

<style scoped>

</style>
