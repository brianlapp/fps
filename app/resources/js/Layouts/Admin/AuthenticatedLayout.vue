<script setup>
import {ref, onMounted, onUpdated} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import SideMenu from "@/Components/Admin/SideMenu.vue";
import { initFlowbite } from 'flowbite'
import FlashMessages from "@/Components/FlashMessages.vue";
const showingNavigationDropdown = ref(false);
// initialize components based on data attribute selectors
onMounted(() => {
    // initFlowbite();
})

// onUpdated(() => {
//     initFlowbite();
// })

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-800">
            <side-menu/>
            <!-- Page Content -->
            <main class="ml-0 md:ml-64 relative">
                <FlashMessages/>
                <!-- Page Heading -->
                <header class="bg-light-secondary-bg dark:bg-dark-secondary-bg border-b border-border-hr-light dark:border-gray-700" v-if="$slots.header">
                    <div class="w-full mx-auto p-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>
                <slot />
            </main>
        </div>
    </div>
</template>
