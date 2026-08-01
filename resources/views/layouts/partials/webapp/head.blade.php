
<script async src="https://www.googletagmanager.com/gtag/js?id=G-PNNHB8LH4K"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-PNNHB8LH4K');
</script>

@inject('settings', 'App\Services\SettingService')
<script>
    if (localStorage.theme === 'light') {
        document.documentElement.classList.add('light');
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
        document.documentElement.classList.remove('light');
    }
</script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="google-site-verification" content="w2VijygYi0N-dJzLst6_6XwTUwn3gx3Am9OWf2dfAdg" />
<title>{{ $title ?? 'NCS Hindi | Premium Royalty-Free Hindi Music & Soundtracks' }}</title>
    
    {{-- Search Engine Optimization (SEO) Meta Tags --}}
    <meta name="description" content="{{ $description ?? 'NCS Hindi is the ultimate hub for premium, royalty-free Hindi music, non-copyright soundtracks, and studio-grade audio assets for content creators.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'ncs hindi, royalty free music, non copyright music, non copyright hindi music, creator music, hindi background music, royalty free sound effects' }}">
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">
    <meta name="robots" content="{{ $robots ?? 'index, follow' }}">

    {{-- Open Graph (OG) Meta Tags (Facebook, LinkedIn, Discord, etc.) --}}
    <meta property="og:site_name" content="NCS Hindi">
    <meta property="og:title" content="{{ $title ?? 'NCS Hindi | Premium Royalty-Free Hindi Music & Soundtracks' }}">
    <meta property="og:description" content="{{ $description ?? 'Discover and download premium, studio-grade Hindi NCS music assets. Perfect for YouTube videos, streams, and content creation.' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('assets/images/logo-dark.png') }}">
    <meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">

    {{-- Twitter Cards Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'NCS Hindi | Premium Royalty-Free Hindi Music & Soundtracks' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Discover and download premium, studio-grade Hindi NCS music assets. Perfect for YouTube videos, streams, and content creation.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('assets/images/logo-dark.png') }}">

    {{-- Schema.org Structured Data (JSON-LD) for Search Engine Rich Snippets --}}
    @php
        $settingsService = app(\App\Services\SettingService::class);
        $socialsList = [];
        foreach(['facebook_url', 'x_url', 'instagram_url', 'whatsapp_url', 'youtube_url', 'linkedin_url'] as $key) {
            $val = $settingsService->get($key);
            if ($val) {
                $socialsList[] = $val;
            }
        }
        $siteLogo = asset('assets/images/logo-dark.png');
    @endphp
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@graph": [
        {
          "@@type": "Organization",
          "@@id": "{{ url('/') }}/#organization",
          "name": "NCS Hindi",
          "url": "{{ url('/') }}",
          "logo": {
            "@@type": "ImageObject",
            "@@id": "{{ url('/') }}/#logo",
            "url": "{{ $siteLogo }}",
            "caption": "NCS Hindi Logo"
          },
          "image": {
            "@@id": "{{ url('/') }}/#logo"
          },
          "description": "{{ $description ?? 'NCS Hindi is the ultimate hub for premium, royalty-free Hindi music, non-copyright soundtracks, and studio-grade audio assets for content creators.' }}",
          @if(!empty($socialsList))
          "sameAs": {!! json_encode($socialsList) !!},
          @endif
          "contactPoint": {
            "@@type": "ContactPoint",
            "contactType": "customer support",
            "email": "support@@ncshindi.com",
            "url": "{{ url('/') }}"
          }
        },
        {
          "@@type": "WebSite",
          "@@id": "{{ url('/') }}/#website",
          "url": "{{ url('/') }}",
          "name": "NCS Hindi",
          "description": "{{ $description ?? 'NCS Hindi is the ultimate hub for premium, royalty-free Hindi music, non-copyright soundtracks, and studio-grade audio assets for content creators.' }}",
          "publisher": {
            "@@id": "{{ url('/') }}/#organization"
          },
          "potentialAction": {
            "@@type": "SearchAction",
            "target": "{{ url('/search-all') }}?query={search_term_string}",
            "query-input": "required name=search_term_string"
          }
        },
        {
          "@@type": "ItemList",
          "@@id": "{{ url('/') }}/#navigation",
          "name": "NCS Hindi Site Navigation",
          "itemListElement": [
            {
              "@@type": "SiteNavigationElement",
              "position": 1,
              "name": "Thread Feed",
              "url": "{{ route('home') }}"
            },
            {
              "@@type": "SiteNavigationElement",
              "position": 2,
              "name": "Community Chat",
              "url": "{{ route('webapp.community.chat') }}"
            },
            {
              "@@type": "SiteNavigationElement",
              "position": 3,
              "name": "Trending Music",
              "url": "{{ route('webapp.trending') }}"
            },
            {
              "@@type": "SiteNavigationElement",
              "position": 4,
              "name": "Music Library",
              "url": "{{ route('webapp.streams') }}"
            }
            @php
              $categoriesList = \App\Models\Category::where('is_active', true)
                ->withCount([
                    'music as public_music_count' => function ($query) {
                        $query->where('is_public', true);
                    }
                ])
                ->orderByDesc('public_music_count')
                ->limit(4)
                ->get();
              $pos = 5;
            @endphp
            @foreach($categoriesList as $cat)
            ,{
              "@@type": "SiteNavigationElement",
              "position": {{ $pos++ }},
              "name": "{{ $cat->name }} Music",
              "url": "{{ route('webapp.music.genre', $cat->slug) }}"
            }
            @endforeach
          ]
        }
      ]
    }
    </script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">

    {{-- Preconnect & DNS prefetch for critical CDNs & Image Hosts --}}
    <link rel="dns-prefetch" href="//i.ibb.co">
    <link rel="dns-prefetch" href="//images.unsplash.com">
    <link rel="dns-prefetch" href="//ui-avatars.com">
    <link rel="preconnect" href="https://i.ibb.co" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    {{-- High-performance Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@700;900&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@stack('heads')
<style>
    /* Default (Dark Mode) Theme Variables */
    :root {
        --bg: #050505;
        --card: #0d0d0f;
        --border: #1a1a1e;
        --text: #efefef;
        --text-muted: #a1a1aa;
        --panel: #0d0d0f;
        --header-bg: rgba(5, 5, 5, 0.5);
        --header-border: rgba(255, 255, 255, 0.05);
        --grad-primary: linear-gradient(135deg, #b45309 0%, #991b1b 100%);
    }

    /* Light Mode Theme Variables */
    html.light {
        --bg: #f8fafc;
        --card: #ffffff;
        --border: #e2e8f0;
        --text: #0f172a;
        --text-muted: #64748b;
        --panel: #f1f5f9;
        --header-bg: rgba(248, 250, 252, 0.7);
        --header-border: rgba(0, 0, 0, 0.05);
    }

    /* Smooth Micro-transitions for Theme Switching */
    html, body, aside, header, nav, div, section, article, a, button, input, select, h1, h2, h3, h4, h5, h6, p, span, i {
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg);
        color: var(--text);
    }

    .font-brand {
        font-family: 'Outfit', sans-serif;
    }

    .btn-vault {
        background: var(--grad-primary);
        box-shadow: 0 4px 15px rgba(153, 27, 27, 0.4);
        border-radius: 12px;
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .forum-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 24px;
        transition: 0.3s;
    }

    .forum-card:hover {
        border-color: #b45309;
        background: #111114;
    }

    html.light .forum-card:hover {
        background: var(--panel);
        border-color: #b45309;
    }

    .stat-badge {
        background: rgba(180, 83, 9, 0.1);
        border: 1px solid rgba(180, 83, 9, 0.2);
        color: #f59e0b;
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* --- Premium Light Mode Style Overrides --- */
    html.light {
        color-scheme: light;
    }
    
    html.light main {
        background-color: var(--bg) !important;
    }
    
    /* Sidebar Overrides */
    html.light aside {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
    }
    html.light aside h1, html.light aside p.text-white {
        color: var(--text) !important;
    }
    html.light aside a {
        color: var(--text-muted) !important;
    }
    html.light aside a:hover {
        color: var(--text) !important;
        background-color: var(--panel) !important;
    }
    html.light aside a.bg-gradient-to-r {
        background-image: linear-gradient(to right, rgba(180, 83, 9, 0.08), transparent) !important;
        color: #b45309 !important;
        border-left-color: #b45309 !important;
    }
    html.light aside div.bg-gradient-to-b {
        background-image: linear-gradient(to bottom, var(--panel), var(--card)) !important;
        border-color: var(--border) !important;
    }
    html.light aside div.bg-zinc-900\/50 {
        background-color: var(--panel) !important;
        border-color: var(--border) !important;
    }

    /* Header Overrides */
    html.light header {
        background-color: var(--header-bg) !important;
        border-color: var(--header-border) !important;
        color: var(--text) !important;
    }
    html.light header h2 {
        color: var(--text) !important;
    }
    html.light #vault-search {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
        color: var(--text) !important;
    }
    html.light #vault-search::placeholder {
        color: var(--text-muted) !important;
    }
    html.light #theme-toggle {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
        color: var(--text-muted) !important;
    }
    html.light #theme-toggle:hover {
        color: var(--text) !important;
        border-color: #b45309 !important;
    }
    html.light #search-results {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
    }
    html.light #results-list a {
        color: var(--text) !important;
        border-color: var(--border) !important;
    }
    html.light #results-list a:hover {
        background-color: var(--panel) !important;
    }

    /* Cards & Feeds */
    html.light article.bg-zinc-900\/20 {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02) !important;
    }
    html.light article.bg-zinc-900\/20:hover {
        background-color: var(--card) !important;
        border-color: #b45309 !important;
        box-shadow: 0 10px 30px rgba(180, 83, 9, 0.05) !important;
    }
    html.light article h2, html.light article h5 {
        color: var(--text) !important;
    }
    html.light article h2:hover, html.light article h5:hover {
        color: #b45309 !important;
    }
    html.light article span.bg-zinc-900 {
        background-color: var(--panel) !important;
        border-color: var(--border) !important;
    }
    html.light article div.border-zinc-800 {
        border-color: var(--border) !important;
    }
    html.light article div.bg-zinc-950 {
        background-color: var(--panel) !important;
    }
    html.light article p.text-zinc-300 {
        color: var(--text) !important;
    }

    /* Navigation & Links Grid in Webapp Home */
    html.light section a.bg-zinc-900\/40 {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
    }
    html.light section a.bg-zinc-900\/40 h4 {
        color: var(--text) !important;
    }
    html.light section a.bg-zinc-900\/40:hover {
        border-color: #b45309 !important;
        background-color: var(--card) !important;
    }
    html.light div.flex.bg-zinc-900\/50 {
        background-color: var(--panel) !important;
        border-color: var(--border) !important;
    }
    html.light div.flex.bg-zinc-900\/50 a.text-zinc-500 {
        color: var(--text-muted) !important;
    }
    html.light div.flex.bg-zinc-900\/50 a.text-zinc-500:hover {
        color: var(--text) !important;
    }
    html.light h3.text-white {
        color: var(--text) !important;
    }

    /* Music Library Overrides */
    html.light .group.bg-zinc-900\/30, html.light [data-like-card] {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02) !important;
    }
    html.light .group.bg-zinc-900\/30:hover {
        border-color: #b45309 !important;
    }
    html.light [data-like-card] h4 {
        color: var(--text) !important;
    }
    html.light [data-like-card] div.border-zinc-800\/50 {
        border-color: var(--border) !important;
    }
    html.light [data-like-card] a.bg-white {
        background-color: var(--text) !important;
        color: var(--bg) !important;
    }
    html.light [data-like-card] a.bg-white:hover {
        background-color: #b45309 !important;
        color: white !important;
    }
    html.light section h1.text-white {
        color: var(--text) !important;
    }
    html.light div.bg-zinc-900\/40 {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
    }
    html.light div.bg-zinc-900\/40 p.text-white {
        color: var(--text) !important;
    }
    html.light form input[name="search"] {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
        color: var(--text) !important;
    }
    html.light form select {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
        color: var(--text) !important;
    }

    /* Music details page specific overrides */
    html.light section.bg-zinc-900\/40 {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
    }
    html.light section h1.truncate {
        color: var(--text) !important;
    }
    html.light section p.text-zinc-400 {
        color: var(--text-muted) !important;
    }
    html.light section div.bg-zinc-800\/60 {
        background-color: rgba(0, 0, 0, 0.05) !important;
        border-color: rgba(0, 0, 0, 0.08) !important;
    }
    html.light section button.bg-zinc-800\/80,
    html.light section a.bg-zinc-800\/80 {
        background-color: rgba(0, 0, 0, 0.05) !important;
        border-color: rgba(0, 0, 0, 0.08) !important;
        color: var(--text) !important;
    }
    html.light section button.bg-zinc-800\/80:hover,
    html.light section a.bg-zinc-800\/80:hover {
        background-color: rgba(0, 0, 0, 0.08) !important;
        border-color: #b45309 !important;
        color: #b45309 !important;
    }
    html.light p.text-zinc-400.font-medium {
        color: var(--text) !important;
    }
    html.light div.bg-gradient-to-br.from-zinc-900 {
        background-image: linear-gradient(to bottom right, var(--panel), var(--card)) !important;
        border-color: var(--border) !important;
    }
    html.light div.bg-gradient-to-br.from-zinc-900 h3 {
        color: var(--text) !important;
    }
    html.light div.bg-gradient-to-br.from-zinc-900 div.text-zinc-500 {
        color: var(--text-muted) !important;
    }

    /* Mobile Bottom Navigation deck */
    html.light nav.lg\:hidden {
        background-color: rgba(255, 255, 255, 0.9) !important;
        border-color: var(--border) !important;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05) !important;
    }
    html.light nav.lg\:hidden a {
        color: var(--text-muted) !important;
    }
    html.light nav.lg\:hidden a.bg-amber-500\/10 {
        background-color: rgba(180, 83, 9, 0.1) !important;
        color: #b45309 !important;
    }
    html.light nav.lg\:hidden div.border-\[\#08080a\] {
        border-color: var(--bg) !important;
    }

    /* Modals and Gates */
    html.light #notificationGateModal div.bg-\[\#0f0f12\] {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
    }
    html.light #notificationGateModal h5, html.light #notificationGateModal h6 {
        color: var(--text) !important;
    }
    html.light #notificationGateModal p {
        color: var(--text-muted) !important;
    }
    html.light #notificationGateModal div.bg-black\/40 {
        background-color: var(--panel) !important;
        border-color: var(--border) !important;
    }
    html.light #notificationGateModal button.bg-white\/5 {
        background-color: var(--panel) !important;
        border-color: var(--border) !important;
        color: var(--text) !important;
    }
    html.light #notificationGateModal button.bg-white\/5:hover {
        border-color: #b45309 !important;
    }

    /* --- Master Text & Typography Contrast Overrides in Light Mode --- */
    html.light {
        --bg: #f3f4f6 !important; /* Soft platinum gray background to pop cards */
        --card: #ffffff !important; /* Crisp pure white cards */
        --border: #d1d5db !important; /* Distinct division lines */
        --text: #09090b !important; /* Solid obsidian black */
        --text-muted: #374151 !important; /* High contrast charcoal */
        --panel: #f9fafb !important; /* Elevated pure light panel */
        --header-bg: rgba(255, 255, 255, 0.95) !important;
        --header-border: rgba(0, 0, 0, 0.1) !important;
    }

    html.light .text-white,
    html.light .text-zinc-100,
    html.light .text-zinc-200,
    html.light .text-zinc-300 {
        color: #09090b !important; /* Solid Obsidian Black */
    }
    
    html.light .text-zinc-400 {
        color: #374151 !important; /* Highly Legible Slate Gray */
    }
    
    html.light .text-zinc-500 {
        color: #1f2937 !important; /* Dark Obsidian-Gray */
    }

    html.light .text-zinc-600 {
        color: #09090b !important; /* Deep contrast body */
    }
    
    html.light .text-zinc-700 {
        color: #09090b !important;
    }

    html.light .text-zinc-800 {
        color: #000000 !important;
    }

    /* Absolute background overrides to contrast nicely against bg-slate */
    html.light .bg-zinc-950,
    html.light .bg-zinc-950\/80,
    html.light .bg-zinc-950\/85,
    html.light .bg-zinc-950\/90,
    html.light .bg-zinc-900\/30,
    html.light .bg-zinc-900\/50,
    html.light .bg-black\/40 {
        background-color: var(--panel) !important;
        border-color: var(--border) !important;
    }

    html.light .bg-zinc-900,
    html.light .bg-zinc-900\/20,
    html.light .bg-zinc-900\/40,
    html.light .bg-black\/50,
    html.light .bg-\[\#0a0a0c\],
    html.light .bg-\[\#0a0a0c\]\/90,
    html.light .bg-\[\#0a0a0c\]\/80,
    html.light .bg-\[\#050505\],
    html.light .bg-\[\#000\],
    html.light .bg-\[\#000000\],
    html.light .forum-card {
        background-color: var(--card) !important;
        background: var(--card) !important;
        border-color: var(--border) !important;
    }

    html.light .border-zinc-800,
    html.light .border-zinc-900,
    html.light .border-zinc-800\/50,
    html.light .border-zinc-800\/60,
    html.light .border-white\/5 {
        border-color: var(--border) !important;
    }

    /* Upgraded Zinc Utility Buttons in Light Mode */
    html.light button.bg-zinc-800,
    html.light button.bg-zinc-900,
    html.light button.bg-zinc-950,
    html.light a.bg-zinc-800,
    html.light a.bg-zinc-900,
    html.light span.bg-zinc-900 {
        background-color: var(--panel) !important;
        border: 1px solid var(--border) !important;
        color: var(--text) !important;
    }
    
    html.light button.bg-zinc-800:hover,
    html.light button.bg-zinc-900:hover,
    html.light a.bg-zinc-800:hover {
        background-color: var(--card) !important;
        border-color: #b45309 !important;
        color: #b45309 !important;
    }

    /* Music Action Buttons (Youtube, Like, Likes Count, Share) in Light Mode */
    html.light [data-like-card] a,
    html.light [data-like-card] button,
    html.light [data-like-card] div.bg-zinc-800\/60 {
        background-color: rgba(0, 0, 0, 0.05) !important; /* Soft semi-transparent gray to blend perfectly! */
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        color: var(--text) !important;
    }

    html.light [data-like-card] a i,
    html.light [data-like-card] button i,
    html.light [data-like-card] div.bg-zinc-800\/60 span {
        color: var(--text) !important;
    }

    html.light [data-like-card] a:hover,
    html.light [data-like-card] button:hover {
        background-color: var(--panel) !important;
        border-color: #b45309 !important;
        color: #b45309 !important;
    }

    html.light [data-like-card] a:hover i,
    html.light [data-like-card] button:hover i {
        color: #b45309 !important;
    }

    /* Primary bg-white buttons (such as Download Button & Google Login Button) in Light Mode */
    html.light a.bg-white,
    html.light button.bg-white {
        background-color: #09090b !important;
        color: #ffffff !important;
        border: 1px solid #09090b !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12) !important;
    }

    html.light a.bg-white:hover,
    html.light button.bg-white:hover {
        background-color: #b45309 !important;
        color: #ffffff !important;
        border-color: #b45309 !important;
        box-shadow: 0 10px 30px rgba(180, 83, 9, 0.25) !important;
    }

    /* Language Badges and Spans in Light Mode */
    html.light span.bg-zinc-800,
    html.light span.bg-zinc-900,
    html.light span.bg-white\/5 {
        background-color: var(--panel) !important;
        border: 1px solid var(--border) !important;
        color: var(--text) !important;
    }
    
    html.light span.bg-zinc-800 i,
    html.light span.bg-zinc-900 i {
        color: var(--text) !important;
    }

    /* Core Action Button (btn-vault) Text Contrast in Light Mode */
    html.light .btn-vault,
    html.light aside a.btn-vault {
        color: #ffffff !important;
        background: var(--grad-primary) !important;
    }

    /* Tag Keywords and Black Buttons in Light Mode */
    html.light a.bg-black,
    html.light button.bg-black {
        background-color: var(--panel) !important;
        border: 1px solid var(--border) !important;
        color: var(--text) !important;
    }
    
    html.light a.bg-black:hover,
    html.light button.bg-black:hover {
        background-color: var(--card) !important;
        border-color: #b45309 !important;
        color: #b45309 !important;
    }

    /* Icon Contrast & Inheritance Rules */
    html.light .text-zinc-400 i,
    html.light .text-zinc-500 i,
    html.light .text-zinc-600 i,
    html.light .text-zinc-700 i,
    html.light .text-zinc-800 i {
        color: inherit !important;
    }

    html.light i.fa-solid,
    html.light i.fa-regular,
    html.light i.fa-brands {
        opacity: 0.95 !important;
    }

    /* Inner Thread & Prose Legibility - Solid Black & Crisp Slate */
    html.light .prose-invert,
    html.light .prose {
        color: #09090b !important;
    }
    
    html.light .prose-invert p,
    html.light .prose p,
    html.light .prose-invert li,
    html.light .prose li,
    html.light .prose-invert strong,
    html.light .prose strong,
    html.light .prose-invert h1,
    html.light .prose-invert h2,
    html.light .prose-invert h3,
    html.light .prose-invert h4,
    html.light .prose-invert h5,
    html.light .prose-invert h6 {
        color: #09090b !important;
        font-weight: 600 !important;
    }

    html.light .prose-invert h1, html.light .prose-invert h2, html.light .prose-invert h3,
    html.light .prose h1, html.light .prose h2, html.light .prose h3 {
        color: #000000 !important;
        font-weight: 800 !important;
    }

    /* Quill Input Editor in Light Mode */
    html.light .ql-toolbar.ql-snow {
        border: 1px solid var(--border) !important;
        background: var(--panel) !important;
    }
    html.light .ql-container.ql-snow {
        border: 1px solid var(--border) !important;
        background: var(--card) !important;
        color: #09090b !important;
    }
    html.light .ql-editor {
        color: #09090b !important;
        background-color: var(--card) !important;
    }
    html.light .ql-snow .ql-stroke {
        stroke: #374151 !important;
    }
    html.light .ql-snow .ql-fill {
        fill: #374151 !important;
    }
    html.light .ql-snow .ql-picker {
        color: #374151 !important;
    }
    html.light .group.bg-zinc-900\/30, html.light [data-like-card] {
        background-color: transparent !important;
        border: transparent !important;
        color: var(--text) !important;
    }

    /* Trending Page Light Mode Overrides */
    html.light .trend-shell {
        background:
            radial-gradient(circle at top left, rgba(180, 83, 9, 0.08), transparent 28%),
            radial-gradient(circle at top right, rgba(153, 27, 27, 0.08), transparent 24%),
            linear-gradient(180deg, var(--card), var(--panel)) !important;
        border-color: var(--border) !important;
    }

    html.light .glass-panel {
        background: rgba(255, 255, 255, 0.7) !important;
        border-color: var(--border) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
        backdrop-filter: blur(16px);
    }

    html.light .trend-card {
        background: var(--card) !important;
        border-color: var(--border) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
    }

    html.light div.bg-gradient-to-br.from-black.via-\[\#0b0b0d\],
    html.light div.bg-gradient-to-br.from-black {
        background-image: linear-gradient(to bottom right, var(--panel), var(--card)) !important;
        border-color: var(--border) !important;
    }

    html.light div.bg-gradient-to-l.from-black\/10 {
        background-image: none !important;
        background: transparent !important;
    }

    html.light input.bg-black\/40,
    html.light select.bg-black\/40 {
        background-color: var(--card) !important;
        border-color: var(--border) !important;
        color: var(--text) !important;
    }

    /* Image Overlay Badges and Buttons in Light Mode */
    html.light span.bg-black\/55,
    html.light button.bg-black\/65,
    html.light a.bg-black\/65 {
        background-color: rgba(0, 0, 0, 0.65) !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
    }
    html.light span.bg-black\/55 {
        color: #ffffff !important;
    }
    html.light button.bg-black\/65 i,
    html.light a.bg-black\/65 i {
        color: #ffffff !important;
    }
</style>




