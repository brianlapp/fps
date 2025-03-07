<template>
    <section class="w-full px-4 lg:px-8">
        <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
            <!-- Top Section (Gradient Border) -->
            <div class="p-2 bg-blue-gradient dark:bg-dark-blue-gradient"></div>

            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-2 gap-4">

                <!-- Image Section (Image on top for mobile, on the right for desktop) -->
                <div class="lg:hidden">
                    <img src="https://storage.googleapis.com/newsearch-storage/production/storage/media/286/responsive-images/media-library4uI2kz___webp_1280_731.webp" alt="Exciting Baby Names for 2025" class="w-full h-auto object-cover">
                </div>

                <!-- Text Section -->
                <div class="p-6 flex flex-col justify-center">

                    <!-- Sub-title (Category) -->
                    <p class="text-sm font-semibold uppercase tracking-wide text-blue-500 mb-2">
                        Featured Article
                    </p>

                    <!-- Main Title -->
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        <span class="block">Exciting Baby Names for 2025</span>
                        <span class="bg-gradient-to-r from-blue-400 to-green-500 bg-clip-text text-transparent">Trends, Tips, and Unique Picks</span>
                    </h1>

                    <!-- Description -->
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                        Choosing a baby name can feel like a monumental task, especially with 2025 just around the corner. Find the perfect name that stands out yet feels timeless!
                    </p>

                    <!-- Call to Action -->
                    <a href="https://freeparentsearch.com/a/exciting-baby-names-for-2025-trends-tips-and-unique-picks" class="block mt-6 text-center text-green-500 py-2 text-xl rounded-lg hover:bg-green-500 hover:text-white transition-all duration-300 border-2 border-green-500">
                        Read More
                    </a>
                </div>

                <!-- Image Section (Desktop version) -->
                <div class="hidden lg:block">
                    <img src="https://storage.googleapis.com/newsearch-storage/production/storage/media/286/responsive-images/media-library4uI2kz___webp_1280_731.webp" alt="Exciting Baby Names for 2025" class="w-full h-full object-cover">
                </div>
            </div>


        </div>
    </section>
</template>

<script>
export default {
    name: "FeaturedArticle"
}
</script>

