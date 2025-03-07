<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import UpdatePasswordForm from '@/Pages/Admin/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Admin/Profile/Partials/UpdateProfileInformationForm.vue';
import {Head} from '@inertiajs/vue3';
import DeleteProfileForm from "@/Pages/Admin/Profile/Partials/DeleteProfileForm.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
        type: Object,
        default() {
            return {};
        }
    },
    roles: {
        type: Object,
        default: () => null
    }
});

</script>

<template>
    <Head title="Teammate Profile"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Teammate Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg relative">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        :user="user"
                        :roles="roles"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg" v-if="user.id">
                    <UpdatePasswordForm class="max-w-xl" :user="user"/>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg" v-if="user.id">
                    <DeleteProfileForm :user="user" class="max-w-xl"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
