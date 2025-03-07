<template>
    <div class="relative">
        <button id="filterOfferCategoriesDropdownButton"
                data-dropdown-toggle="filterOfferCategoriesDropdown"
                class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                type="button">
            <i class="fa-solid fa-filter w-4 mr-2 text-gray-700 dark:text-gray-400"></i>
            Filters <span
            class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -end-2 dark:border-gray-900"
            v-if="filterCount > 0">{{ filterCount }}</span>
            <i class="fa-solid fa-chevron-down w-4 ml-2 text-gray-700 dark:text-gray-400"></i>
        </button>
        <div id="filterOfferCategoriesDropdown"
             class="z-10 hidden p-3 bg-white rounded-lg shadow dark:bg-gray-700 flex flex-col gap-y-3 absolute"
             :class="[dropdownClass]"
             aria-labelledby="filterOfferCategoriesDropdownButton">
            <slot/>
        </div>
    </div>

</template>

<script>
import {isEmpty} from "lodash";

export default {
    name: "FiltersWrapper",
    props: {
        filters: {
            type: Object,
            default: () => null
        },
        dropdownClass: {
            type: String,
            default: 'w-48'
        }
    },
    computed: {
        filterCount() {
            return Object.keys(this.filters || {}).filter(key => {
                return key !== 'search' && !isEmpty(this.filters[key])
            }).length;
        }
    },
}
</script>

<style scoped>

</style>
