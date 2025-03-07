<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            $title = (($meta['title'] ?? null) ? $meta['title'] . ' - ' : ''). $page['props']['app']['seo']['title'];
            $headline = $meta['headline'] ?? $page['props']['app']['seo']['headline'];
            $image = $meta['image'] ?? $page['props']['app']['seo']['image'];
            $link = $meta['link'] ?? $page['props']['app']['seo']['link'];
            $googleCard = isset($meta['googleCard']) ? $meta['googleCard'] : $page['props']['app']['seo']['googleCard'];
        @endphp

        <title inertia>{{$title}}</title>
        <meta name="description" content="{{$headline}}">
        <meta property="og:type" content="website"/>
        <meta property="og:title" content=" {{$title}} ">
        <meta property="og:description" content="{{$headline}}">
        <meta property="og:image" content="{{$image}}"/>
        <meta property="og:url" content=" {{$link}} ">

        <meta property="twitter:title" content="{{$title}}">
        <meta property="twitter:description" content="{{$headline}}">
        <meta property="twitter:card" content="summary_large_image"/>
        <meta property="twitter:image" content="{{$image}} ">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

        @if(!empty($googleCard))
            <script type="application/ld+json">
                @json($googleCard)
            </script>
        @endif

        <link rel="apple-touch-icon" sizes="180x180" href="/images/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/icons/favicon-16x16.png">
        <link rel="manifest" href="/images/icons/site.webmanifest">
        <link rel="mask-icon" href="/images/icons/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="shortcut icon" href="/images/icons/favicon.ico">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-config" content="/images/icons/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link id="heading-font" rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800;900&display=swap" media="all" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- External resources for local development -->
        <script src="https://kit.fontawesome.com/08eb134ce7.js" crossorigin="anonymous"></script>
        <!-- <script type="text/javascript" src="https://get.free.ca/scripts/sdk/everflow.js"></script> -->
        <!-- <script src="{{url('/everflow.js')}}"></script> -->
        <!-- Facebook Pixel Code - Disabled for local development -->
        <script>
            // Facebook pixel disabled for local development
            window.fbq = function() { console.log('Facebook Pixel disabled for local development'); };
        </script>
        <style>
            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                    transform: scale(1);
                }
                50% {
                    opacity: 0.75;
                    transform: scale(1.05);
                }
            }

            .pulse {
                animation: pulse 2s infinite;
            }
        </style>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js'])
        @inertiaHead
        {!! $codeInjections['header'] ?? '' !!}
    </head>
    <body class="font-sans antialiased @if($noSidebar ?? false) no-sidebar @endif">
    <!-- Google Tag Manager disabled for local development -->
    <!-- <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TZVMK3QL"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> -->
    <!-- End Google Tag Manager (noscript) -->
        <!-- Enhanced debug information with fallback content -->
        <div id="laravel-debug" style="position: fixed; bottom: 0; left: 0; width: 100%; background: rgba(255,0,0,0.7); color: white; padding: 10px; z-index: 9999; display: none;"></div>
        
        <!-- Fallback content if Inertia fails to load -->
        <div id="fallback-content" style="display: none; padding: 20px; max-width: 800px; margin: 0 auto; text-align: center;">
            <h1>Free Parent Search</h1>
            <p>We're experiencing some technical difficulties. Please try refreshing the page.</p>
            <div style="margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 4px; text-align: left;">
                <h3>Troubleshooting:</h3>
                <ul style="list-style-type: disc; padding-left: 20px;">
                    <li>Clear your browser cache</li>
                    <li>Try a different browser</li>
                    <li>Check your internet connection</li>
                </ul>
            </div>
        </div>
        
        <script>
            console.log('Debug script loaded');
            // Log all errors to the debug div
            window.addEventListener('error', function(e) {
                console.error('JavaScript error:', e.message, 'at', e.filename, ':', e.lineno);
                var debugEl = document.getElementById('laravel-debug');
                debugEl.style.display = 'block';
                debugEl.innerHTML = '<strong>JavaScript Error:</strong> ' + e.message + ' at ' + e.filename + ':' + e.lineno;
            });
            
            // Check for missing Inertia app and show fallback content if needed
            window.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded, checking content');
                setTimeout(function() {
                    if (!window.app && !document.querySelector('[data-page]')) {
                        console.error('Inertia app not initialized');
                        var debugEl = document.getElementById('laravel-debug');
                        debugEl.style.display = 'block';
                        debugEl.innerHTML = 'Inertia app not initialized. Check browser console for errors.';
                        
                        // Show fallback content
                        document.getElementById('fallback-content').style.display = 'block';
                    }
                }, 1000);
            });
        </script>
        @inertia
    </body>
    <footer>
        {!! $codeInjections['footer'] ?? '' !!}
    </footer>
</html>
