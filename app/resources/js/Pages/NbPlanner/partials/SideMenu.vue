<script setup>
import {Link, usePage} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import StringMixin from "@/Mixins/StringMixin.vue";
import {some} from "lodash";
import SignupButton from "@/Components/SignupButton.vue";
import ThemeToggle from "@/Components/ThemeToggle.vue";
import Socials from "@/Components/Socials.vue";
import ComingSoon from "@/Components/ComingSoon.vue";
import NewLabel from "@/Components/New.vue";

const props = defineProps({
    externalShow: {
        type: Boolean,
        default: false
    },
    items: {
        type: Array,
        default: []
    }
});
const show = ref(false),
    isMatchingUrl = StringMixin.methods.matchCurrentUrl,
    swipe = function (direction) {
        show.value = direction === 'right'
    },
    swipeLeft= () => swipe('left'),
    swipeRight= () => swipe('right'),
    emit = defineEmits(['clickedOnForbidden']);

watch(() => props.externalShow, (first, second) => {
    show.value = props.externalShow;
});
</script>

<template>
    <aside id="nb-planner-sidebar"  v-touch:swipe.left="swipeLeft"
           class="fixed top-0 left-0 z-50 sm:z-40 w-64  min-h-screen h-full transition-transform -translate-x-full xxl:translate-x-0 dark:border-gray-700 pt-5  text-white flex-shrink-0"
           :class="{'translate-x-0': show, '-translate-x-full': !show}"
           aria-label="Sidenav">
        <button @click="show = !show"
                v-touch:swipe.left="swipeLeft"
                v-touch:swipe.right="swipeRight"
                type="button"
                class="absolute opacity-75 -right-11 block items-center text-sm text-white bg-blue-200 transform -translate-y-1/2 top-1/2 rounded-r-lg py-12 px-4 xxl:hidden">
            <i class="fa-solid fa-xl py-2" :class="{'fa-chevron-right': !show, 'fa-chevron-left': show}"></i>
        </button>
        <div class="sm:flex flex-col gap-y-1 hidden border-b border-gray-300 dark:border-gray-600  dark:text-white h-32 justify-between">
            <div class="px-4 pb-2" v-if="$page.props.auth.user">
                <div>
                    <div class="">
                        {{ $page.props.auth.user.name }}
                    </div>
                    <div class="text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div>

                <div class="mt-3 flex justify-between">
                    <a :href="route('profile.edit')" class="hover:dark:text-gray-400 hover:text-gray-600"> Profile</a>
                    <Link :href="route('logout')" method="post" as="button" class="hover:dark:text-gray-400 hover:text-gray-600">
                        Log Out
                    </Link>
                </div>
            </div>
            <div class="px-4 text-center" v-else>
                <SignupButton/>
            </div>
            <div class="px-4 text-center">
                <ThemeToggle/>
            </div>

        </div>
        <div
            class="py-4 px-3 h-full flex flex-col justify-start relative bg-blue-600">
            <h2 class="text-xl mb-4 text-center font-bold">Ultimate Checklist</h2>
            <ul class="space-y-2">
                <li class="rounded hover:bg-blue-700" v-for="item in props.items" :key="item.slug" :class="{'bg-white text-blue-600': item.is_current}">
                    <a :href="item.link" v-if="item.allowed_for_guest || $page?.props?.auth?.user" class="block py-2 px-4 ">{{ item.title }}</a>
                    <span @click="emit('clickedOnForbidden')" v-else class="block py-2 px-4 cursor-pointer">{{ item.title }}</span>
                </li>
            </ul>
        </div>
    </aside>
</template>
