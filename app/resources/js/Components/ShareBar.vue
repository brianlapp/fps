<script>
import dataMixin from "@/Mixins/DataMixin.vue";

export default {
    name: "ShareBar",
    mixins: [dataMixin],
    props: {
        article: {
            type: Object,
            default: () => null
        }
    }
}
</script>

<template>
    <div
        class="py-4 rounded-lg flex-row items-center justify-between overflow-hidden bg-gray-100 dark:bg-gray-900 px-6 flex w-full flex-wrap gap-y-4">
        <div class="flex flex-row items-center space-x-3 text-sm justify-between">
            <a :href="article?.promptAuthor?.slug? route('prompt_author.show', article?.promptAuthor?.slug) : 'javascript:;'"
               class="flex items-center w-fit rounded dark:hover:bg-gray-700 hover:bg-gray-200 hover:bg-opacity-50 hover:dark:bg-opacity-50"
               v-if="article?.promptAuthor">
                <img class="w-6 md:w-12 h-6 md:h-12 rounded-full mr-2 md:mr-4" :src="article?.promptAuthor?.avatar || '/images/user_placeholder.webp'"
                     :alt="article?.promptAuthor?.name">
                <div class="text-xs">
                    <p class="text-gray-900 dark:text-gray-200 leading-none">{{ article?.promptAuthor?.name || 'Anonymous'}}</p>
                    <p class="text-gray-600 dark:text-gray-400">{{ formatDate(article.published_at) }}</p>
                </div>
            </a>

        </div>

        <div class="sm:w-auto">
            <ul class="flex items-center space-x-4 md:space-x-4 w-full">
                <li class="inline-block">
                    <a target="_blank" class="hover:text-[#3b5998]" :href="'https://www.facebook.com/sharer/sharer.php?u='.concat(article.link)" title="Share to Facebook">
                        <i class="fa fa-xl fa-facebook"/>
                    </a>
                </li>
                <li class="inline-block">
                    <a target="_blank" class="hover:text-[#00acee]" :href="'https://twitter.com/intent/tweet?text='.concat(article.title).concat('&url=').concat(article.link)" title="Share to Twitter">
                        <i class="fa fa-xl fa-twitter"/>
                    </a>
                </li>
                <li class="inline-block">
                    <a target="_blank" class="hover:text-yellow-700" :href="'mailto:?subject='.concat(article.title).concat('&amp;body=').concat(article.link)" title="Share via Email">
                        <i class="fa fa-xl fa-envelope"/>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>

</style>
