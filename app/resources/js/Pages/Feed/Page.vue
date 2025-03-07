<template>
    <MainLayout>
        <Head title="Feed"/>
        <section class="mt-5">
            <div class="container md:px-6 mx-auto text-center py-16 md:py-20 xl:py-28">
                <div class="w-full sm:hidden px-2 mb-4">
                    <TagsInput v-model="tags" :default-list="popularTags"/>
                </div>
                <div
                    class="w-full mb-4 flex flex-row justify-between px-2 md:px-0 items-end">
                    <Dropdown align="center" class="">
                        <template #trigger>
                                            <span class="inline-flex rounded-md">
                                                <span class="dark:text-gray-100 text-gray-900 mr-2">Sorting: </span>
                                                <button type="button"
                                                        class="tracking-wide transition-colors duration-200 hover:text-gray-500 dark:text-gray-100 text-gray-900 hover:dark:text-gray-400">
                                                        {{ currentSortingOption.label }}
                                                    <i class="fa fa-chevron-down fa-xs ml-1"></i>
                                                </button>
                                            </span>
                        </template>

                        <template #content>
                            <div v-for="option in sortOptions" :key="option.value" @click="changeSort(option.value)"
                                 class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out cursor-pointer">
                                {{ option.label }}
                            </div>
                        </template>
                    </Dropdown>
                    <div class="w-full hidden sm:block sm:w-1/3">
                        <TagsInput v-model="tags" :default-list="popularTags"/>
                    </div>
                    <div class="w-fit gap-x-3 dark:text-gray-600 text-gray-400 flex">
                        <Toggle v-model="my" label="My Articles"></Toggle>
                        <div class="cursor-pointer hover:text-gray-900 dark:hover:text-gray-400 hidden sm:block "
                             :class="{'text-gray-900 dark:text-gray-400': view === 'grid'}"
                             @click="changeView('grid')">
                            <i class="fa fa-xl fa-grip-vertical"></i>
                        </div>
                        <div class="cursor-pointer hover:text-gray-900 dark:hover:text-gray-400 hidden sm:block "
                             :class="{'text-gray-900 dark:text-gray-400': view === 'list'}"
                             @click="changeView('list')">
                            <i class="fa fa-xl fa-list"></i>
                        </div>
                    </div>
                </div>
                <div v-if="articles?.data?.length > 0">
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 py-4 px-2 sm:px-0 rounded"
                        v-if="view === 'grid'">
                        <template v-for="article in articles.data"
                                  :key="article.id">
                            <GridItem :article="article"/>
                        </template>
                    </div>
                    <div class="flex flex-col gap-y-4 py-4 px-2 sm:px-0 rounded" v-else-if="view === 'list'">
                        <template v-for="article in articles.data"
                                  :key="article.id">
                            <ListItem :article="article"/>
                        </template>
                    </div>
                    <div class="w-full text-center mt-4 p-4">
                        <button @click="loadMore" v-if="hasNextPage" :disabled="isLoading"
                                class="text-center inline-block text-white rounded-lg text-2xl py-3 bg-primary-500 font-bold mt-4 hover:bg-primary-400 w-fit px-12">
                            {{ isLoading ? 'Loading...' : 'Load More' }}
                        </button>
                    </div>
                </div>

                <div v-else class="min-h-[400px] flex flex-col justify-center items-center p-4 rounded dark:text-gray-400 text-gray-600 gap-y-8">
                    <i class="fa-solid fa-2xl fa-face-frown"></i>
                    <h3 class="text-2xl">Nothing Found</h3>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script>
import {Head, Link, router} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import GridItem from "@/Pages/Feed/partials/GridItem.vue";
import ListItem from "@/Pages/Feed/partials/ListItem.vue";
import Dropdown from "@/Components/Dropdown.vue";
import {concat} from "lodash";
import TagsInput from "@/Components/TagsInput.vue";
import Toggle from "@/Components/Toggle.vue";
import FeedMixin from "@/Mixins/FeedMixin.vue";

export default {
    components: {Toggle, TagsInput, Dropdown, ListItem, GridItem, Head, MainLayout},
    mixins: [FeedMixin],
    data() {
        return {
           feedUrl: route('articles.load-feed')
        }
    },
}

</script>
