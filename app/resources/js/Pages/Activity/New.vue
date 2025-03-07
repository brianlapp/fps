<template>
    <Head v-if="seo">
        <title>{{seo.title}}</title>
        <meta head-key="description" name="description" :content="seo.headline">
        <meta head-key="og:title" property="og:title" :content="seo.title">
        <meta head-key="og:description" property="og:description" :content="seo.headline">
        <meta head-key="og:image" v-if="seo.image" property="og:image" :content="seo.image"/>
        <meta head-key="og:url" property="og:url" :content="route('activity.index')">

        <meta head-key="twitter:title" property="twitter:title" :content="seo.title">
        <meta head-key="twitter:description" property="twitter:description" :content="seo.headline">
        <meta head-key="twitter:image" v-if="seo.image" property="twitter:image" :content="seo.image">
    </Head>
    <div class="container mx-auto mt-8 p-4 max-w-3xl dark:text-gray-200">
        <div class="w-full">
            <div class="mt-6 mb-10 max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="p-4 bg-blue-gradient dark:bg-dark-blue-gradient">
                    <h1 class="text-xl uppercase font-black text-white text-center pb-6">
                        Parent-Child Activity Generator
                    </h1>
                </div>
                <div class="flex flex-col justify-between p-4">
                    <p class="md:text-lg mb-6 text-center">Generate fun and engaging activities for you and your child by filling in
                        the details below:</p>
                    <form @submit.prevent="submit" class="flex flex-col gap-y-4">
                        <div class="form-group w-full">
                            <label for="child_age" class="block mb-2 font-medium text-gray-900 dark:text-white">Child's
                                Age <span class="text-red-500 required-dot">*</span>
                            </label>
                            <select id="child_age" v-model="form.child_age" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected :value="null" :disabled></option>
                                <option v-for="({value, label}) in options.child_age" :value="label">{{ label }}
                                </option>

                            </select>
                            <InputError class="mt-2" :message="form.errors.child_age"/>
                        </div>
                        <div class="form-group w-full">
                            <label for="available_time" class="block mb-2 font-medium text-gray-900 dark:text-white">
                                Available Time <span class="text-red-500 required-dot">*</span>
                            </label>
                            <ul class="w-full font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600"
                                    v-for="({value, label}, i) in options.available_time">
                                    <div class="flex items-center ps-3">
                                        <input :id="'available_time_'.concat(i)" type="radio" :value="label"
                                               v-model="form.available_time" required name="available_time"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label :for="'available_time_'.concat(i)"
                                               class="w-full py-3 ms-2 font-medium text-gray-900 dark:text-gray-300">{{
                                                label
                                            }}</label>
                                    </div>
                                </li>
                            </ul>
                            <InputError class="mt-2" :message="form.errors.available_time"/>
                        </div>
                        <div class="form-group w-full">
                            <label for="location" class="block mb-2 font-medium text-gray-900 dark:text-white">
                                Location <span class="text-red-500 required-dot">*</span>
                            </label>
                            <ul class="w-full font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600"
                                    v-for="({value, label}, i) in options.location">
                                    <div class="flex items-center ps-3">
                                        <input :id="'location_'.concat(i)" type="radio" :value="label"
                                               v-model="form.location" required name="location"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label :for="'location_'.concat(i)"
                                               class="w-full py-3 ms-2 font-medium text-gray-900 dark:text-gray-300">{{
                                                label
                                            }}</label>
                                    </div>
                                </li>
                            </ul>
                            <InputError class="mt-2" :message="form.errors.location"/>
                        </div>
                        <div class="form-group w-full" id="activity_type_preferences">
                            <label for="activity_type_preferences"
                                   class="block mb-2 font-medium text-gray-900 dark:text-white">
                                Activity Type Preferences <span class="text-red-500 required-dot">*</span>
                            </label>
                            <ul class="w-full font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600"
                                    v-for="({value, label}, i) in options.activity_type_preferences">
                                    <div class="flex items-center ps-3">
                                        <input :id="'activity_type_preferences_'.concat(i)" type="checkbox"
                                               :value="label" v-model="form.activity_type_preferences" minlength="1"
                                               name="activity_type_preferences[]"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label :for="'activity_type_preferences_'.concat(i)"
                                               class="w-full py-3 ms-2 font-medium text-gray-900 dark:text-gray-300">{{
                                                label
                                            }}</label>
                                    </div>
                                </li>
                            </ul>
                            <InputError class="mt-2" :message="form.errors.activity_type_preferences"/>
                        </div>
                        <div class="form-group w-full">
                            <label for="available_resources"
                                   class="block mb-2 font-medium text-gray-900 dark:text-white">
                                Available resources <span class="text-red-500 required-dot">*</span>
                            </label>
                            <textarea id="available_resources" v-model="form.available_resources"
                                      :placeholder="options.available_resources" required
                                      rows="4"
                                      class="block p-2.5 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                            <InputError class="mt-2" :message="form.errors.available_resources"/>
                        </div>

                        <div class="form-group w-full">
                            <label for="special_requests" class="block mb-2 font-medium text-gray-900 dark:text-white">
                                Special Requests
                            </label>
                            <textarea id="special_requests" v-model="form.special_requests"
                                      :placeholder="options.special_requests"
                                      rows="4"
                                      class="block p-2.5 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                            <InputError class="mt-2" :message="form.errors.special_requests"/>
                        </div>

                        <div class="form-group w-full text-center mt-4">
                            <button type="submit"
                                    class="btn-link py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-2xl text-xl bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 sm:p-4">
                                Generate Activities
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <SignupModal v-if="showCtaModal" @closed="closeModal"/>
        </div>

    </div>
</template>


<script>
import DataMixin from "@/Mixins/DataMixin.vue";
import {Head, Link, useForm} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import InputError from "@/Components/InputError.vue";
import SignupModal from "@/Pages/Activity/partials/SignupModal.vue";
import ActivityMixin from "@/Mixins/ActivityMixin.vue";

export default {
    components: {SignupModal, InputError, Head, Link},
    layout: MainLayout,
    mixins: [DataMixin, ActivityMixin],
    props: {
        seo: Object,
    },
    data() {
        return {
            form: useForm({
                'child_age': null,
                'available_time': null,
                'location': null,
                'activity_type_preferences': [],
                'available_resources': null,
                'special_requests': null
            }),
            showCtaModal: false,
            ctaModalWasShown: false,
        }
    },
    methods: {
        submit() {
            this.form.clearErrors()
            if (!this.$page.props.auth.user) {
                return  this.showCtaModal = true;
            }
            if (this.form.activity_type_preferences.length < 1) {
                this.form.errors.activity_type_preferences = 'Please Select at least one item';
                return document.getElementById('activity_type_preferences').scrollIntoView({behavior: 'smooth'});
            }
            this.form.post(route('activity.create'))
        },
        closeModal() {
            this.showCtaModal = false;
        }
    },
    watch: {
        form: {
            handler() {
                if (!this.$page.props.auth.user && !this.ctaModalWasShown) {
                    this.showCtaModal = true;
                    this.ctaModalWasShown = true;
                }
            },
            deep: true
        }
    }
}
</script>

<style scoped>

</style>
