<script>
export default {
    name: "SignupButton"
}
</script>

<template>
    <div>
        <a class="px-2 font-medium text-base text-white bg-primary-500 hover:bg-primary-700  dark:hover:bg-primary-700 dark:bg-primary-600 py-2 rounded whitespace-nowrap rounded-r-none"
           :href="route('signup')">Sign up</a>
        <a class="px-2 font-medium text-base text-white bg-secondary-400 hover:bg-secondary-700 dark:hover:bg-secondary-500 dark:bg-secondary-600 py-2 rounded whitespace-nowrap rounded-l-none"
           :href="route('login')">Login</a>
    </div>
</template>
