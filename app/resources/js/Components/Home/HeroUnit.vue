<template>
  <section class="hero-section bg-gradient-to-br from-blue-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-16 lg:py-20">
      <div class="grid md:grid-cols-2 gap-8 items-center">
        <!-- Hero Content Column -->
        <div class="order-2 md:order-1 animate-fadeIn">
          <HeroHeadline />
          <p class="mt-4 text-lg md:text-xl text-gray-600 dark:text-gray-300 max-w-2xl animate-fadeIn animation-delay-200">
            Free tools, activities, and expert advice for parents at every stage of your family's journey.
          </p>
          
          <!-- Search Component -->
          <div class="mt-8 animate-fadeIn animation-delay-300">
            <HeroSearch />
          </div>
          
          <!-- Feature Quick Links -->
          <div class="mt-8 animate-fadeIn animation-delay-400">
            <HeroFeatureLinks />
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
              src="/images/family.png" 
              alt="Happy family enjoying quality time together" 
              class="rounded-xl shadow-lg w-full h-auto object-cover max-h-[500px] relative z-10 transform transition-transform duration-700 hover:scale-[1.02]"
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import HeroHeadline from './HeroHeadline.vue';
import HeroSearch from './HeroSearch.vue';
import HeroFeatureLinks from './HeroFeatureLinks.vue';
</script>

<style scoped>
.animate-fadeIn {
  opacity: 0;
  animation: fadeIn 0.8s ease-out forwards;
}

.animation-delay-100 {
  animation-delay: 200ms;
}

.animation-delay-200 {
  animation-delay: 400ms;
}

.animation-delay-300 {
  animation-delay: 600ms;
}

.animation-delay-400 {
  animation-delay: 800ms;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (prefers-reduced-motion: reduce) {
  .animate-fadeIn {
    animation: none;
    opacity: 1;
  }
}
</style>
