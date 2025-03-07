<template>
    <Head>
        <title>{{seo.title}}</title>
        <meta head-key="description" name="description" :content="seo.headline">
        <meta head-key="og:title" property="og:title" :content="seo.title">
        <meta head-key="og:description" property="og:description" :content="seo.headline">
        <meta head-key="og:image" v-if="seo.image" property="og:image" :content="seo.image"/>
        <meta head-key="og:url" property="og:url" :content="route('activity.index')">

        <meta head-key="twitter:title" property="twitter:title" :content="seo.title">
        <meta head-key="twitter:description" property="twitter:description" :content="seo.headline">
        <meta head-key="twitter:image" v-if="seo.image" property="twitter:image" :content="seo.image">
    </Head>
    <div class="container mx-auto mt-8 p-4 flex flex-col md:flex-row md: gap-x-4 dark:text-gray-200">
        <div class="w-full">
            <div class="flex items-center overflow-hidden" v-if="!my">
                <div class="container max-w-screen-lg mx-auto px-6 flex items-center justify-center py-16">
                    <!-- Text Section (2/3 width) -->
                    <div class="w-full sm:w-2/3 lg:w-2/3 flex flex-col">
                        <span class="w-20 h-2 bg-gray-800 dark:bg-white mb-6"></span>
                        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            <span class="block xl:inline">Find Amazing</span>
                            <span class="bg-gradient-to-r from-blue-400 to-red-500 bg-clip-text text-transparent">
                        Activities for You and Your Child
                    </span>
                            <div class="mt-2 text-3xl text-gray-900 dark:text-gray-300">
                                <span class="relative">Tailored just for you</span>
                            </div>
                        </h1>
                        <p class="mx-auto mt-3 max-w-xl text-lg text-gray-500 dark:text-slate-400 sm:mt-5 md:mt-5">
                            Enter a few details below and let our generator do the magic! Perfect for those days when you need fresh ideas.
                        </p>
                    </div>

                    <!-- Image Section (1/3 width) -->
                    <div class="hidden sm:block w-full sm:w-1/3 lg:w-1/3">
                        <img src="/images/activities.png" alt="Parent-Child Activities" class="max-w-xs md:max-w-sm m-auto">
                    </div>
                </div>
            </div>
            <div class="mb-6 w-full flex items-center justify-between" v-else>
                <h1 class="text-2xl md:text-4xl text-center font-extrabold dark:text-gray-200">
                    {{my ? 'My ' : ''}}Parent-Child Activities</h1>
                <a :href="route('activity.new')" class="text-center inline-block text-white rounded-lg py-3 bg-primary-500 font-bold hover:bg-primary-400 w-fit px-4">
                    {{ my ? 'Generate another one!' : 'Generate your Own!'}}
                </a>
            </div>
            <Form v-if="!my"/>
            <div class="my-4 flex justify-between">
                <div class="w-1/4 flex flex-col gap-y-1">
                    <label for="child_age">Child's Age</label>
                    <select v-model="filters.child_age" id="child_age"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option :value="null" key="any">Any</option>
                        <option v-for="(option, i) in options.child_age" :value="option.label"
                                :key="'child_age_'.concat(i)">{{ option.label }}
                        </option>
                    </select>
                </div>
                <div class="w-1/4 flex flex-col gap-y-1">
                    <label for="location">Location</label>
                    <select v-model="filters.location" id="location"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option :value="null" key="any">Any</option>
                        <option v-for="(option, i) in options.location" :value="option.label"
                                :key="'location_'.concat(i)">{{ option.label }}
                        </option>
                    </select>
                </div>
                <div class="w-1/4 flex flex-col gap-y-1">
                    <label for="location">Available Time</label>
                    <select v-model="filters.available_time" id="location"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option :value="null" key="any">Any</option>
                        <option v-for="(option, i) in options.available_time" :value="option.label"
                                :key="'available_time_'.concat(i)">{{ option.label }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="flex flex-col gap-y-4" v-if="activities?.data?.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 py-4 px-2 sm:px-0 rounded">
                    <FeedItem v-for="item in activities?.data" :key="item.uuid" :item="item"/>
                </div>

                <div class="w-full text-center mt-4 p-4" v-if="hasNextPage">
                    <button @click="loadMore" :disabled="isLoading"
                            class="text-center inline-block text-white rounded-lg text-2xl py-3 bg-primary-500 font-bold mt-4 hover:bg-primary-400 w-fit px-12">
                        {{ isLoading ? 'Loading...' : 'Load More' }}
                    </button>
                </div>
            </div>
            <div class="w-full text-center mt-4 p-4" v-if="my && !hasItems">
                <a :href="route('activity.new')"
                   class="text-center inline-block text-white rounded-lg text-2xl py-3 bg-primary-500 font-bold mt-4 hover:bg-primary-400 w-fit px-12">
                    Generate your First Activity Plan!
                </a>
            </div>
        </div>
    </div>
</template>


<script>
import DataMixin from "@/Mixins/DataMixin.vue";
import {Head, Link} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import FeedItem from "@/Pages/Activity/partials/FeedItem.vue";
import Form from "@/Pages/Activity/New.vue";
import ActivityMixin from "@/Mixins/ActivityMixin.vue";

export default {
    components: {FeedItem, Head, Link, Form},
    layout: MainLayout,
    mixins: [DataMixin, ActivityMixin],
    props: {
        seo: Object,
        hasItems: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {}
    }
}
</script>

<style scoped>

</style>
