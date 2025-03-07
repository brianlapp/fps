<script setup>
defineProps({
    message: {
        type: String,
    },
    textClass: {
        type: String,
        default: 'text-red-600 dark:text-red-400'
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm " :class="[textClass]">
            {{ message }}
        </p>
    </div>
</template>
