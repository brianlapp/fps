<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link
        class="flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 whitespace-nowrap"
    >
        <slot />
    </Link>
</template>
