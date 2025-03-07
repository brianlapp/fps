<template>
    <vue3-tags-input :tags="model"
                     @on-tags-changed="handleChangeTag"
                     class="relative"
                     v-model="input"
                     @on-select="handleSelectedTag"
                     placeholder="enter some tags"
                     :loading="isLoading"
                     :select="true"
                     :select-items="items">
        <template #item="{ name, index }">
            {{ name }}
        </template>
        <template #select-item="tag">
            <span class="text-base" v-if="tag?.id > 0">
               {{ tag.name }}
            </span>
            <span class="text-base" v-else-if="items.length === 1">
               {{ tag.name }} (new tag)
            </span>
        </template>
    </vue3-tags-input>
</template>

<script>
import {defineComponent} from 'vue';
import Vue3TagsInput from 'vue3-tags-input';
import Spinner from "@/Components/Spinner.vue";
import {concat, debounce} from "lodash";

export default defineComponent({
    name: "TagsInput",
    props: {
        modelValue: {
            type: Array,
            default: () => {
                return [];
            }
        },
        type: {
            type: String,
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
            this.items = [{id:0, name: query}];
            fetch(route('admin.tags.search').concat('?query=').concat(query), {
                method: 'GET',
            }).then(result => {
                result.json().then(({tags}) => this.items = concat(this.items, tags))
            }).finally(() => {
                this.isLoading = false;
            });
        },
        handleSelectedTag(tag) {
            this.model.push(tag.name);
            this.input = '';
            this.$emit('update:modelValue', this.model);
        },
    },
    watch: {
        input: {
            handler: debounce(function (val) {
                if (val?.length >= 3) {
                    this.loadItems(val);
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
        @apply border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-b p-2 border-r border-l border-b;
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
        @apply p-2;
        &:hover {
            @apply dark:bg-gray-700 rounded dark:text-white;
        }
    }
}

</style>
