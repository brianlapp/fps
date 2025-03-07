const defaultOfferID = 1972;
const defaultAffiliateID = 1748;

// Organic tracking settings are configured using the "organic" property.
EF.configure({
    organic: {
        offer_id: defaultOfferID,
        affiliate_id: defaultAffiliateID
    },
    tracking_domain: 'https://get.free.ca'
})

// EF.impression({
//     offer_id: EF.urlParameter('oid') || defaultOfferID,
//     affiliate_id: EF.urlParameter('affid') || 1,
//
//     sub1: EF.urlParameter('sub1'),
//     sub2: EF.urlParameter('sub2'),
//     sub3: EF.urlParameter('sub3'),
//     sub4: EF.urlParameter('sub4'),
//     sub5: EF.urlParameter('sub5'),
//     uid: EF.urlParameter('uid'),
//     source_id: EF.urlParameter('source_id'),
//     transaction_id: EF.urlParameter('transaction_id')
// });

window.EfImpression = function (query) {
    // Extract the query parameters from the current URL
    const urlParams = new URLSearchParams(window.location.search);

    // List of parameters you want to pass
    const paramsToPass = ['sub1', 'sub2', 'sub3', 'sub4', 'sub5', 'oid', 'source_id', 'affid', 'transaction_id', 'uid'];

    // Create a new URL object for the target URL
    const targetUrl = new URL('https://get.free.ca/i/3SC25F5/4H9CXNC/');

    // Append the parameters to the target URL if they exist in the current URL
    paramsToPass.forEach(param => {
        const value = urlParams.get(param);
        if (value) {
            targetUrl.searchParams.append(param, value);
        }
    });
    if (!targetUrl.searchParams.has('affid')) {
        targetUrl.searchParams.append('affid', query?.aff_id || defaultAffiliateID);
    }
    if (!targetUrl.searchParams.has('oid')) {
        targetUrl.searchParams.append('oid',query?.offer_id || defaultOfferID);
    }

    // Pinging the Pixel URL
    fetch(targetUrl)
        .catch(error => {
            console.error('EF Impression Error:', error);
        });
}

window.EfConversion = function (query) {
    EF.conversion({
        offer_id: EF.urlParameter('oid') || query?.offer_id || defaultOfferID,
        affiliate_id: EF.urlParameter('affid') || query?.aff_id || defaultAffiliateID,
        sub1: EF.urlParameter('sub1'),
        sub2: EF.urlParameter('sub2'),
        sub3: EF.urlParameter('sub3'),
        sub4: EF.urlParameter('sub4'),
        sub5: EF.urlParameter('sub5'),
        uid: EF.urlParameter('uid'),
        source_id: EF.urlParameter('source_id'),
        transaction_id: EF.urlParameter('transaction_id')
    }).then(function (result) {
        console.info('conversion', result)
    });
}

window.EfClick = function (query) {
    EF.click({
        offer_id: EF.urlParameter('oid') || query?.offer_id || defaultOfferID,
        affiliate_id: EF.urlParameter('affid') || query?.aff_id || defaultAffiliateID,
        sub1: EF.urlParameter('sub1'),
        sub2: EF.urlParameter('sub2'),
        sub3: EF.urlParameter('sub3'),
        sub4: EF.urlParameter('sub4'),
        sub5: EF.urlParameter('sub5'),
        uid: EF.urlParameter('uid'),
        source_id: EF.urlParameter('source_id'),
        transaction_id: EF.urlParameter('transaction_id')
    });
}
