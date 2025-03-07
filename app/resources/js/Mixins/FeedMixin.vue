<script>
import {concat} from "lodash";

export default {
    name: "FeedMixin",
    props: {
        articles: {
            type: Object
        },
        currentView: {
            type: String
        },
        currentSort: {
            type: String
        },
        currentTags: {
            type: Array
        },
        currentMy: {
            type: Boolean,
            default: false
        },
        popularTags: {
            type: Array
        },
    },
    data() {
        return {
            view: this.currentView || 'grid',
            sort: this.currentSort || 'newest',
            sortOptions: [
                {
                    label: 'Newest',
                    value: 'newest'
                },
                {
                    label: 'Oldest',
                    value: 'oldest'
                }
            ],
            isLoading: false,
            tags: this.currentTags || [],
            my: this.currentMy
        }
    },
    computed: {
        hasNextPage() {
            return !!this.articles.next_page_url;
        },
        nextPage() {
            return this.articles.current_page + 1;
        },
        currentSortingOption() {
            return this.sortOptions.find(option => option.value === this.sort);
        }
    },
    methods: {
        loadMore() {
            this.isLoading = true;
            const url = new URL(this.feedUrl);
            url.searchParams.set('sort', this.sort);
            url.searchParams.set('page', this.nextPage);
            axios.get(url.toString()).then(({data}) => {
                this.articles.data = concat(this.articles.data, data.data);
                this.articles.current_page = data.current_page;
                this.articles.next_page_url = data.next_page_url;
            }).finally(() => {
                this.isLoading = false;
            });
        },
        changeSort(sort) {
            this.sort = sort;
            this.reloadFeed();
        },
        changeView(view) {
            this.view = view;
        },
        reloadFeed() {
            const url = new URL(window.location);
            url.searchParams.set('sort', this.sort);
            url.searchParams.set('view', this.view);
            url.searchParams.set('tags', this.tags);
            url.searchParams.set('my', this.my);
            window.location.href = url.toString();
        }
    },
    watch: {
        tags: {
            handler() {
                this.reloadFeed();
            },
            deep: true
        },
        my: {
            handler() {
                this.reloadFeed();
            },
        }
    }
}
</script>
