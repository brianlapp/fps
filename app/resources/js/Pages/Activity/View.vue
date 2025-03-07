<template>
    <Head>
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
    <div class="container mx-auto mt-8 p-4 flex flex-col md:flex-row md: gap-x-4 dark:text-gray-200">
        <div class="w-full">
            <div class="flex flex-col gap-y-4 w-full p-4 bg-white dark:bg-gray-700 rounded">
                <h2 class="text-2xl mb-4 text-center">Request:</h2>
                <div class="flex justify-between gap-x-4">
                    <b>Child's age:</b>
                    <div>{{ record.child_age }}</div>
                </div>
                <div class="flex justify-between gap-x-4">
                    <b>Available Time:</b>
                    <div>{{ record.available_time }}</div>
                </div>
                <div class="flex justify-between gap-x-4">
                    <b>Location:</b>
                    <div>{{ record.location }}</div>
                </div>
                <div class="flex justify-between gap-x-4">
                    <b>Available resources:</b>
                    <div>{{ record.available_resources }}</div>
                </div>
                <div class="flex justify-between gap-x-4" v-if="record.special_requests">
                    <b>Special requests:</b>
                    <div>{{ record.special_requests }}</div>
                </div>
            </div>
            <div class="h-[calc(50vh)] bg-white p-8 mt-8 shadow-md rounded-md dark:bg-gray-800 flex flex-col justify-center" v-if="status === 'generating'">
                <div class="text-center flex flex-col justify-end gap-y-12">
                    <div class="relative">
                        <Spinner/>
                    </div>
                    <div class="my-6">
                        Your Activity Plan is being generated...
                    </div>

                </div>
            </div>
            <div v-else-if="status === 'error'" class="mt-8 bg-white p-4 shadow-md rounded-md dark:bg-gray-800">
                <h2 class="text-2xl mb-4 text-center text-red-600">Error!</h2>
                <p class="text-center">Sorry, something went wrong!</p>
                <div class="w-full text-center">
                    <a :href="route('activity.new')"
                       class="text-center inline-block text-white rounded-lg text-2xl py-3 bg-primary-500 font-bold mt-4 hover:bg-primary-400 w-fit px-12">
                        Try Again!
                    </a>
                </div>
            </div>
            <div v-else-if="status === 'success'" class="mt-8 bg-white p-4 shadow-md rounded-md dark:bg-gray-800">
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed mb-8 article-content marked" v-html="result"/>

                <div class="w-full text-center mt-4 p-4" >
                    <a :href="route('activity.new')"
                            class="text-center inline-block text-white rounded-lg text-2xl py-3 bg-primary-500 font-bold mt-4 hover:bg-primary-400 w-fit px-12">
                       Generate another One!
                    </a>
                </div>
            </div>

        </div>
    </div>
</template>


<script>
import DataMixin from "@/Mixins/DataMixin.vue";
import {Head, Link} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import Spinner from "@/Components/Spinner.vue";
import {marked} from 'marked';

export default {
    components: {Head, Link, Spinner},
    layout: MainLayout,
    mixins: [DataMixin],
    props: {
        record: Object,
        seo: Object,
    },
    data() {
        return {
            result: this.record.result ? marked.parse(this.record.result) : null,
            status: this.record.status,
            ping: this.record.status === 'generating' ? setInterval(() => {
                this.checkStatus()
            }, 1000) : null
        }
    },
    methods: {
        checkStatus() {
            axios.get(route('activity.status', this.record.uuid)).then(({data}) => {
                this.result = data.result ? marked.parse(data.result) : null;
                this.record.result = data.result;
                this.status = data.status;
                if (this.status !== 'generating') {
                    clearInterval(this.ping);
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
