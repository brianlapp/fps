<script>
import ComingSoon from "@/Components/ComingSoon.vue";

export default {
    name: "Categories",
    components: {ComingSoon}
}
</script>

<template>
    <div class="max-w-screen-2xl px-4 md:px-8 mx-auto text-slate-900 dark:text-slate-100">
        <div class="mb-10 md:mb-16">
            <h2 class="font-bold text-center mb-4 text-3xl md:mb-6 lg:text-4xl">
                Our Categories</h2>
            <p class="max-w-screen-md md:text-lg text-center mx-auto">
                Explore our diverse range of resources tailored to support every stage of your parenting journey. From
                expert tips to exclusive deals, find everything you need right here.
            </p>
        </div>
        <div class="px-12 grid gap-12 sm:grid-cols-2 xl:grid-cols-3 xl:gap-16">
            <div class="flex flex-col items-center">
                <div
                    class="w-12 md:w-14 h-12 md:h-14 flex justify-center items-center text-primary-500 mb-2 sm:mb-4">
                    <img src="/images/categories/new-parents.png" alt="New Parents"/>
                </div>
                <h3 class="text-lg md:text-xl font-semibold text-center mb-2">New Parents</h3>
                <p class="text-center mb-2">Get essential advice and resources to navigate the exciting journey of
                    parenthood.</p>
            </div>
            <div class="flex flex-col items-center">
                <div
                    class="w-12 md:w-14 h-12 md:h-14 flex justify-center items-center text-primary-500 mb-2 sm:mb-4">
                    <img src="/images/categories/expecting-parents.png" alt="Expecting Parents"/>
                </div>
                <h3 class="text-lg md:text-xl font-semibold text-center mb-2">Expecting Parents</h3>
                <p class="text-center mb-2">Prepare for your new arrival with tips, guides, and support tailored for
                    expecting parents</p>
            </div>
            <div class="flex flex-col items-center">
                <div
                    class="w-12 md:w-14 h-12 md:h-14 flex justify-center items-center text-primary-500 mb-2 sm:mb-4">
                    <img src="/images/categories/paretning-tips.png" alt="Parenting Tips"/>
                </div>
                <h3 class="text-lg md:text-xl font-semibold text-center mb-2">Parenting Tips</h3>
                <p class="text-center mb-2">Find practical tips and strategies for raising happy and healthy
                    children.</p>
            </div>
            <div class="flex flex-col items-center relative">
                <div
                    class="w-12 md:w-14 h-12 md:h-14 flex justify-center items-center text-primary-500 mb-2 sm:mb-4">
                    <img src="/images/categories/free-samples.png" alt="Free Samples"/>

                </div>
                <ComingSoon class="absolute -right-4 md:right-0"/>
                <h3 class="text-lg md:text-xl font-semibold text-center mb-2">Free Samples</h3>
                <p class="text-center mb-2">Discover the latest free samples of baby products, parenting essentials, and
                    more from top brands. We find them so you don't have to. </p>
            </div>
            <div class="flex flex-col items-center relative">
                <div
                    class="w-12 md:w-14 h-12 md:h-14 flex justify-center items-center text-primary-500 mb-2 sm:mb-4">
                    <img src="/images/categories/sweepstakes.png" alt="Sweepstakes"/>
                </div>
                <ComingSoon class="absolute -right-4 md:right-0"/>
                <h3 class="text-lg md:text-xl font-semibold text-center mb-2">Sweepstakes</h3>
                <p class="text-center mb-2">Join our exclusive sweepstakes for chances to win fantastic prizes!</p>
            </div>
            <div class="flex flex-col items-center relative">
                <div
                    class="w-12 md:w-14 h-12 md:h-14 flex justify-center items-center text-primary-500 mb-2 sm:mb-4">
                    <img src="/images/categories/paid-surveys.png" alt="Paid Surveys"/>
                </div>
                <ComingSoon class="absolute -right-4 md:right-0"/>
                <h3 class="text-lg md:text-xl font-semibold text-center mb-2">Paid Surveys</h3>
                <p class="text-center mb-2">Earn money and rewards by participating in surveys and points programs.</p>
            </div>
        </div>
    </div>
</template>

