<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {Link} from '@inertiajs/vue3';
import ThemeToggle from "@/Components/ThemeToggle.vue";
import Logo from "@/Components/Logo.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <ThemeToggle class="absolute top-1 left-1 md:top-4 md:left-4"/>
            <FlashMessages/>
            <div class="max-w-7xl mx-auto p-4 sm:px-6 lg:px-8">
                <div
                    class="flex flex-col sm:justify-center items-center pt-6 sm:py-10 bg-light-main-bg dark:bg-dark-main-bg relative h-screen">
                    <Logo class="w-1/12 mb-4"/>
                    <div class="bg-white p-6 shadow-md rounded-md dark:bg-gray-800 dark:text-gray-300 w-full md:w-6/12">
                        <slot/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
