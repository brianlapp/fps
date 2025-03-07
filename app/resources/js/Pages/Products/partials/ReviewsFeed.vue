<template>
    <!-- Reviews List -->
    <div class="relative min-h-32 flex flex-col justify-center">
        <Spinner v-if="isLoading"/>
        <div class="flex flex-col gap-y-6" :class="{'opacity-20': isLoading}" v-if="reviews?.length > 0">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6" v-for="review in reviews" :key="review.id">
                <div
                    class="flex flex-col md:flex-row items:start md:items-center md:justify-between gap-y-4 border-b border-gray-300 dark:border-gray-600 pb-2">
                    <div class="flex flex-col md:flex-row md:items-center md:gap-x-2 gap-y-2 items-start">
                        <div class="flex justify-between w-full md:w-fit">
                            <span class="text-yellow-400">{{ getStars(review.rate) }}</span>
                            <div
                                class="ml-2 md:hidden text-gray-600 dark:text-gray-400 text-right w-full md:w-fit text-xs md:text-sm">
                                by <b>{{ review.author?.name }}</b> <br>on {{ formatDate(review.created_at) }}
                            </div>
                        </div>

                        <div class="text-gray-900 font-bold dark:text-gray-100 m-0">"{{ review.title }}"</div>
                    </div>
                    <span
                        class="hidden md:block text-gray-600 dark:text-gray-400 text-right w-full md:w-fit text-xs md:text-sm">by <b>{{
                            review.author?.name
                        }}</b> on {{ formatDate(review.created_at) }}</span>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mt-2">{{ review.content }}</p>
                <div class="w-fit mt-4" v-if="review.isPending">
                    <div :data-tooltip-target="'tooltip-moderating-' + review.id" data-tooltip-placement="right">
                        <div class="text-yellow-400 dark:text-yellow-600 cursor-help">Moderating.... <i class="fa fa-question-circle"/></div>

                    </div>
                    <div :id="'tooltip-moderating-' + review.id" role="tooltip"
                         class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Your Review will be published after check with AI and our Moderation team
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="gap-y-6 w-full" v-else-if="!isLoading">
            <div class="text-gray-600 dark:text-gray-400 text-2xl w-full text-center">No Reviews so far...</div>
        </div>
        <div class="w-full text-center mt-2 p-4" v-if="hasNextPage">
            <button @click="loadNextPage" :disabled="isLoading"
                    class="text-center inline-block text-white rounded-lg text-2xl py-3 bg-primary-500 font-bold mt-4 hover:bg-primary-400 w-fit px-12">
                {{ isLoading ? 'Loading...' : 'Load More' }}
            </button>
        </div>
    </div>
</template>

<script>
import {concat} from "lodash";
import Spinner from "@/Components/Spinner.vue";
import ProductReviewsMixin from "@/Mixins/ProductReviewsMixin.vue";
import DataMixin from "@/Mixins/DataMixin.vue";
import {initTooltips} from 'flowbite';

export default {
    name: "ReviewsFeed",
    components: {Spinner},
    mixins: [ProductReviewsMixin, DataMixin],
    props: {
        productId: String
    },
    data() {
        return {
            reviews: [],
            isLoading: false,
            page: 1,
            hasNextPage: false,
            perPage: 50
        }
    },
    mounted() {
        this.loadFeed();
    },
    methods: {
        loadFeed() {
            this.isLoading = true;
            axios.get(route('product_categories.reviews', this.productId), {
                params: {
                    page: this.page,
                    perPage: this.perPage
                }
            }).then(({data}) => {
                this.reviews = concat(this.reviews, data.data);
                this.page = data.current_page;
                this.hasNextPage = !!data.next_page_url;
                this.$nextTick(function () {
                    initTooltips();
                })

            }).finally(() => {
                this.isLoading = false;
            });
        },
        loadNextPage() {
            this.page += 1;
            this.loadFeed();
        }
    }
}
</script>
