<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="flex w-full items-center justify-center rounded-lg bg-secondary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-secondary-800 focus:outline-none focus:ring-4 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800 whitespace-nowrap"
    >
        <slot />
    </button>
</template>
