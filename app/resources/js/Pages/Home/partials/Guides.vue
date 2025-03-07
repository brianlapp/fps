<script>
export default {
    name: "Guides",
    data() {
        return {
            guides: [
                {
                    id: 1,
                    title: 'What to Expect When You First Come Home from the Hospital with Your Baby',
                    description: 'Bringing your newborn home is an incredibly special moment, but it can also be overwhelming and filled with surprises. This guide is here to help you navigate those first few days and weeks, providing practical tips and reassurance.',
                    image: 'https://i.imgur.com/NEpxWij.png',
                    viewUrl: '#',
                    downloadUrl: '#',
                },
                {
                    id: 2,
                    title: 'Financial Plan for Canadian Parents',
                    description: 'This guide from FreeParentSearch.com offers practical financial planning tips, from saving for education to maximizing rewards programs and smart shopping. Plus, you\'ll discover free samples, savings, articles, tips, and fun activities for kids—all available on our website.',
                    image: 'https://i.imgur.com/81ieKhk.png',
                    viewUrl: '#',
                    downloadUrl: '#',
                }
            ],
            isLoggedIn: !!this.$page.props.auth.user?.id
        }
    }
}
</script>

<template>
    <div class="py-8">
        <div>
            <h2 class="font-bold text-center mb-4 text-3xl text-slate-900 dark:text-slate-100 md:mb-6 lg:text-4xl">
                Featured Guides</h2>
            <p class="max-w-screen-md md:text-lg text-center mx-auto dark:text-white">Become a member and access
                featured
                guides!</p>
        </div>
        <div class="flex flex-col gap-y-5 max-w-7xl mx-auto py-16 text-xl lg:grid lg:grid-cols-2 lg:gap-x-5 px-4">
            <div
                class="p-5 grid grid-cols-6 lg:relative gap-x-2 dark:bg-gray-800 dark:text-gray-100 "
                v-for="guide in guides" :key="guide.id">
                <div class="col-span-4 flex flex-col justify-between">
                    <h1 class="text-slate-900 dark:text-white text-xl font-bold">
                        {{ guide.title }}
                    </h1>
                    <p class="text-sm my-4">
                        {{ guide.description }}
                    </p>
                    <div class="flex gap-x-2" v-if="isLoggedIn">
                        <a class="items-center rounded border-2 px-2 font-black bg-primary-400 border-primary-400 dark:bg-primary-500 dark:border-primary-500 py-1 text-base text-white w-fit"
                           :href="guide.viewUrl">View
                        </a>
                        <a class="items-center rounded border-2 px-2 font-black bg-primary-400 border-primary-400 dark:bg-primary-500 dark:border-primary-500 py-1 text-base text-white w-fit"
                           :href="guide.downloadUrl">Download
                        </a>
                    </div>
                    <a class="items-center rounded border-2 px-4 font-black bg-primary-400 border-primary-400 dark:bg-primary-500 dark:border-primary-500 py-3 text-xl text-white w-fit"
                       :href="route('signup')" v-else>Sign up to access
                    </a>
                </div>
                <img
                    class="mx-auto w-48 md:-my-5 md:-mr-5 rounded-lg col-span-2 "
                    :alt="guide.title" :src="guide.image"/>
            </div>
        </div>
    </div>
</template>
