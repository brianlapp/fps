<template>
    <section class="w-full px-2 lg:px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <AuthorBanner class="mb-5"/>
            <div class="grid md:grid-cols-2 gap-4 lg:gap-6">

                <!-- Activity Generator Section with Image and Max Height (50/50) -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <!-- Top Section -->
                    <div class="p-4 bg-blue-gradient dark:bg-dark-blue-gradient"></div>

                    <!-- Image Section -->
                    <div class="p-4">
                        <img
                            src="/images/activity_banner.jpg"
                            alt="Activity Generator" class="w-full h-auto rounded-lg lg:max-h-80 object-cover">
                    </div>

                    <!-- Text Section -->
                    <div class="p-6">
                        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            <span class="block">Find Amazing</span>
                            <span class="bg-gradient-to-r from-blue-400 to-green-500 bg-clip-text text-transparent">Activities for You and Your Child</span>
                        </h1>
                        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                            Enter a few details below and let our generator do the magic! Perfect for those days when
                            you need fresh ideas.
                        </p>
                        <a :href="route('activity.index')"
                           class="block mt-6 text-center text-green-500 py-4 text-xl rounded-lg hover:bg-green-500 hover:text-white transition-all duration-300 border-2 border-green-500">
                            Try the Activity Generator
                        </a>
                    </div>
                </div>

                <!-- Article Generator Search Card (50/50) -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <div class="p-4 bg-blue-gradient-reversed dark:bg-dark-blue-gradient"></div>
                    <!-- Image Section -->
                    <div class="p-4">
                        <img
                            src="/images/search_banner.webp"
                            alt="Activity Generator" class="w-full h-auto rounded-lg lg:max-h-60 object-cover">
                    </div>
                    <!-- Text Section -->
                    <div class="p-6">
                        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            <span class="block">Get Instant Parenting Articles Tailored Just For You!</span>
                            <span class="bg-gradient-to-r from-blue-400 to-green-500 bg-clip-text text-transparent">Enter a Topic to Get Started</span>
                        </h1>
                        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                            Have a question or want to learn? Type a topic, headline or question here and we'll make you
                            a custom article explaining.
                        </p>
                        <!-- Search Form -->
                        <div
                            class="w-full mt-6 bg-transparent border rounded focus-within:border-blue-400 focus-within:ring focus-within:ring-blue-300 focus-within:ring-opacity-40 dark:border-gray-700 border-gray-300 dark:focus-within:border-blue-300 py-0 pr-0 items-center">
                            <form class="flex flex-col md:flex-row md:items-center" @submit.prevent="submit">
                                <input autofocus name="query" placeholder="Type any parenting topic.." v-model="form.query"
                                       class="flex-1 h-10 px-4 py-2 m-1 text-gray-900 text-2xl dark:text-gray-100 placeholder-gray-400 bg-transparent border-none appearance-none focus:outline-none focus:placeholder-transparent focus:ring-0">
                                <button type="submit"
                                    class="text-green-500 transition-colors duration-200 transform bg-transparent rounded hover:bg-green-500 hover:text-white h-16 focus:outline-none focus:bg-blue-400 py-2 px-6 text-xl border-2 border-green-500 rounded-t-none md:rounded-l-none md:text-2xl">
                                    DISCOVER
                                </button>
                            </form>
                        </div>
                        <!-- Disclaimer -->
                        <p class="mt-4 text-sm text-gray-500 italic">Disclaimer: Our content supports your parenting
                            journey, but it’s not a substitute for professional advice.</p>
                        <div class="text-left">
                            <InputError class="my-2 px-4" :message="form.errors.query"/>
                            <InputError class="my-2 px-4" :message="form.errors.recaptcha_response"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import InputError from "@/Components/InputError.vue";
import searchMixin from "@/Mixins/SearchMixin.vue";
import AuthorBanner from "@/Pages/Home/partials/AuthorBanner.vue";

export default {
    name: "MainGrid",
    components: {AuthorBanner, InputError},
    mixins: [searchMixin]
}
</script>
