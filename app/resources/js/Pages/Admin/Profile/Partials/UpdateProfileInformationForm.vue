<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Link, useForm, usePage} from '@inertiajs/vue3';
import ProfileAvatar from "@/Pages/Admin/Profile/Partials/Avatar.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import WYSIWYG from "@/Components/Admin/WYSIWYG.vue";

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
        type: Object
    },
    roles: {
        type: Object,
        default: () => null
    }
});

const form = useForm({
        name: props.user.name,
        email: props.user.email,
        title: props.user.title,
        short_bio: props.user.short_bio,
        long_bio: props.user.long_bio,
        role: props.user.role_id || null,
        password: null,
        password_confirmation: null,
    }),
    isCurrentUser = props.user.id === usePage().props.auth.user.id;
</script>

<template>
    <section>
        <header>
            <profile-avatar class="absolute -top-[4rem] -left-[5rem]" :user="user" v-if="user.id"/>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100" :class="{'pl-[5rem]': user.id}">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 pl-[5rem]" v-if="isCurrentUser">
                Update your account's profile information and email address.
            </p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 pl-[5rem]" v-else-if="user.id">
                Update the account's profile information and email address.
            </p>

        </header>

        <form @submit.prevent="user.id ? form.patch(isCurrentUser ? route('admin.profile.update') : route('admin.team.update', user.id)) : form.post(route('admin.team.store'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Name"/>

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name"/>
            </div>

            <div>
                <InputLabel for="email" value="Email"/>
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="off"
                />

                <InputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div>
                <InputLabel for="title" value="Title"/>
                <TextInput
                    id="title"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.title"
                    required
                />

                <InputError class="mt-2" :message="form.errors.title"/>
            </div>
            <div v-if="roles">
                <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                <select id="role" v-model="form.role" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option v-for="(role, id) in roles" :value="parseInt(id)">{{ role }}</option>
                </select>
            </div>
            <div>
                <InputLabel for="shortBio" value="Short Bio"/>
                <TextareaInput
                    id="shortBio"
                    maxlength="255"
                    class="mt-1 block w-full"
                    v-model="form.short_bio"
                />

                <InputError class="mt-2" :message="form.errors.short_bio"/>
            </div>

            <div>
                <InputLabel for="longBio" value="Long Bio"/>
                <WYSIWYG v-model="form.long_bio" entity="admin"/>

                <InputError class="mt-2" :message="form.errors.long_bio"/>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null && !isCurrentUser">
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <Link
                        :href="route('admin.verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div v-if="!user.id">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div v-if="!user.id">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
