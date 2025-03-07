<template>
    <vue3-tags-input :tags="model"
                     @on-tags-changed="handleChangeTag"
                     class="relative"
                     v-model="input"
                     @on-select="handleSelectedTag"
                     @on-remove="handleRemovedTag"
                     placeholder="Filter by Topics"
                     :loading="isLoading"
                     :select="true"
                     :select-items="items"
                     :duplicate-select-item="false"
                     @on-focus="onFocus">
        <template #item="{ name, index }">
            {{ name }}
        </template>
        <template #select-item="tag">
            <span class="text-base" :class="{'line-through': !showTag(tag)}">{{ tag.name }}</span>
        </template>
        <template #no-data>
            <span class="my-6" v-if="input.length >= 3">Nothing Found</span>
            <span class="my-6" v-else>Start typing</span>
        </template>
    </vue3-tags-input>
</template>

<script>
import {defineComponent} from 'vue';
import Vue3TagsInput from 'vue3-tags-input';
import Spinner from "@/Components/Spinner.vue";
import {concat, debounce, includes} from "lodash";

export default defineComponent({
    name: "TagsInput",
    props: {
        modelValue: {
            type: Array,
            default: () => {
                return [];
            }
        },
        defaultList: {
            type: Array,
            default: () => {
                return [];
            }
        }
    },
    components: {
        Spinner,
        Vue3TagsInput
    },
    data() {
        return {
            input: '',
            model: this.modelValue,
            items: [],
            isLoading: false
        }
    },
    methods: {
        handleChangeTag(tags) {
            this.$emit('update:modelValue', tags);
        },
        loadItems(query) {
            if (this.isLoading) {
                return false;
            }
            this.isLoading = true;
            this.items = [];
            fetch(route('tags.search').concat('?query=').concat(query), {
                method: 'GET',
            }).then(result => {
                result.json().then(({tags}) => this.items = concat(this.items, tags))
            }).finally(() => {
                this.isLoading = false;
            });
        },
        handleSelectedTag(tag) {
            if (includes(this.modelValue, tag.name)) {
                return;
            }
            this.model.push(tag.name);
            this.input = '';
            this.$emit('update:modelValue', this.model);
        },
        handleRemovedTag(index) {
            this.model = this.model.length > 1 ? this.model.splice(index, 1) : [];
            this.$emit('update:modelValue', this.model);
        },
        showTag(tag) {
            return !includes(this.modelValue, tag.name);
        },
        onFocus() {
            if(this.items.length < 1 && this.input.length < 1) {
                this.items = this.defaultList;
            }
        }
    },
    watch: {
        input: {
            handler: debounce(function (val) {
                if (val?.length >= 1) {
                    this.loadItems(val);
                } else {
                    this.items = this.defaultList;
                }
            }, 500)
        }
    }

})
</script>

<style lang="scss">
:root {
    .v3ti {
        @apply border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded p-2;
    }

    .v3ti .v3ti-context-menu {
        @apply border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-b p-2 border-r border-l border-b max-h-[420px] pt-8;
    }

    .v3ti .v3ti-tag {
        @apply bg-primary-700 text-white dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 whitespace-nowrap ;
    }

    .v3ti .v3ti-tag .v3ti-remove-tag {
        @apply text-white;
        transition: color .3s;
    }

    .v3ti .v3ti-tag .v3ti-remove-tag:hover {
        color: #ffffff;
    }

    .v3ti .v3ti-context-item {
        @apply p-2 w-fit dark:bg-gray-700 rounded dark:text-white mr-3 mb-3 bg-secondary-200;
        width: fit-content !important;
        display: inline-flex !important;
        &:hover {
            @apply dark:bg-gray-500 dark:text-white bg-secondary-300;
        }
    }

    .v3ti-no-data {
        @apply dark:text-gray-600;
    }

    .v3ti-new-tag:focus {
        outline: none !important;
        box-shadow: none !important;
    }
}

</style>
