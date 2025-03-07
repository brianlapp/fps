<script setup>
import Modal from '@/Components/Modal.vue';

const emit = defineEmits(['closed']),
    closeModal = () => {
        emit('closed')
    };
</script>

<template>
    <Modal :show="true" @close="closeModal">
        <div class="p-6">
            <h2 class="text-xl sm:text-2xl text-center font-medium text-gray-900 dark:text-gray-100">
                Signup for free to continue
            </h2>

            <div class="py-4 sm:text-lg text-gray-600 dark:text-gray-400 text-center">
                <p>
                    To submit a review you have to be a Registered User. </p>
                <p><b>Join our community</b> of new parents today — it’s
                    quick, easy, and
                    completely free!
                </p>
            </div>

            <div class="p-8 text-center">
                <a class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-5xl text-3xl bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 sm:p-4"
                   :href="route('signup')">
                    Join for Free!
                </a>
            </div>
        </div>
    </Modal>
</template>
