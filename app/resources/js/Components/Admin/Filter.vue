<script>
export default {
    name: "Filter",
    props: {
        modelValue: {
            type: Boolean,
            default: false
        },
        options: {
            type: Array
        },
        label: {
            type: String
        }
    },
    data() {
        return {
            value: this.modelValue,
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
    <div>
        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">{{ label }}</h6>
        <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
            <li class="flex items-center" v-for="option in options" :key="'trashed-' + option.value">
                <input :id="'trashed-' + option.value" type="checkbox" :checked="value === option.value" @click="value = option.value"
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500 cursor-pointer">
                <label :for="'trashed-' + option.value"
                       class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ option.label }}
                </label>
            </li>
        </ul>
    </div>

</template>

<style scoped>

</style>
