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
  		'slug' => 'teams',
  		'name' => 'teams',
  		'title' => 'Teams'
  	],
  	[
  		'slug' => 'matches',
  		'name' => 'matches',
  		'title' => 'Matches'	  		
  	],
  	[
  		'slug' => 'venue',
  		'name' => 'venue',
  		'title' => 'Venue'
  	],
  	[
  		'slug' => 'tournament',
  		'name' => 'tournament',
  		'title' => 'Tournament',
  		'additional_pages' => [
  			[
  				'slug' => 'rules',
	  			'name' => 'rules',
	  			'title' => 'Rules'
	  		],
  			[
  				'slug' => 'history',
	  			'name' => 'history',
	  			'title' => 'History'
	  		],		  			
  		]
  	],
  	[
  		'slug' => 'program',
  		'name' => 'program',
  		'title' => 'Program'
  	],
  	[
  		'slug' => 'stay',
  		'name' => 'stay',
  		'title' => 'Stay',
  		'additional_pages' => [
  			[
  				'slug' => 'meals',
	  			'name' => 'meals',
	  			'title' => 'Meals'
	  		],
  			[
  				'slug' => 'accommodation',
	  			'name' => 'accommodation',
	  			'title' => 'Accommodation'
	  		]  			
  		]
  	],
  	[
  		'slug' => 'visitors',
  		'name' => 'visitors',
  		'title' => 'Visitors',
  		'additional_pages' => [
  			[
  				'slug' => 'tourist-information',
	  			'name' => 'tourist information',
	  			'title' => 'Tourist information'
	  		]
  		]
  	],	  	
  ]
];