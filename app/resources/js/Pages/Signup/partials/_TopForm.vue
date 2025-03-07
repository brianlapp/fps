<script>
import SignupForm from "@/Components/SignupForm.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import {Link} from "@inertiajs/vue3";
import GoogleButton from "@/Components/GoogleButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {mask} from "vue-the-mask";
import stringMixin from "@/Mixins/StringMixin.vue";

export default {
    name: "_TopForm",
    components: {Checkbox, GoogleButton, Link, InputError, TextInput, SignupForm},
    directives: {mask},
    mixins: [stringMixin]
}
</script>

<template>
    <div class="py-12 w-full px-4 self-start sm:px-6 lg:w-1/2 lg:px-8">
        <div class="max-w-lg mx-auto text-center">
            <h1 class="text-2xl md:text-4xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 xl:font-black">
                    Get Your FREE Ultimate Baby Checklist & Planner!
            </h1>
            <p class="mt-4 text-gray-500 dark:text-gray-400">
                Fill out the form below to join our parenting community and unlock instant access to your personalized baby planner.
            </p>
            <div class="bg-pink-600 text-white font-bold py-4 px-4 mt-4 mb-2 text-sm rounded-full inline-block pulse">
                <u>
                    <strong>FREE</strong>
                </u>
                Baby Planner Available Now  🥳
            </div>
        </div>
        <SignupForm v-slot="slotProps">
            <div class="bg-blue-600 dark:bg-blue-700 rounded-md py-10 px-8 t-2 mt-2"
                 :class="{'opacity-50' : slotProps.form.processing}">
                <div class="mb-4">
                    <label class="block text-sm font-medium dark:text-white">
                        <span class="sr-only">Full name</span>
                    </label>
                    <input
                        id="first_name"
                        type="text"
                        v-model="slotProps.form.first_name"
                        required
                        autofocus
                        autocomplete="first_name"
                        class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 focus:dark:bg-slate-800 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                        name="first_name"
                        placeholder="First name"/>
                    <InputError class="mt-2" textClass="text-red-300" :message="slotProps.form.errors.first_name"/>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium dark:text-white">
                        <span class="sr-only">Last name</span>
                    </label>
                    <input
                        id="last_name"
                        type="text"
                        v-model="slotProps.form.last_name"
                        required
                        autofocus
                        autocomplete="last_name"
                        class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 focus:dark:bg-slate-800 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                        name="last_name"
                        placeholder="Last name"/>
                    <InputError class="mt-2" textClass="text-red-300" :message="slotProps.form.errors.last_name"/>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium dark:text-white">
                        <span class="sr-only">Email address</span>
                    </label>
                    <input id="email"
                           type="email"
                           v-model="slotProps.form.email"
                           required
                           autocomplete="email"
                           class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                           name="email"
                           placeholder="Email address"/>
                    <InputError class="mt-2" textClass="text-red-300" :message="slotProps.form.errors.email"/>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium dark:text-white">
                        <span class="sr-only">Postal Code</span>
                    </label>
                    <input
                        id="postcode"
                        type="text"
                        v-mask="'A#A#A#'"
                        v-model="slotProps.form.postcode"
                        required
                        autocomplete="postal_code"
                        class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 focus:dark:bg-slate-800 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                        name="postcode"
                        :placeholder="postalLabel"/>
                    <InputError class="mt-2" textClass="text-red-600" :message="slotProps.form.errors.postcode"/>
                </div>
                <div class="mb-4" v-if="slotProps.isMomsPath">
                    <div class="relative">
                        <select class="w-full p-3 pr-12 text-sm rounded-border-gray-200 rounded-md font-medium dark:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:p-4 dark:bg-slate-800 dark:border-gray-700" v-model="slotProps.form.child_age" required name="child_age" id="child_age">
                            <option value="" disabled>Age of youngest child</option>
                            <option value="pregnantwithkids">I’m pregnant and have kids</option>
                            <option value="pregnant">I’m pregnant with my first child</option>
                            <option value="uptothree">0 - 36 months (0 - 3 years)</option>
                            <option value="threeplus">3+ years (over 36 months)</option>
                            <option value="nochildren">No children</option>
                        </select>
                    </div>
                    <InputError class="mt-2" textClass="text-red-300" :message="slotProps.form.errors.child_age"/>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium dark:text-white">
                        <span class="sr-only">Password</span>
                    </label>
                    <input
                        id="password"
                        type="password"
                        v-model="slotProps.form.password"
                        required
                        autocomplete="new-password"
                        class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                        name="password"
                        placeholder="Password"/>
                    <InputError class="mt-2" textClass="text-red-300" :message="slotProps.form.errors.password"/>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium dark:text-white">
                        <span class="sr-only">Confirm Password</span>
                    </label>
                    <input
                        id="password"
                        type="password"
                        v-model="slotProps.form.password_confirmation"
                        required
                        autocomplete="new-password"
                        class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                        name="password_confirmation"
                        placeholder="Confirm Password"/>
                    <InputError class="mt-2" textClass="text-red-300"
                                :message="slotProps.form.errors.password_confirmation"/>
                </div>
                <div class="mb-4">
                    <div class="flex items-center gap-x-2">
                        <input type="checkbox" name="disclaimer" v-model="slotProps.form.disclaimer" required/>
                        <p class="mt-2 text-sm text-gray-100 dark:text-gray-400">
                            By entering your information and clicking Join Now, you agree to our <a class="underline hover:text-blue-400" :href="route('page', 'privacy')">Privacy Policy</a> , <a :href="route('page', 'terms')" class="underline hover:text-blue-400">Terms and Conditions</a> and understand that we will be sending you our newsletters, offers and promotions from ourselves and our partners by email. Unsubscribe at any time.</p>
                    </div>
                    <InputError class="mt-2" textClass="text-red-300"
                                :message="slotProps.form.errors.disclaimer"/>
                </div>
                <div class="grid">
                    <button :class="{ 'opacity-25': slotProps.form.processing }" :disabled="slotProps.form.processing"
                            class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-5xl text-3xl bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 sm:p-4">
                        Join Free Now
                    </button>
                    <InputError class="mt-2" textClass="text-red-300"
                                :message="slotProps.form.errors.recaptcha_response"/>
                </div>
                <a
                    :href="route('login')"
                    class="hover:underline text-sm text-gray-100 dark:text-gray-200 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </a>
            </div>

            <div class="grid mt-5 mb-3" v-if="!slotProps.isMomsPath">
               <GoogleButton :url="route('auth', 'google').concat(slotProps.queryString)"/>
            </div>
        </SignupForm>
    </div>

</template>

<style scoped>

</style>
