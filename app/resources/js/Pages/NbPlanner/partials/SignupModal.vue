<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";

const confirmingItemDeletion = ref(false),
    props = defineProps({
        url: {
            type: String,
        },
        warningText: {
            type: String,
            default: null
        }
    }),
    emit = defineEmits(['closed']),
    closeModal = () => {
        emit('closed')
    };
</script>

<template>
    <Modal :show="true"  @close="closeModal">
        <div class="p-6">
            <h2 class="text-xl sm:text-2xl text-center font-medium text-gray-900 dark:text-gray-100">
                Signup for free to continue
            </h2>

            <p class="py-4 sm:text-lg text-gray-600 dark:text-gray-400">
                Welcome to your personalized newborn checklist! To make the most of this journey, sign up for free and unlock your own member profile. Save your progress, track your checklist, and return anytime with everything exactly where you left off. Join our community of new parents today — it’s quick, easy, and completely free!
            </p>


            <div class="p-8 text-center">
                <a class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:focus:ring-offset-gray-800 md:text-5xl text-3xl bg-green-500 hover:bg-green-400 dark:bg-green-600 dark:hover:bg-green-500 sm:p-4"
                   :href="route('signup')">
                    Join for Free!
                </a>
            </div>
        </div>
    </Modal>
</template>
