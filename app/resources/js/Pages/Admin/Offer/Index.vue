<template>
    <Head title="Offers"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4">{{category.title}}</h2>
            <div
                class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <Search v-model="form.search"/>
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <Link :href="route('admin.offers.create', category.slug)"
                          class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                        <i class="fa-solid fa-plus w-4 mr-2"></i>
                        Create {{ category.single_title }}
                    </Link>
                    <div class="flex items-center space-x-3 w-full md:w-auto relative">
                        <FiltersWrapper :filters="form" dropdown-class="w-72">
                            <Filter :options="isActiveOptions" v-model="form.isActive" label="Is Active"/>

                            <div class="border-b dark:border-gray-600"/>
                            <div>
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Countries</h6>

                                <multiselect v-model="filterCountries" :options="countries" :multiple="true"
                                             :close-on-select="false"
                                             :clear-on-select="false"
                                             :preserve-search="true" placeholder="Select Countries" label="name"
                                             track-by="id"
                                             :preselect-first="false">
                                    <template #selection="{ values, search, isOpen }">
                                        <div class="multiselect__single"
                                             v-if="values.length"
                                             v-show="!isOpen">
                                            <div class="flex gap-x-2 flex-wrap gap-y-2">
                                                <div class="py-1 px-2 bg-blue-600 text-white text-xs rounded-full"
                                                     v-for="value in values">
                                                    {{ value.name }}
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </multiselect>
                            </div>
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
                    <div class="overflow-x-auto" v-if="list.data.length > 0">
                        <table class="w-full text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Title</th>
                                <th scope="col" class="px-4 py-3">Image</th>
                                <th scope="col" class="px-4 py-3">Is Active</th>
                                <th scope="col" class="px-4 py-3">Countries</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border-b dark:border-gray-700" v-for="item in list.data" :key="item.id">
                                <td class="px-4 py-3">{{ item.title }}</td>
                                <td class="px-4 py-3 h-[4.5rem]">
                                    <img :src="item.thumbnail" class="w-[6rem] h-auto" v-if="item.thumbnail"
                                         :alt="item.title"/>
                                    <span v-else>No Image</span>
                                </td>
                                <td class="px-4 py-3"
                                    :class="{'text-red-500' : !item.is_active, 'text-green-500' : item.is_active }">
                                    {{ item.is_active ? 'Active' : 'Inactive' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-x-2 flex-wrap gap-y-2 max-w-[40%]">
                                        <div class="py-1 px-2 bg-blue-600 text-white text-xs rounded-full cursor-pointer hover:bg-blue-800"
                                             @click="selectCountry(country)"
                                             v-for="country in item.countries">
                                            {{ country.name }}
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3 flex items-center justify-end"
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
                                                <a :href="item.link"
                                                   target="_blank"
                                                   class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">View</a>
                                            </li>
                                            <li v-if="item.can.edit">
                                                <Link :href="route('admin.offers.edit', [category.slug, item.id])"
                                                      class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    Edit
                                                </Link>
                                            </li>
                                        </ul>
                                        <div class="py-1" v-if="item.can.delete">
                                            <DeleteItemModal :url="route('admin.offers.destroy', item.id)" v-if="!item.deleted_at"
                                                             v-slot="slotProps"
                                                             @deleted="itemDeleted(item)">
                                                <span @click="slotProps.confirmItemDeletion"
                                                      class="cursor-pointer text-red-400 block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-red-500 dark:hover:text-white">Delete</span>
                                            </DeleteItemModal>
                                            <RecoverItemModal :url="route('admin.offers.recover', item.id)" v-else
                                                              v-slot="slotProps"
                                                              @recovered="itemRecovered(item)">
                                                <span @click="slotProps.confirmItemRecovery"
                                                      class="cursor-pointer text-yellow-500 block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-yellow-600 dark:hover:text-white">Recover</span>
                                            </RecoverItemModal>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
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
import {includes, isEmpty, mapValues, pickBy} from "lodash";
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
import Multiselect from "vue-multiselect";

export default {
    name: "Index",
    components: {
        Multiselect,
        FiltersWrapper,
        RecoverItemModal,
        Filter,
        TrashedFilter,
        DeleteItemModal, DangerButton, DeleteProfileForm, Search, Pagination, AuthenticatedLayout, Head, Link
    },
    props: {
        list: Object,
        filters: Object,
        statuses: Object,
        category: Object,
        countries: Array
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed || null,
                isActive: this.filters.isActive || null,
                countries: this.filters.isActive || [],
            },
            isActiveOptions: [
                {
                    value: null,
                    label: 'All',
                },
                {
                    value: 'active',
                    label: 'Active',
                },
                {
                    value: 'inactive',
                    label: 'Inactive',
                }
            ],
            filterCountries: this.countries.filter(country => includes(this.filters.countries || [], country.id.toString()))
        }
    },
    watch: {
        form: {
            handler: throttle(function () {
                const query = pickBy(this.form),
                request = Object.keys(query).length ? query : {remember: 'forget'};
                request.offerCategory = this.category.slug;
                this.$inertia.replace(this.route('admin.offers.index', request));
            }, 150),

            deep: true
        },
        filterCountries: {
            handler() {
                this.form.countries = this.filterCountries.map(country => country.id);
            }
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
        selectCountry(country) {
            this.form.countries = [country.id];
            this.filterCountries = [country];
        }
    }
}
</script>
<style scoped>

</style>
