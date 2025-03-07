<template>
    <Head>
        <title>{{page.seo_title || page.title}}</title>
        <meta head-key="description" name="description" :content="page.seo_headline || page.headline">
        <meta head-key="og:title" property="og:title" :content="page.seo_title || page.title">
        <meta head-key="og:description" property="og:description" :content="page.seo_headline || page.headline">
        <meta head-key="og:image" property="og:image" :content="page.image"/>
        <meta head-key="og:url" property="og:url" :content=" page.link ">

        <meta head-key="twitter:title" property="twitter:title" :content="page.seo_title || page.title">
        <meta head-key="twitter:description" property="twitter:description" :content="page.seo_headline || page.headline">
        <meta head-key="twitter:image" property="twitter:image" :content="page.image">
    </Head>
    <div class="container mx-auto mt-8 p-4 flex flex-col md:flex-row md: gap-x-4">
        <div class="w-full">
            <article class="bg-white p-8 shadow-md rounded-md dark:bg-gray-800">
                <!-- Page Title -->
                <h1 class="text-2xl md:text-4xl font-extrabold mb-6 dark:text-gray-200">{{ page.title }}</h1>
                
                <!-- Page Content -->
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed mb-8 article-content"
                     v-html="page.content">
                </div>

                <!-- Contact Form -->
                <div class="mt-8 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4 dark:text-gray-200">Get in Touch</h2>
                    <form @submit.prevent="submitForm" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                v-model="form.name" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-200" 
                                placeholder="John Doe"
                                required
                            >
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                v-model="form.email" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-200" 
                                placeholder="john@example.com"
                                required
                            >
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
                            <input 
                                type="text" 
                                id="subject" 
                                v-model="form.subject" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-200" 
                                placeholder="How can we help?"
                                required
                            >
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
                            <textarea 
                                id="message" 
                                v-model="form.message" 
                                rows="4" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-200" 
                                placeholder="Your message here..."
                                required
                            ></textarea>
                        </div>
                        <div>
                            <button 
                                type="submit" 
                                class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                :disabled="submitting"
                            >
                                <span v-if="submitting">Sending...</span>
                                <span v-else>Send Message</span>
                            </button>
                        </div>
                    </form>
                    <!-- Success Message -->
                    <div v-if="showSuccess" class="mt-4 p-4 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-md">
                        Thank you for your message! We'll get back to you as soon as possible.
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-4">
                            <i class="fas fa-envelope text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2 dark:text-gray-200">Email Us</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-center">contact@freeparentsearch.com</p>
                    </div>
                    <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-4">
                            <i class="fas fa-phone text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2 dark:text-gray-200">Call Us</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-center">(123) 456-7890</p>
                    </div>
                    <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-4">
                            <i class="fas fa-map-marker-alt text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2 dark:text-gray-200">Location</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-center">123 Parent Street, Family City</p>
                    </div>
                </div>

                <!-- Tags List -->
                <div class="flex flex-wrap gap-2 mt-8" v-if="page.tags && page.tags.length > 0">
                    <span
                        class="inline-block bg-blue-200 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-700 dark:text-blue-100"
                        v-for="(tag, i) in page.tags" :key="'tag-'.concat(i)">{{ tag }}</span>
                </div>
            </article>
        </div>
    </div>
</template>

<script>
import {Head, Link} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import { ref } from 'vue';

export default {
    name: "Contact",
    components: {Head, Link},
    layout: MainLayout,
    props: {
        page: Object,
    },
    setup() {
        const form = ref({
            name: '',
            email: '',
            subject: '',
            message: ''
        });
        const submitting = ref(false);
        const showSuccess = ref(false);

        const submitForm = () => {
            submitting.value = true;
            
            // Simulate form submission (in a real app, this would be an API call)
            setTimeout(() => {
                submitting.value = false;
                showSuccess.value = true;
                
                // Reset form
                form.value = {
                    name: '',
                    email: '',
                    subject: '',
                    message: ''
                };
                
                // Hide success message after 5 seconds
                setTimeout(() => {
                    showSuccess.value = false;
                }, 5000);
            }, 1500);
        };

        return {
            form,
            submitting,
            showSuccess,
            submitForm
        };
    }
}
</script>

<style scoped>
.article-content :deep(h2) {
    font-size: 1.5rem;
    font-weight: 700;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    color: #1a202c;
}

.article-content :deep(p) {
    margin-bottom: 1rem;
}

.dark .article-content :deep(h2) {
    color: #e2e8f0;
}
</style>
