<template>
    <StaticLayout>
        <Head title="Sign up!"/>
        <FlashMessages/>
        <div class="relative">
            <section class="relative w-full lg:max-w-7xl lg:mx-auto lg:flex lg:justify-end" id="topSection">
                <div class="relative w-full h-96 sm:h-[800px] lg:w-1/2 lg:h-[800px] lg:py-12">
                    <img class="inset-0 object-cover w-full h-full bg-blue-100" alt="Banner"
                         src="/images/toddler.png"/>
                </div>
                <div class="lg:py-12 w-full px-4 self-start sm:px-6 lg:w-1/2 lg:px-8">
                    <div class="max-w-lg mx-auto text-center">
                        <h1 class="relative text-2xl md:text-4xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 xl:font-black">
                            <div class="relative inline-block">
                                <div class="relative z-10">Don't miss out!</div>
                                <div
                                    class="absolute left-0 bottom-0 w-full h-2 bg-pink-500 z-0 transform -skew-x-12"></div>
                            </div>
                            Toddler Adventure Sweepstakes!
                        </h1>

                        <p class="mt-4 text-gray-500 dark:text-gray-400">
                            Fill out the form below to join our parenting community and enter for a chance to win an Outdoor Fun Kit in our exclusive Toddler Sweepstakes!
                        </p>

                        <!-- New Info Section (Who's it for?) -->
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                            <strong>Who's it for?</strong> <i>Parents of toddler-aged kids.</i>
                        </p>

                        <div
                            class="bg-pink-600 text-white font-bold py-4 px-4 mt-4 mb-2 text-sm rounded-full inline-block pulse">
                            <u><strong>Prize Valued at:</strong></u> $150
                        </div>
                    </div>
                    <SweepForm :rules-url="route('page', 'toddler-sweeps-rules-and-regulations')"
                               prize="toddler-sweeps"/>
                </div>
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
            <SweepDisclaimer/>
        </div>
    </StaticLayout>
</template>

<script>
import StaticLayout from "@/Layouts/StaticLayout.vue";
import {Head, useForm, usePage} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import moment from 'moment';
import {mask} from 'vue-the-mask'
import TopForm from "@/Pages/Signup/partials/_TopForm.vue";
import BottomForm from "@/Pages/Signup/partials/_BottomForm.vue";
import Newsletter from "@/Pages/Signup/partials/_Newsletter.vue";
import Why from "@/Pages/Signup/partials/_Why.vue";
import Social from "@/Pages/Signup/partials/_Social.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import SweepForm from "@/Pages/Sweeps/partials/_Form.vue";
import SweepDisclaimer from "@/Pages/Sweeps/partials/_Disclamier.vue";

export default {
    name: "SweepsSignup",
    components: {
        SweepDisclaimer,
        SweepForm,
        FlashMessages,
        Social,
        Why,
        Newsletter,
        BottomForm,
        TopForm,
        InputError,
        Head,
        StaticLayout
    },
    directives: {mask},
    props: {
        email: String,
        standalone: {
            type: Boolean,
            default: false
        }
    },
}
</script>
