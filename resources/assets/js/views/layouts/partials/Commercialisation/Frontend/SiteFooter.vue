<template>
    <footer class="section-padding footer">
    	<div class="container">
    		<div class="row">
    			<div class="col-sm-6">
    				<h4 class="text-uppercase text-white mb-2 font-weight-bold">Sales Enquiries</h4>
    				<ul class="list-unstyled mb-md-0">
    					<li class="d-lg-inline"><a href="mailto:sales@tournamentplanner.com" class="text-white">sales@tournamentplanner.com</a></li>
    					<li class="d-lg-inline pl-0 pl-lg-4"><a href="tel:+44(0)1234 567 890" class="text-white">+44(0)1234 567 890</a></li>
    				</ul>
    			</div>

    			<div class="col-sm-6">
    				<h4 class="text-uppercase text-white mb-2 font-weight-bold">Helpdesk</h4>
    				<ul class="list-unstyled mb-md-0">
    					<li class="d-lg-inline"><a href="mailto:help@tournamentplanner.com" class="text-white">help@tournamentplanner.com</a></li>
    					<li class="d-lg-inline pl-0 pl-lg-4"><a href="tel:+44(0)1234 567 890" class="text-white">+44(0)1234 567 890</a></li>
    				</ul>
    			</div>
    		</div>
			
			<div class="section-divider my-4"></div>

    		<div class="row">
    			<div class="col-sm-6">
    				<ul class="list-unstyled text-center text-sm-left mb-md-0 policy-links">
    					<li class="d-inline small"><a href="#" class="text-white">Privacy Policy</a></li>
    					<li class="d-inline pl-4 small"><a href="#" class="text-white">Terms & Conditions</a></li>
    				</ul>
    			</div>
    			<div class="col-sm-6 text-center text-sm-right">
    				<p class="text-white small mb-0">&copy; {{currentYear}} Easy Tournament Manager</p>
    			</div>
    		</div>
    	</div>
    </footer>
</template>
<script type="text/javascript">
    export default {
        data() {
            return {
                'currentYear': new Date().getFullYear()
            }
        },
        mounted() {
            $(document).ready( function(){
                var headerHeight = $(".header").outerHeight();
                var footerHeight = $(".footer").outerHeight();
                // $('.main-section').css({ 'min-height': 'calc(100vh - ' + (headerHeight + footerHeight) + 'px)' });
                $('.main-section').css({ 'min-height': 'calc(100vh - ' + (footerHeight) + 'px)' });
            });
        }            
    }
</script>