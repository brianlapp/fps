<script>
export default {
    name: "Comments",
    mounted() {
        this.injectScript();
    },
    methods: {
        injectScript() {
            const script = document.createElement('script');
            script.type = 'text/javascript';
            script.defer = 'true';
            script.src = 'https://comments.freeparentsearch.com/comentario.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(script);
        },
    }
}
</script>

<template>
    <div class="mt-6">
        <h3 class="text-2xl text-center dark:text-gray-300">Comments</h3>
        <div id="comentario" class="mt-4">
            <comentario-comments></comentario-comments>
        </div>
    </div>

</template>

<style>
.dark {
    #comentario {
        comentario-comments {
            color-scheme: dark;
            --cmntr-bg: #212529;
            --cmntr-bg-shade: #343a40;
            --cmntr-bg-highlight: #4263eb;
            --cmntr-bg-blink: #fab005;
            --cmntr-danger-bg: #c92a2a;
            --cmntr-deleted-bg: #f06595;
            --cmntr-dlg-header-bg: #868e96;
            --cmntr-input-bg: #212529;
            --cmntr-pending-bg: #565c63;
            --cmntr-placeholder-bg: #868e96;
            --cmntr-rejected-bg: #f08c00;
            --cmntr-success-bg: #2f9e44;
            --cmntr-warning-bg: #f08c00;
            --cmntr-color: #f1f3f5;
            --cmntr-danger-color: #ffc9c9;
            --cmntr-input-color: #e9ecef;
            --cmntr-input-ph-color: #ced4da;
            --cmntr-input-disabled-color: #dee2e6;
            --cmntr-label-color: #e9ecef;
            --cmntr-link-color: #8b91fc;
            --cmntr-link-hover-color: #5b62e5;
            --cmntr-muted-color: #dee2e6;
            --cmntr-score-color: #adb5bd;
            --cmntr-score-up-color: #8ce99a;
            --cmntr-score-down-color: #ffc078;
            --cmntr-sticky-color: #fcc419;
            --cmntr-success-color: #b2f2bb;
            --cmntr-warning-color: #ffec99;
            --cmntr-card-border: #343a40;
            --cmntr-dlg-border: #ced4da;
            --cmntr-pending-border: #ffe066;
            --cmntr-table-border: #868e96
        }
    }
}

#comentario {
    comentario-comments {
        --cmntr-bg: #ffffff;
        --cmntr-bg-shade: #f1f3f5;
        --cmntr-bg-highlight: #bac8ff;
        --cmntr-bg-blink: #ffe066;
        --cmntr-danger-bg: #ffe3e3;
        --cmntr-deleted-bg: #fcc2d7;
        --cmntr-dlg-header-bg: #dee2e6;
        --cmntr-input-bg: #ffffff;
        --cmntr-pending-bg: #e9ecef;
        --cmntr-placeholder-bg: #dee2e6;
        --cmntr-placeholder-mask: #212529;
        --cmntr-rejected-bg: #fff8c5;
        --cmntr-success-bg: #d3f9d8;
        --cmntr-warning-bg: #fff8c5;
        --cmntr-color: #343a40;
        --cmntr-danger-color: #f03e3e;
        --cmntr-input-color: #565c63;
        --cmntr-input-ph-color: #adb5bd;
        --cmntr-input-disabled-color: #868e96;
        --cmntr-label-color: #565c63;
        --cmntr-link-color: #181fab;
        --cmntr-link-hover-color: #5b62e5;
        --cmntr-muted-color: #868e96;
        --cmntr-score-color: #ced4da;
        --cmntr-score-up-color: #40c057;
        --cmntr-score-down-color: #fd7e14;
        --cmntr-sticky-color: #ffd43b;
        --cmntr-success-color: #37b24d;
        --cmntr-warning-color: #f59f00;
        --cmntr-card-border: #f1f3f5;
        --cmntr-dlg-border: #adb5bd;
        --cmntr-pending-border: #fab005;
        --cmntr-table-border: #dee2e6
    }

}


</style>
