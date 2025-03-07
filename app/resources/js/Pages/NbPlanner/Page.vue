<template>
    <Head>
        <title>{{seoTitle}}</title>
        <meta head-key="description" name="description" :content="seoHeadline">
        <meta head-key="og:title" property="og:title" :content="seoTitle">
        <meta head-key="og:description" property="og:description" :content="seoHeadline ">
        <meta head-key="og:image" property="og:image" :content="category.image"/>
        <meta head-key="og:url" property="og:url" :content=" category.link ">

        <meta head-key="twitter:title" property="twitter:title" :content="seoTitle">
        <meta head-key="twitter:description" property="twitter:description" :content="seoHeadline">
        <meta head-key="twitter:image" property="twitter:image" :content="category.image">
    </Head>
    <SideMenu :items="allCategories" @clicked-on-forbidden="openSignupModal"/>
    <div class="py-6 px-2">
        <h1 class="text-3xl md:text-5xl font-extrabold mb-6 dark:text-gray-200 text-center relative pt-3 lg:pt-0">
            Ultimate Baby Checklist &
            Planner
            <Beta class="absolute -top-4 right-1 lg:relative inline-flex justify-self-center align-top ml-2"/>
        </h1>
        <div class="w-full h-6 bg-gray-200 rounded-full dark:bg-gray-700 flex items-center relative">
            <div
                class="h-6 bg-primary-500 leading-none rounded-full text-center"
                :style="{width: (currentProgress || 0).toString().concat('%')}">
            </div>
            <span class="text-lg font-medium text-blue-100 text-center px-3 absolute inset-0">
                {{ currentProgress }}%
            </span>
        </div>
    </div>
    <div
        class="container mx-auto mt-4 sm:mt-8 pt-6 sm:pt-12 pb-4 sm:pb-6 px-4 sm:px-8 flex flex-col bg-white dark:bg-gray-800 rounded-lg shadow-xl">
        <div class="border-b dark:border-gray-600 mb-8">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4 sm:mb-6 dark:text-gray-200 text-center w-full">{{
                    category.title
                }}</h2>
            <template v-if="category?.sections?.length > 0">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Track your progress.</p>
                <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                    <div class="bg-blue-500 h-3 rounded-full"
                         :style="{width: (categoryProgress || 0).toString().concat('%')}"></div>
                    <!-- Section-specific progress -->
                </div>
            </template>
            <img :src="category.image" v-if="category.image && category.show_image" :srcset="category.imageSrcSet"
                 class="rounded w-auto mx-auto mb-3"/>
            <div class="text-gray-700 dark:text-gray-300 leading-relaxed article-content mb-4 py-4 block"
                 v-html="category.description">
            </div>

            <div class="py-4 article-content">
                <div v-for="(section, i) in category.sections" :key="'section-'.concat(i)">
                    <h3 class="text-2xl font-extrabold mb-6 dark:text-gray-200 w-full">
                        {{ section.title }}
                    </h3>
                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed article-content mb-8 py-4 block"
                         v-if="section.description"
                         v-html="section.description">
                    </div>
                    <div class="py-2">
                        <div class="flex gap-x-4" v-for="item in section.items">
                            <div>
                                <Checkbox :checked="isItemChecked(item)" @update:checked="checked(item)"/>
                            </div>
                            <div>
                                <h4 class="text-lg font-extrabold mb-1 dark:text-gray-200 w-full mt-custom mb-custom">
                                    {{ item.title }}
                                    <span v-if="item.label" class="ml-2 px-2 text-sm font-normal rounded-lg"
                                          :style="{'background-color': item.color}">{{ item.label }}</span>
                                </h4>
                                <div class="text-gray-700 dark:text-gray-300 leading-relaxed article-content py-4 block"
                                     v-html="item.description">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="p-4 text-center" v-if="next">
                    <a class="btn-link py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-5xl text-3xl bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 sm:p-4"
                       :href="next.link" v-if="allowedToContinue">
                        Continue
                    </a>
                    <button
                        class="btn-link py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-5xl text-3xl bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 sm:p-4"
                        @click="openSignupModal" v-else>
                        Continue
                    </button>
                </div>
            </div>
        </div>
        <SignupModal v-if="showSignupModal" @close="closeSignupModal"/>
    </div>
</template>


<script>
import DataMixin from "@/Mixins/DataMixin.vue";
import {Head, Link, router} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import Checkbox from "@/Components/Checkbox.vue";
import SideMenu from "@/Pages/NbPlanner/partials/SideMenu.vue";
import SignupModal from "@/Pages/NbPlanner/partials/SignupModal.vue";
import Beta from "@/Components/Beta.vue";

export default {
    name: "NbPlanner",
    components: {Beta, SignupModal, Checkbox, PrimaryLink, PrimaryButton, Head, Link, SideMenu},
    layout: MainLayout,
    mixins: [DataMixin],
    props: {
        category: Object,
        next: Object,
        allCategories: Array,
        progress: Number,
        checkedItems: Array
    },
    data() {
        return {
            seoTitle: 'Ultimate Baby Checklist & Planner: Prepare for Your Newborn',
            seoHeadline: 'Get Ready for Your Baby with the Ultimate Baby Checklist & Planner',
            showSignupModal: false,
            currentProgress: this.progress,
            categoryProgress: this.category.progress
        }
    },
    methods: {
        checked(item) {
            if (!this.$page?.props?.auth?.user) {
                return this.openSignupModal();
            }

            axios.get(route('planner_categories.toggleItem', item.uuid))
                .then(({data}) => {
                    this.currentProgress = data.progress;
                    if (data.categoryProgress !== null) {
                        this.categoryProgress = data.categoryProgress;
                    }
                })
                .catch(e => {
                    // Doing nothing
                    console.error(e);
                })
        },
        closeSignupModal() {
            this.showSignupModal = false;
        },
        openSignupModal() {
            this.showSignupModal = true;
        },
        isItemChecked(item) {
            return this.checkedItems.includes(item.uuid);
        }
    },
    computed: {
        allowedToContinue() {
            return this.category?.sections?.length < 1 || this.$page?.props?.auth?.user;
        }
    }
}
</script>

<style scoped>

</style>
