<template>
    <button data-drawer-target="admin-sidebar" data-drawer-toggle="admin-sidebar" aria-controls="admin-sidebar"
            type="button"
            class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Menu</span>
        <i class="fa-solid fa-bars fa-xl py-2"></i>
    </button>

    <aside id="admin-sidebar"
           class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
           aria-label="Sidenav">
        <div
            class="overflow-y-auto py-5 px-3 h-full bg-white border-r border-gray-200 dark:bg-gray-900 dark:border-gray-700">
            <div class="py-2 mb-5 space-y-2 border-b border-gray-200 dark:border-gray-700">
                <button type="button"
                        class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        aria-controls="dropdown-authentication" data-collapse-toggle="dropdown-authentication">
                    <img class="rounded-full w-14 h-14" :src="$page.props.auth.user.avatar"
                         :alt="$page.props.auth.user.name"/>
                    <span class="flex-1 ml-3 text-left overflow-hidden" :class="{'text-sm': $page.props.auth.user.name.length > 20}">{{ $page.props.auth.user.name }}</span>
                    <i class="fa-solid fa-chevron-down w-4 text-gray-700 dark:text-gray-400"></i>
                </button>
                <ul id="dropdown-authentication" class="hidden py-2 space-y-2">
                    <li>
                        <Link :href="route('admin.profile.edit')"
                              class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            Profile
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('admin.logout')" method="post"
                              class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            Log Out
                        </Link>
                    </li>
                </ul>
            </div>
            <ul class="space-y-2">
                <template v-for="(menuItem, i) in $page.props.sideMenu">
                    <li v-if="menuItem.visible" :key="'menu-'.concat(i)">
                        <template v-if="!menuItem.items || menuItem.items.length < 1">
                            <Link :href="menuItem.url"
                               class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group "
                               :class="{'bg-gray-100 dark:bg-gray-700': menuItem.active}">
                                <i class="flex-shrink-0 fa-md text-gray-400 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                   :class="[menuItem.icon]"></i>
                                <span class="ml-3">{{ menuItem.label }}</span>
                            </Link>
                        </template>
                        <template v-else>
                            <button type="button"
                                    class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                    :aria-controls="'menu-dropdown-'.concat(i)"
                                    :data-collapse-toggle="'menu-dropdown-'.concat(i)">
                                <i class="flex-shrink-0 fa-md text-gray-400 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                   :class="[menuItem.icon]"></i>
                                <span class="flex-1 ml-3 text-left whitespace-nowrap"> {{ menuItem.label }}</span>
                                <i class="fa-solid fa-chevron-down w-4 text-gray-700 dark:text-gray-400"></i>
                            </button>
                            <ul :id="'menu-dropdown-'.concat(i)" :class="{hidden: !menuItem.active}"
                                class="py-2 space-y-2">
                                <template v-for="(subItem,ii) in menuItem.items">
                                    <li v-if="subItem.visible" :key="'menu-sub-'.concat(ii)" :class="[subItem?.class ?? '']">
                                        <Link :href="subItem.url"
                                           class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                           :class="{'bg-gray-100 dark:bg-gray-700': subItem.active, su}">
                                            {{ subItem.label }}
                                        </Link>
                                    </li>
                                </template>
                            </ul>
                        </template>
                    </li>
                </template>
            </ul>
            <div
                class="absolute bottom-0 left-0 justify-center p-4 space-x-4 w-full flex bg-white dark:bg-gray-800 z-20 border-r border-gray-200 dark:border-gray-700">
                <theme-toggle/>
            </div>
        </div>
    </aside>
</template>
<script setup>
import ThemeToggle from "@/Components/ThemeToggle.vue";
import {Link} from "@inertiajs/vue3";
</script>
