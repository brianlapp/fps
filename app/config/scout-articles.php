<?php

return [
    "settings" => [
        "minWordSizefor1Typo" => 4,
        "minWordSizefor2Typos" => 8,
        "hitsPerPage" => 20,
        "maxValuesPerFacet" => 100,
        "searchableAttributes" => [
            "unordered(title)",
            "unordered(headline)",
            "unordered(tags)",
        ],
        "numericAttributesToIndex" => null,
        "attributesToRetrieve" => null,
        "ignorePlurals" => [
            "en"
        ],
        "unretrievableAttributes" => null,
        "optionalWords" => null,
        "queryLanguages" => [
            "en"
        ],
        "attributesForFaceting" => [
        ],
        "attributesToSnippet" => null,
        "attributesToHighlight" => null,
        "paginationLimitedTo" => 1000,
        "attributeForDistinct" => null,
        "exactOnSingleWordQuery" => "attribute",
        "ranking" => [
            "typo",
            "geo",
            "words",
            "filters",
            "proximity",
            "attribute",
            "exact",
            "custom"
        ],
        "customRanking" => null,
        "separatorsToIndex" => "",
        "removeWordsIfNoResults" => "none",
        "queryType" => "prefixLast",
        "highlightPreTag" => "<em>",
        "highlightPostTag" => "</em>",
        "alternativesAsExact" => [
            "ignorePlurals",
            "singleWordSynonym"
        ],
        "enablePersonalization" => config('scout.algolia.enable_personalization'),
        "indexLanguages" => [
            "en"
        ],
    ],
];
