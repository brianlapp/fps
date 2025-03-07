<script>
export default {
    name: "Beta",
    props: {
        text: {
            type: String,
            default: 'Beta'
        }
    }
}
</script>

<template>
    <div class="items-center px-3 py-1 bg-yellow-200 text-yellow-800 text-xs font-semibold rounded-full w-fit">
        {{ text }}
    </div>
</template>

<style scoped>

</style>
