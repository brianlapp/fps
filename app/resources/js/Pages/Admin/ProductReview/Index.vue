<template>
    <Head title="Reviews"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4">Reviews</h2>
            <div
                class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <Search v-model="form.search"/>
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <div class="flex items-center space-x-3 w-full md:w-auto relative">
                        <FiltersWrapper :filters="form" dropdown-class="w-72">
                            <Filter :options="rateOptions" v-model="form.rate" label="Rate"/>
                            <Filter :options="statusOptions" v-model="form.status" label="Status"/>
                            <div class="border-b dark:border-gray-600"/>
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
                                <th scope="col" class="px-4 py-3">Author</th>
                                <th scope="col" class="px-4 py-3">Product</th>
                                <th scope="col" class="px-4 py-3">Rate</th>
                                <th scope="col" class="px-4 py-3">Content</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3">Is verified by AI</th>
                                <th scope="col" class="px-4 py-3">Timestamp</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border-b dark:border-gray-700" v-for="item in list.data" :key="item.id">
                                <td class="px-4 py-3">{{ item.user.name }}</td>
                                <td class="px-4 py-3 article-content">
                                    <a :href="route('admin.products.edit', item.product.id)">
                                        {{ item.product.title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <span class="text-yellow-400 text-lg">{{ getStars(item.rate) }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div data-accordion="collapse">
                                        <h2 :id="'accordion-collapse-heading-'.concat(item.id)">
                                            <button type="button"
                                                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                                                    :data-accordion-target="'#accordion-collapse-content-'.concat(item.id)"
                                                    aria-expanded="false"
                                                    :aria-controls="'accordion-collapse-content-'.concat(item.id)">
                                                <span> {{ item.title }}</span>
                                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0"
                                                     aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                     viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                          stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                                                </svg>
                                            </button>
                                        </h2>

                                        <div :id="'accordion-collapse-content-'.concat(item.id)" class="hidden"
                                             :aria-labelledby="'accordion-collapse-heading-'.concat(item.id)">
                                            <div
                                                class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                                                {{ item.content }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 capitalize"
                                    :class="{'text-red-500' : item.status === 'declined', 'text-green-500' : item.status === 'approved',  'text-yellow-500' : item.status === 'pending', }">
                                    {{ item.status }}
                                </td>
                                <td class="px-4 py-3"
                                    :class="{'text-red-500' : item.ai_error, 'text-green-500' : item.is_verified_by_ai,  'text-yellow-500' : !item.ai_error && !item.is_verified_by_ai, }">
                                    {{ item.ai_error ? 'ERROR!' : (item.is_verified_by_ai ? 'Yes' : 'No') }}
                                </td>
                                <td class="px-4 py-3">{{ formatDate(item.created_at, 'YYYY-M-D H:mm') }}</td>
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
                                        <div class="py-1" v-if="item.can.delete">
                                            <DeleteItemModal :url="route('admin.product_reviews.destroy', item.id)"
                                                             v-if="!item.deleted_at"
                                                             v-slot="slotProps"
                                                             warning-text="All Products related to this will be deleted!"
                                                             @deleted="itemDeleted(item)">
                                                <span @click="slotProps.confirmItemDeletion"
                                                      class="cursor-pointer text-red-400 block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-red-500 dark:hover:text-white">Delete</span>
                                            </DeleteItemModal>
                                            <RecoverItemModal :url="route('admin.product_reviews.recover', item.id)"
                                                              v-else
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
import ProductReviewsMixin from "@/Mixins/ProductReviewsMixin.vue";
import dataMixin from "@/Mixins/DataMixin.vue";

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
    mixins: [ProductReviewsMixin, dataMixin],
    props: {
        list: Object,
        filters: Object,
        statuses: Array
    },
    data() {
        const rateOptions = [
                {
                    value: null,
                    label: 'All',
                }
            ];
        for (let i = 1; i <= (this.$page.props.app.maxRate); i++) {
            rateOptions.push({
                value: i.toString(),
                label: this.getStars(i, this.$page.props.app.maxRate),
            },)
        }

        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed || null,
                rate: this.filters.rate || null,
                status: this.filters.status || null,
            },
            rateOptions: rateOptions,
            statusOptions: [
                {
                    value: null,
                    label: 'All',
                },
                ...this.statuses
            ]
        }
    },
    watch: {
        form: {
            handler: throttle(function () {
                let query = pickBy(this.form)
                this.$inertia.replace(this.route('admin.product_reviews.index', Object.keys(query).length ? query : {remember: 'forget'}))
            }, 150),

            deep: true
        },
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
    }
}
</script>
<style scoped>

</style>
