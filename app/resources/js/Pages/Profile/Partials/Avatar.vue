<template>
    <div class="relative">
        <img class="rounded-full w-[10rem] h-[10rem]" alt="avatar" :src="user.fullAvatar"/>
        <div
            class="absolute top-0 p-0 left-0 bg-gray-400 dark:bg-gray-600 rounded-full w-[10rem] h-[10rem] opacity-0 hover:opacity-50 cursor-pointer flex items-center justify-center"
            @click="openModal">
            <i class="fa-solid fa-edit w-4 text-gray-900 dark:text-gray-100 opacity-100"/>
        </div>
        <Modal :show="showModal" @close="closeModal">
            <loading v-model:active="form.processing"
                     :is-full-page="false"/>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between mb-6">
                    <PrimaryButton>
                        <span>Select Picture</span>
                        <input
                            ref="uploadInput"
                            class="absolute opacity-0"
                            type="file"
                            accept="image/jpg, image/jpeg, image/png, image/webp"
                            @change="selectFile"
                        />
                    </PrimaryButton>
                </h2>
                <div class="text-red-300" v-if="sizeError">
                    <b>Error!</b>
                    Please select an image that is smaller than {{prettyBytes(maxFileSize)}} in size
                </div>

                <Cropper v-if="pic"
                         :boxStyle="cropperConfig.boxStyle"
                         :img="pic"
                         :options="cropperConfig.options"
                         :presetMode="cropperConfig.presetMode"
                />

                <div class="mt-6 flex justify-end border-t border-gray-400 pt-5">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                    <PrimaryButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing || !pic}"
                        :disabled="form.processing || !pic"
                        @click="crop"
                    >
                        Crop
                    </PrimaryButton>
                </div>
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
import {useForm, usePage} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Cropper, {cropper} from 'vue-picture-cropper';
import Loading from "vue-loading-overlay";
import stringMixin from "@/Mixins/StringMixin.vue";

export default {
    name: 'profile-avatar',
    props: {
        user: Object
    },
    mixins: [stringMixin],
    components: {
        Loading,
        PrimaryButton, InputLabel, InputError, DangerButton, SecondaryButton, TextInput, Modal, Cropper
    },
    data() {
        return {
            showModal: false,
            form: useForm({
                'avatar': null
            }),
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
                    aspectRatio: 1,
                    cropBoxResizable: true,
                },
                presetMode: {
                    mode: 'round',
                    width: 600,
                    height: 600,
                }
            },
            maxFileSize: 2 * 1024 * 1024,
            sizeError: false
        }
    },
    methods: {
        openModal() {
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.pic = null;
            this.form.reset();
        },
        crop() {
            if (!cropper) {
                return
            }
            this.form.avatar = cropper.getDataURL()

            this.form.post(this.route('profile.avatar'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.user.fullAvatar = cropper.getDataURL();
                    this.closeModal();
                },
                // onError: () => this.$page.props.flash.error = this.$t('common.something_wrong'),
                onError: (e) => console.error(e),
            });
        },
        selectFile(e) {
            this.pic = null;
            this.sizeError = false;
            // Get selected files
            const {files} = e.target
            if (!files || !files.length) {
                return
            }

            // Convert to dataURL and pass to the cropper component
            const file = files[0];
            if (file.size > this.maxFileSize) {
                return this.sizeError = true;
            }

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
        }
    }
}
</script>
