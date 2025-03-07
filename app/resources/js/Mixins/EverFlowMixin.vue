<template>

</template>

<script>
import moment from "moment";
import {usePage} from "@inertiajs/vue3";

const paramsList = [
    'oid',
    'affid',
    'sub1',
    'sub2',
    'sub3',
    'sub4',
    'sub5',
    'uid',
    'source_id',
    'transaction_id',
]
export default {
    data() {
        const queryParams = usePage().props.query,
            savedQuery = {};
        paramsList.forEach(param => {
            savedQuery[param] = queryParams[param] || null;
        })
        return {
            savedQuery
        }
    },
    methods: {
        EFConversion() {
            EfConversion(this.savedQuery);
        }
    }
}

</script>
