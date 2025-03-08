<script setup>
// Using images from public directory
</script>

<template>
  <section class="hero-section bg-gradient-to-br from-blue-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-16 lg:py-20">
      <div class="grid md:grid-cols-2 gap-8 items-center">
        <!-- Hero Content Column -->
        <div class="order-2 md:order-1 animate-fadeIn">
          <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight">
            <span class="block bg-gradient-to-r from-green-500 to-blue-400 bg-clip-text text-transparent">Parenting Support</span>
            <span class="block bg-gradient-to-r from-green-500 to-blue-400 bg-clip-text text-transparent mt-2">at Your Fingertips</span>
          </h1>
          <p class="mt-4 text-lg md:text-xl text-gray-600 dark:text-gray-300 max-w-2xl animate-fadeIn animation-delay-200">
            Free tools, activities, and expert advice for parents at every stage of your family's journey.
          </p>
          
          <!-- Search Component -->
          <div class="mt-8 animate-fadeIn animation-delay-300">
            <div class="w-full">
              <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Find resources for your parenting journey:</h2>
              <form class="flex flex-col sm:flex-row">
                <div class="relative flex-grow">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa fa-search text-gray-400"></i>
                  </div>
                  <input 
                    type="text" 
                    class="block w-full p-4 pl-10 text-lg text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400" 
                    placeholder="Search for parenting resources..."
                    aria-label="Search for parenting resources"
                  >
                </div>
                <button 
                  type="button"
                  class="mt-2 sm:mt-0 sm:ml-2 px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800"
                >
                  <span>Search</span>
                  <i class="fa fa-arrow-right ml-2"></i>
                </button>
              </form>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Try: "toddler sleep tips", "healthy snacks", "screen time"</p>
            </div>
          </div>
          
          <!-- Feature Quick Links -->
          <div class="mt-8 animate-fadeIn animation-delay-400">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Popular features:</h3>
            <div class="flex flex-wrap gap-3">
              <a 
                href="#"
                class="flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 group"
              >
                <i class="fas fa-gamepad text-blue-500 dark:text-blue-400 mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                <span class="text-gray-800 dark:text-gray-200">Activity Generator</span>
              </a>
              <a 
                href="#"
                class="flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 group"
              >
                <i class="fas fa-newspaper text-blue-500 dark:text-blue-400 mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                <span class="text-gray-800 dark:text-gray-200">Parenting Articles</span>
              </a>
              <a 
                href="#"
                class="flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 group"
              >
                <i class="fas fa-star text-blue-500 dark:text-blue-400 mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                <span class="text-gray-800 dark:text-gray-200">Product Reviews</span>
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">New</span>
              </a>
              <a 
                href="#"
                class="flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 group"
              >
                <i class="fas fa-list text-blue-500 dark:text-blue-400 mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                <span class="text-gray-800 dark:text-gray-200">Baby Checklist</span>
              </a>
            </div>
          </div>
        </div>
        
        <!-- Hero Image Column -->
        <div class="order-1 md:order-2 animate-fadeIn animation-delay-100">
          <div class="relative">
            <!-- Decorative elements -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-100 dark:bg-blue-900 rounded-full opacity-50 blur-xl"></div>
            <div class="absolute -bottom-5 -left-5 w-24 h-24 bg-green-100 dark:bg-green-900 rounded-full opacity-50 blur-xl"></div>
            
            <!-- Main image with fallback -->
            <img 
              src="/images/hero-family-new.jpg" 
              alt="Happy family enjoying quality time together" 
              class="rounded-xl shadow-lg w-full h-auto object-cover max-h-[500px] relative z-10 transform transition-transform duration-700 hover:scale-[1.02]"
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
