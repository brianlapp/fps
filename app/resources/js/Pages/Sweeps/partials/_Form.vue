<script>
import SignupForm from "@/Components/SignupForm.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import {Link, useForm, usePage} from "@inertiajs/vue3";
import GoogleButton from "@/Components/GoogleButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {mask} from "vue-the-mask";
import stringMixin from "@/Mixins/StringMixin.vue";
import {useReCaptcha} from "vue-recaptcha-v3";

export default {
    name: "SweepForm",
    components: {Checkbox, GoogleButton, Link, InputError, TextInput, SignupForm},
    directives: {mask},
    mixins: [stringMixin],
    props: {
        rulesUrl: {
            type: String
        },
        prize: {
            type: String
        }
    },
    data() {
        const inputs = [...usePage().props.app.trackingParams, ...['first_name', 'last_name', 'email']],
            formConfig = {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',
                recaptcha_response: '',
                disclaimer: null,
                rules: null,
                child_age: '',
                postcode: '',
                prize: this.prize,
                lang: 'en'
            },
            query = usePage().props.query,
            fbQuery = ['source_id=fbshare'];
        inputs.forEach(inputName => {
            formConfig[inputName] = query[inputName] || '';
            if (query[inputName] && inputName !== 'source_id') {
                fbQuery.push(inputName.concat('=', query[inputName] || ''));
            }
        });
        return {
            form: useForm(formConfig),
            trackingQuery: {
                offer_id: 1982
            },
            fbShareUrl: route(route().current()).concat('?').concat(fbQuery.join('&'))
        }
    },
    mounted() {
        const {executeRecaptcha, recaptchaLoaded} = useReCaptcha();
        this.recaptchaLoaded = recaptchaLoaded;
        this.executeRecaptcha = executeRecaptcha;
        this.fillRecaptchaToken();
        EfImpression(this.trackingQuery);
        EfClick(this.trackingQuery);
    },
    methods: {
        getRecaptchaToken() {
            return this.recaptchaLoaded().then(() => {
                return this.executeRecaptcha('login');
            })
        },
        fillRecaptchaToken() {
            this.getRecaptchaToken().then(token => {
                this.form.recaptcha_response = token;
            })
        },
        submit() {
            this.form.post(route('sweeps.submit'), {
                onFinish: (q) => {
                    if (!this.form.hasErrors) {
                        EfConversion(this.trackingQuery);
                        fbq('track', 'Lead');
                    }
                    this.form.reset('password', 'password_confirmation');
                    this.fillRecaptchaToken();
                },
                preserveScroll: 'errors'
            });
        }
    }
}
</script>

<template>
    <form @submit.prevent="submit" class="relative" :class="{'opacity-50' : form.processing}">
        <div class="bg-blue-600 dark:bg-blue-700 rounded-md py-10 px-8 t-2 mt-2"
             :class="{'opacity-50' : form.processing}">
            <div class="mb-4">
                <label class="block text-sm font-medium dark:text-white">
                    <span class="sr-only">Full name</span>
                </label>
                <input
                    id="first_name"
                    type="text"
                    v-model="form.first_name"
                    required
                    autofocus
                    autocomplete="first_name"
                    class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 focus:dark:bg-slate-800 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                    name="first_name"
                    placeholder="First name"/>
                <InputError class="mt-2" textClass="text-red-300" :message="form.errors.first_name"/>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium dark:text-white">
                    <span class="sr-only">Last name</span>
                </label>
                <input
                    id="last_name"
                    type="text"
                    v-model="form.last_name"
                    required
                    autofocus
                    autocomplete="last_name"
                    class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 focus:dark:bg-slate-800 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                    name="last_name"
                    placeholder="Last name"/>
                <InputError class="mt-2" textClass="text-red-300" :message="form.errors.last_name"/>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium dark:text-white">
                    <span class="sr-only">Email address</span>
                </label>
                <input id="email"
                       type="email"
                       v-model="form.email"
                       required
                       autocomplete="email"
                       class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                       name="email"
                       placeholder="Email address"/>
                <InputError class="mt-2" textClass="text-red-300" :message="form.errors.email"/>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium dark:text-white">
                    <span class="sr-only">Postal Code</span>
                </label>
                <input
                    id="postcode"
                    type="text"
                    v-mask="'A#A#A#'"
                    v-model="form.postcode"
                    required
                    autocomplete="postal_code"
                    class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 focus:dark:bg-slate-800 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                    name="postcode"
                    :placeholder="postalLabel"/>
                <InputError class="mt-2" textClass="text-red-600" :message="form.errors.postcode"/>
            </div>
            <div class="mb-4">
                <div class="relative">
                    <select
                        class="w-full p-3 pr-12 text-sm rounded-border-gray-200 rounded-md font-medium dark:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:p-4 dark:bg-slate-800 dark:border-gray-700"
                        v-model="form.child_age" required name="child_age" id="child_age">
                        <option value="" disabled>Age of youngest child</option>
                        <option value="pregnantwithkids">I’m pregnant and have kids</option>
                        <option value="pregnant">I’m pregnant with my first child</option>
                        <option value="uptothree">0 - 36 months (0 - 3 years)</option>
                        <option value="threeplus">3+ years (over 36 months)</option>
                        <option value="nochildren">No children</option>
                    </select>
                </div>
                <InputError class="mt-2" textClass="text-red-300" :message="form.errors.child_age"/>
            </div>
            <div class="mb-4">
                <div class="flex items-center gap-x-2">
                    <input type="checkbox" name="disclaimer" v-model="form.rules" required/>
                    <p class="text-sm text-gray-100 dark:text-gray-400">
                        I agree to the Sweepstakes
                        <a class="underline hover:text-blue-400" :href="rulesUrl">Rules and Registrations</a>
                    </p>
                </div>
                <InputError class="mt-2" textClass="text-red-300"
                            :message="form.errors.disclaimer"/>
            </div>
            <div class="mb-4" v-if="!$page.props.auth.user">
                <div class="flex items-center gap-x-2">
                    <input type="checkbox" name="disclaimer" v-model="form.disclaimer" required/>
                    <p class="mt-2 text-sm text-gray-100 dark:text-gray-400">
                        By entering your information and clicking Join Now, you agree to our
                        <a class="underline hover:text-blue-400" :href="route('page', 'privacy')">Privacy Policy</a>,
                        <a :href="route('page', 'terms')" class="underline hover:text-blue-400">Terms and
                            Conditions</a> and understand that we will be sending you our newsletters, offers and
                        promotions from ourselves and our partners by email. Unsubscribe at any time.</p>
                </div>
                <InputError class="mt-2" textClass="text-red-300"
                            :message="form.errors.disclaimer"/>
            </div>
            <div class="grid">
                <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-5xl text-3xl bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 sm:p-4">
                    Enter Free Now
                </button>
                <InputError class="mt-2" textClass="text-red-300"
                            :message="form.errors.recaptcha_response"/>
            </div>
            <div class="mt-4 text-center min-h-[56px]">
                <p class="text-sm font-medium text-white mb-2">Like and Share this sweeps!</p>
                <div class="fb-like" :data-href="fbShareUrl" data-width="350px" data-layout="button_count" data-action="like" data-size="large" data-share="true"></div>
            </div>
        </div>
    </form>
</template>

<style scoped>

</style>
