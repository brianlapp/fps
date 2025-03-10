<script setup>
import { ref, onMounted } from 'vue'

// State Management
const videoLoading = ref(true)
const videoError = ref(false)
const videoElement = ref(null)

// Event Handlers
const handleVideoLoad = () => {
  console.log('Video loaded successfully')
  videoLoading.value = false
}

const handleVideoError = (error) => {
  console.error('Video failed to load:', error)
  console.log('Video source:', videoElement.value?.currentSrc || 'No source')
  videoError.value = true
  videoLoading.value = false
}

// Lifecycle
onMounted(() => {
  console.log('Video element:', videoElement.value)
})
</script>

<template>
  <section class="hero-section bg-gradient-to-br from-blue-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden">
    <div class="max-w-7xl mx-auto px-0 sm:px-6 lg:px-8 pt-0 pb-2 md:py-16 lg:py-20">
      <div class="grid md:grid-cols-2 gap-4 md:gap-8 items-center">
        <!-- Hero Content Column -->
        <div class="order-2 md:order-1 animate-fadeIn px-4 sm:px-0">
          <h1 class="relative text-4xl sm:text-5xl md:text-6xl font-black tracking-tight leading-none mb-4">
            <div class="flex flex-col space-y-0">
              <span class="bg-gradient-to-r from-green-500 to-blue-400 bg-clip-text text-transparent pb-1">Parenting Support</span>
              <span class="bg-gradient-to-r from-green-500 to-blue-400 bg-clip-text text-transparent">at Your Fingertips</span>
            </div>
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
            
            <!-- Hero Video -->
            <div class="relative w-full aspect-video md:aspect-square overflow-hidden md:rounded-lg md:shadow-xl border-b border-gray-200 dark:border-gray-700 sm:border-0">
              <!-- Loading State -->
              <div v-if="videoLoading" class="absolute inset-0 w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent"></div>
              </div>

              <!-- Error State -->
              <div v-if="videoError" class="absolute inset-0 w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                <div class="text-center">
                  <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  <p>Failed to load video</p>
                </div>
              </div>

              <!-- Video Element -->
              <video 
                ref="videoElement"
                class="absolute inset-0 w-full h-full object-cover"
                autoplay
                loop
                muted
                playsinline
                @loadeddata="handleVideoLoad"
                @error="handleVideoError"
              >
                <source src="https://i.imgur.com/IOBO9ZC.mp4" type="video/mp4">
                Your browser does not support the video tag.
              </video>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
