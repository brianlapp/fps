<template>
    <div>
        <form @submit.prevent="submit" class="flex flex-col md:flex-row md:items-center">
            <input autofocus name="query" placeholder="Type any parenting topic.." v-model="form.query"
                   class="flex-1 h-10 px-4 py-2 m-1 text-gray-900 text-2xl dark:text-gray-100 placeholder-gray-400 bg-transparent border-none appearance-none focus:outline-none focus:placeholder-transparent focus:ring-0 border-gray-300"/>
            <button
                class="text-white transition-colors duration-200 transform bg-primary-500 rounded hover:bg-primary-700 h-16 focus:outline-none focus:bg-blue-400 py-2 px-6 text-xl rounded-t-none md:rounded-l-none md:text-2xl">
                DISCOVER
            </button>
        </form>
    </div>
    <div class="text-left">
        <InputError class="my-2 px-4" :message="form.errors.query"/>
        <InputError class="my-2 px-4" :message="form.errors.recaptcha_response"/>
    </div>

</template>

<script>
import searchMixin from "@/Mixins/SearchMixin.vue";
import InputError from "@/Components/InputError.vue";

export default {
    name: "SearchForm",
    components: {InputError},
    mixins: [searchMixin]
}
</script>

