<template>
  <section class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden">
      <div class="md:flex">
        <!-- Image Section (1/3 width on medium screens and up) -->
        <div class="md:w-1/3">
          <img src="../assets/images/article_placeholder.png" alt="Featured Article" class="w-full h-full object-cover">
        </div>
        
        <!-- Content Section (2/3 width on medium screens and up) -->
        <div class="md:w-2/3 p-6 md:p-8 lg:p-10">
          <div class="flex items-center mb-4">
            <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-xs font-semibold px-2.5 py-0.5 rounded">Featured</span>
            <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">10 min read</span>
          </div>
          
          <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            The Complete Guide to Creating a Nurturing Bedtime Routine for Children
          </h2>
          
          <p class="text-gray-600 dark:text-gray-300 text-lg mb-6">
            A consistent bedtime routine is one of the most powerful tools parents have for helping children develop healthy sleep habits. This comprehensive guide explores the science behind effective routines and provides practical steps for creating a calming bedtime experience for children of all ages.
          </p>
          
          <div class="flex items-center mb-6">
            <img src="../assets/images/avatar.png" alt="Author" class="w-10 h-10 rounded-full mr-3">
            <div>
              <p class="text-gray-900 dark:text-white font-medium">Dr. Sarah Johnson</p>
              <p class="text-gray-500 dark:text-gray-400 text-sm">Child Development Specialist</p>
            </div>
          </div>
          
          <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition duration-200">
            Read Full Article
            <i class="fas fa-arrow-right ml-2"></i>
          </a>
        </div>
      </div>
    </div>
  </section>
</template>
