<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';

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
    emit = defineEmits(['deleted']),
    form = useForm({}),
    confirmItemDeletion = () => {
        confirmingItemDeletion.value = true;
    },
    deleteItem = () => {
        form.delete(props.url, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                emit('deleted');
            },
            onFinish: () => form.reset(),
        });
    },
    closeModal = () => {
        confirmingItemDeletion.value = false;

        form.reset();
    };
</script>

<template>
    <section class="space-y-6">
        <slot :confirmItemDeletion="confirmItemDeletion">
            <DangerButton @click="confirmItemDeletion">Delete Item</DangerButton>
        </slot>


        <Modal :show="confirmingItemDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete the item?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" v-if="warningText">
                    {{ warningText }}
                </p>


                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal" class="w-fit"> Cancel</SecondaryButton>

                    <DangerButton
                        class="ms-3 w-fit"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteItem"
                    >
                        Delete Item
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
