<template>
    <section class="w-full px-4 lg:px-8 py-12">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">

            <!-- Top Section (Gradient Border) -->
            <div class="p-2 bg-blue-gradient dark:bg-dark-blue-gradient"></div>

            <!-- Text Section -->
            <div class="p-8 flex flex-col justify-center">

                <!-- Sub-title (Category) -->
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-500 mb-2 text-center">
                    About Us
                </p>

                <!-- Main Title with Gradient -->
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-center text-gray-900 dark:text-white mb-6">
                    <span class="block">Our Story</span>
                    <span class="bg-gradient-to-r from-blue-400 to-green-500 bg-clip-text text-transparent">Who We Are and What We Do</span>
                </h1>

                <!-- Description -->
                <p class="text-lg text-gray-600 dark:text-gray-400 text-center mb-4">
                    <strong>About FreeParentSearch.com</strong><br>
                    FreeParentSearch.com is a revolutionary platform designed to provide parents with the information, resources, and community they need, all in one place. Leveraging the power of AI, we create tailored content that addresses the specific needs and interests of our users, ensuring that every visit to our site is valuable and relevant.
                </p>

                <p class="text-lg text-gray-600 dark:text-gray-400 text-center">
                    <strong>Our Mission</strong><br>
                    Our mission is to support parents by providing them with accurate, personalized, and timely information. Whether you're a new parent or expecting, our goal is to help you navigate the journey of parenthood with confidence and ease.
                </p>

            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "OurStory"
}
</script>

