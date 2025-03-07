<?php

namespace App\Helpers;

use DOMDocument;
use PHPUnit\Event\Runtime\PHP;

class HtmlHelper
{
    public const TRACKING_PARAMS = ['utm_source', 'utm_medium', 'utm_campaign', 'sub1', 'sub2', 'sub3', 'sub4', 'sub5', 'oid', 'source_id', 'affid', 'transaction_id', 'uid'];

    public static function processHtmlString(string $htmlString): array
    {
        $dom = new \DOMDocument('1.0', 'utf-8');

        // Load the HTML string
        @$dom->loadHTML($htmlString);

        // Initialize title
        $title = null;
        $tags = [];

        // Try to get the title from <title> tag
        $titleTag = $dom->getElementsByTagName('title')->item(0);
        $h1Tag = $dom->getElementsByTagName('h1')->item(0);
        if (!empty($titleTag)) {
            $title = $titleTag->nodeValue;
        }

        // If title not found in <title>, try to get it from <h1>
        if (empty($title) && !empty($h1Tag)) {
            $title = $h1Tag->nodeValue;
        }

        // Remove <title> tag
        if (!empty($titleTag)) {
            $titleTag->parentNode->removeChild($titleTag);
        }

        // Remove <h1> tag
        if (!empty($h1Tag)) {
            $h1Tag->parentNode->removeChild($h1Tag);
        }

        // Remove <meta> tags
        $metaTags = $dom->getElementsByTagName('meta');
        for ($i = $metaTags->length - 1; $i >= 0; $i--) {
            $metaTag = $metaTags->item($i);
            if ($metaTag->getAttribute('name') === 'tags') {
                $tags = explode(',', $metaTag->getAttribute('content'));
                $tags = array_map('trim', $tags);
            }
            $metaTag->parentNode->removeChild($metaTag);
        }

        // Initialize new HTML string

        // Check if <article> tag exists
        $articleTag = $dom->getElementsByTagName('article')->item(0);
        if (!empty($articleTag)) {
            $newHtmlString = $dom->saveHTML($articleTag);
        } else {
            // If <article> tag doesn't exist, fall back to <body> content
            $bodyTag = $dom->getElementsByTagName('body')->item(0);
            if (!empty($bodyTag)) {
                $newHtmlString = $dom->saveHTML($bodyTag);
            } else {
                $newHtmlString = $dom->saveHTML();
            }
        }

        $clearHtmlString = str_replace(['<body>', '</body>', '<article>', '</article>'], ['', '', '', ''], $newHtmlString);

        return ['title' => $title, 'html' => $clearHtmlString, 'tags' => $tags];
    }

    public static function insertInTheMiddle($html, $contentToInsert): false|string
    {
        $meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        $html = $meta . trim($html);
        // Load the HTML into a DOMDocument
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Suppress warnings from invalid HTML
        $dom->loadHTML($html, LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Get all paragraph elements
        $paragraphs = $dom->getElementsByTagName('p');

        // Calculate total text length
        $totalLength = 0;
        foreach ($paragraphs as $p) {
            if (collect($p->childNodes)->where('tagName', 'p')->count() > 0) {
                continue;
            }
            $totalLength += strlen($p->textContent);
        }
        $midpoint = $totalLength / 2;

        // Find the insertion point
        $currentLength = 0;
        $insertionNode = null;
        foreach ($paragraphs as $p) {
            if (collect($p->childNodes)->where('tagName', 'p')->count() > 0) {
                continue;
            }
            $currentLength += strlen($p->textContent);
            if ($currentLength >= $midpoint) {
                $insertionNode = $p;
                break;
            }
        }

        // Insert the content after the midpoint paragraph
        if ($insertionNode) {
            $fragment = $dom->createDocumentFragment();
            $fragment->appendXML($contentToInsert);

            if ($insertionNode->nextSibling) {
                $insertionNode->parentNode->insertBefore($fragment, $insertionNode->nextSibling);
            } else {
                $insertionNode->parentNode->appendChild($fragment);
            }
        }

        // Return the modified HTML
        return str_replace($meta, '', $dom->saveHTML());
    }

    /**
     * Remove all URLs from the given string.
     *
     * @param string $text
     * @return string
     */
    public static function removeUrls(string $text): string
    {
        // Regular expression to match URLs
        $pattern = '/\bhttps?:\/\/[^\s]+/i';

        // Replace all matches with an empty string
        return preg_replace($pattern, '', $text);
    }

    public static function clearContent(string $content): string
    {
        return self::removeUrls(strip_tags($content));
    }
}
