<?php

namespace App\SiteMapProfiles;

use Illuminate\Support\Str;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;
use Psr\Http\Message\UriInterface;

class NoAdminCrawlProfile extends CrawlProfile
{
    public function shouldCrawl(UriInterface $url): bool
    {
        return !Str::startsWith($url->getPath(), ['/admin', '/images', '/build', './']);
    }
}

