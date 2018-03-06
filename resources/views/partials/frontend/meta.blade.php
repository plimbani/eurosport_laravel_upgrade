<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

<title>{{ $meta_details['tournament_name'] }} - {{ $pageTitle }}</title>

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="{{ $meta_details['description'] }}">
<meta name="author" content="aecor">

<!-- Open Graph Meta -->
<meta property="og:title" content="{{ $meta_details['tournament_name'] }} - {{ $pageTitle }}">
<meta property="og:site_name" content="{{ $meta_details['tournament_name'] }}">
<meta property="og:description" content="{{ $meta_details['description'] }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $meta_details['open_graph_image'] }}">