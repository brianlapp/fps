<script>
import Logo from "@/Components/Logo.vue";
import ThemeToggle from "@/Components/ThemeToggle.vue";
import {Head, Link} from "@inertiajs/vue3";
import Socials from "@/Components/Socials.vue";

export default {
    name: "StaticLayout",
    components: {Socials, Link, Head, ThemeToggle, Logo}
}
</script>

<template>
    <Head>
        <meta head-key="description" name="description" :content="$page.props.app.seo.headline">
        <meta head-key="og:type" property="og:type" content="website" />
        <meta head-key="og:title" property="og:title" :content=" $page.props.app.seo.title ">
        <meta head-key="og:description" property="og:description" :content=" $page.props.app.seo.headline ">
        <meta head-key="og:image" property="og:image" :content="$page.props.app.seo.image"/>
        <meta head-key="og:url" property="og:url" :content=" $page.props.app.seo.link ">

        <meta head-key="twitter:title" property="twitter:title" :content="$page.props.app.seo.title">
        <meta head-key="twitter:description" property="twitter:description" :content="$page.props.app.seo.headline">
        <meta head-key="twitter:card" property="twitter:card" content="summary_large_image"/>
        <meta head-key="twitter:image" property="twitter:image" :content="$page.props.app.seo.image ">

        <meta head-key="twitter:viewport" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    </Head>
    <div class="min-h-screen flex flex-col justify-between dark:bg-gray-700 overflow-x-hidden">
        <div class="from-amber-500 to-amber-200 dark:bg-gray-900 shadow relative">
            <div class="mx-auto sm:max-w-xl md:max-w-full lg:max-w-screen-xl md:px-24 lg:px-8">
                <div class="relative flex grid items-center justify-center">
                    <Logo class="max-w-[160px] p-3 items-center justify-center"/>
                </div>
            </div>
            <ThemeToggle class="absolute right-4 top-[40%]"/>
        </div>
        <slot/>
        <footer class="to-amber-200 dark:bg-gray-900 shadow pb-4">
            <div class="max-w-7xl mx-auto items-center justify-center flex mb-4">
                <Logo class="flex justify-center items-center max-w-[100px] pt-6"/>
            </div>
            <div class="w-full flex flex-row items-center justify-center gap-x-6 dark:border-gray-500  border-t pt-8">
                <a class="text-gray-600 dark:text-gray-400 hover:text-gray-900 hover:dark:text-gray-200" :href="route('page', 'about-us')">About Us</a>
                <a class="text-gray-600 dark:text-gray-400 hover:text-gray-900 hover:dark:text-gray-200" :href="route('page', 'terms')">Terms & Conditions</a>
                <a class="text-gray-600 dark:text-gray-400 hover:text-gray-900 hover:dark:text-gray-200" :href="route('page', 'privacy')">Privacy Policy</a>
            </div>
            <Socials class="mt-4"/>
            <div class="text-gray-400 dark:text-gray-500 text-sm text-center mt-4">©
                {{ new Date().getFullYear() }} - FreeParentsSearch. All rights reserved.
            </div>
        </footer>
    </div>
</template>

<style scoped>

</style>
