<template>
  <div class="w-full">
    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Find resources for your parenting journey:</h2>
    <form @submit.prevent="submit" class="flex flex-col sm:flex-row">
      <div class="relative flex-grow">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <i class="fa fa-search text-gray-400"></i>
        </div>
        <input 
          type="text" 
          v-model="form.query"
          class="block w-full p-4 pl-10 text-lg text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400" 
          placeholder="Search for parenting resources..."
          aria-label="Search for parenting resources"
          required
        >
        <div v-if="popularSearches.length > 0" class="absolute top-full left-0 right-0 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg mt-1 shadow-lg z-10 max-h-60 overflow-y-auto" v-show="showSuggestions">
          <div 
            v-for="(search, index) in popularSearches" 
            :key="index"
            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer text-gray-800 dark:text-gray-200"
            @click="selectSuggestion(search)"
          >
            {{ search }}
          </div>
        </div>
      </div>
      <button 
        type="submit"
        class="mt-2 sm:mt-0 sm:ml-2 px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800"
      >
        <span>Search</span>
        <i class="fa fa-arrow-right ml-2"></i>
      </button>
    </form>
    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Try: "toddler sleep tips", "healthy snacks", "screen time"</p>
    <div v-if="form.errors.query" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.query }}</div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  query: '',
  fingerprint: '',
  recaptcha_response: ''
});

const popularSearches = ref([
  'Toddler sleep tips',
  'Healthy meals for kids',
  'Screen time recommendations',
  'Potty training advice',
  'Dealing with tantrums',
  'Activities for rainy days',
  'Parenting teenagers',
  'Baby development milestones'
]);

const showSuggestions = ref(false);

// Get fingerprint from browser if available
onMounted(() => {
  if (window.navigator && window.navigator.userAgent) {
    form.fingerprint = window.navigator.userAgent;
  }
});

const submit = () => {
  if (form.query.trim() === '') return;
  form.post(route('search.submit'));
};

const selectSuggestion = (suggestion) => {
  form.query = suggestion;
  showSuggestions.value = false;
};

const handleClickOutside = (event) => {
  if (!event.target.closest('form')) {
    showSuggestions.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
/* Component-specific styles can be added here */
</style>
