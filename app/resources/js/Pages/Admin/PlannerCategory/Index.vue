<template>
    <Head title="Planner Categories"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4">Planner
                Categories</h2>
            <div
                class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <Search v-model="form.search"/>
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <a :href="route('admin.planner_categories.create')"
                          class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                        <i class="fa-solid fa-plus w-4 mr-2"></i>
                        Create Planner Category
                    </a>
                    <div class="flex items-center space-x-3 w-full md:w-auto relative">
                        <FiltersWrapper :filters="form">
                            <div class="border-b dark:border-gray-600"/>
                            <TrashedFilter v-model="form.trashed"/>
                        </FiltersWrapper>
                    </div>
                </div>
            </div>
        </template>
        <section class="bg-gray-50 dark:bg-gray-900 p-3 min-h-[90vh]">
            <div class="mx-auto w-full px-4 lg:px-9">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-visible">
                    <div class="text-gray-500 dark:text-gray-400" v-if="items.length > 0">
                        <vue-nestable :value="items" @input="items = $event" class="w-full"
                                      @change="reorder" :maxDepth="1">
                            <template v-slot="{item}">
                                <div class="border-b dark:border-gray-700 flex w-full" :key="item.id">
                                    <div class="px-4 py-3">
                                        <vue-nestable-handle :item="item">
                                            <i class="fas fa-bars mt-1 mb-1 cursor-grab"/>
                                        </vue-nestable-handle>
                                    </div>
                                    <div class="px-4 py-3 flex-1 text-left">{{ item.title }}</div>
                                    <div class="px-4 py-3  flex-1 flex items-center justify-end"
                                         :key="item.id.toString().concat('-dropdown')">
                                        <button :id="item.id.toString().concat('-dropdown-button')"
                                                :data-dropdown-toggle="item.id.toString().concat('-dropdown')"
                                                class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                type="button">
                                            <i class="fa-solid fa-ellipsis w-4 ml-2"></i>
                                        </button>
                                        <div :id="item.id.toString().concat('-dropdown')"
                                             class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                :aria-labelledby="item.id.toString().concat('-dropdown-button')">
                                                <li>
                                                    <a :href="route('planner_categories.show', item.slug)"
                                                       target="_blank"
                                                       class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">View</a>
                                                </li>
                                                <li v-if="item.can.edit">
                                                    <a :href="route('admin.planner_categories.edit', item.id)"
                                                          class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        Edit
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="py-1" v-if="item.can.delete">
                                                <DeleteItemModal
                                                    :url="route('admin.planner_categories.destroy', item.id)"
                                                    v-if="!item.deleted_at"
                                                    v-slot="slotProps"
                                                    warning-text="All Planners related to this Category will be deleted!"
                                                    @deleted="itemDeleted(item)">
                                                <span @click="slotProps.confirmItemDeletion"
                                                      class="cursor-pointer text-red-400 block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-red-500 dark:hover:text-white">Delete</span>
                                                </DeleteItemModal>
                                                <RecoverItemModal
                                                    :url="route('admin.planner_categories.recover', item.id)" v-else
                                                    v-slot="slotProps"
                                                    @recovered="itemRecovered(item)">
                                                <span @click="slotProps.confirmItemRecovery"
                                                      class="cursor-pointer text-yellow-500 block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-yellow-600 dark:hover:text-white">Recover</span>
                                                </RecoverItemModal>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </vue-nestable>
                    </div>
                    <div class="min-h-[200px] flex justify-center items-center" v-else>
                        <h2 class=" text-gray-500 dark:text-gray-400 text-lg">Nothing Found</h2>
                    </div>
                    <Pagination :list="list" v-if="list.data.length > 0"/>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/Admin/AuthenticatedLayout.vue";
import {throttle} from "lodash/function.js";
import {clone, isEmpty, mapValues, pickBy} from "lodash";
import {Head, Link} from '@inertiajs/vue3';
import Pagination from "@/Components/Admin/Table/Pagination.vue";
import Search from "@/Components/Admin/Table/Search.vue";
import DeleteProfileForm from "@/Pages/Admin/Profile/Partials/DeleteProfileForm.vue";
import DangerButton from "@/Components/DangerButton.vue";
import DeleteItemModal from "@/Components/Admin/DeleteItemModal.vue";
import TrashedFilter from "@/Components/Admin/TrashedFilter.vue";
import Filter from "@/Components/Admin/Filter.vue";
import moment from "moment";
import RecoverItemModal from "@/Components/Admin/RecoverItemModal.vue";
import FiltersWrapper from "@/Components/Admin/FiltersWrapper.vue";
import {VueNestable, VueNestableHandle} from "vue3-nestable";

export default {
    name: "Index",
    components: {
        FiltersWrapper,
        RecoverItemModal,
        Filter,
        TrashedFilter,
        DeleteItemModal, DangerButton, DeleteProfileForm, Search, Pagination, AuthenticatedLayout, Head, Link,
        VueNestable, VueNestableHandle
    },
    props: {
        list: Object,
        filters: Object,
        statuses: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed || null,
            },
            items: clone(this.list?.data ?? [])
        }
    },
    watch: {
        form: {
            handler: throttle(function () {
                let query = pickBy(this.form)
                this.$inertia.replace(this.route('admin.planner_categories.index', Object.keys(query).length ? query : {remember: 'forget'}))
            }, 150),

            deep: true
        },
        'list.data': {
            handler(val) {
                this.items = clone(val ?? []);
            },
            deep: true
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null);
        },
        itemDeleted(item) {
            item.deleted_at = moment.utc().toISOString();
        },
        itemRecovered(item) {
            item.deleted_at = null;
        },
        reorder(value, items) {
            axios.post(this.route('admin.planner_categories.reorder'), items)
                .catch(e => {
                    this.$page.props.flash.error = e.response.data.message || e;
                });
        }
    }
}
</script>
<style scoped>

</style>
