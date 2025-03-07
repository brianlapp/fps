<template>
    <Head>
        <title>{{article.seo_title || article.title}}</title>
        <meta head-key="description" name="description" :content="article.seo_headline || article.headline">
        <meta head-key="og:title" property="og:title" :content="article.seo_title || article.title">
        <meta head-key="og:description" property="og:description" :content="article.seo_headline || article.headline">
        <meta head-key="og:image" property="og:image" :content="article.image"/>
        <meta head-key="og:url" property="og:url" :content=" article.link ">

        <meta head-key="twitter:title" property="twitter:title" :content="article.seo_title || article.title">
        <meta head-key="twitter:description" property="twitter:description"
              :content="article.seo_headline || article.headline">
        <meta head-key="twitter:image" property="twitter:image" :content="article.image">
    </Head>
    <div class="container mx-auto mt-2 sm:mt-8 p-0 sm:p-4 ">
        <div class="text-xs sm:text-sm italic text-gray-500 p-4">We strive to keep FPS free for everyone by partnering
            with trusted brands. Some links may earn us a commission at no extra cost to you.
        </div>
        <div class="flex flex-col md:flex-row md:gap-x-4">
            <div class="w-full md:w-9/12">
                <article class="bg-white p-4 sm:p-8 shadow-md rounded-md dark:bg-gray-800">
                    <!-- Article Title -->
                    <h1 class="text-2xl md:text-4xl font-extrabold mb-6 dark:text-gray-200">{{ article.title }}</h1>

                    <div
                        class="flex flex-col sm:flex-row gap-y-8 sm:gap-x-8 items-center py-8 px-6 dark:bg-gray-900 bg-gray-100 mb-4 rounded-xl shadow-lg"
                        v-if="article.is_image_being_generated">
                        <img class="border-gray-200 rounded-full dark:border-gray-800"
                             src="/images/image_generating.gif"
                             alt="Image is being generated" width="200" height="200"/>
                        <div class="text-gray-900 dark:text-gray-200 text-2xl font-semibold">Image is being generated...
                        </div>
                    </div>
                    <!-- Article Image -->
                    <div v-else-if="article.image" class=" mb-4">
                        <img class="w-full" :src="article.image" :srcset="article.imageSrcSet"
                             :alt="article.image_caption || article.title" :title="article.title" width="1280"
                             height="731"/>
                        <p class="mt-1 p-0 text-xs italic text-gray-600 dark:text-gray-400"
                           v-if="article.image_caption">
                            {{ article.image_caption }}</p>
                    </div>

                    <div class="flex gap-x-2 justify-between flex-wrap items-center mb-8 gap-y-4">
                        <!-- Author Information -->
                        <a :href="route('author.show', article.author.slug)"
                           class="flex items-center w-fit rounded dark:hover:bg-gray-700 hover:bg-gray-200 hover:bg-opacity-50 hover:dark:bg-opacity-50"
                           v-if="article.author">
                            <img class="w-8 md:w-12 h-8 md:h-12 rounded-full mr-4" :src="article.author.avatar"
                                 :alt="article.author.name">
                            <div class="text-xs">
                                <p class="text-gray-400 dark:text-gray-500 leading-none mb-1">Writer</p>
                                <p class="text-gray-900 dark:text-gray-200 leading-none">{{ article.author.name }}</p>
                                <p class="text-gray-600 dark:text-gray-400">{{ formatDate(article.published_at) }}</p>
                            </div>
                        </a>

                        <!--                     Prompt Author Information -->
                        <a v-if="article?.promptAuthor"
                           :href="article?.promptAuthor?.slug ? route('prompt_author.show', article?.promptAuthor?.slug) : 'javascript:;'"
                           class="flex items-center w-fit rounded dark:hover:bg-gray-700 hover:bg-gray-200 hover:bg-opacity-50 hover:dark:bg-opacity-50">
                            <img class="w-8 md:w-12 h-8 md:h-12 rounded-full mr-4"
                                 :src="article?.promptAuthor?.avatar || '/images/user_placeholder.webp'"
                                 :alt="article?.promptAuthor?.name">
                            <div class="text-xs">
                                <p class="text-gray-400 dark:text-gray-500 leading-none mb-1">Prompt Author</p>
                                <p class="text-gray-900 dark:text-gray-200 leading-none">
                                    {{ article?.promptAuthor?.name }}</p>
                            </div>
                        </a>

                        <div class="flex gap-x-4">
                            <button class="group" :data-tooltip-target="toolTipKey" data-tooltip-placement="left" role="button" @click="toggleFav" v-if="$page.props.auth.user">
                                <i  class="fa-solid group-hover:fa-solid fa-bookmark group-hover:text-red-500 text-4xl cursor-pointer dark:text-gray-400" data-tooltip-style="" v-if="isArticleFavorite"/>
                                <i  class="fa-regular fa-bookmark group-hover:text-red-500 text-4xl cursor-pointer dark:text-gray-400" data-tooltip-style="" v-else/>
                            </button>
                            <div :id="toolTipKey" role="tooltip"
                                 class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                {{isArticleFavorite ? 'Remove from Saved Articles' : 'Add to Saved Articles'}}
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <a class="border-2 duration-200 ease items-center transition py-3 pl-5 pr-7 text-lg rounded-lg text-white border-blue-600 bg-blue-600 hover:bg-blue-700 hover:border-blue-700 w-full sm:w-fit flex justify-center"
                               target="_blank" rel="noopener"
                               :href="'https://www.facebook.com/sharer/sharer.php?u='.concat(article.link)"
                               aria-label="Share on Facebook" draggable="false">
                                <svg class="w-8 h-8" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg"
                                     xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"
                                     fill="#ffffff" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                    <g> <path class="st0"
                                              d="M512,230.431L283.498,44.621v94.807C60.776,141.244-21.842,307.324,4.826,467.379 c48.696-99.493,149.915-138.677,278.672-143.14v92.003L512,230.431z"></path> </g> </g></svg>
                                <span class="ml-2 font-black">Share</span>
                            </a>
                        </div>

                    </div>


                    <!-- Article Content -->
                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed mb-8 article-content ck-content">
                        <div v-html="contentFirstHalf"/>
                        <div class="my-8 p-2 sm:p-4 dark:bg-gray-900 bg-blue-50 rounded" v-if="!$page.props.auth.user">
                            <SignupFormWidget/>
                        </div>

                        <div v-html="contentSecondHalf" v-if="contentSecondHalf"/>
                    </div>

                    <!-- Tags List -->
                    <div class="flex flex-wrap gap-2 mb-6" v-if="article?.tags.length > 0">
                        <a :href="getTagUrl(tag)"
                           class="inline-block bg-blue-200 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-700 dark:text-blue-100 dark:hover:bg-blue-900 hover:bg-blue-400"
                           v-for="(tag, i) in article.tags" :key="'tag-'.concat(i)">{{ tag }}</a>
                    </div>
                </article>
                <ArticleVotes :article="article"/>
                <Comments/>
            </div>

            <div class="w-full md:w-3/12 py-8 px-2 shadow-md rounded-md dark:bg-gray-800 dark:text-gray-200 bg-white">
                <div>
                    <h2 class="text-3xl mb-8 text-center">Suggested Articles</h2>
                    <template v-for="article in suggestedArticles"
                              :key="article.id">
                        <GridItem :article="article"/>
                    </template>
                </div>
            </div>
        </div>


    </div>
