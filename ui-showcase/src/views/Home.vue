<template>
  <div class="min-h-screen">
    <!-- UI Preview Notice -->
    <div class="ui-notice">
      <strong>UI Preview Only:</strong> This is a static preview of the Free Parent Search UI. Backend functionality is not available in this deployment.
    </div>
    
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <img src="/images/logo.png" alt="Free Parent Search" class="h-8 w-auto">
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <a href="#" class="border-blue-500 text-gray-900 dark:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Home
              </a>
              <a href="#" class="border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Resources
              </a>
              <a href="#" class="border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Articles
              </a>
              <a href="#" class="border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                About
              </a>
            </div>
          </div>
          <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <button class="bg-white dark:bg-gray-800 p-1 rounded-full text-gray-400 hover:text-gray-500 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              <span class="sr-only">View notifications</span>
              <i class="fas fa-bell"></i>
            </button>
            <div class="ml-3 relative">
              <div>
                <button class="bg-white dark:bg-gray-800 rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  <span class="sr-only">Open user menu</span>
                  <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                </button>
              </div>
            </div>
          </div>
          <div class="-mr-2 flex items-center sm:hidden">
            <button type="button" class="bg-white dark:bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
              <span class="sr-only">Open main menu</span>
              <i class="fas fa-bars"></i>
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Unit -->
    <HeroUnit />
    
    <!-- Main Content -->
    <main>
      <!-- Main Grid -->
      <MainGrid />
      
      <!-- Featured Articles -->
      <FeaturedArticles />
      
      <!-- Categories Section -->
      <section class="py-6 dark:bg-gray-800 bg-white sm:py-8 lg:py-12 w-full mt-20 mb-12">
        <Categories />
      </section>
      
      <!-- Featured Article -->
      <FeaturedArticle />
      
      <!-- Our Story -->
      <OurStory />
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <div class="mb-4 md:mb-0">
            <img src="/images/logo.png" alt="Free Parent Search" class="h-8 w-auto mb-2">
            <p class="text-gray-400">Connecting families with essential resources</p>
          </div>
          <div class="flex space-x-4">
            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
        <hr class="border-gray-700 my-6">
        <div class="text-center text-gray-400">
          <p>&copy; 2025 Free Parent Search. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import HeroUnit from '../components/HeroUnit.vue';
import MainGrid from '../components/MainGrid.vue';
import FeaturedArticles from '../components/FeaturedArticles.vue';
import Categories from '../components/Categories.vue';
import FeaturedArticle from '../components/FeaturedArticle.vue';
import OurStory from '../components/OurStory.vue';
</script>

<style>
.ui-notice {
  background-color: #f0f9ff;
  color: #0369a1;
  text-align: center;
  padding: 0.5rem;
  font-size: 0.875rem;
}

@media (prefers-color-scheme: dark) {
  .ui-notice {
    background-color: #075985;
    color: #e0f2fe;
  }
}
</style>
