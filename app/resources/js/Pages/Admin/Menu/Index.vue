<template>
    <Head title="Menu"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4">Menu</h2>
            <div
                class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <Search v-model="form.search"/>
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <Link :href="route('admin.menu.create')"
                          class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                        <i class="fa-solid fa-plus w-4 mr-2"></i>
                        Create Menu Item
                    </Link>
                    <div class="flex items-center space-x-3 w-full md:w-auto relative">
                    </div>
                </div>
            </div>
        </template>
        <section class="bg-gray-50 dark:bg-gray-900 p-3 min-h-[90vh]">
            <div class="w-full max-w-4xl px-4 lg:px-9">
                <h2 class="text-2xl mb-6 text-gray-700 dark:text-gray-300">Main Nav Level 1</h2>
                <vue-nestable :value="mainList" @input="mainList = $event"
                              @change="(value, items) => change(value, items, 'main')" :maxDepth="2" cross-list
                              group="cross">
                    <template v-slot="{item}">
                        <div
                            class="mt-2 mb-2 bg-gray-200 dark:bg-gray-700 dark:text-white flex flex-row justify-between px-2 py-2">
                            <div class="flex-row flex">
                                <vue-nestable-handle :item="item">
                                    <i class="fas fa-bars mt-1 mb-1 cursor-grab"/>
                                </vue-nestable-handle>
                                <div :class="{'text-red-400': item.deleted_at}" class="ml-3">
                                    {{ (item.deleted_at ? '[DELETED] ' : '').concat(item.name) }}
                                    <span
                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-gray-100 text-gray-800 hover:bg-gray-200 ml-1"
                                        v-if="item.is_coming_soon">Coming Soon</span>
                                    <span
                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-green-500 text-white hover:green-600 ml-1"
                                        v-if="item.is_new">New</span>
                                    <span
                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-yellow-600 text-white hover:green-600 ml-1"
                                        v-if="item.is_beta">Beta</span>
                                    <span
                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-red-600 text-white hover:green-600 ml-1"
                                        v-if="item.auth_only">Logged In Only</span>
                                </div>
                            </div>


                            <div class="flex flex-row justify-center items-center gap-x-4">
                                <Link :href="route('admin.menu.edit', item.id)"
                                      class="dark:text-gray-300 hover:text-gray-600 hover:dark:text-white"><i
                                    class="fas fa-edit"></i></Link>
                                <DeleteItemModal :url="route('admin.menu.destroy', item.id)" v-if="!item.deleted_at"
                                                 v-slot="slotProps"
                                                 @deleted="itemDeleted(item)">
                                                <span @click="slotProps.confirmItemDeletion"
                                                      class="cursor-pointer text-red-600 hover:text-red-400 block text-sm dark:text-red-500 dark:hover:text-red-600">
                                                    <i class="fa-solid fa-trash"/>
                                                </span>
                                </DeleteItemModal>
                                <RecoverItemModal :url="route('admin.menu.recover', item.id)" v-else
                                                  v-slot="slotProps"
                                                  @recovered="itemRecovered(item)">
                                                <span @click="slotProps.confirmItemRecovery"
                                                      class="cursor-pointer text-yellow-600 hover:text-yellow-400 block text-sm dark:text-yellow-500 dark:hover:text-yellow-600">
                                                    <i class="fa-solid fa-trash-can-arrow-up"/>
                                                </span>
                                </RecoverItemModal>
                            </div>

                        </div>

                    </template>
                </vue-nestable>
            </div>
            <div class="w-full max-w-4xl px-4 lg:px-9 mt-8">
                <h2 class="text-2xl mb-6 text-gray-700 dark:text-gray-300">Main Nav Level 2</h2>
                <vue-nestable :value="mainList2" @input="mainList2 = $event"
                              @change="(value, items) => change(value, items, 'main2')" :maxDepth="2"
                              cross-list
                              group="cross">
                    <template v-slot="{item}">
                        <div
                            class="mt-2 mb-2 bg-gray-200 dark:bg-gray-700 dark:text-white flex flex-row justify-between px-2 py-2">
                            <div class="flex-row flex">
                                <vue-nestable-handle :item="item">
                                    <i class="fas fa-bars mt-1 mb-1 cursor-grab"/>
                                </vue-nestable-handle>
                                <div :class="{'text-red-400': item.deleted_at}" class="ml-3">
                                    {{ (item.deleted_at ? '[DELETED] ' : '').concat(item.name) }}
                                    <span
                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-gray-100 text-gray-800 hover:bg-gray-200 ml-1"
                                        v-if="item.is_coming_soon">Coming Soon</span>
                                    <span
                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-green-500 text-white hover:green-600 ml-1"
                                        v-if="item.is_new">New</span>
                                    <span
                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-yellow-600 text-white hover:green-600 ml-1"
                                        v-if="item.is_beta">Beta</span>
                                    <span
                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-red-600 text-white hover:green-600 ml-1"
                                        v-if="item.auth_only">Logged In Only</span>
                                </div>
                            </div>


                            <div class="flex flex-row justify-center items-center gap-x-4">
                                <Link :href="route('admin.menu.edit', item.id)"
                                      class="dark:text-gray-300 hover:text-gray-600 hover:dark:text-white"><i
                                    class="fas fa-edit"></i></Link>
                                <DeleteItemModal :url="route('admin.menu.destroy', item.id)" v-if="!item.deleted_at"
                                                 v-slot="slotProps"
                                                 @deleted="itemDeleted(item)">
                                                <span @click="slotProps.confirmItemDeletion"
                                                      class="cursor-pointer text-red-600 hover:text-red-400 block text-sm dark:text-red-500 dark:hover:text-red-600 ">
                                                    <i class="fa-solid fa-trash"/>
                                                </span>
                                </DeleteItemModal>
                                <RecoverItemModal :url="route('admin.menu.recover', item.id)" v-else
                                                  v-slot="slotProps"
                                                  @recovered="itemRecovered(item)">
                                                <span @click="slotProps.confirmItemRecovery"
                                                      class="cursor-pointer text-yellow-600 hover:text-yellow-400 block text-sm dark:text-yellow-500 dark:hover:text-yellow-600">
                                                    <i class="fa-solid fa-trash-can-arrow-up"/>
                                                </span>
                                </RecoverItemModal>
                            </div>

                        </div>

                    </template>
                </vue-nestable>
            </div>

