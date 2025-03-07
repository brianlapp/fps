<script>
import {startsWith} from "lodash";

export default {
    name: "StringMixin",
    methods: {
        isOdd(number) {
            return number % 2 !== 0;
        },
        matchCurrentUrl(urlToMatch) {
            const trimTrailingSlash = (url) => url.replace(/\/$/, ""),
                currentUrl = new URL(window.location.origin + window.location.pathname),
                matchedUrl = new URL(urlToMatch, (startsWith(urlToMatch, 'http') ? undefined : window.location.origin));

            return trimTrailingSlash(currentUrl.href) === trimTrailingSlash(matchedUrl.href);
        },
        prettyBytes(num) {
            if (typeof num !== 'number' || isNaN(num)) {
                throw new TypeError('Expected a number');
            }

            let exponent;
            let unit;
            let neg = num < 0;
            let units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            if (neg) {
                num = -num;
            }

            if (num < 1) {
                return (neg ? '-' : '') + num + ' B';
            }

            exponent = Math.min(Math.floor(Math.log(num) / Math.log(1024)), units.length - 1);
            num = (num / Math.pow(1024, exponent)).toFixed(2) * 1;
            unit = units[exponent];

            return (neg ? '-' : '') + num + ' ' + unit;
        }
    },
    computed: {
        postalLabel() {
            return this.$page.props.country === 'CA' ? 'Postal Code' : 'ZIP Code';
        }
    }
}
</script>
