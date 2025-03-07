<script>
import Filter from "@/Components/Admin/Filter.vue";

export default {
    name: "TrashedFilter",
    components: {Filter},
    props: {
        modelValue: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            value: this.modelValue,
            options: [
                {
                    value: null,
                    label: 'Not Trashed',
                },
                {
                    value: 'with',
                    label: 'With Trashed',
                },
                {
                    value: 'only',
                    label: 'Only Trashed',
                }
            ]
        }
    },
    watch: {
        value: {
            handler(val) {
                this.$emit('update:modelValue', val);
            }
        }
    }
}
</script>

<template>
   <Filter :options="options" label="Trashed" v-model="value"/>
</template>

<style scoped>

</style>
