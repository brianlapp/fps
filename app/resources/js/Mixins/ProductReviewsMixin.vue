<script>

export default {
    data() {
        return {
            maxRate: this.$page.props.app.maxRate,
        }
    },
    methods: {
        getStars(rate, maxRate = null) {
            const stars = [];
            for (let i = 1; i <= (maxRate || this.maxRate); i++) {
                if (i > Math.round(rate)) {
                    stars.push('☆');
                } else {
                    stars.push('★');
                }
            }

            return stars.join('');
        },
    }
}

</script>
