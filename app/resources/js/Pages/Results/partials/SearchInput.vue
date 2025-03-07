<template>
    <div class="w-full">
        <div class="w-full">
            <form class="flex w-full" @submit.prevent="submit">
                <input type="text" name="query" v-model="form.query"
                       class="flex-grow p-2 border border-gray-300 rounded-l-md focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="Search...">
                <PrimaryButton type="submit" class="rounded-l-none rounded-r w-fit">Search
                </PrimaryButton>
            </form>
        </div>
        <div class="text-left w-full">
            <InputError class="my-2 px-4" :message="form.errors.query"/>
            <InputError class="my-2 px-4" :message="form.errors.recaptcha_response"/>
        </div>
    </div>
</template>

<script>
import searchMixin from "@/Mixins/SearchMixin.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
    name: "SearchInput",
    props: {
        query: {
            type: String,
            default: null
        }
    },
    components: {PrimaryButton, InputError},
    mixins: [searchMixin],
    mounted() {
        this.form.query = this.query ?? null;
    }
}
</script>

