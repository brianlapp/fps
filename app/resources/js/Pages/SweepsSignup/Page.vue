<template>
    <StaticLayout>
        <Head title="Thank You"/>
        <FlashMessages/>
        <div class="relative">
            <section class="relative flex flex-wrap lg:h-screen lg:items-center md:min-h-[800px]" id="topSection">
                <div class="relative w-full h-96 sm:h-[800px] lg:w-1/2 lg:h-[800px]">
                    <img class="absolute inset-0 object-cover w-full h-full bg-blue-100" alt="Banner"
                    src="/images/sweeps.png"/></div>
                <SweepForm/>
            </section>
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
import SweepForm from "@/Pages/SweepsSignup/partials/_Form.vue";

export default {
    name: "SweepsSignup",
    components: {SweepForm, FlashMessages, Social, Why, Newsletter, BottomForm, TopForm, InputError, Head, StaticLayout},
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