<!--            <div class="w-full max-w-4xl px-4 lg:px-9 mt-8">-->
<!--                <h2 class="text-2xl mb-6 text-gray-700 dark:text-gray-300">Sidebar</h2>-->
<!--                <vue-nestable :value="sidebarList" @input="sidebarList = $event"-->
<!--                              @change="(value, items) => change(value, items, 'sidebar')" :maxDepth="2"-->
<!--                              cross-list-->
<!--                              group="cross">-->
<!--                    <template v-slot="{item}">-->
<!--                        <div-->
<!--                            class="mt-2 mb-2 bg-gray-200 dark:bg-gray-700 dark:text-white flex flex-row justify-between px-2 py-2">-->
<!--                            <div class="flex-row flex">-->
<!--                                <vue-nestable-handle :item="item">-->
<!--                                    <i class="fas fa-bars mt-1 mb-1 cursor-grab"/>-->
<!--                                </vue-nestable-handle>-->
<!--                                <div :class="{'text-red-400': item.deleted_at}" class="ml-3">-->
<!--                                    {{ (item.deleted_at ? '[DELETED] ' : '').concat(item.name) }}-->
<!--                                    <span-->
<!--                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-gray-100 text-gray-800 hover:bg-gray-200 ml-1"-->
<!--                                        v-if="item.is_coming_soon">Coming Soon</span>-->
<!--                                    <span-->
<!--                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-green-500 text-white hover:green-600 ml-1"-->
<!--                                        v-if="item.is_new">New</span>-->
<!--                                    <span-->
<!--                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-yellow-600 text-white hover:green-600 ml-1"-->
<!--                                        v-if="item.is_beta">Beta</span>-->
<!--                                    <span-->
<!--                                        class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-red-600 text-white hover:green-600 ml-1"-->
<!--                                        v-if="item.auth_only">Logged In Only</span>-->
<!--                                </div>-->
<!--                            </div>-->


