<template>
    <Head title="Articles"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4">Articles</h2>
            <div
                class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <Search v-model="form.search"/>
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <ConfirmModal
                        v-slot="slotProps"
                        ref="resetFeaturedModal"
                        :is-processing="isResettingFeatured"
                        button-title="Reset Featured Articles"
                        warning-text="All Featured Articles will become not Featured!"
                        @confirmed="resetFeatured"/>
                    <Link :href="route('admin.articles.create')"
                          class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                        <i class="fa-solid fa-plus w-4 mr-2"></i>
                        Create Article
                    </Link>
                    <div class="flex items-center space-x-3 w-full md:w-auto">
                        <FiltersWrapper :filters="form">
                            <div>
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Choose Status</h6>
                                <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                    <li class="flex items-center" v-for="(value, label) in statuses" :key="label">
                                        <input :id="label" type="checkbox" :value="value" v-model="form.status"
                                               class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500 cursor-pointer">
                                        <label :for="label"
                                               class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ label }}
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Pages</h6>
                                <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                    <li class="flex items-center" v-for="(value, label) in pagesFilter" :key="label">
                                        <input :id="label" type="checkbox" :value="value" v-model="form.pages"
                                               class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500 cursor-pointer">
                                        <label :for="label"
                                               class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ label }}
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Pages</h6>
                                <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                    <li class="flex items-center" v-for="(value, label) in featuredFilter" :key="label">
                                        <input :id="label" type="checkbox" :value="value" v-model="form.featured"
                                               class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500 cursor-pointer">
                                        <label :for="label"
                                               class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ label }}
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Improved</h6>
                                <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                    <li class="flex items-center" v-for="(value, label) in improvedFilter" :key="label">
                                        <input :id="label" type="checkbox" :value="value"
                                               :checked="form.improved === value" @change="form.improved = value"
                                               class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500 cursor-pointer">
                                        <label :for="label"
                                               class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ label }}
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </FiltersWrapper>
                    </div>
                </div>
            </div>
        </template>
        <section class="bg-gray-50 dark:bg-gray-900 p-3 min-h-[90vh]">
            <div class="mx-auto w-full px-4 lg:px-9">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                    <div class="overflow-x-auto" v-if="list.data.length > 0">
                        <table class="w-full text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Title</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3">Improved</th>
                                <th scope="col" class="px-4 py-3">Featured</th>
                                <th scope="col" class="px-4 py-3">Author</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border-b dark:border-gray-700" v-for="item in list.data" :key="item.id">
                                <td class="px-4 py-3">
                                    <span class="mr-2 bg-primary-500 text-white rounded px-2 py-1" v-if="item.is_page">PAGE</span>
                                    <span class="mr-2 bg-orange-400 text-white rounded px-2 py-1"
                                          v-if="item.is_featured">FEATURED</span>
                                    {{ item.title }}
                                </td>
                                <td class="px-4 py-3">{{ item.status }}</td>
                                <td class="px-4 py-3 ">
                                    <span class="text-xs"
                                          :class="{'text-red-600': !item.was_improved, 'text-green-600': item.was_improved }">{{
                                            item.was_improved ? 'YES' : 'NO'
                                        }}</span>
                                </td>
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex flex-row gap-x-3 items-center">
                                        <img class="rounded-full w-8 h-8" :src="item.author?.avatar"
                                             :alt="item.author?.name"/>
                                        {{ item.author?.name }}
                                    </div>

                                </th>

                                <td class="px-4 py-3 flex items-center justify-end">
                                    <button :id="item.id.toString().concat('-dropdown-button')"
                                            :data-dropdown-toggle="item.id.toString().concat('-dropdown')"
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                            type="button">
                                        <i class="fa-solid fa-ellipsis w-4 ml-2"></i>
                                    </button>
                                    <div :id="item.id.toString().concat('-dropdown')"
                                         class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby="apple-imac-27-dropdown-button">
                                            <li>
                                                <a :href="route((item.is_page ? 'page' : 'articles.show'), item.slug)"
                                                   target="_blank"
                                                   class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">View</a>
                                            </li>
                                            <li v-if="item.can.edit">
                                                <Link :href="route('admin.articles.edit', item.id)"
                                                      class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    Edit
                                                </Link>
                                            </li>
                                        </ul>
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
import {mapValues, pickBy} from "lodash";
import {Head, Link} from '@inertiajs/vue3';
import Pagination from "@/Components/Admin/Table/Pagination.vue";
import Search from "@/Components/Admin/Table/Search.vue";
import DeleteProfileForm from "@/Pages/Admin/Profile/Partials/DeleteProfileForm.vue";
import DangerButton from "@/Components/DangerButton.vue";
import FiltersWrapper from "@/Components/Admin/FiltersWrapper.vue";
import DeleteItemModal from "@/Components/Admin/DeleteItemModal.vue";
import ConfirmModal from "@/Components/Admin/ConfirmModal.vue";

export default {
    name: "Index",
    components: {
        ConfirmModal,
        DeleteItemModal,
        FiltersWrapper, DangerButton, DeleteProfileForm, Search, Pagination, AuthenticatedLayout, Head, Link},
    props: {
        list: Object,
        filters: Object,
        statuses: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed,
                status: this.filters.status || [],
                pages: this.filters.pages || null,
                improved: this.filters.improved || null,
                featured: this.filters.featured || null,
            },
            pagesFilter: {
                'Pages Only': 1,
            },
            featuredFilter: {
                'Featured Only': 1,
            },
            improvedFilter: {
                'All': null,
                'Improved Only': 'yes',
                'Non-Improved Only': 'no',
            },
            isResettingFeatured: false
        }
    },
    watch: {
        form: {
            handler: throttle(function () {
                let query = pickBy(this.form)
                this.$inertia.replace(this.route('admin.articles.index', Object.keys(query).length ? query : {remember: 'forget'}))
            }, 150),

            deep: true
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null);
        },
        resetFeatured() {
            this.isResettingFeatured = true;
            axios.get(route('admin.articles.resetFeatured', this.article?.id)).then(({data}) => {
                this.list.data.forEach(item => {
                    item.is_featured = false;
                })
            }).catch(e => {
                this.$page.props.flash.error = e?.response?.data?.error || e;
            }).finally(() => {
                this.isResettingFeatured = false;
            });
        },
    }

}
</script>
<style scoped>

</style>
