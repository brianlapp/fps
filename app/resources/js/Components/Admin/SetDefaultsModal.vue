<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import WarningButton from "@/Components/WarningButton.vue";

const confirming = ref(false),
    props = defineProps({
        url: {
            type: String,
        },
        warningText: {
            type: String,
            default: null
        }
    }),
    emit = defineEmits(['updated']),
    form = useForm({}),
    confirm = () => {
        confirming.value = true;
    },
    deleteItem = () => {
        form.patch(props.url, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                emit('updated');
            },
            onFinish: () => form.reset(),
        });
    },
    closeModal = () => {
        confirming.value = false;

        form.reset();
    };
</script>

<template>
    <section class="space-y-6">
        <slot :confirm="confirm">
            <WarningButton class="w-fit" @click="confirm">Set Defaults</WarningButton>
        </slot>


        <Modal :show="confirming" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to set Default Parameters
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
                        Set Defaults
                    </WarningButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
