<script>
import {concat} from "lodash";

export default {
    name: "ActivityMixin",
    props: {
        activities: {
            type: Object
        },
        activeFilters: {
            type: Object
        },
        my: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
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
            filters: {
                'child_age': this.activeFilters?.child_age ?? null,
                'available_time': this.activeFilters?.available_time ?? null,
                'location': this.activeFilters?.location ?? null,
            },
            isLoading: false,
            options: {
                'child_age': [
                    {
                        label: '0-2 years',
                        value: '2 years old or younger',
                    },
                    {
                        label: '3-5 years',
                        value: 'from 3 to 5 years old',
                    },
                    {
                        label: '6-8 years',
                        value: 'from 6 to 8 years old',
                    },
                    {
                        label: '9-12 years',
                        value: 'from 9 to 12 years old',
                    },
                    {
                        label: '13+ years',
                        value: '13 years old or older',
                    },
                ],
                'available_time': [
                    {
                        label: '15 mins',
                        value: '15 minutes',
                    },
                    {
                        label: '30 mins',
                        value: '30 minutes',
                    },
                    {
                        label: '1 hour',
                        value: '1 hour',
                    },
                    {
                        label: '2+ hours',
                        value: '2 or more hours',
                    }
                ],
                'location': [
                    {
                        label: 'Indoor',
                        value: 'Inside house or apartment',
                    },
                    {
                        label: 'Outdoor',
                        value: 'Backyard, public part or other appropriate outdoor place',
                    },
                    {
                        label: 'Both',
                        value: 'Both Indoor and Outdoor location',
                    },
                ],
                'activity_type_preferences': [
                    {
                        label: 'Educational',
                        value: 'Educational',
                    },
                    {
                        label: 'Physical',
                        value: 'Physical',
                    },
                    {
                        label: 'Creative',
                        value: 'Creative',
                    },
                    {
                        label: 'Quiet Time',
                        value: 'Quiet Time',
                    }
                ],
                'available_resources': 'Enter any available resources (common household items, toys, crafting supplies, outdoor equipment)',
                'special_requests': 'Enter any special requests here (e.g., "daughter loves unicorns", "include magic elements")'
            },
        }
    },
    computed: {
        hasNextPage() {
            return !!this.activities.next_page_url;
        },
        nextPage() {
            return this.activities.current_page + 1;
        },
        currentSortingOption() {
            return this.sortOptions.find(option => option.value === this.sort);
        }
    },
    methods: {
        loadMore() {
            this.isLoading = true;
            const url = new URL(route('activity.feed'));
            // url.searchParams.set('sort', this.sort);
            url.searchParams.set('page', this.nextPage);
            Object.keys(this.filters).forEach(key => {
                if (this.filters[key]) {
                    url.searchParams.set(key, this.filters[key]);
                }
            })
            if (this.my) {
                url.searchParams.set('my', '1');
            }
            axios.get(url.toString()).then(({data}) => {
                this.activities.data = concat(this.activities.data, data.data);
                this.activities.current_page = data.current_page;
                this.activities.next_page_url = data.next_page_url;
            }).finally(() => {
                this.isLoading = false;
            });
        },
        changeSort(sort) {
            this.sort = sort;
            this.reloadFeed();
        },
        reloadFeed() {
            const url = new URL(window.location);
            // url.searchParams.set('sort', this.sort);
            Object.keys(this.filters).forEach(key => {
                if (this.filters[key]) {
                    url.searchParams.set(key, this.filters[key]);
                } else {
                    url.searchParams.delete(key);
                }
            })
            window.location.href = url.toString();
        }
    },
    watch: {
        filters: {
            handler() {
                this.reloadFeed();
            },
            deep: true
        }
    }
}
</script>
