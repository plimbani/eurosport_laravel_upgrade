<title>{{ $meta_details['tournament_name'] }} - {{ $pageTitle }}</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="{{ $meta_details['description'] }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel='canonical' href='current-url'>
<meta name="author" content="aecor">
<meta name='mobile-web-app-capable' content="yes">
<meta name='apple-mobile-web-app-capable' content="yes">
<meta name='application-name' content="World of tournament">
<meta name='apple-mobile-web-app-status-bar-style' content="black">
<meta name='apple-mobile-web-app-title' content="{{ $meta_details['tournament_name'] }} - {{ $pageTitle }}">
<!-- Chrome, Firefox OS and Opera -->
<meta name='theme-color' content="#667eea">
<!-- Windows Phone -->
<meta name="msapplication-navbutton-color" content="#667eea">

<!-- Open Graph Meta -->
<meta property="og:title" content="{{ $meta_details['tournament_name'] }} - {{ $pageTitle }}" />
<meta property="og:site_name" content="{{ $meta_details['tournament_name'] }}" />
<meta property="og:description" content="{{ $meta_details['description'] }}" />
<meta property="og:site_name" content="{{ $meta_details['tournament_name'] }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:image" content="{{ $meta_details['open_graph_image'] }}" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="635" />
<meta name='twitter:card' content="summary" />
<meta name='twitter:url' content="{{ url()->current() }}" />
<meta name='twitter:title' content="{{ $meta_details['tournament_name'] }} - {{ $pageTitle }}" />
<meta name='twitter:description' content="{{ $meta_details['description'] }}" />
<meta name='twitter:image' content="{{ $meta_details['open_graph_image'] }}" />
<meta name='twitter:creator' content="">
<!-- iOS Safari -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
