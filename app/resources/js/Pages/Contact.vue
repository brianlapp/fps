<template>
    <MainLayout>
        <Head title="Contact Us"/>
        <div class="my-6 bg-blue-50 dark:bg-gray-800 min-h-[81vh]">
            <h1 class="text-4xl my-8 pt-8 text-gray-700 dark:text-gray-300 text-center">Contact Us</h1>
            <div class="max-w-xl mx-auto bg-blue-100 rounded dark:bg-gray-700 p-8">
                <div class="w-full mb-4">
                    <InputLabel for="name" value="Name" class="text-xl"/>
                    <TextInput v-model="form.name" class="w-full" type="text" name="name" id="name" required/>

                    <InputError class="mt-2" :message="form.errors.name"/>
                </div>

                <div class="w-full mb-4">
                    <InputLabel for="email" value="Email" class="text-xl"/>
                    <TextInput v-model="form.email" class="w-full" type="email" name="email" id="email" required/>

                    <InputError class="mt-2" :message="form.errors.email"/>
                </div>

                <div class=" mb-4">
                    <InputLabel for="topic" value="Topic" class="text-xl"/>
                    <select v-model="form.topic" required name="topic" id="topic"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-900 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option class="text-xl" v-for="topic in topics" :key="topic" :value="topic">
                            {{ topic }}
                        </option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.topic"/>
                </div>
                <div class="w-full mb-4">
                    <InputLabel for="message" value="Message" class="text-xl"/>
                    <TextareaInput v-model="form.message" class="w-full" rows="5"/>

                    <InputError class="mt-2" :message="form.errors.message"/>
                </div>

                <PrimaryButton class="text-xl" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                               @click="submit">
                    Submit
                </PrimaryButton>
                <InputError class="mt-2" :message="form.errors.recaptcha_response"/>
            </div>
        </div>
    </MainLayout>
</template>

<script>
import {Head, useForm} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useReCaptcha} from "vue-recaptcha-v3";



export default {
    name: "Contact Us",
    components: {PrimaryButton, TextInput, InputError, InputLabel, TextareaInput, MainLayout, Head},
    props: {
        content: String
    },
    data() {
        return {
            form: useForm({
                name: this.$page.props.auth.user?.name || null,
                email: this.$page.props.auth.user?.email || null,
                message: null,
                recaptcha_response: null,
                executeRecaptcha: null,
                recaptchaLoaded: null,
                topic: null,
            }),
            topics: ['Contact', 'Advertising']
        }
    },
    mounted() {
        const {executeRecaptcha, recaptchaLoaded} = useReCaptcha();
        this.recaptchaLoaded = recaptchaLoaded;
        this.executeRecaptcha = executeRecaptcha;
        this.fillRecaptchaToken();
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
            this.form.post(route('contact-us.submit'), {
                onFinish: () => {
                    this.fillRecaptchaToken();
                },
                preserveScroll: 'errors'
            });
        },
    }
}
</script>
