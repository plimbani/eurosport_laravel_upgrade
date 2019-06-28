@if($websiteDetail->tournament_logo)
	<link rel="apple-touch-icon" sizes="180x180" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'apple-touch-icon.png' }}">
	<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 3)" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'apple-touch-startup-image-1182x2208.png' }}">
	<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 3)" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'apple-touch-startup-image-1242x2148.png' }}">
	<link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'apple-touch-startup-image-1496x2048.png' }}">
	<link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'apple-touch-startup-image-1536x2008.png' }}">
	<link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'apple-touch-startup-image-320x460.png' }}">
	<link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'apple-touch-startup-image-640x1096.png' }}">
	<link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'apple-touch-startup-image-640x920.png' }}">
	<link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 1)" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'apple-touch-startup-image-748x1024.png' }}">
	<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'apple-touch-startup-image-750x1294.png' }}">
	<link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 1)" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'apple-touch-startup-image-768x1004.png' }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'favicon-32x32.png' }}">
	<link rel="icon" type="image/png" sizes="230x230" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'favicon-230x230.png' }}">
	<link rel="icon" type="image/png" sizes="192x192" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'android-chrome-192x192.png' }}">
	<link rel="icon" type="image/png" sizes="228x228" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'coast-228x228.png' }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'favicon-16x16.png' }}">
	<link rel="manifest" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'site.webmanifest' }}">
	<link rel="mask-icon" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'safari-pinned-tab.svg" color="#FFFFFF' }}">
	<link rel="shortcut icon" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'favicon.ico' }}">
	<link rel="yandex-tableau-widget" href="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'yandex-browser-manifest.json' }}">
	<meta property="og:image" content="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'open-graph.png' }}">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="msapplication-TileColor" content="#654321">
	<meta name="msapplication-TileImage" content="{{ config('filesystems.disks.s3.url') . config('wot.imagePath.favicon') . $websiteDetail->id . '/' . 'mstile-144x144.png' }}">
	<meta name="theme-color" content="#FFFFFF">
@else
	<link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/apple-icon-57x57.png') }}">
	<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/apple-icon-60x60.png') }}">
	<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/apple-icon-72x72.png') }}">
	<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/apple-icon-76x76.png') }}">
	<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/apple-icon-114x114.png') }}">
	<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/apple-icon-120x120.png') }}">
	<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/apple-icon-144x144.png') }}">
	<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/apple-icon-152x152.png') }}">
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/apple-icon-180x180.png') }}">
	<link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/android-icon-192x192.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/favicon-96x96.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/favicon-16x16.png') }}">
	<link rel="manifest" href="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/manifest.json') }}">
	<meta name='msapplication-TileImage' content="{{ asset('assets/img/favicons/'.config('config-variables.current_layout').'/ms-icon-144x144.png') }}">
	<meta name='msapplication-TileColor' content="#667eea">
	<link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
@endif