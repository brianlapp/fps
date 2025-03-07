<template>
    <Head title="Offers"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4">AI Settings</h2>
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
                                <th scope="col" class="px-4 py-3">Function</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border-b dark:border-gray-700" v-for="item in list.data" :key="item.id">
                                <td class="px-4 py-3">{{ item.function }}</td>
                                <td class="px-4 py-3 flex items-center justify-end">
                                    <Link :href="route('admin.ai-settings.edit', item.function)"
                                          class="dark:text-gray-300 hover:text-gray-600 hover:dark:text-white">
                                        <i class="fas fa-edit"></i>
                                    </Link>
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
import {isEmpty, mapValues, pickBy} from "lodash";
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

export default {
    name: "Index",
    components: {
        FiltersWrapper,
        RecoverItemModal,
        Filter,
        TrashedFilter,
        DeleteItemModal, DangerButton, DeleteProfileForm, Search, Pagination, AuthenticatedLayout, Head, Link
    },
    props: {
        list: Object,
    },
    data() {
        return {
            form: {
            },
        }
    },
    watch: {
        form: {
            handler: throttle(function () {
                const query = pickBy(this.form),
                request = Object.keys(query).length ? query : {remember: 'forget'};
                this.$inertia.replace(this.route('admin.ai-settings.index', request));
            }, 150),

            deep: true
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null);
        },
    }
}
</script>
<style scoped>

</style>
