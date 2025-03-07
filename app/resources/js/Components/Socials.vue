<template>
    <div v-if="!isEmpty" class="w-full flex flex-row gap-x-4 justify-center items-center py-2">
        <template v-for="(value, key) in socials" :key="key" >
            <a v-if="value" target="_blank" :href="value" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 hover:dark:text-gray-200">
                <i class="fa fa-xl" :class="['fa-'.concat(key)]"/>
            </a>
        </template>
    </div>
</template>
<script>
import _ from "lodash";

export default {
    name: 'Socials',
    data() {
        return {
            socials: this.$page.props.social_networks
        }
    },
    computed: {
        isEmpty() {
            return _.keys(this.socials).every(key => !this.socials[key]);
        }
    }
}
</script>
