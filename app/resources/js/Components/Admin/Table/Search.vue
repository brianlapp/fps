<script>
export default {
    name: "Search",
    props: {
        modelValue: String
    },
    data() {
        return {
            model: this.modelValue,
        }
    },
    watch: {
        model: {
            handler(val) {
                this.$emit('update:modelValue', val);
            }
        }
    }
}
</script>

<template>
    <div class="w-full md:w-1/2">
        <form class="flex items-center">
            <label for="simple-search" class="sr-only">Search</label>
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-search w-4 mr-2 text-gray-700 dark:text-gray-400"></i>
                </div>
                <input type="text" id="simple-search" v-model="model"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                       placeholder="Search" required="">
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
