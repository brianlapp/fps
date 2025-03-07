<script setup>
import Modal from '@/Components/Modal.vue';
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
        originalContent: {
            type: String,
        },
        improvedContent: {
            type: String,
            default: null
        }
    }),
    emit = defineEmits(['close']),
    closeModal = () => {
       emit('close')
    };
</script>

<template>
    <div>
        <Modal @close="closeModal" :show="true" max-width="full" class="w-full max-w-7xl">
            <DangerButton @click="closeModal" class="mx-auto absolute right-0">
                CLOSE
            </DangerButton>
            <div class="p-4 flex gap-x-8 h-screen overflow-y-auto">
                <div class="flex-1 text-gray-700 dark:text-gray-300">
                    <h2 class="text-3xl mb-8 pb-4 w-full border-b">Original</h2>
                    <div class="article-content  leading-relaxed pb-8" v-html="originalContent"/>
                </div>
                <div class="flex-1 text-gray-700 dark:text-gray-300">
                    <h2 class="text-3xl mb-8 pb-4 w-full border-b">Improved</h2>
                    <div class="article-content leading-relaxed pb-8" v-html="improvedContent"/>
                </div>
            </div>
        </Modal>
    </div>

</template>
