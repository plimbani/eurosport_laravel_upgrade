<?php

return [
	'website_customisation_options' => [
		'colors' => [
			'#D0021B', '#FF9F00', '#417505', '#50E3C2', '#1E469D', '#9013FE'
		],
		'fonts' => [
			'Open Sans', 'Montserrat', 'Roboto', 'Lato', 'PT Sans', 'Helvetica'
		],
	],
  'website_default_pages' => [
    [
      'url' => '/',
      'name' => 'home',
      'page_name' => 'home',
      'title' => 'Home',
      'content' => null,
      'is_enabled' => 1,
      'is_published' => 1,
      'is_permission_changeable' => 0,
      'accessible_routes' => ['home.page.details']
    ],
  	[
  		'url' => '/teams',
  		'name' => 'teams',
      'page_name' => 'teams',
  		'title' => 'Teams',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['team.page.details']
  	],
  	[
  		'url' => '/matches',
  		'name' => 'matches',
      'page_name' => 'matches',
  		'title' => 'Matches',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['match.page.details']
  	],
  	[
  		'url' => '/venue',
  		'name' => 'venue',
      'page_name' => 'venue',
  		'title' => 'Venue',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['venue.page.details']
  	],
  	[
  		'url' => '/tournament',
  		'name' => 'tournament',
      'page_name' => 'tournament',
  		'title' => 'Tournament',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['tournament.page.details'],
  		'children' => [
  			[
  				'url' => '/rules',
	  			'name' => 'rules',
          'page_name' => 'rules',
	  			'title' => 'Rules',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['rules.page.details']
	  		],
  			[
  				'url' => '/history',
	  			'name' => 'history',
          'page_name' => 'history',
	  			'title' => 'History',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['history.page.details']
	  		],
  		]
  	],
  	[
  		'url' => '/program',
  		'name' => 'program',
      'page_name' => 'program',
  		'title' => 'Program',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['program.page.details']
  	],
  	[
  		'url' => '/stay',
  		'name' => 'stay',
      'page_name' => 'stay',
  		'title' => 'Stay',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['stay.page.details'],
  		'children' => [
  			[
  				'url' => '/meals',
	  			'name' => 'meals',
          'page_name' => 'meals',
	  			'title' => 'Meals',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['meals.page.details']
	  		],
  			[
  				'url' => '/accommodation',
	  			'name' => 'accommodation',
          'page_name' => 'accommodation',
	  			'title' => 'Accommodation',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['accommodation.page.details']
	  		]
  		]
  	],
  	[
  		'url' => '/visitors',
  		'name' => 'visitors',
      'page_name' => 'visitors',
  		'title' => 'Visitors',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['visitor.page.details'],
  		'children' => [
  			[
  				'url' => '/tourist-information',
	  			'name' => 'tourist_information',
          'page_name' => 'tourist-information',
	  			'title' => 'Tourist information',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['tourist.page.details']
	  		]
  		]
  	],
    [
      'url' => '/media',
      'name' => 'media',
      'page_name' => 'media',
      'title' => 'Media',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['media.page.details']
    ],
    [
      'url' => '/contact',
      'name' => 'contact',
      'page_name' => 'contact',
      'title' => 'Contact',
      'content' => null,
      'is_enabled' => 1,
      'is_published' => 1,
      'is_permission_changeable' => 0,
      'accessible_routes' => ['contact.page.details', 'submit.enquiry']
    ]
  ],
  'permissions_not_changeable_pages' => ['home', 'contact'],
  'current_domain' => null,
  'default_accessible_routes' => [],
  'google_api_key' => env('GOOGLE_API_KEY', ''),
  'imagePath' => [
    'website_tournament_logo' => '/assets/img/website_tournament_logo/',
    'social_sharing_graphic' => '/assets/img/social_sharing_graphic/',
    'hero_image' => '/assets/img/hero_image/',
    'welcome_image' => '/assets/img/welcome_image/',
    'organiser_logo' => '/assets/img/organiser/',
    'sponsor_logo' => '/assets/img/sponsor/',
    'photo' => '/assets/img/photo/',
    'document' => '/assets/img/document/',
    'editor_image' => '/assets/img/editor_image/',
  ],
  'tempImagePath' => storage_path() . '/temp_images/',
  'notification_page_names' => [
    'website' => 'Website',
    'home' => 'Home',
    'teams' => 'Teams',
    'matches' => 'Matches',
    'venue' => 'Venue',
    'tournament' => 'Tournament',
    'rules' => 'Tournament',
    'history' => 'Tournament',
    'program' => 'Program',
    'stay' => 'Stay',
    'meals' => 'Stay',
    'accommodation' => 'Stay',
    'visitors' => 'Visitors',
    'tourist_information' => 'Visitors',
    'media' => 'Media',
    'contact' => 'Contact',
  ],
  'message_notification_days' => 14,
  'activity_notification_recepients' => [
    'to' => ['bgrout@aecordigital.com', 'mtilokani@aecordigital.com'],
    'cc' => [],
    'bcc' => ['ssheth@aecordigital.com'],
  ],
  'inquiries_recipient' => ['ssheth@aecordigital.com'],
  'google_re_captcha_site_key' => env('GOOGLE_RE_CAPTCHA_SITE_KEY', ''),
  'google_re_captcha_secret_key' => env('GOOGLE_RE_CAPTCHA_SECRET_KEY', '')
];