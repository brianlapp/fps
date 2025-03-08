<template>
  <section class="py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
          <span class="block">Trending Articles</span>
          <span class="block text-blue-500 dark:text-blue-400">From Our Experts</span>
        </h2>
        <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-600 dark:text-gray-300">
          Stay up-to-date with the latest parenting advice and tips from our community of experts.
        </p>
      </div>

      <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        <!-- Article Card 1 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:transform hover:scale-[1.02]">
          <img src="/images/toddler.png" alt="Toddler Development" class="w-full h-48 object-cover">
          <div class="p-6">
            <div class="flex items-center mb-2">
              <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-xs font-semibold px-2.5 py-0.5 rounded">Toddlers</span>
              <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">5 min read</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">10 Activities to Boost Your Toddler's Development</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Discover simple, engaging activities that promote cognitive, physical, and emotional growth in toddlers.</p>
            <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center">
              Read More
              <i class="fas fa-arrow-right ml-1"></i>
            </a>
          </div>
        </div>

        <!-- Article Card 2 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:transform hover:scale-[1.02]">
          <img src="/images/preschool.png" alt="Preschool Readiness" class="w-full h-48 object-cover">
          <div class="p-6">
            <div class="flex items-center mb-2">
              <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 text-xs font-semibold px-2.5 py-0.5 rounded">Preschool</span>
              <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">7 min read</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Is Your Child Ready for Preschool? Key Signs to Watch For</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Learn about the developmental milestones and social skills that indicate your child is ready for the preschool environment.</p>
            <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center">
              Read More
              <i class="fas fa-arrow-right ml-1"></i>
            </a>
          </div>
        </div>

        <!-- Article Card 3 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:transform hover:scale-[1.02]">
          <img src="/images/school.png" alt="School Age Children" class="w-full h-48 object-cover">
          <div class="p-6">
            <div class="flex items-center mb-2">
              <span class="bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 text-xs font-semibold px-2.5 py-0.5 rounded">School Age</span>
              <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">6 min read</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Helping Your Child Navigate Friendship Challenges</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Practical advice for parents on how to support children through social difficulties and build healthy friendship skills.</p>
            <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center">
              Read More
              <i class="fas fa-arrow-right ml-1"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="mt-10 text-center">
        <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition duration-200">
          View All Articles
          <i class="fas fa-arrow-right ml-2"></i>
        </a>
      </div>
    </div>
  </section>
</template>
