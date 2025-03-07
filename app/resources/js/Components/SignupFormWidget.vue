<template>
    <div class="my-4 dark:bg-gray-900 bg-blue-50 rounded">
        <div class="w-full signup-widget">
            <div
                class="font-extrabold tracking-tight text-blue-400 pt-2 dark:text-slate-100 text-2xl sm:text-3xl md:text-4xl xl:font-black text-center mb-4">
                Exclusive Tips & Freebies Await!
            </div>
            <div>
                <form @submit.prevent="submit">
                <div class="bg-blue-50 dark:bg-blue-700 rounded-md py-10 px-10 t-2 mt-2">
                    <div class="mb-4">
                        <label class="block text-sm font-medium dark:text-white">
                            <span class="sr-only">First name</span>
                        </label>
                        <input
                            id="name"
                            type="text"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 focus:dark:bg-slate-800 sm:p-4 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400"
                            name="name"
                            placeholder="Name"/>
                        <InputError class="mt-2" textClass="text-red-300" :message="form.errors.name"/>
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
                    <div class="grid">
                        <button
                           class="btn-link py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-5xl text-3xl bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 sm:p-4">
                            Join Free Now
                        </button>
                        <InputError class="mt-2" textClass="text-red-300" :message="form.errors.recaptcha_response"/>
                    </div>
                </div>
                <div class="grid mt-5 mb-3">
                    <GoogleButton :url="googleUrl"/>
                </div>
                </form>
            </div>
        </div>
    </div>

</template>

<script>
import SignupForm from "@/Components/SignupForm.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import {Link, useForm, usePage} from "@inertiajs/vue3";
import GoogleButton from "@/Components/GoogleButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import SearchMixin from "@/Mixins/SearchMixin.vue";

export default {
    name: "SignupFormWidget",
    components: {Checkbox, GoogleButton, Link, InputError, TextInput, SignupForm},
    mixins: [SearchMixin],
    data() {
        const inputs = [...this.$page.props.app.trackingParams, ...['mom']],
            query = usePage().props.query,
            googleUrl = new URL(route('auth', 'google')),
            formData = {
                name: null,
                email: null,
                recaptcha_response: null
            }
        inputs.forEach(inputName => {
            if (query.hasOwnProperty(inputName)) {
                formData[inputName] = query[inputName] || '';
                googleUrl.searchParams.set(inputName, query[inputName] || '');
            }
        });
        googleUrl.searchParams.set('backto', window.location.href);

        return {
            form: useForm(formData),
            googleUrl: googleUrl.toString(),
        }
    },
    methods: {
        submit() {
            this.form.post(route('joinNewsletter', {backto: window.location.href}), {
                onError: () => {
                    this.fillRecaptchaToken();
                },
                onSuccess: () => {
                    this.fillRecaptchaToken();
                },
                preserveScroll: 'errors'
            });
        },
    }
}
</script>
