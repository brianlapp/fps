

<template>

</template>

<script>
import moment from "moment";

export default {
    methods: {
        formatDate(date, format = 'MMMM D, YYYY') {
            return moment(date).format(format)
        }
    }
}

</script>
