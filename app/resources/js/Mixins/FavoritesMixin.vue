<script>

export default {
    props: {
        isFavorite: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            isArticleFavorite: this.article?.is_favorite,
            toolTipKey: 'fav-tooltip-'.concat(this.article.slug)
        }
    },
    methods: {
        toggleFav() {
            this.isArticleFavorite = !this.isArticleFavorite;

            axios.post(route('articles.addToFavorites', this.article.slug));
        }
    }
}

</script>
