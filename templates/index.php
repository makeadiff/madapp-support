<div id="content" class="container">
	<?php foreach($support_types as $support) { ?>
	<div class="tile col-md-3" style="background-color:<?php echo color() ?>"><a href="<?php 
		echo $support.'.php';
	?>"><?php echo format($support); ?></a></div>
	<?php } ?>
</div> 
