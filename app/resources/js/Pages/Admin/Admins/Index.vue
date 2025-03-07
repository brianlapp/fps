<template>
    <Head title="Team"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4">Team</h2>

            <div
                class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <Search v-model="form.search"/>
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <Link :href="route('admin.team.create')" type="button"
                          class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                        <i class="fa-solid fa-plus w-4 mr-2"></i>
                        Add Teammate
                    </Link>
                    <div class="flex items-center space-x-3 w-full md:w-auto">
                        <FiltersWrapper :filters="form">
                            <div>
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Choose role</h6>
                                <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                    <li class="flex items-center" v-for="(role,i) in roles" :key="role">
                                        <input :id="role" type="checkbox" :value="i" v-model="form.role"
                                               class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label :for="role"
                                               class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ role }}
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </FiltersWrapper>
                    </div>
                </div>
            </div>
        </template>
        <section class="bg-gray-50 dark:bg-gray-900 px-3 py-5 min-h-[90vh]">
            <div class="mx-auto w-full px-4 lg:px-12">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Name</th>
                                <th scope="col" class="px-4 py-3">Email</th>
                                <th scope="col" class="px-4 py-3">Role</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border-b dark:border-gray-700" v-for="item in list.data" :key="item.id">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex flex-row gap-x-3 items-center">
                                        <img class="rounded-full w-8 h-8" :src="item.avatar" :alt="item.name"/>
                                        {{ item.name }}
                                    </div>

                                </th>
                                <td class="px-4 py-3">{{ item.email }}</td>
                                <td class="px-4 py-3">{{ item.role }}</td>
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
                                                <a :href="route('author.show', item.slug)"
                                                   target="_blank"
                                                   class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">See
                                                    Page</a>
                                            </li>
                                            <li>
                                                <Link :href="route('admin.team.edit', item.id)"
                                                      class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    Edit
                                                </Link>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <DeleteProfileForm :user="item" v-slot="slotProps">
                                                <span @click="slotProps.confirmUserDeletion"
                                                      class="cursor-pointer text-red-400 block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-red-500 dark:hover:text-white">Delete</span>
                                            </DeleteProfileForm>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <Pagination :list="list"/>
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

export default {
    name: "Index",
    components: {FiltersWrapper, DangerButton, DeleteProfileForm, Search, Pagination, AuthenticatedLayout, Head, Link},
    props: {
        list: Object,
        filters: Object,
        roles: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed,
                role: this.filters.role || [],
            }
        }
    },
    watch: {
        form: {
            handler: throttle(function () {
                let query = pickBy(this.form)
                this.$inertia.replace(this.route('admin.team.index', Object.keys(query).length ? query : {remember: 'forget'}))
            }, 150),

            deep: true
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null);
        }
    }

}
</script>
<style scoped>

</style>
