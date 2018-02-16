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
      'slug' => 'home',
      'name' => 'home',
      'title' => 'Home',
      'content' => null,
      'is_enabled' => 1,
      'is_published' => 1,
      'is_permission_changeable' => 0,
    ],
  	[
  		'slug' => 'teams',
  		'name' => 'teams',
  		'title' => 'Teams',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'slug' => 'matches',
  		'name' => 'matches',
  		'title' => 'Matches',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'slug' => 'venue',
  		'name' => 'venue',
  		'title' => 'Venue',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'slug' => 'tournament',
  		'name' => 'tournament',
  		'title' => 'Tournament',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  		'children' => [
  			[
  				'slug' => 'rules',
	  			'name' => 'rules',
	  			'title' => 'Rules',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		],
  			[
  				'slug' => 'history',
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
  		'slug' => 'program',
  		'name' => 'program',
  		'title' => 'Program',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'slug' => 'stay',
  		'name' => 'stay',
  		'title' => 'Stay',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  		'children' => [
  			[
  				'slug' => 'meals',
	  			'name' => 'meals',
	  			'title' => 'Meals',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		],
  			[
  				'slug' => 'accommodation',
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
  		'slug' => 'visitors',
  		'name' => 'visitors',
  		'title' => 'Visitors',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  		'children' => [
  			[
  				'slug' => 'tourist-information',
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
      'slug' => 'media',
      'name' => 'media',
      'title' => 'Media',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
    ],
  ],
  'permissions_not_changeable_pages' => ['home'],
];