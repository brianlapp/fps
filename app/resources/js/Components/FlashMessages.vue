<template>
    <div class="absolute top-16 left-0 w-full backdrop-opacity-100 z-50">
        <TransitionGroup appear name="fade">
            <div v-if="$page.props.flash.message" key="success" id="alert-success" class="max-w-7xl mx-auto flex items-center p-4 mb-4 text-green-800 rounded-lg border bg-gray-100 dark:bg-gray-800 dark:shadow-box border-border-gray-opacity-light dark:border-border-gray-opacity dark:text-green-400" role="alert">
                <p v-html="$page.props.flash.message"></p>
                <button type="button" @click="closeSuccess" class="ms-auto -mx-1.5 -my-1.5  bg-light-secondary-bg dark:bg-dark-secondary-bg text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            <div v-if="$page.props.flash.warning" key="warning" id="alert-warning" class="max-w-7xl mx-auto flex items-center p-4 mb-4 text-yellow-800 rounded-lg border bg-gray-100 dark:bg-gray-800 dark:shadow-box border-border-gray-opacity-light dark:border-border-gray-opacity dark:text-yellow-300" role="alert">
                <p v-html="$page.props.flash.warning"></p>
                <button type="button" @click="closeWarning" class="ms-auto -mx-1.5 -my-1.5  bg-light-secondary-bg dark:bg-dark-secondary-bg text-yellow-500 rounded-lg focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-200 inline-flex items-center justify-center h-8 w-8 dark:text-yellow-300 dark:hover:bg-gray-700" data-dismiss-target="#alert-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            <div v-if="$page.props.flash.error" key="danger" id="alert-danger" class="max-w-7xl mx-auto flex items-center p-4 mb-4 text-red-400 rounded-lg border bg-gray-100 dark:bg-gray-800 dark:shadow-box border-border-gray-opacity-light dark:border-border-gray-opacity dark:text-red-400" role="alert">
                <p v-html="$page.props.flash.error"></p>
                <button @click="closeError" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-light-secondary-bg dark:bg-dark-secondary-bg text-red-400 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-danger" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            <template v-if="$page.props.flash.multipleErrors">
                <div v-for="error in Object.values($page.props.flash.multipleErrors).flatMap(value => value)" key="danger" id="alert-danger" class="max-w-7xl mx-auto flex items-center p-4 mb-4 text-red-400 rounded-lg border bg-gray-100 dark:bg-gray-800 dark:shadow-box border-border-gray-opacity-light dark:border-border-gray-opacity dark:text-red-400" role="alert">
                    <p v-html="error"></p>
                    <button @click="closeMultipleErrors" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-light-secondary-bg dark:bg-dark-secondary-bg text-red-400 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-danger" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
            </template>
        </TransitionGroup>
   </div>
</template>
<script setup>
import {usePage} from '@inertiajs/vue3';
import { watch } from 'vue';

const page = usePage();
const closeError = () => {
  page.props.flash.error = null;
}
const closeSuccess = () => {
  page.props.flash.message = null;
}
const closeWarning = () => {
  page.props.flash.warning = null;
}
const closeMultipleErrors = () => {
    page.props.flash.multipleErrors = null;
}

setTimeout(function () {
    closeWarning();
    closeSuccess();
    closeError();
    closeMultipleErrors();
}, 5000);
watch(
    () => [page.props.flash.message, page.props.flash.error, page.props.flash.warning],
    () => {
        if (page.props.flash.message) {
            setTimeout(function (){
                closeSuccess();
            },5000)
        }
        if (page.props.flash.error) {
            setTimeout(function (){
                closeError();
            },5000)
        }
        if (page.props.flash.warning) {
            setTimeout(function (){
                closeWarning();
            },5000)
        }
        if (page.props.flash.multipleErrors) {
            setTimeout(function (){
                closeMultipleErrors();
            },10000)
        }
    },
);
</script>
