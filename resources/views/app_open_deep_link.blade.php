<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
	var googleStoreOpenDeepLink = '{!! $googleStoreOpenDeepLink !!}';
</script>
<script type="text/javascript">
	$(document).ready(function() {
		setTimeout(function() {		
			window.location.href = googleStoreOpenDeepLink;
		}, 500);
	});
</script>