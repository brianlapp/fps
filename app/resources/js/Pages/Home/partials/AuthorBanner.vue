<script>
export default {
    name: "AuthorBanner",
    data() {
        return {
            hideImmediately: false
        }
    },
    computed: {
        show() {
            return !this.hideImmediately && !localStorage.getItem(this.storageKey) && this.$page.props.auth.user?.prompt_attribution;
        },
        storageKey() {
            return (this.$page.props.auth.user?.email || 0).toString().concat('-hide-author-banner');
        }
    },
    methods: {
        hide() {
            localStorage.setItem(this.storageKey, '1');
            this.hideImmediately = true;
        }
    }
}
</script>

<template>

    <div class="bg-green-200 text-green-700 text-center p-2 rounded-md relative pt-5 sm:p-2" v-if="show">
        <p class="font font text-sm ">
            Your Name Will Appear As Prompt Author On All Your Articles:
            <a :href="route('profile.edit')"
               class="bg-blue-500 text-white rounded mt-2 sm:mt-0 px-3 py-1 text-sm font-bold ml-2 block sm:inline-flex">Change
                Settings HERE</a>
        </p>
        <svg class="w-3 h-3 absolute top-2 right-2 sm:top-4 sm:right-3 cursor-pointer hover:text-green-900" aria-hidden="true"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14" @click="hide">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
    </div>
</template>

<style scoped>

</style>
