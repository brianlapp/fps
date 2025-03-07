<template>
    <StaticLayout>
        <Head title="Thank You"/>
        <div class="">
            <div class="text p-4 text-center text-slate-600 dark:text-gray-300 max-w-3xl mx-auto mb-4">
                <h3 class="text-2xl sm:text-4xl text-blue-400 font-bold">Thanks for joining!</h3>
               <div class="sm:text-xl">
                   You now qualify to enter for a
                   chance to win in our Exclusive, members-only, <b>Smart Start $2500 RESP Sweepstakes!</b> Sponsored by
                   CST Savings.
               </div>
            </div>
            <div class="max-w-3xl mx-auto mb-8">
                <img class="w-full rounded-lg" src="/images/cst_2500.png" alt="CST Contest">
            </div>
            <form @submit.prevent="submit" v-show="!lastChance"
                  class="max-w-3xl mx-auto w-full p-4 sm:p-8 bg-blue-50 dark:bg-gray-900 rounded-lg">
                <div class="mb-4 sm:mb-8">
                    <h2 class="text-xl sm:text-3xl text-center dark:text-gray-200">Complete the form to enter to win!</h2>
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
                            <span>Youngest baby's Birthday (or due date) *</span>
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
                <div class="mb-4">
                    <div class="flex items-center gap-x-2 article-content">
                        <input type="checkbox" name="disclaimer" v-model="form.disclaimer" required/>
                        <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                            * I agree to the Contest <a :href="route('page', 'cst-rules')" target="_blank"> Rules and
                            Regulations</a>

                        </p>
                    </div>
                    <InputError class="mt-2" textClass="text-red-600"
                                :message="form.errors.disclaimer"/>
                </div>
                <div class="mb-4">
                    <div class="flex items-center gap-x-2 article-content">
                        <input type="checkbox" name="cst_consent" v-model="form.cst_consent"/>
                        <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                            Yes! I agree to be
                            contacted at the information above by CST Savings Inc. and its affiliates to learn about
                            saving for my child's post-secondary education with a CST RESP and <strong>how I can access
                            $7,200 or more in government grants</strong>.
                            I understand I may withdraw my consent at any time. For more details see our
                            <a href="https://www.cstsavings.ca/Privacy" target="_blank">Privacy Policy</a> or
                            <a href="https://www.cstsavings.ca/contact-us" target="_blank">Contact Us</a>
                        </p>
                    </div>
                    <InputError class="mt-2" textClass="text-red-600"
                                :message="form.errors.cst_consent"/>
                </div>
                <div class="grid">
                    <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                            class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-5xl text-3xl bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 sm:p-4">
                        Enter Now
                    </button>
                </div>
            </form>
            <div class="max-w-3xl mx-auto mb-8 p-2" v-if="lastChance">
                <h2 class="text-5xl text-center dark:text-gray-200 mb-8">Wait!</h2>
                <p class="text-xl sm:text-2xl md:text-3xl text-center dark:text-gray-200">Last Chance! <strong>Click
                    YES</strong> to learn more about gaining access to <strong>$7,200</strong> in Government Grants for
                    your child's post-secondary education with a CST RESP</p>
                <div class="mb-4 p-2 article-content">
                    <p class="mt-2 text-xs text-gray-800 dark:text-gray-200 text-center">
                        I understand I may withdraw my consent at any time. For more details see our
                        <a href="https://www.cstsavings.ca/Privacy" target="_blank">Privacy Policy</a> or
                        <a href="https://www.cstsavings.ca/contact-us" target="_blank">Contact Us</a>
                    </p>
                </div>
                <div class="grid gap-y-2">
                    <button type="button" :class="{ 'opacity-25': form.processing }" @click="yes"
                            :disabled="form.processing"
                            class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-5xl text-3xl bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 sm:p-4">
                        YES!
                    </button>
                    <button type="button" :class="{ 'opacity-25': form.processing }" @click="submit(true)"
                            :disabled="form.processing"
                            class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-2xl text-xl bg-gray-500 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500 sm:p-4">
                        No, thanks
                    </button>
                </div>
            </div>
        </div>
    </StaticLayout>
</template>

<script>
import StaticLayout from "@/Layouts/StaticLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import moment from 'moment';
import {mask} from 'vue-the-mask'

export default {
    name: "TY",
    components: {InputError, Head, StaticLayout},
    directives: {mask},
    props: {
        email: String
    },
    data() {
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 6;
        const endYear = currentYear + 1;
        const yearList = [];

        for (let year = startYear; year <= endYear; year++) {
            yearList.push(year);
        }
        return {
            form: useForm({
                email: this.email,
                first_name: null,
                last_name: null,
                child_dob: null,
                postcode: null,
                phone: null,
                disclaimer: null,
                cst_consent: false,
                provider: 'csts',
                lang: 'en'
            }),
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
        }
    },
    methods: {
        submit(force = false) {
            if (!this.form.cst_consent && force !== true) {
                return this.lastChance = true;
            }
            this.form.post(route('giveaway-submit'), {
                onError: () => {
                    this.lastChance = false;
                },
            });
        },
        yes() {
            this.form.cst_consent = true;
            this.submit(true);
        }
    },
    computed: {
        months() {
            const monthList = {};

            for (let i = 0; i < 12; i++) {
                monthList[i + 1] = moment().month(i).format('MMMM');
            }

            return monthList;
        },
        dates() {
            const daysInMonth = moment(`${this.dob.year}-${this.dob.month}`, 'YYYY-MM').daysInMonth();
            const dateList = [];

            for (let day = 1; day <= daysInMonth; day++) {
                dateList.push(day);
            }

            return dateList;
        }
    },
    watch: {
        dob: {
            handler(val) {
                if (val.year && val.month && val.day) {
                    this.form.child_dob = moment(Object.values(this.dob).join('-')).format('YYYY-MM-DD');
                } else {
                    this.form.child_dob = null;
                }
            },
            deep: true
        }
    }
}
</script>
