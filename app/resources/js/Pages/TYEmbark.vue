<template>
    <StaticLayout>
        <Head title="Thank You"/>
        <div class="">
            <div
                class="max-w-3xl mx-auto bg-blue-50 dark:bg-gray-800 border rounded-lg mt-3 overflow-hidden p-6 text-center mb-8"
                v-if="!standalone">
                <h3 class="text-2xl sm:text-4xl text-blue-400 font-bold mb-4">Thanks for joining!</h3>
                <div class="text-lg sm:text-xl text-gray-600 dark:text-gray-300">
                    You now qualify to enter for a
                    chance to win in our Exclusive, members-only, <b>Smart Start $2500 RESP Sweepstakes!</b> Sponsored
                    by Embark Student Corp.
                </div>
            </div>
            <div class="max-w-3xl mx-auto mb-8" :class="{'mt-7': standalone}">
                <img class="w-full rounded-lg" src="/images/embark.png" alt="Embark Contest">
            </div>
            <form @submit.prevent="submit" v-show="!lastChance"
                  class="max-w-3xl mx-auto w-full p-4 sm:p-8 bg-blue-50 dark:bg-gray-900 rounded-lg mb-8">
                <div class="mb-4 sm:mb-8">
                    <h2 class="text-xl sm:text-3xl text-center dark:text-gray-200">Complete the form to enter to
                        win!</h2>
                </div>
                <div class="mb-4 flex flex-col gap-y-2 sm:flex-row sm:gap-x-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium dark:text-white">
                            <span class="sr-only">First name</span>
                        </label>
                        <input
                            id="first_name"
                            type="text"
                            v-model="form.first_name"
                            required
                            autocomplete="first_name"
                            class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 focus:dark:bg-slate-800 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                            name="first_name"
                            placeholder="First name *"/>
                        <InputError class="mt-2" textClass="text-red-600" :message="form.errors.first_name"/>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium dark:text-white">
                            <span class="sr-only">Last name</span>
                        </label>
                        <input
                            id="last_name"
                            type="text"
                            v-model="form.last_name"
                            required
                            autocomplete="last_name"
                            class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 focus:dark:bg-slate-800 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                            name="last_name"
                            placeholder="Last name *"/>
                        <InputError class="mt-2" textClass="text-red-600" :message="form.errors.last_name"/>
                    </div>
                </div>
                <div class="mb-4 flex flex-col gap-y-2 sm:flex-row sm:gap-x-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium dark:text-white required">
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
                            placeholder="Postal Code *"/>
                        <InputError class="mt-2" textClass="text-red-600" :message="form.errors.postcode"/>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium dark:text-white">
                            <span class="sr-only">Phone</span>
                        </label>
                        <input
                            id="phone"
                            type="text"
                            v-model="form.phone"
                            v-mask="'(###) ###-####'"
                            required
                            autocomplete="phone"
                            class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 focus:dark:bg-slate-800 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                            name="phone"
                            placeholder="Phone *"/>
                        <InputError class="mt-2" textClass="text-red-600" :message="form.errors.phone"/>
                    </div>
                </div>
                <div class="mb-4 flex flex-col gap-y-2 sm:flex-row sm:gap-x-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium dark:text-white">
                            <span class="sr-only">Email</span>
                        </label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="email"
                            class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 focus:dark:bg-slate-800 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                            name="email"
                            placeholder="Email *"/>
                        <InputError class="mt-2" textClass="text-red-600" :message="form.errors.email"/>
                    </div>
                    <div class="flex-1">
                        <!--                        <label class="block text-sm font-medium dark:text-white">-->
                        <!--                            <span class="sr-only">Language</span>-->
                        <!--                        </label>-->
                        <!--                        <select v-model="form.lang" required-->
                        <!--                                class="w-full text-sm rounded-border-gray-200 rounded-md font-medium dark:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:p-4 dark:bg-slate-800 dark:border-gray-700">-->
                        <!--                            <option value="" disabled>Language *</option>-->
                        <!--                            <option v-for="(label, code) in languages" :key="code" :value="code">{{ label }}</option>-->
                        <!--                        </select>-->
                        <!--                        <InputError class="mt-2" textClass="text-red-600" :message="form.errors.lang"/>-->
                    </div>
                </div>
                <div
                    class="mb-4 flex flex-col gap-y-2 sm:flex-row sm:gap-x-2 items-center dark:bg-slate-800 dark:border-gray-700 rounded-lg p-2 bg-white">
                    <div class="flex-1 ml-2">
                        <label class="block  font-medium required dark:text-gray-400 text-gray-600">
                            <span>Baby's Birthday *</span>
                        </label>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-row gap-x-2 text-gray-600">
                            <select v-model="dob.year" required
                                    class="text-sm rounded-border-gray-200 rounded-md font-medium dark:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:p-4 dark:bg-slate-900 dark:border-gray-700 w-1/3 bg-blue-50">
                                <option value="" disabled>Year</option>
                                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                            </select>
                            <select v-model="dob.month" required
                                    class="text-sm rounded-border-gray-200 rounded-md font-medium dark:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:p-4 dark:bg-slate-900 dark:border-gray-700 w-1/3 bg-blue-50"
                                    :disabled="!dob.year">
                                <option value="" disabled>Month</option>
                                <option v-for="(label, number) in months" :key="number" :value="number">{{ label }}
                                </option>
                            </select>
                            <select v-model="dob.day" required
                                    class="text-sm rounded-border-gray-200 rounded-md font-medium dark:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:p-4 dark:bg-slate-900 dark:border-gray-700 w-1/3 bg-blue-50"
                                    :disabled="!dob.month">
                                <option value="" disabled>Day</option>
                                <option v-for="(date) in dates" :key="date" :value="date">{{ date }}</option>
                            </select>
                        </div>
                        <input v-model="form.child_dob" type="hidden" name="child_dob" required/>
                        <InputError class="mt-2" textClass="text-red-600" :message="form.errors.child_dob"/>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="flex items-center gap-x-2 article-content">
                        <input type="checkbox" name="disclaimer" v-model="form.disclaimer" required/>
                        <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                            * I agree to the Contest <a :href="route('page', 'embark-rules')" target="_blank"> Rules and
                            Regulations</a>

                        </p>
                    </div>
                    <InputError class="mt-2" textClass="text-red-600"
                                :message="form.errors.disclaimer"/>
                </div>
                <div class="mb-2">
                    <div class="flex items-center gap-x-2 article-content">
                        <input type="checkbox" name="embark_consent" required v-model="form.embark_consent"/>
                        <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                            * Yes, I agree to receive commercial electronic messages and phone calls about the products
                            and services offered by <a href="https://www.embark.ca/" target="_blank">Embark Student
                            Corp</a>. and Embark Student Foundation. I understand that I can withdraw my consent at any
                            time. We respect your privacy; no information will be shared with 3rd parties. Please refer
                            to our <a href="https://www.embark.ca/legal" target="_blank">Terms of Use</a>,&nbsp;<a
                            :href="route('page', 'embark-rules')" target="_blank">Contest
                            Rules</a>&nbsp; and <a href="https://www.embark.ca/privacy-policy" target="_blank">Privacy
                            Policy</a> for more details <a href="https://www.embark.ca/contact-us" target="_blank">Contact
                            Us</a>
                        </p>
                    </div>
                    <InputError class="mt-2" textClass="text-red-600"
                                :message="form.errors.embark_consent"/>
                </div>
                <div class="mb-4" v-if="standalone && !$page.props.auth.user">
                    <div class="flex items-center gap-x-2 article-content">
                        <input type="checkbox" name="disclaimer" v-model="form.fps_disclaimer" required/>
                        <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                            * By entering your information and clicking Join Now, you agree to our <a
                            class="underline hover:text-blue-400" :href="route('page', 'privacy')">Privacy Policy</a> ,
                            <a :href="route('page', 'terms')" class="underline hover:text-blue-400">Terms and
                                Conditions</a> and understand that we will be sending you our newsletters, offers and
                            promotions from ourselves and our partners by email. Unsubscribe at any time.</p>
                    </div>
                    <InputError class="mt-2" textClass="text-red-300"
                                :message="form.errors.fps_disclaimer"/>
                </div>
                <div class="grid">
                    <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                            class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-5xl text-3xl bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 sm:p-4">
                        Enter Now
                    </button>
                </div>
            </form>
        </div>
    </StaticLayout>
