/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referencing this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'jobviewer\'">' + entity + '</span>' + html;
	}
	var icons = {
		'jv-comment': '&#xe904;',
		'jv-home': '&#x1f3e0;',
		'jv-dashboard-app': '&#xe901;',
		'jv-dashboard': '&#xe902;',
		'jv-tools': '&#xe903;',
		'jv-clock': '&#x1f553;',
		'jv-car': '&#x1f698;',
		'jv-chats': '&#xe906;',
		'jv-chat': '&#xe907;',
		'jv-water': '&#xe908;',
		'jv-fish': '&#xe909;',
		'jv-pipe': '&#xe90a;',
		'jv-pipe-line': '&#xe926;',
		'jv-calendar': '&#xe90b;',
		'jv-list': '&#xe90c;',
		'jv-user': '&#xe90d;',
		'jv-doc': '&#xe90e;',
		'jv-edit': '&#xe90f;',
		'jv-backward': '&#xe910;',
		'jv-forward': '&#xe911;',
		'jv-previous': '&#xe912;',
		'jv-next': '&#xe913;',
		'jv-checked-arrow': '&#xe914;',
		'jv-checked': '&#xe915;',
		'jv-downarrow-black': '&#xe916;',
		'jv-uparrow-black': '&#xe917;',
		'jv-downarrow': '&#xe918;',
		'jv-uparrow': '&#xe919;',
		'jv-down': '&#xe91a;',
		'jv-up': '&#xe91b;',
		'jv-up-down': '&#xe927;',
		'jv-plus': '&#x2795;',
		'jv-close': '&#x2715;',
		'jv-search': '&#x1f50d;',
		'jv-dustbin': '&#x267b;',
		'jv-download': '&#xe900;',
		'jv-find-doc': '&#x1f50e;',
		'jv-go-up': '&#xe922;',
		'jv-cloud': '&#x1f325;',
		'jv-reload': '&#xe924;',
		'jv-web': '&#x1f310;',
		'jv-error': '&#x26a0;',
		'jv-calendar-time': '&#x1f4c6;',
		'jv-checklist': '&#xe928;',
		'jv-steering': '&#xe92a;',
		'jv-truck': '&#x1f69a;',
		'jv-truck-info': '&#x1f69b;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/jv-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
