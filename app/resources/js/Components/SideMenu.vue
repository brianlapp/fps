<template>
    <aside id="main-sidebar"
           class="fixed top-0 left-0 z-50 sm:z-40 w-80 h-screen transition-transform -translate-x-full bg-gray-200 dark:bg-gray-700 dark:border-gray-700 pt-5 sm:hidden"
           :class="{'translate-x-0': show, '-translate-x-full': !show}"
           aria-label="Sidenav">
        <button @click="show = !show"
                type="button"
                class="absolute opacity-75 -right-11 hidden sm:block items-center text-sm text-gray-500 bg-gray-200 dark:bg-gray-800 dark:text-gray-900 transform -translate-y-1/2 top-1/2 rounded-r-lg py-12 px-4">
            <span class="sr-only">Menu</span>
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
            class=" py-5 px-3 h-full flex flex-col justify-between relative">
            <div></div>
<!--            <ul class="space-y-2" v-show="show">-->
<!--                <template v-for="(menuItem, i) in menu" :key="'menu-'.concat(i)">-->
<!--                    <li>-->
<!--                        <template v-if="!menuItem.children || menuItem.children.length < 1">-->
<!--                            <a :href="menuItem.link"-->
<!--                               :target="menuItem.target"-->
<!--                               class="flex items-center p-2 pl-4 text-base font-normal rounded-lg hover:text-gray-100 dark:text-white hover:bg-blue-500 group "-->
<!--                               :class="{'text-gray-100 bg-blue-500':!menuItem.is_coming_soon &&  isMatchingUrl(menuItem.link)}">-->
<!--                                {{ menuItem.name }}-->
<!--                                <NewLabel class="py-0.5 text-xs ml-2 mt-0"-->
<!--                                          v-if="menuItem.is_new"/>-->
<!--                                <ComingSoon class="py-0.5 text-xs ml-2 mt-0"-->
<!--                                            v-else-if="menuItem.is_coming_soon"/>-->
<!--                            </a>-->

<!--                        </template>-->
<!--                        <template v-else>-->
<!--                            <button type="button"-->
<!--                                    class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group dark:text-white hover:bg-blue-500 hover:text-gray-100 "-->
<!--                                    :aria-controls="'menu-dropdown-'.concat(i)"-->
<!--                                    :data-collapse-toggle="'menu-dropdown-'.concat(i)">-->
<!--                                <span class="flex-1 ml-3 text-left whitespace-nowrap"> {{ menuItem.name }}</span>-->
<!--                                <i class="fa-solid fa-chevron-down w-4"></i>-->
<!--                            </button>-->
<!--                            <ul :id="'menu-dropdown-'.concat(i)" :class="{hidden: !isDropDownActive(menuItem)}"-->
<!--                                class="py-2 space-y-2">-->
<!--                                <template v-for="(subItem,ii) in menuItem.children" :key="'menu-sub-'.concat(ii)">-->
<!--                                    <li class="relative">-->
<!--                                        <a :href="subItem.link"-->
<!--                                           :target="subItem.target"-->
<!--                                           class="flex items-center p-2 pl-11 text-base font-normal rounded-lg hover:text-gray-100 dark:text-white hover:bg-blue-500 group "-->
<!--                                           :class="{'text-gray-100 bg-blue-500': !subItem.is_coming_soon && isMatchingUrl(subItem.link)}">-->
<!--                                                {{ subItem.name }}-->
<!--                                            <NewLabel class="py-0.5 text-xs ml-2 mt-0"-->
<!--                                                      v-if="subItem.is_new"/>-->
<!--                                            <ComingSoon class="py-0.5 text-xs ml-2 mt-0"-->
<!--                                                        v-else-if="subItem.is_coming_soon"/>-->
<!--                                        </a>-->

<!--                                    </li>-->
<!--                                </template>-->
<!--                            </ul>-->
<!--                        </template>-->
<!--                    </li>-->
<!--                </template>-->
<!--            </ul>-->

            <div class="flex flex-col gap-y-2 sm:hidden border-t border-gray-300 dark:border-gray-600 pt-4 dark:text-white">
                <div class="px-4 border-b pb-4 border-gray-300 dark:border-gray-600" v-if="$page.props.auth.user">
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
                    <Socials/>
                </div>
                <div class="px-4 text-center">
                    <ThemeToggle/>
                </div>
            </div>


        </div>
    </aside>
</template>
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
    }
});
const show = ref(false),
    menu = usePage().props.menu.filter(item => item.list === 'sidebar'),
    isMatchingUrl = StringMixin.methods.matchCurrentUrl,
    isDropDownActive = (item) => {
        return some(item.children, child => isMatchingUrl(child.link));
    }

watch(() => props.externalShow, (first, second) => {
    show.value = props.externalShow;
});
</script>
