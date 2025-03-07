<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import WarningButton from "@/Components/WarningButton.vue";

const confirming = ref(false),
    props = defineProps({
        buttonTitle: {
            type: String,
            default: null
        },
        warningText: {
            type: String,
            default: null
        },
        isProcessing: {
            type: Boolean,
            default: false
        }
    }),
    emit = defineEmits(['confirmed']),
    confirmItemDeletion = () => {
        confirming.value = true;
    },
    confirm = () => {
        emit('confirmed');
        closeModal();
    },
    closeModal = () => {
        confirming.value = false;
    };
</script>

<template>
    <section class="space-y-6">
        <slot :confirmItemDeletion="confirmItemDeletion">
            <WarningButton @click="confirmItemDeletion">{{props.buttonTitle}}</WarningButton>
        </slot>


        <Modal :show="confirming" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Please confirm your action
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" v-if="warningText">
                    {{ warningText }}
                </p>


                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal" class="w-fit"> Cancel</SecondaryButton>

                    <WarningButton
                        class="ms-3 w-fit"
                        :class="{ 'opacity-25': props.isProcessing }"
                        :disabled="props.isProcessing"
                        @click="confirm"
                    >
                        Confirm
                    </WarningButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
