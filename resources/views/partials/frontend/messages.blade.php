<?php 
	foreach ($messages as $key => $message) {
	?>
	<div class="alert alert-dismissible alert-warning fade position-absolute show" role="alert">
	  <?php echo $message->content ?>
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>
	<?php
	}
?>