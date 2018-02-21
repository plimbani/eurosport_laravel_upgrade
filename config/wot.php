<?php

return [
	'website_customisation_options' => [
		'primary_colors' => [
			'#800000', '#FF0000', '#FFA500', '#FFFF00', '#808000', '#008000', '#0000FF', '#00FFFF'
		],
		'secondary_colors' => [
			'#800000', '#FF0000', '#FFA500', '#FFFF00', '#808000', '#008000', '#0000FF', '#00FFFF'
		],
		'heading_font' => [
			'Open Sans', 'Lato', 'Old Standard TT', 'Abril Fatface', 'PT Serif'
		],
		'body_font' => [
			'Ubuntu', 'Vollkorn', 'Droid', 'PT Mono', 'Gravitas One'
		]
	],
  'website_default_pages' => [
    [
      'url' => 'home',
      'name' => 'home',
      'title' => 'Home',
      'content' => null,
      'is_enabled' => 1,
      'is_published' => 1,
      'is_permission_changeable' => 0,
    ],
  	[
  		'url' => 'teams',
  		'name' => 'teams',
  		'title' => 'Teams',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'url' => 'matches',
  		'name' => 'matches',
  		'title' => 'Matches',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'url' => 'venue',
  		'name' => 'venue',
  		'title' => 'Venue',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'url' => 'tournament',
  		'name' => 'tournament',
  		'title' => 'Tournament',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  		'children' => [
  			[
  				'url' => 'rules',
	  			'name' => 'rules',
	  			'title' => 'Rules',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		],
  			[
  				'url' => 'history',
	  			'name' => 'history',
	  			'title' => 'History',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		],
  		]
  	],
  	[
  		'url' => 'program',
  		'name' => 'program',
  		'title' => 'Program',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'url' => 'stay',
  		'name' => 'stay',
  		'title' => 'Stay',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  		'children' => [
  			[
  				'url' => 'meals',
	  			'name' => 'meals',
	  			'title' => 'Meals',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		],
  			[
  				'url' => 'accommodation',
	  			'name' => 'accommodation',
	  			'title' => 'Accommodation',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		]  			
  		]
  	],
  	[
  		'url' => 'visitors',
  		'name' => 'visitors',
  		'title' => 'Visitors',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  		'children' => [
  			[
  				'url' => 'tourist-information',
	  			'name' => 'tourist_information',
	  			'title' => 'Tourist information',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		]
  		]
  	],
    [
      'url' => 'media',
      'name' => 'media',
      'title' => 'Media',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
    ],
  ],
  'permissions_not_changeable_pages' => ['home'],
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
  'tempImages' => storage_path().'/temp_images/',
];