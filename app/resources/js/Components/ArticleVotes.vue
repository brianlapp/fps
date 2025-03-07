<template>
    <div class="bg-white p-4 sm:p-8 shadow-md rounded-md dark:bg-gray-800 mt-6">
        <h2 class="text-xl md:text-2xl font-extrabold text-center mb-6 md:mb-12 dark:text-gray-200">Did you find this article helpful?</h2>
        <div class="flex gap-x-8 md:gap-x-15 w-full justify-center align-middle px-4 text-4xl md:text-6xl h-[4.5rem]">
            <div v-for="item in rates" :key="'rate-'.concat(item.rate)"
                 class="cursor-pointer md:px-[1.15rem] transition-all duration-200" @click="vote(item.rate)"
            :class="{'hover:text-8xl hover:px-0 hover:-mt-4 ' : !item.isMy, 'opacity-50': item.isMy}">
                {{item.emoji}}
            </div>
        </div>
        <div class="text-xl text-green-600 text-center mt-6" v-if="jusVoted">Thank you for your feedback!</div>
    </div>
</template>

<script>
import {useReCaptcha} from "vue-recaptcha-v3";

export default {
    name: 'ArticleVotes',
    props: {
        article: Object
    },
    data() {
        return {
            recaptchaLoaded: null,
            executeRecaptcha: null,
            emojis: {
                1: '👎',
                2: '🤨',
                3: '😐',
                4: '👍',
                5: '❤️',
            },
            jusVoted: false
        }
    },
    mounted() {
        const {executeRecaptcha, recaptchaLoaded} = useReCaptcha();
        this.recaptchaLoaded = recaptchaLoaded;
        this.executeRecaptcha = executeRecaptcha;
    },
    computed: {
        rates() {
            const rates = [];
            for (let i = this.article.vote_cnf.max + 1; --i; i > this.article.vote_cnf.min) {
                rates.push({
                    rate: i,
                    emoji: this.emojis[i],
                    cnt: this.article.vote_stat ? this.article.vote_stat[i] : 0,
                    isMy: this.article.my_vote === i
                });
            }

            return rates;
        }
    },
    methods: {
        vote(rate) {
            this.article.vote_stat = this.article.vote_stat || {};
            this.article.vote_cnt = this.article.my_vote ? this.article.vote_cnt : this.article.vote_cnt + 1;
            this.article.vote_stat[rate] = this.article.my_vote ? this.article.vote_cnt : this.article.vote_cnt + 1;
            this.article.my_vote = rate;

            return this.recaptchaLoaded().then(() => {
                return this.executeRecaptcha('login');
            }).then(token => {
                return axios.post(route('articles.vote', this.article.slug), {
                    vote: rate,
                    recaptcha_response: token
                });
            }).then(() => {
                this.jusVoted = true;
            }).catch(() => {
                this.$page.props.flash.error = 'Something went Wrong!'
            });
        }
    }
}
</script>

<style scoped>

</style>
