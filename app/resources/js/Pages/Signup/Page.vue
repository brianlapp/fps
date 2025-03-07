<script setup>
import {Head} from '@inertiajs/vue3';
import TopForm from "@/Pages/Signup/partials/_TopForm.vue";
import BottomForm from "@/Pages/Signup/partials/_BottomForm.vue";
import AOS from "aos";
import 'aos/dist/aos.css';
import ThemeToggle from "@/Components/ThemeToggle.vue";
import Features from "@/Pages/Signup/partials/_Features.vue";
import Why from "@/Pages/Signup/partials/_Why.vue";
import Newsletter from "@/Pages/Signup/partials/_Newsletter.vue";
import Social from "@/Pages/Signup/partials/_Social.vue";
import Logo from "@/Components/Logo.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import StaticHeader from "@/Components/StaticHeader.vue";
import StaticLayout from "@/Layouts/StaticLayout.vue";
import {onMounted} from "vue";

onMounted(() => {
    EfImpression();
    EfClick();
})
AOS.init({
    offset: 50,
    duration: 800
});
</script>

<template>
    <StaticLayout>
        <Head>
            <title>{{'Signup'}}</title>
        </Head>
        <FlashMessages/>
        <div class="relative">
            <section class="relative flex flex-wrap lg:h-screen lg:items-center md:min-h-[1200px]" id="topSection">
                <div class="relative w-full h-64 sm:h-96 lg:w-1/2 lg:h-full"><img
                    class="absolute inset-0 object-cover w-full h-full bg-blue-100 rounded-br-full" alt="Banner"
                    src="/images/banner2.png"/></div>
                <TopForm/>
            </section>
            <section class="bg-blue-50 dark:bg-gray-600 dark:text-gray-300">
                <Why/>
            </section>
            <section class="md:py-16">
                <Newsletter/>
            </section>
            <section class="bg-blue-50 dark:bg-gray-600 dark:text-gray-300 py-10 xl:py-14" data-aos="fade-left">
                <Social/>
            </section>
            <div class="py-11">
                <div class="text-center flex flex-col px-2 gap-y-4 sm:mx-auto sm:max-w-xl">
                    <p class="dark:text-gray-300">Get Started</p>
                    <h1 class="font-bold text-3xl tracking-tighter text-slate-900 dark:text-slate-100 sm:text-4xl sm:max-w-md sm:mx-auto">
                        Ready to join?</h1>
                    <BottomForm/>
                </div>
            </div>
        </div>
    </StaticLayout>
</template>
