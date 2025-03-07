<template>
    <Head>
        <title>{{offerCategory.title}}</title>
        <meta head-key="description" name="description" :content="offerCategory.headline">
        <meta head-key="og:title" property="og:title" :content=" offerCategory.title ">
        <meta head-key="og:description" property="og:description" :content="offerCategory.headline ">
        <meta head-key="og:image" property="og:image" :content="offerCategory.image"/>
        <meta head-key="og:url" property="og:url" :content=" offerCategory.link ">

        <meta head-key="twitter:title" property="twitter:title" :content="offerCategory.title">
        <meta head-key="twitter:description" property="twitter:description" :content="offerCategory.headline">
        <meta head-key="twitter:image" property="twitter:image" :content="offerCategory.image ">
    </Head>
    <div class="container mx-auto mt-8 p-4 flex flex-col bg-white dark:bg-gray-800">
        <div class="border-b dark:border-gray-600 mb-8">
            <h1 class="text-4xl font-extrabold mb-6 dark:text-gray-200 text-center w-full">{{
                    offerCategory.title
                }}</h1>
            <img :src="offerCategory.image" v-if="offerCategory.image" :srcset="offerCategory.imageSrcSet"
                 class="rounded w-auto mx-auto mb-3"/>
            <div class="text-gray-700 dark:text-gray-300 leading-relaxed article-content  mb-8"
                 v-html="offerCategory.description">
            </div>
            <div class="my-4 flex justify-center align-middle">
                <div class="w-1/4 flex flex-col gap-y-1  dark:text-gray-200 ">
                    <label for="child_age" class="text-center">Filter by Country</label>
                    <select v-model="form.country" id="child_age"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="all" key="all">All</option>
                        <option v-for="(option, i) in countries" :value="option.code"
                                :key="'country_'.concat(i)">{{ option.name }}
                        </option>
                    </select>
                </div>
            </div>

        </div>
        <div v-if="offers.data.length > 0"
             class="flex flex-col gap-y-4 px-8 md:px-0 md:grid md:grid-cols-2 md:gap-4 lg:grid lg:grid-cols-3 lg:gap-4">
            <template v-for="offer in offers.data"
                      :key="offer.id">
                <div class="bg-white dark:bg-gray-900 shadow rounded-lg overflow-hidden my-8 hidden md:block">
                    <div class="bg-blue-400 p-4">
                        <h2 class="text-2xl uppercase font-black text-white text-center line-clamp-2 md:min-h-[65px]">
                            {{ offer.title }}</h2>
                    </div>
                    <div class="p-2 flex flex-col justify-between">
                        <img :src="offer.image" :alt="offer.title" class="w-full h-auto mb-4 rounded-lg"
                             v-if="offer.image">
                        <p class="text-gray-700 dark:text-gray-300 md:min-h-[75px] line-clamp-3">
                            {{ offer.description }}
                        </p>
                        <a :href="offer.link" target="_blank"
                           class="text-center px-4 inline-block text-white rounded-lg w-full text-xl py-3 mb-2 bg-primary-500 font-bold mt-4 hover:bg-primary-400">
                            Continue
                        </a>
                    </div>
                </div>
                <div
                    class="bg-white shadow rounded-lg overflow-hidden flex flex-col sm:flex-row visible md:hidden dark:bg-gray-900 ">
                    <img :src="offer.image" :alt="offer.title" class="w-full h-auto sm:w-1/3 sm:h-auto mb-4 sm:mb-0"
                         v-if="offer.image">
                    <div class="p-4 flex flex-col justify-center">
                        <h2 class="text-xl font-black text-blue-400 mb-2">{{ offer.title }}</h2>
                        <p class="text-gray-700 mb-4 dark:text-gray-300">{{ offer.description }}</p>
                        <a :href="offer.link" target="_blank"
                           class="text-center px-4 inline-block text-white rounded-lg w-full text-xl py-3 mb-2 bg-primary-500 font-bold mt-4 hover:bg-primary-400">
                            Continue
                        </a>
                    </div>
                </div>
            </template>
        </div>
        <div class="w-full" v-else>
            <h2 class="text-4xl uppercase font-black text-white text-center">No Offers So far!</h2>
        </div>
        <div class="flex flex-row justify-between mt-4" v-if="offers.prev_page_url || offers.next_page_url">
            <PrimaryLink class="w-fit" :class="{'opacity-0' : !offers.prev_page_url}"
                         :href="enrichUrl(offers.prev_page_url)">
                Prev
            </PrimaryLink>
            <PrimaryLink class="w-fit justify-self-end" :class="{'opacity-0' : !offers.next_page_url}"
                         :href="enrichUrl(offers.next_page_url)">
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
import {throttle} from "lodash/function.js";
import {pickBy} from "lodash";

export default {
    name: "Offers",
    components: {PrimaryLink, PrimaryButton, Head, Link},
    layout: MainLayout,
    mixins: [DataMixin],
    props: {
        offerCategory: Object,
        offers: Object,
        countries: Array,
        filters: Object,
    },
    data() {
        return {
            form: {
                country: this.filters.country || null,
            },
        }
    },
    watch: {
        form: {
            handler: throttle(function () {
                const query = pickBy(this.form),
                    request = Object.keys(query).length ? query : {remember: 'forget'};
                request.slug = this.offerCategory.slug;
                this.$inertia.replace(this.route('offer_categories.show', request));
            }, 150),

            deep: true
        },
    },
    methods: {
        enrichUrl(url) {
            if (!url) {
                return null;
            }
            const obj = new URL(url),
                query = pickBy(this.form),
                request = Object.keys(query).length ? query : {};
            Object.keys(request).forEach(key => {
                obj.searchParams.set(key, request[key])
            });

            return obj.toString();

        }
    }
}
</script>

<style scoped>

</style>