</template>

<script>
import StaticLayout from "@/Layouts/StaticLayout.vue";
import {Head, useForm, usePage} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import moment from 'moment';
import {mask} from 'vue-the-mask'

export default {
    name: "TY",
    components: {InputError, Head, StaticLayout},
    directives: {mask},
    props: {
        email: String,
        standalone: {
            type: Boolean,
            default: false
        },
    },
    mounted() {
        EfImpression(this.trackingQuery);
        EfClick(this.trackingQuery);
        this.additionalFb.forEach(id => {
            fbq('init', id);
        });
        fbq('track', 'PageView');
    },
    data() {
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 6;
        const endYear = currentYear;
        const yearList = [],
            inputs = [...usePage().props.app.trackingParams, ...['first_name', 'last_name', 'email']],
            formConfig = {
                email: this.email,
                first_name: null,
                last_name: null,
                child_dob: null,
                postcode: null,
                phone: null,
                disclaimer: null,
                embark_consent: false,
                provider: 'embark',
                lang: 'en',
                embark_standalone: this.standalone,
                fps_disclaimer: null
            };

        inputs.forEach(inputName => {
            formConfig[inputName] = this.$page.props.query[inputName] || null;
        });

        for (let year = startYear; year <= endYear; year++) {
            yearList.push(year);
        }

        return {
            form: useForm(formConfig),
            dob: {
                year: '',
                month: '',
                day: '',
            },
            years: yearList,
            languages: {
                'en': 'English',
                'fr': 'French',
            },
            lastChance: false,
            trackingQuery: {
                offer_id: this.standalone ? 1981 : 1983,
                aff_id: this.standalone ? null : 2628,
            },
            additionalFb: [
                1123350169050630,
                1252921529108623,
                648990966268274
            ]
        }
    },
    methods: {
        submit(force = false) {
            if (!this.form.embark_consent && force !== true) {
                return this.lastChance = true;
            }
            this.form.post(route('giveaway-submit'), {
                onSuccess: () => {
                    if (this.standalone && !this.form.hasErrors) {
                        fbq('track', 'Lead');
                    }
                    EfConversion(this.trackingQuery);
                    fbq('track', 'Contact');
                    fbq('track', 'Purchase');
                },
                onError: () => {
                    if (this.standalone && !this.form.hasErrors) {
                        EfConversion(this.trackingQuery);
                    }
                    this.lastChance = false;
                },
            });
        },
        yes() {
            this.form.embark_consent = true;
            this.submit(true);
        }
    },
    computed: {
        months() {
            const monthList = {};

            for (let i = 0; i < 12; i++) {
                if (this.dob.year < moment().year() || i <= moment().month()) {
                    monthList[i + 1] = moment().month(i).format('MMMM');
                }
            }

            return monthList;
        },
        dates() {
            const daysInMonth = moment(`${this.dob.year}-${this.dob.month}`, 'YYYY-MM').daysInMonth();
            const dateList = [];
            console.error(this.dob.month, moment().month())
            for (let day = 1; day <= daysInMonth; day++) {
                if (this.dob.year < moment().year() || this.dob.month < (moment().month() + 1) || day <= moment().date()) {
                    dateList.push(day);
                }
            }

            return dateList;
        }
    },
    watch: {
        dob: {
            handler(val) {

                if (this.dob.year === moment().year() && this.dob.month >= (moment().month() + 1) && this.dob.day > moment().date()) {
                    this.dob.day = null;
                }
                if (this.dob.year === moment().year() && this.dob.month > (moment().month() + 1)) {
                    this.dob.month = null;
                }
                if (val.year && val.month && val.day) {
                    this.form.child_dob = Object.values(this.dob).map(v => parseInt(v) < 10 ? '0'.concat(v) : v).join('-');
                } else {
                    this.form.child_dob = null;
                }
            },
            deep: true
        }
    }
}
</script>
