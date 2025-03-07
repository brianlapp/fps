<?=
/* Using an echo tag here so the `<? ... ?>` won't get parsed as short tags */
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<?php
$latestArticle = \App\Models\Article::published()->orderBy('published_at', 'DESC')->first();
?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <atom:link href="{{ url('feed') }}" rel="self" type="application/rss+xml" />
        <title>{!! \Spatie\Feed\Helpers\Cdata::out($meta['title'] ) !!}</title>
        <link>{!! \Spatie\Feed\Helpers\Cdata::out(url($meta['link']) ) !!}</link>
@if(!empty($meta['image']))
        <image>
            <url>{{ url('images/article_placeholder.png') }}</url>
            <title>{!! \Spatie\Feed\Helpers\Cdata::out($meta['title'] ) !!}</title>
            <link>{!! \Spatie\Feed\Helpers\Cdata::out(url($meta['link']) ) !!}</link>
        </image>
@endif
        <description>{!! \Spatie\Feed\Helpers\Cdata::out($meta['description'] ) !!}</description>
        <language>{{ $meta['language'] }}</language>
        <pubDate>{{ ($latestArticle->published_at ?? \Carbon\Carbon::now('UTC'))->format(DateTime::RFC822) }}</pubDate>

        @foreach($items as $item)
            <item>
                <title>{!! \Spatie\Feed\Helpers\Cdata::out($item->title) !!}</title>
                <link>{{ url($item->link) }}</link>
                <description>{!! \Spatie\Feed\Helpers\Cdata::out($item->summary) !!}</description>
                <author>{!! \Spatie\Feed\Helpers\Cdata::out($item->authorName.(empty($item->authorEmail)?'':' <'.$item->authorEmail.'>')) !!}</author>
                <guid>{{ url($item->id) }}</guid>
                @if(!empty($item->image))
                    <image>
                        <url>{{ $item->image }}</url>
                        <title>{!! \Spatie\Feed\Helpers\Cdata::out($item->title ) !!}</title>
                    </image>
                @endif
                <pubDate> {{ $item->updated->format(DateTime::RFC822) }} </pubDate>
                @foreach($item->category as $category)
                    <category>{{ $category }}</category>
                @endforeach
            </item>
        @endforeach
    </channel>
</rss>
