<script>
import StringMixin from "@/Mixins/StringMixin.vue";

export default {
    name: "RecentSearches",
    mixins: [StringMixin],
    props: {
        latestSearches: {
            type: Array
        }
    }
}
</script>

<template>
    <div
        class="grid grid-cols-1 gap-y-8 gap-x-16 max-w-screen-xl  py-16 lg:grid-cols-2 lg:items-center lg:px-8 2xl:max-w-7xl 2xl:mx-auto">
        <div class="max-w-lg px-4 mx-auto text-center lg:text-left lg:mx-0 lg:px-0 text-gray-900 dark:text-gray-100">
            <h2 class="text-3xl font-bold">Recent Searches</h2>
            <p class="mt-4 text-lg">
                Explore the latest questions and topics from our community of parents. Stay updated and find answers to your parenting queries
            </p>
            <a class="flex px-5 py-3 mt-8 font-medium rounded bg-primary-500 text-white items-center justify-between w-56 mx-auto lg:mx-0"
               :href="route('signup')">
                Start Searching
                <div class="order-last">
                    <div>
                        <svg stroke="currentColor" fill="currentColor"
                             stroke-width="0" viewBox="0 0 448 512"
                             height="1em" width="1em"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M448 80v352c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48zm-88 16H248.029c-21.313 0-32.08 25.861-16.971 40.971l31.984 31.987L67.515 364.485c-4.686 4.686-4.686 12.284 0 16.971l31.029 31.029c4.687 4.686 12.285 4.686 16.971 0l195.526-195.526 31.988 31.991C358.058 263.977 384 253.425 384 231.979V120c0-13.255-10.745-24-24-24z"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
            <a class="p-4  border border-gray-100 shadow-sm rounded dark:border-gray-900 dark:text-gray-300 text-gray-900 hover:bg-gray-50 hover:dark:bg-gray-600 font-medium justify-center flex flex-col"
               v-for="(request, i) in latestSearches" :key="request.id" :href="request.url"  :class="{'dark:bg-gray-800 bg-blue-50' : !isOdd(i), 'dark:bg-gray-700 bg-white': isOdd(i)}">
                {{ request.search_query }}
            </a>
        </div>
    </div>
</template>

<style scoped>

</style>
