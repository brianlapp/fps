<template>
    <div class="w-full">
        <div v-html="content.replaceAll('```html', '').replaceAll('```', '')"
             class="w-full article-content"/>

        <div v-if="localArticle" class="mt-8">
            <a :href="localArticle.link" class="block underline">
                <i class="fa fa-eye pr-2"></i>View Article</a>
            <ShareBar :article="localArticle" class="mt-4"/>
        </div>

    </div>

</template>

<script>
import ShareBar from "@/Components/ShareBar.vue";

export default {
    name: "LiveArticle",
    components: {ShareBar},
    props: {
        payload: Object,
        url: String,
        requestId: String,
        article: {
            type: Object,
            default: () => null
        }
    },
    data() {
        return {
            localArticle: this.article,
            content: '',
            done: false,
            error: false,
            link: null
        }
    },
    mounted() {
        // We have stored live article from the ArticleRequest, so we don't need to generate one
        if (this.article) {
            this.content = `<h1>${this.article.title}</h1>`.concat(this.article.content);
            this.$emit('done');
            return;
        }
        this.go();
    },
    methods: {
        async go() {
            try {
                const response = await fetch(this.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        stream: true,
                        body: JSON.stringify(this.payload)
                    }),
                    reader = response.body.getReader(),
                    decoder = new TextDecoder();

                while (!this.done) {
                    const {value, done: doneReading} = await reader.read();
                    const chunk = decoder.decode(value, {stream: true}),
                        line = chunk.replaceAll('data: ', '').replaceAll('\n\n', '');
                    if (line !== '\n\n') {
                        this.content += line;
                    }
                    this.done = doneReading;
                }
            } catch (e) {
                this.error = true;
                this.content = '<h2>Error!</h2><p>Sorry! Something went wrong!</p><p>Please try again later</p>'
                // We still want to show other generated articles
                this.$emit('done');
            }
        },
        save() {
            axios.post(route('articles.save'), {
                'request_id': this.requestId,
                html: this.content.replaceAll('```html', '')
            }).then(({data}) => {
                this.localArticle = data.article || null;
            })
        }
    },
    watch: {
        'done': {
            handler(val) {
                if (val) {
                    this.$emit('done');
                    this.save();
                }
            }
        }
    }
}
</script>