<!--                            <div class="flex flex-row justify-center items-center gap-x-4">-->
<!--                                <Link :href="route('admin.menu.edit', item.id)"-->
<!--                                      class="dark:text-gray-300 hover:text-gray-600 hover:dark:text-white"><i-->
<!--                                    class="fas fa-edit"></i></Link>-->
<!--                                <DeleteItemModal :url="route('admin.menu.destroy', item.id)" v-if="!item.deleted_at"-->
<!--                                                 v-slot="slotProps"-->
<!--                                                 @deleted="itemDeleted(item)">-->
<!--                                                <span @click="slotProps.confirmItemDeletion"-->
<!--                                                      class="cursor-pointer text-red-600 hover:text-red-400 block text-sm dark:text-red-500 dark:hover:text-red-600 ">-->
<!--                                                    <i class="fa-solid fa-trash"/>-->
<!--                                                </span>-->
<!--                                </DeleteItemModal>-->
<!--                                <RecoverItemModal :url="route('admin.menu.recover', item.id)" v-else-->
<!--                                                  v-slot="slotProps"-->
<!--                                                  @recovered="itemRecovered(item)">-->
<!--                                                <span @click="slotProps.confirmItemRecovery"-->
<!--                                                      class="cursor-pointer text-yellow-600 hover:text-yellow-400 block text-sm dark:text-yellow-500 dark:hover:text-yellow-600">-->
<!--                                                    <i class="fa-solid fa-trash-can-arrow-up"/>-->
<!--                                                </span>-->
<!--                                </RecoverItemModal>-->
<!--                            </div>-->

<!--                        </div>-->

<!--                    </template>-->
<!--                </vue-nestable>-->
<!--            </div>-->
        </section>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/Admin/AuthenticatedLayout.vue";
import {throttle} from "lodash/function.js";
import {isEmpty, mapValues, pickBy} from "lodash";
import {Head, Link} from '@inertiajs/vue3';
import Pagination from "@/Components/Admin/Table/Pagination.vue";
import Search from "@/Components/Admin/Table/Search.vue";
import DeleteProfileForm from "@/Pages/Admin/Profile/Partials/DeleteProfileForm.vue";
import DangerButton from "@/Components/DangerButton.vue";
import DeleteItemModal from "@/Components/Admin/DeleteItemModal.vue";
import TrashedFilter from "@/Components/Admin/TrashedFilter.vue";
import Filter from "@/Components/Admin/Filter.vue";
import RecoverItemModal from "@/Components/Admin/RecoverItemModal.vue";
import FiltersWrapper from "@/Components/Admin/FiltersWrapper.vue";
import {VueNestable, VueNestableHandle} from 'vue3-nestable';
import moment from "moment/moment.js";

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
        menuItems: Array,
        filters: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed || null,
            },
            mainList: this.menuItems.filter(item => item.list === 'main'),
            sidebarList: this.menuItems.filter(item => item.list === 'sidebar'),
            mainList2: this.menuItems.filter(item => item.list === 'main2'),
        }
    },
    watch: {
        form: {
            handler: throttle(function () {
                const query = pickBy(this.form),
                    request = Object.keys(query).length ? query : {remember: 'forget'};
                this.$inertia.replace(this.route('admin.menu.index', request));
            }, 150),

            deep: true
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null);
        },
        change(value, items, list) {
            items.items = items.items.map(item => {
                item.list = list;
                return item;
            })
            axios.post(this.route('admin.menu.change'), items)
                .catch(e => {
                    this.$page.props.flash.error = e.response.data.message || e;
                });
        },
        itemDeleted(item) {
            item.deleted_at = moment.utc().toISOString();
        },
        itemRecovered(item) {
            item.deleted_at = null;
        }
    }
}
</script>
<style scoped>

</style>
