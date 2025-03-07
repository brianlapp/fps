<template xmlns="http://www.w3.org/1999/html">
    <div class="relative">

        <img class="w-[16rem]" alt="image" :src="modelValue" v-if="modelValue"
             :style="{height: (height ? height.toString().concat('rem') : 'auto')}"/>
        <div class="w-[16rem] min-h-[9rem] bg-gray-200 dark:bg-gray-400 flex justify-center items-center"
             :style="{height: (height ? height.toString().concat('rem') : 'auto')}" v-else>
            <span class="text-gray-500 dark:text-gray-600"> No Image</span>
        </div>
        <div
            class="absolute top-0 p-0 left-0 bg-gray-400 dark:bg-gray-600 w-[16rem] h-full opacity-0 hover:opacity-50 cursor-pointer flex items-center justify-center"
            @click="openModal">
            <i class="fa-solid fa-edit w-4 text-gray-900 dark:text-gray-100 opacity-100"/>
        </div>
        <Modal :show="showModal" @close="closeModal">
            <div class="p-6">
                <div class="min-w-2xl min-h-[400px]">
                    <Cropper v-if="pic"
                             :boxStyle="cropperConfig.boxStyle"
                             :img="pic"
                             :options="cropperConfig.options"
                             :presetMode="cropperConfig.presetMode"
                    />
                    <p class="mt-1 p-0 text-xs italic text-gray-600 dark:text-gray-400 mb-4" v-if="caption">{{caption}}</p>
                </div>


                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between mb-6 cursor-pointer">
                    <PrimaryButton>
                        <span>Select Picture</span>
                        <input
                            ref="uploadInput"
                            class="absolute opacity-0 cursor-pointer"
                            type="file"
                            accept="image/jpg, image/jpeg, image/png, image/webp"
                            @change="selectFile"
                        />
                    </PrimaryButton>
                </h2>

                <div class="mt-6 flex border-t border-gray-400 pt-5"
                     :class="{'justify-between': generateUrl, 'justify-end': !generateUrl }">
                    <PrimaryButton
                        class="w-fit"
                        :class="{ 'opacity-25': isGenerating}"
                        v-if="generateUrl"
                        @click="generate"
                    >
                        {{ isGenerating ? 'Generating....' : 'Generate an Image' }}
                    </PrimaryButton>
                    <div class="flex justify-end">
                        <DangerButton @click="remove" class="mr-3 w-fit" v-if="modelValue"> Remove</DangerButton>
                        <SecondaryButton @click="closeModal" class="w-fit"> Cancel</SecondaryButton>

                        <PrimaryButton
                            class="ms-3 w-fit"
                            :class="{ 'opacity-25': !pic}"
                            :disabled="!pic"
                            @click="crop"
                        >
                            Crop
                        </PrimaryButton>
                    </div>
                </div>
                <InputError class="mt-3" :message="generationError" v-if="generationError"/>
            </div>
        </Modal>
    </div>

</template>
<script>

import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Cropper, {cropper} from 'vue-picture-cropper';

export default {
    name: 'image-editor',
    props: {
        modelValue: {
            type: String,
            default: null
        },
        aspectRatio: {
            type: Number,
            default: 1792 / 1024
        },
        generateUrl: {
            type: String,
            default: null
        }
    },
    components: {PrimaryButton, InputLabel, InputError, DangerButton, SecondaryButton, TextInput, Modal, Cropper},
    data() {
        return {
            showModal: false,
            pic: null,
            cropperConfig: {
                boxStyle: {
                    width: '100%',
                    height: '100%',
                    backgroundColor: '#f8f8f8',
                    'min-height': '200px',
                    margin: 'auto',
                },
                options: {
                    viewMode: 1,
                    dragMode: 'move',
                    aspectRatio: this.aspectRatio,
                    cropBoxResizable: true,
                },
                presetMode: {
                    mode: 'rectangle',
                    width: 430,
                }
            },
            isGenerating: false,
            generationError: null,
            caption: null
        }
    },
    methods: {
        openModal() {
            this.showModal = true;
            this.pic = this.modelValue;
        },
        closeModal() {
            this.showModal = false;
            this.pic = null;
        },
        remove() {
            this.$emit('update:modelValue', null);
            this.closeModal();
        },
        crop() {
            if (!cropper) {
                return
            }
            this.$emit('update:modelValue', cropper.getDataURL());
            this.$emit('setCaption', this.caption);
            this.closeModal();
        },
        selectFile(e) {
            this.pic = null;

            // Get selected files
            const {files} = e.target
            if (!files || !files.length) {
                return
            }

            // Convert to dataURL and pass to the cropper component
            const file = files[0]
            const reader = new FileReader()
            reader.readAsDataURL(file)
            reader.onload = () => {
                // Update the picture source of the `img` prop
                this.pic = String(reader.result)

                // Clear selected files of input element
                if (!this.$refs.uploadInput?.value) {
                    return
                }
                this.$refs.uploadInput.value = ''
            }
        },
        generate() {
            this.isGenerating = true;
            this.generationError = null;
            axios.get(this.generateUrl).then(({data}) => {
                this.pic = data.image;
                this.caption = data.caption;
            }).catch((e) => {
                this.generationError = e.response.data.error || 'Something Went Wrong';
            }).finally(() => {
                this.isGenerating = false;
            });
        }
    },
    computed: {
        height() {
            return this.aspectRatio ?  Math.round(16 / (this.aspectRatio)) : null;
        }
    }
}
</script>
