<script>
import StaticLayout from "@/Layouts/StaticLayout.vue";
import {Head} from "@inertiajs/vue3";

export default {
    name: "TY",
    components: {Head, StaticLayout},
    props: {
        height: String,
        content: String,
    },
}
</script>

<template>
    <StaticLayout>
        <Head title="Thank You"/>
        <div class="py-11 dark:bg-amber-50 min-h-[calc(100vh-22rem)] flex flex-col" v-if="content">
            <div class="w-full sm:mx-auto sm:max-w-xl my-auto" v-html="content"></div>
        </div>
        <div class="py-11" v-else>
            <div class="text-center flex flex-col px-2 gap-y-4 sm:mx-auto sm:max-w-xl ">
                <p class="text-xl font-bold text-slate-600 dark:text-slate-300">Thank you for joining FPS!</p>
                <h1 class="font-bold text-3xl tracking-tighter dark:text-green-600 text-green-400 sm:text-4xl sm:max-w-md sm:mx-auto">
                    BONUS</h1>
                <p class="text-base">
                    <div class="w-full px-4 self-start sm:px-6 lg:px-8">
                        <div class="max-w-lg mx-auto text-center">
                            <h1 class="font-black text-4xl text-blue-400">ENTER for a chance to WIN a $5,000 CST
                                RESP</h1>
                            <img class="mx-auto py-2 flex" alt="" src="/images/cst_fam.jpg"/>
                            <a :href="route('home')"
                               class="text-[32px] px-4 inline-block text-white rounded-global w-full text-2xl py-4 mb-6 font-black mt-4 bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500">
                                Continue!
                            </a>
                            <p class="mt-4 text-gray-500 dark:text-gray-300">Join our community of parents today and
                                receive personalized
                                parenting tips, access to free samples, sweepstakes, coupons, articles, hacks and
                                more—right in your inbox!</p>
                        </div>
                    </div>
                </p>
            </div>
        </div>
    </StaticLayout>
</template>

<style>
.panel-default {
    border: none !important;
}

.offer-title {
    @apply w-full text-center mb-4 text-3xl sm:text-4xl;
}

.offer-title a {
    font-weight: 500 !important;
    font-size: 1em !important;
}

.open {
    line-height: 2px !important;
    font-size: 2em !important;
}

.btn-success {
    @apply py-4 text-center text-white px-12 sm:w-full rounded block mx-auto text-xl font-black bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 mb-12;
    width: fit-content;
}
</style>
