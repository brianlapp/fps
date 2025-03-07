<template>
    <!-- Product Card -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
        <img class="w-full object-cover" :src="product.thumbnail" :alt="product.title">
        <div class="p-4">
            <h3 class="text-lg dark:text-gray-200 font-bold">{{ product.title }}</h3>
            <div class="text-gray-600 dark:text-gray-300 flex gap-x-2">
                <div>Price Range:</div>
                <div v-if="product.price_range_string">
                    {{ product.price_range_string }}
                </div>
                <div v-else-if="product.price_from">
                    ${{ product.price_from }}
                    {{ product.price_to ? ' - $'.concat(product.price_to) : '' }}
                </div>
            </div>
            <div class="flex items-center mt-2" v-if="product.reviews_count > 0">
                <span class="text-yellow-400 text-lg">{{ getStars(product.rating) }}</span>
                <span class="ml-2 text-gray-600 dark:text-gray-400">({{ product.rating }}/{{ maxRate }})</span>
            </div>
            <div class="flex items-center mt-2" v-else>
                <b class="text-gray-600 dark:text-gray-400">No Reviews so far</b>
            </div>
            <p class="mt-4 text-gray-600 dark:text-gray-200 line-clamp-3">{{ product.headline }}</p>
            <div class="mt-4 flex space-x-2">
                <a :href="url"
                   class="text-center px-4 inline-block text-white rounded-lg w-full text-xl py-3 mb-2 bg-primary-500
                    font-bold mt-4 hover:bg-primary-400">
                    Read More
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import ProductReviewsMixin from "@/Mixins/ProductReviewsMixin.vue";

export default {
    name: "ProductTile",
    mixins: [ProductReviewsMixin],
    props: {
        product: Object,
        categorySlug: String
    },
    computed: {
        url() {
            if (this.categorySlug) {
                return route('product_categories.showCategoryProduct', [this.categorySlug, this.product.slug]);
            }
            return route('product_categories.showProduct', this.product.slug);
        }
    }
}
</script>
