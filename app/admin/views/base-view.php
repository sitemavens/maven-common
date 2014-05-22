<div id="maven-body" class="wrap">
    <div id="maven-header">
		<div class="wrapper">
			<h1><a href="#">&nbsp;</a></h1>
			
			<?php if (isset($title) OR isset($add_new_button)): ?>
			<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
			<h2><?php if (isset($title))
			echo $title ?> <?php if (isset($add_new_button)): ?><a id="addNew" class="add-new-h2" href="<?php echo $add_new_button ?> "><?php _e("Add New"); ?></a> <?php endif; ?></h2>
			<?php endif; ?>
		</div>
	</div>
	<div class="maven-messages">
		<div id="messageContainer" class="updated below-h2" style="<?php echo (isset($success_message) AND $success_message)?'':'display: none;'; ?>">
			<p><span id="succesMessage"><?php echo (isset($success_message) AND $success_message)?$success_message:''; ?></span></p>
		</div>

		<div id="errorMessageContainer" class="error below-h2" style="<?php echo (isset($error_message) AND $error_message)?'':'display: none;'; ?>">
			<p><span id="errorMessage"><?php echo (isset($error_message) AND $error_message)?$error_message:''; ?></span></p>
		</div>
	</div>
	<div class="maven-content">
		<?php echo $view ?>
	</div>
</div>