<script setup>
import {Head, Link} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import RecentSearches from "@/Pages/Home/partials/RecentSearches.vue";
import TrendingArticles from "@/Pages/Home/partials/TrendingArticles.vue";
import Categories from "@/Pages/Home/partials/Categories.vue";
import Guides from "@/Pages/Home/partials/Guides.vue";
import AuthorBanner from "@/Pages/Home/partials/AuthorBanner.vue";
import SearchForm from "@/Pages/Home/partials/SearchForm.vue";
import MainGrid from "@/Pages/Home/partials/MainGrid.vue";
import FeaturedArticles from "@/Pages/Home/partials/FeaturedArticles.vue";
import FeaturedArticle from "@/Pages/Home/partials/FeaturedArticle.vue";
import OurStory from "@/Pages/Home/partials/OurStory.vue";
import HeroUnit from "@/Components/Home/HeroUnit.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    latestSearches: {
        type: Array
    },
    trendingArticles: {
        type: Array
    }
});



</script>

<template>
    <MainLayout>
        <Head title="Search"/>

        <HeroUnit />

        <MainGrid/>

        <!-- Featured Articles Section -->
        <FeaturedArticles :trending-articles="trendingArticles" v-if="trendingArticles.length > 0"/>

        <section class="py-6 dark:bg-gray-800 bg-white sm:py-8 lg:py-12 w-[100vw] -ml-[calc((100vw-100%)/2)] -mr-[calc((100vw-100%)/2) mt-20 mb-12">
            <Categories/>
        </section>

        <!-- Featured Hero Article Section with Outer Padding -->
       <FeaturedArticle/>

        <!-- About Us Section with Gradient H1 -->
        <OurStory/>
    </MainLayout>

</template>
