<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import {Head, usePage} from '@inertiajs/vue3';
import ComingSoon from "@/Components/ComingSoon.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    hasPassword: {
        type: Boolean,
    },
});

const user = usePage().props.auth.user;
</script>

<template>
    <Head title="Profile" />

    <MainLayout>
        <template #header>
            <ul class="flex flex-col gap-y-2 sm:flex-row sm:gap-x-4 text-gray-900 dark:text-gray-100">
                <li class="border rounded p-2 hover:bg-blue-200 dark:hover:bg-blue-600 relative">
                    <i class="fa fa-bookmark mr-2"/><a :href="route('articles.favorites')">Saved Articles</a>
                </li>
                <li class="border rounded p-2 hover:bg-blue-200 dark:hover:bg-blue-600">
                    <i class="fa fa-user mr-2"/><a :href="route('prompt_author.show', user.slug)">My articles</a>
                </li>
                <li class="border rounded p-2 hover:bg-blue-200 dark:hover:bg-blue-600 relative">
                    <i class="fa fa-search mr-2"/> <a :href="'#'">Recent Searches</a>
                </li>
            </ul>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdatePasswordForm :has-password="hasPassword" class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </MainLayout>
</template>
