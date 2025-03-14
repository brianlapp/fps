<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm, usePage} from '@inertiajs/vue3';
import {nextTick, ref} from 'vue';

const confirmingUserDeletion = ref(false),
    passwordInput = ref(null),
    props = defineProps({
        user: {
            type: Object,
            default() {
                return null;
            }
        }
    }), form = useForm({
        password: '',
    }), confirmUserDeletion = () => {
        confirmingUserDeletion.value = true;

        nextTick(() => passwordInput.value.focus());
    },
    deleteUser = () => {
        form.delete(isCurrentUser ? route('admin.profile.destroy') : route('admin.team.destroy', props.user.id), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => passwordInput.value.focus(),
            onFinish: () => form.reset(),
        });
    },
    closeModal = () => {
        confirmingUserDeletion.value = false;

        form.reset();
    },
    isCurrentUser = props.user?.id === usePage().props.auth.user.id;

</script>

<template>
    <section class="space-y-6">
        <slot :confirmUserDeletion="confirmUserDeletion">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Delete Account</h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" v-if="isCurrentUser">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
                    your account, please download any data or information that you wish to retain.
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" v-else>
                    Once the account is deleted, all of its resources and data will be permanently deleted.
                </p>
            </header>
            <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>
        </slot>



        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete {{ isCurrentUser ? 'your' : props.user.name.concat("'s") }} account?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete {{ isCurrentUser ? 'your' : props.user.name.concat("'s") }} account.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only"/>

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2"/>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
