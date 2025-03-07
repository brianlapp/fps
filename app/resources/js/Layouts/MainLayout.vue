<script setup>
import {ref} from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Head, Link, usePage} from '@inertiajs/vue3';
import Logo from "@/Components/Logo.vue";
import ThemeToggle from "@/Components/ThemeToggle.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import Socials from "@/Components/Socials.vue";
import SignupButton from "@/Components/SignupButton.vue";
import SideMenu from "@/Components/SideMenu.vue";
import ComingSoon from "@/Components/ComingSoon.vue";
import NewLabel from "@/Components/New.vue";
import StringMixin from "@/Mixins/StringMixin.vue";
import {some} from "lodash";
import {Collapse} from "flowbite";
import Beta from "@/Components/Beta.vue";

const showingNavigationDropdown = ref(false),
    menu = usePage().props.menu.filter(item => item.list === 'main'),
    menu2 = usePage().props.menu.filter(item => item.list === 'main2'),
    isMatchingUrl = StringMixin.methods.matchCurrentUrl,
    isDropDownActive = (item) => {
        return some(item.children, child => !child.is_coming_soon && isMatchingUrl(child.link));
    },
    user = usePage().props.auth.user;
const hideDropdown = (i) => {
        const dropdown = new Collapse(
            document.getElementById('main-menu-dropdown-'.concat(i)),
        );
        dropdown.collapse();
    },
    openDropdown = (i) => {
        const dropdown = new Collapse(
            document.getElementById('main-menu-dropdown-'.concat(i)),
        );
        dropdown.expand();
    },
    closeOtherDropdowns = (index) => {
        try {
            openDropdown(index);
        } catch (e) {
            // Doing Nothing
        }

        menu.forEach((item, i) => {
            if (index != item.id) {
                try {
                    hideDropdown(item.id);
                } catch (e) {
                    // Doing Nothing
                }
            }
        })

        menu2.forEach((item, i) => {
            if (index != item.id) {
                try {
                    hideDropdown(item.id);
                } catch (e) {
                    // Doing Nothing
                }
            }
        })
    }
