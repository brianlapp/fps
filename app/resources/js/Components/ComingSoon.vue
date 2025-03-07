<script>
export default {
    name: "ComingSoon",
    props: {
        text: {
            type: String,
            default: 'Coming Soon'
        }
    }
}
</script>

<template>
    <div class="items-center px-3 py-1 bg-green-200 text-green-800 text-xs font-semibold rounded-full w-fit">
        {{ text }}
    </div>
</template>

<style scoped>

</style>
