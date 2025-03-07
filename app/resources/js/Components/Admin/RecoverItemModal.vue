<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import WarningButton from "@/Components/WarningButton.vue";

const confirmingItemRecovery = ref(false),
    props = defineProps({
        url: {
            type: String,
        },
        warningText: {
            type: String,
            default: null
        }
    }),
    emit = defineEmits(['deleted']),
    form = useForm({}),
    confirmItemRecovery = () => {
        confirmingItemRecovery.value = true;
    },
    deleteItem = () => {
        form.delete(props.url, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                emit('recovered');
            },
            onFinish: () => form.reset(),
        });
    },
    closeModal = () => {
        confirmingItemRecovery.value = false;

        form.reset();
    };
</script>

<template>
    <section class="space-y-6">
        <slot :confirmItemRecovery="confirmItemRecovery">
            <WarningButton @click="confirmItemRecovery">Recover Item</WarningButton>
        </slot>


        <Modal :show="confirmingItemRecovery" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to recover the item?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" v-if="warningText">
                    {{ warningText }}
                </p>


                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal" class="w-fit"> Cancel</SecondaryButton>

                    <WarningButton
                        class="ms-3 w-fit"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteItem"
                    >
                        Recover Item
                    </WarningButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