</script>
<template>
    <Head>
        <title>{{$page.props.app.seo.title}}</title>
        <meta head-key="description" name="description" :content="$page.props.app.seo.headline">
        <meta head-key="og:type" property="og:type" content="website"/>
        <meta head-key="og:title" property="og:title" :content=" $page.props.app.seo.title ">
        <meta head-key="og:description" property="og:description" :content=" $page.props.app.seo.headline ">
        <meta head-key="og:image" property="og:image" :content="$page.props.app.seo.image"/>
        <meta head-key="og:url" property="og:url" :content=" $page.props.app.seo.link ">

        <meta head-key="twitter:title" property="twitter:title" :content="$page.props.app.seo.title">
        <meta head-key="twitter:description" property="twitter:description" :content="$page.props.app.seo.headline">
        <meta head-key="twitter:card" property="twitter:card" content="summary_large_image"/>
        <meta head-key="twitter:image" property="twitter:image" :content="$page.props.app.seo.image ">

        <meta head-key="twitter:viewport" name="viewport"
              content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    </Head>
    <div>
        <div class="min-h-screen bg-blue-50 dark:bg-gray-900">
            <nav
                class="bg-white dark:bg-gray-800 pt-5 shadow border-b border-gray-200 dark:border-gray-600 z-50 relative">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16" :class="{'sm:pb-[7.2rem]': menu2.length > 0}">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a :href="route('home')">
                                    <Logo
                                        class="max-w-[160px] p-3 items-center justify-center"
                                    />
                                </a>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center justify-between gap-x-6 sm:ms-6">
                            <!-- Social Networks -->
                            <Socials/>
                            <!-- Settings Dropdown -->
                            <div class="relative" v-if="$page.props.auth.user">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent whitespace-nowrap
                                                leading-4 font-medium rounded-md
                                                text-gray-900 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <i class="fa fa-chevron-down ml-2"></i>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile</DropdownLink>
                                        <DropdownLink :href="route('articles.favorites')" class="relative">Saved Articles</DropdownLink>
                                        <DropdownLink :href="route('prompt_author.show', user.slug)">My articles</DropdownLink>
                                        <DropdownLink :href="'#'">Recent Searches</DropdownLink>

                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            <div class="" v-else>
                                <SignupButton/>
                            </div>
                            <ThemeToggle/>
                        </div>

                        <!-- Hamburger -->
                        <div id="main-sidebar-hamburger" class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="h-16 absolute left-0 right-0">
                        <!-- Navigation Links -->
                        <ul class="items-start px-10  space-x-8 flex w-full max-w-7xl mx-auto relative"
                            style="overflow-x: auto">
                            <template v-for="(menuItem) in menu" :key="menuItem.id">
                                <li class="whitespace-nowrap flex flex-col relative pt-2">
                                    <template v-if="!menuItem.children || menuItem.children.length < 1">
                                        <a class="font-normal tracking-wide flex transition-colors duration-200 dark:text-gray-100 hover:text-gray-100 rounded-lg hover:bg-blue-500 p-2 justify-center"
                                           :class="{'text-gray-100 bg-blue-500' : isMatchingUrl(menuItem.link)}"
                                           :target="menuItem.target"
                                           :href="menuItem.link">
                                            {{ menuItem.name }}
                                            <NewLabel class="ml-2 text-xs mt-0 " v-if="menuItem.is_new"/>
                                            <ComingSoon text="Soon" class="mt-0 ml-2  text-xs"
                                                        v-else-if="menuItem.is_coming_soon"/>
                                            <Beta class="mt-0 ml-2  text-xs"
                                                        v-else-if="menuItem.is_beta"/>
                                        </a>

                                    </template>
                                    <template v-else>
                                        <button type="button"
                                                @click.prevent="closeOtherDropdowns(menuItem.id)"
                                                class="relative flex items-center p-2 text-base font-normal text-gray-900 rounded-lg transition duration-75 group dark:text-white hover:bg-blue-500 hover:text-gray-100 "
                                                :id="'main-menu-dropdown-button-'.concat(menuItem.id)"
                                                :aria-controls="'main-menu-dropdown-'.concat(menuItem.id)"
                                                :data-collapse-toggle="'main-menu-dropdown-'.concat(menuItem.id)">
                                        <span class="flex-1 text-left whitespace-nowrap"> {{
                                                menuItem.name
                                            }}</span>
                                            <NewLabel class="mt-0  ml-2 text-xs" v-if="menuItem.is_new"/>
                                            <ComingSoon text="Soon" class="mt-0 ml-2  text-xs"
                                                        v-else-if="menuItem.is_coming_soon"/>
                                            <Beta class="mt-0 ml-2  text-xs"
                                                  v-else-if="menuItem.is_beta"/>
                                            <i class="fa-solid fa-chevron-down w-4 ml-2"></i>
                                        </button>
                                        <ul :id="'main-menu-dropdown-'.concat(menuItem.id)"
                                            class="p-2 px-4 space-y-2 bg-white rounded dark:bg-gray-700 hidden z-10">
                                            <template v-for="(subItem,ii) in menuItem.children"
                                                      :key="'menu-sub-'.concat(ii)">
                                                <li class="relative">
                                                    <a :href="subItem.link"
                                                       @click="hideDropdown(menuItem.id)"
                                                       :target="subItem.target"
                                                       class="flex items-center p-2 text-base font-normal rounded-lg hover:text-gray-100 dark:text-white hover:bg-blue-500 group "
                                                       :class="{'text-gray-100 bg-blue-500': !subItem.is_coming_soon && isMatchingUrl(subItem.link)}">
                                                        {{ subItem.name }}
                                                        <NewLabel class="mt-0 ml-2 text-xs" v-if="subItem.is_new"/>
                                                        <ComingSoon text="Soon" class="mt-0 ml-2  text-xs"
                                                                    v-else-if="subItem.is_coming_soon"/>
                                                        <Beta class="mt-0 ml-2  text-xs"
                                                              v-else-if="menuItem.is_beta"/>
                                                    </a>

                                                </li>
                                            </template>
                                        </ul>

                                    </template>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <div class="h-16 absolute left-0 right-0 top-[8.5rem] bg-gray-100 dark:bg-gray-700 text-gray-500 hidden sm:block" v-if="menu2.length > 0">
                    <!-- Navigation Links -->
                    <ul class="items-start px-10 space-x-8 hidden sm:flex w-full max-w-7xl mx-auto relative"
                        style="overflow-x: auto">
                        <template v-for="(menuItem) in menu2" :key="menuItem.id">
                            <li class="whitespace-nowrap flex flex-col relative pt-2">
                                <template v-if="!menuItem.children || menuItem.children.length < 1">
                                    <a class="font-normal text-base flex transition-colors duration-200 dark:text-gray-100 hover:text-gray-100 rounded-lg hover:bg-blue-500 p-2 justify-center"
                                       :class="{'text-gray-100 bg-blue-500' : isMatchingUrl(menuItem.link)}"
                                       :target="menuItem.target"
                                       :href="menuItem.link">
                                        {{ menuItem.name }}
                                        <NewLabel class="ml-2 text-xs mt-0 " v-if="menuItem.is_new"/>
                                        <ComingSoon text="Soon" class="mt-0 ml-2  text-xs"
                                                    v-else-if="menuItem.is_coming_soon"/>
                                        <Beta class="mt-0 ml-2  text-xs"
                                              v-else-if="menuItem.is_beta"/>
                                    </a>

                                </template>
                                <template v-else>
                                    <button type="button"
                                            @click.prevent="closeOtherDropdowns(menuItem.id)"
                                            class="relative flex items-center p-2 text-base font-normal rounded-lg transition duration-75 group dark:text-white hover:bg-blue-500 hover:text-gray-100 "
                                            :id="'main-menu-dropdown-button-'.concat(menuItem.id)"
                                            :aria-controls="'main-menu-dropdown-'.concat(menuItem.id)"
                                            :data-collapse-toggle="'main-menu-dropdown-'.concat(menuItem.id)">
                                        <span class="flex-1 text-left whitespace-nowrap"> {{
                                                menuItem.name
                                            }}</span>
                                        <NewLabel class="mt-0  ml-2 text-xs" v-if="menuItem.is_new"/>
                                        <ComingSoon text="Soon" class="mt-0 ml-2  text-xs"
                                                    v-else-if="menuItem.is_coming_soon"/>
                                        <Beta class="mt-0 ml-2  text-xs"
                                              v-else-if="menuItem.is_beta"/>
                                        <i class="fa-solid fa-chevron-down w-4 ml-2"></i>
                                    </button>
                                    <ul :id="'main-menu-dropdown-'.concat(menuItem.id)"
                                        class="p-2 px-4 space-y-2 bg-white rounded dark:bg-gray-700 hidden z-10">
                                        <template v-for="(subItem,ii) in menuItem.children"
                                                  :key="'menu-sub-'.concat(ii)">
                                            <li class="relative">
                                                <a :href="subItem.link"
                                                   @click="hideDropdown(menuItem.id)"
                                                   :target="subItem.target"
                                                   class="flex items-center p-2 text-base font-normal rounded-lg hover:text-gray-100 dark:text-white hover:bg-blue-500 group"
                                                   :class="{'text-gray-100 bg-blue-500': !subItem.is_coming_soon && isMatchingUrl(subItem.link)}">
                                                    {{ subItem.name }}
                                                    <NewLabel class="mt-0 ml-2 text-xs" v-if="subItem.is_new"/>
                                                    <ComingSoon text="Soon" class="mt-0 ml-2  text-xs"
                                                                v-else-if="subItem.is_coming_soon"/>
                                                    <Beta class="mt-0 ml-2  text-xs"
                                                          v-else-if="menuItem.is_beta"/>
                                                </a>

                                            </li>
                                        </template>
                                    </ul>

                                </template>
                            </li>
                        </template>
                    </ul>
                </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"/>
                </div>
            </header>
            <FlashMessages/>
            <!-- Page Content -->
            <SideMenu :external-show="showingNavigationDropdown"/>
            <main class="max-w-7xl mx-auto">
                <slot/>
            </main>
            <div class="max-w-7xl mx-auto dark:border-gray-500  border-t py-8">
                <div
                    class="w-full flex flex-row items-center justify-center gap-x-3 sm:gap-6 text-sm sm:text-base flex-wrap">
                    <a class="text-gray-600 dark:text-gray-400 hover:text-gray-900 hover:dark:text-gray-200"
                       :href="route('page', 'about-us')">About Us</a>
                    <a class="text-gray-600 dark:text-gray-400 hover:text-gray-900 hover:dark:text-gray-200"
                       :href="route('page', 'terms')">Terms & Conditions</a>
                    <a class="text-gray-600 dark:text-gray-400 hover:text-gray-900 hover:dark:text-gray-200"
                       :href="route('page', 'privacy')">Privacy Policy</a>
                    <a class="text-gray-600 dark:text-gray-400 hover:text-gray-900 hover:dark:text-gray-200"
                       :href="route('contact-us.show-form')">Contact Us</a>
                </div>
                <Socials class="mt-4"/>
                <div class="text-gray-400 dark:text-gray-500 text-sm text-center mt-4">©
                    {{ new Date().getFullYear() }} - FreeParentsSearch. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</template>
