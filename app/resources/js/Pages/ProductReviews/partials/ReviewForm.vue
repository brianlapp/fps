<template>
    <div class="bg-white dark:bg-gray-800  shadow-md rounded-lg p-6 mb-6 relative">
        <Spinner v-if="form.processing"/>
        <h3 class="text-xl font-bold mb-4 dark:text-gray-400">Leave a Review</h3>
        <form @submit.prevent="submit" :class="{'opacity-20': form.processing}">
            <div class="flex flex-col gap-y-1 mb-4">
                <input
                    class="w-full p-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-lg"
                    minlength="5" maxlength="100" required
                    v-model="form.title"
                    placeholder="Write your short impression..."/>
                <InputError :message="form.errors.title"/>
            </div>
            <div class="flex flex-col gap-y-1 mb-4">
            <textarea
                class="w-full p-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-lg"
                rows="4" v-model="form.content" minlength="50" maxlength="500" required
                placeholder="Write your review..."></textarea>
                <InputError :message="form.errors.content"/>
            </div>
            <div class="flex flex-col md:flex-row justify-between md:items-center gap-y-4">
                <div class="flex items-center space-x-2">
                    <label class="font-bold dark:text-gray-400">Your Rating:</label>
                    <select
                        class="border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg px-2"
                        v-model="form.rate" required>
                        <option value="" disabled>Rate the product</option>
                        <option v-for="option in rateOptions" :value="option">{{ getStars(option) }}</option>
                    </select>
                    <InputError :message="form.errors.rate"/>
                </div>
                <button class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition duration-300"
                        type="submit">
                    Submit Review
                </button>
            </div>
            <InputError :message="form.errors.recaptcha_response"/>
        </form>
        <SignupModal v-if="showSignupModal" @close="closeSignupModal"/>
    </div>
</template>

<script>
import {useForm} from "@inertiajs/vue3";
import {useReCaptcha} from "vue-recaptcha-v3";
import InputError from "@/Components/InputError.vue";
import Spinner from "@/Components/Spinner.vue";
import SignupModal from "@/Pages/Products/partials/SignupModal.vue";
import ProductReviewsMixin from "@/Mixins/ProductReviewsMixin.vue";

export default {
    name: "ReviewForm",
    components: {SignupModal, InputError, Spinner},
    mixins: [ProductReviewsMixin],
    props: {
        productId: String
    },
    data() {
        return {
            form: useForm({
                title: null,
                content: null,
                rate: '',
                recaptcha_response: null
            }),
            recaptchaLoaded: null,
            executeRecaptcha: null,
            showSignupModal: false,
            signupFormWasShown: false
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
            if (!this.$page?.props?.auth?.user) {
                return this.openSignupModal();
            }
            this.form.post(route('product_categories.submitReview', this.productId), {
                onFinish: () => {
                    this.fillRecaptchaToken();
                },
                onSuccess: () => {
                    this.form.reset();
                    this.$emit('reviewSubmitted')
                },
                preserveScroll: 'errors'
            });
        },
        closeSignupModal() {
            this.showSignupModal = false;
        },
        openSignupModal() {
            this.showSignupModal = true;
        },
        openSignupModalOnce() {
            if (this.$page?.props?.auth?.user) {
                return;
            }
            if (!this.signupFormWasShown) {
                this.openSignupModal();
                this.signupFormWasShown = true;
            }
        }
    },
    computed: {
        rateOptions() {
            const options = [];
            for (let i = this.maxRate; i > 0; i--) {
                options.push(i);
            }

            return options;
        }
    },
    watch: {
        'form.title': {
            handler() {
                this.openSignupModalOnce();
            }
        },
        'form.content': {
            handler() {
                this.openSignupModalOnce();
            }
        },
        'form.rate': {
            handler() {
                this.openSignupModalOnce();
            }
        },
    }
}
</script>
