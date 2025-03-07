<script>

import {useForm} from "@inertiajs/vue3";
import {useReCaptcha} from "vue-recaptcha-v3";


export default {
    data() {
        return {
            form: useForm({
                query: null,
                recaptcha_response: null
            }),
            recaptchaLoaded: null,
            executeRecaptcha: null
        }
    },
    mounted() {
        const {executeRecaptcha, recaptchaLoaded} = useReCaptcha();
        this.executeRecaptcha = executeRecaptcha;
        this.recaptchaLoaded = recaptchaLoaded;
        this.fillRecaptchaToken()
    },
    methods: {
        async getRecaptchaToken() {

            await this.recaptchaLoaded();

            return await this.executeRecaptcha('login');
        },
        fillRecaptchaToken() {
            this.getRecaptchaToken().then(token => {
                this.form.recaptcha_response = token;
            });
        },
        submit() {
            this.form.post(route('search.submit'), {
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
