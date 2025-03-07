<template>
  <div>
    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Popular features:</h3>
    <div class="flex flex-wrap gap-3">
      <a 
        v-for="feature in features" 
        :key="feature.name"
        :href="feature.route"
        class="flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 group"
      >
        <i :class="`fas ${feature.icon} text-blue-500 dark:text-blue-400 mr-2 group-hover:scale-110 transition-transform duration-200`"></i>
        <span class="text-gray-800 dark:text-gray-200">{{ feature.name }}</span>
        <span v-if="feature.new" class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">New</span>
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const features = ref([
  {
    name: 'Activity Generator',
    icon: 'fa-gamepad',
    route: route('activity.index'),
    new: false
  },
  {
    name: 'Parenting Articles',
    icon: 'fa-newspaper',
    route: route('articles.feed'),
    new: false
  },
  {
    name: 'Product Reviews',
    icon: 'fa-star',
    route: route('product_categories.index'),
    new: true
  },
  {
    name: 'Baby Checklist',
    icon: 'fa-list',
    route: route('planner_categories.index'),
    new: false
  }
]);
</script>

<style scoped>
/* Component-specific styles can be added here */
</style>