</template>


<script>
import DataMixin from "@/Mixins/DataMixin.vue";
import {Head, Link} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import GridItem from "@/Pages/Feed/partials/GridItem.vue";
import SignupFormWidget from "@/Components/SignupFormWidget.vue";
import Comments from "@/Components/Comments.vue";
import FavoritesMixin from "@/Mixins/FavoritesMixin.vue";
import ArticleVotes from "@/Components/ArticleVotes.vue";
import 'ckeditor5/ckeditor5.css';
export default {
    name: "Article",
    components: {ArticleVotes, Comments, SignupFormWidget, GridItem, Head, Link},
    layout: MainLayout,
    mixins: [DataMixin, FavoritesMixin],
    props: {
        article: Object,
        offerCategories: Array,
        suggestedArticles: Array,
    },
    computed: {
        contentFirstHalf() {
            const parts = this.article.content.split('<middle></middle>');

            return parts.length === 2 ? parts[0] : this.article.content;
        },
        contentSecondHalf() {
            const parts = this.article.content.split('<middle></middle>');

            return parts.length === 2 ? parts[1] : null;
        }
    },
    methods: {
        getTagUrl(tag) {
            return route('articles.feed').concat('?tags=').concat(tag);
        }
    }
}
</script>

<style scoped>

</style>
