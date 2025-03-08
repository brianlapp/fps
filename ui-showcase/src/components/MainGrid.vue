<script setup>
// Using images from public directory
</script>

<template>
  <section class="w-full px-2 lg:px-4 py-8">
    <div class="max-w-7xl mx-auto">
      <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden p-6 mb-6">
        <div class="flex items-center space-x-4 mb-4">
          <img src="/images/user_placeholder.webp" alt="Author" class="w-12 h-12 rounded-full">
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Join Our Community</h3>
            <p class="text-gray-600 dark:text-gray-300">Connect with other parents and share your experiences</p>
          </div>
        </div>
        <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
          Sign Up Now
        </button>
      </div>
      
      <div class="grid md:grid-cols-2 gap-4 lg:gap-6">
        <!-- Activity Generator Section -->
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
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
              <span class="block bg-gradient-to-r from-green-500 to-blue-400 bg-clip-text text-transparent">Find Amazing</span>
              <span class="block bg-gradient-to-r from-green-500 to-blue-400 bg-clip-text text-transparent mt-2">Activities for You and Your Child</span>
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
              Enter a few details below and let our generator do the magic! Perfect for those days when
              you need fresh ideas.
            </p>
            <a href="#"
              class="block mt-6 text-center text-green-500 py-4 text-xl rounded-lg hover:bg-green-500 hover:text-white transition-all duration-300 border-2 border-green-500">
              Try the Activity Generator
            </a>
          </div>
        </div>

        <!-- Article Search Card -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
          <div class="p-4 bg-blue-gradient-reversed dark:bg-dark-blue-gradient"></div>
          <!-- Image Section -->
          <div class="p-4">
            <img
              src="/images/banner.png"
              alt="Article Search" class="w-full h-auto rounded-lg lg:max-h-60 object-cover">
          </div>
          
          <!-- Text Section -->
          <div class="p-6">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
              <span class="block">Discover</span>
              <span class="bg-gradient-to-r from-green-500 to-blue-400 bg-clip-text text-transparent">Expert Parenting Articles</span>
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
              Browse our collection of articles written by parenting experts and fellow parents. Find answers to your most pressing questions.
            </p>
            <a href="#"
              class="block mt-6 text-center text-blue-500 py-4 text-xl rounded-lg hover:bg-blue-500 hover:text-white transition-all duration-300 border-2 border-blue-500">
              Browse Articles
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
