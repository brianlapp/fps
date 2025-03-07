<script>
export default {
    name: "Toggle",
    props: {
        modelValue: {
            type: Boolean,
            default: false
        },
        label: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            value: this.modelValue
        }
    },
    watch: {
        value: {
            handler(val) {
                this.$emit('update:modelValue', val);
            }
        }
    }
}
</script>

<template>
    <label class="flex w-full items-center cursor-pointer whitespace-nowrap">
        <input type="checkbox" value="" class="sr-only peer" :checked="value" v-model="value">
        <div
            class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500"></div>
        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ label }}</span>
    </label>
</template>

<style scoped>

</style>
