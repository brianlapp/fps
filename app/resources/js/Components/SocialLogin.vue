<template>
    <div class="gap-y-3 w-full relative border-t flex justify-center">
        <div class="hr-separator absolute top-0 -left-6 -right-6 h-[1px] bg-border-hr-light dark:bg-border-hr"></div>
       <GoogleButton class="w-full gap-x-5"/>
    </div>
</template>
<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import GoogleButton from "@/Components/GoogleButton.vue";
</script>
