<script>
import DataMixin from "@/Mixins/DataMixin.vue";
import GridItem from "@/Pages/Feed/partials/GridItem.vue";
import FavoritesMixin from "@/Mixins/FavoritesMixin.vue";

export default {
    name: "ListItem",
    components: {GridItem},
    mixins: [DataMixin, FavoritesMixin],
    props: {
        article: Object
    }
}
</script>

<template>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex-row hidden sm:flex">
        <div class="w-1/2 relative flex flex-col justify-center">
            <img :src="article.image" :alt="article.title" class="w-full h-auto">

            <div class="flex justify-between items-center px-2 absolute bottom-0 py-1 w-full bg-gray-100 dark:bg-gray-600 bg-opacity-75 dark:bg-opacity-75 rounded-b-lg hover:bg-opacity-90 hover:dark:bg-opacity-90">
                <a :href="route('author.show', article.author.slug)"
                   class="flex items-center "
                   v-if="article.author">
                    <img class="w-8 md:w-10 h-8 md:h-10 rounded-full mr-2" :src="article.author.avatar"
                         :alt="article.author.name">
                    <div class="text-sm text-left">
                        <p class="text-gray-900 dark:text-gray-200 leading-none">{{ article.author.name }}</p>
                        <p class="text-gray-600 dark:text-gray-300 text-xs">{{ formatDate(article.published_at) }}</p>
                    </div>
                </a>

                <button class="group" :data-tooltip-target="'l'.concat(toolTipKey)" data-tooltip-placement="top" role="button" @click="toggleFav" v-if="$page.props.auth.user">
                    <i  class="fa-solid group-hover:fa-solid fa-bookmark group-hover:text-red-500 text-xl cursor-pointer dark:text-gray-400" data-tooltip-style="" v-if="isArticleFavorite"/>
                    <i  class="fa-regular fa-bookmark group-hover:text-red-500 text-xl cursor-pointer dark:text-gray-400" data-tooltip-style="" v-else/>
                </button>
                <div :id="'l'.concat(toolTipKey)" role="tooltip"
                     class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    {{isArticleFavorite ? 'Remove from Saved Articles' : 'Add to Saved Articles'}}
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-between w-1/2">
            <h2 class="text-md md:text-xl lg:text-2xl uppercase font-black text-blue-400 text-center px-2 line-clamp-3 md:min-h-[83px] my-2">
                {{ article.title }}</h2>
            <p class="text-gray-700 dark:text-gray-300 text-sm lg:text-base md:min-h-[75px] line-clamp-3 lg:line-clamp-5 px-2 text-left">
                {{ article.headline }}
            </p>
            <a :href="article.link"
               class="block text-green-500 text-center py-2 px-8 mb-2 mt-4 text-xl rounded-lg hover:bg-green-500 hover:text-white transition-all duration-300 border-2 border-green-500 max-w-[420px] mx-auto">
                Read More
            </a>
        </div>
    </div>
    <GridItem :article="article" class="sm:hidden"/>
</template>

<style scoped>

</style>
