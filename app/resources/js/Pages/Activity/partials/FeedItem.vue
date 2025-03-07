<template>
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col">
        <div class="p-4 bg-blue-gradient dark:bg-dark-blue-gradient">
            <h2 class="text-xl uppercase font-black text-white text-center line-clamp-3 min-h-[83px]">
                {{ item.status === 'generating' ? 'Generating... ' : item.title }}
            </h2>
        </div>
        <div class="flex flex-col p-4 flex-grow">
            <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">
                {{ item.brief_description }}
            </p>
            <div class="space-y-4 flex flex-col">
                <!-- Age Range with Icon -->
                <div class="flex items-center justify-between gap-x-2">
                    <div :data-tooltip-target="'tooltip-age-' + key" data-tooltip-placement="right">
                        <i class="fa-solid fa-child-reaching fa-xl"></i>
                    </div>
                    <div :id="'tooltip-age-' + key" role="tooltip"
                         class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Age Range
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <div class="ml-2 text-gray-700 dark:text-gray-300">{{ item.age_range || item.child_age }}</div>
                </div>
                <!-- Time Required with Icon -->
                <div class="flex items-center justify-between gap-x-2">
                    <div :data-tooltip-target="'tooltip-time-' + key" data-tooltip-placement="right">
                        <i class="fa-solid fa-clock fa-xl"></i>
                    </div>
                    <div :id="'tooltip-time-' + key" role="tooltip"
                         class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Time Required
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <div class="ml-2 text-gray-700 dark:text-gray-300">{{
                            item.time_required || item.available_time
                        }}
                    </div>
                </div>
                <!-- Materials Needed with Icon -->
                <div class="flex items-start justify-between  gap-x-2">
                    <div :data-tooltip-target="'tooltip-materials-' + key" data-tooltip-placement="right">
                        <i class="fa-solid fa-boxes-packing fa-xl"></i>
                    </div>
                    <div :id="'tooltip-materials-' + key" role="tooltip"
                         class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Materials Needed
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <div class="ml-2 text-gray-700 dark:text-gray-300 text-sm line-clamp-3">
                        {{ materials }}
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 justify-self-end mt-auto">
            <a :href="route('activity.view', item.uuid)"
               class="block text-green-500 text-center py-2 text-xl rounded-lg hover:bg-green-500 hover:text-white transition-all duration-300 border-2 border-green-500">
                See More
            </a>
        </div>
    </div>
</template>


<script>
import {marked} from "marked";
import DataMixin from "@/Mixins/DataMixin.vue";
import _ from 'lodash';

export default {
    props: {
        item: Object
    },
    mixins: [DataMixin],
    data() {
        return {
            key: _.random(1, 9999)
        }
    },
    computed: {
        materials() {
            if (!this.item.materials_needed) {
                return null;
            }
            if (typeof this.item.materials_needed === 'object') {
                return this.item.materials_needed.join(', ');
            }

            return this.item.materials_needed;
        }
    }
}
</script>

