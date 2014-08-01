
<div id="reg_form">
	<h1>Please Register</h1>
	<?php echo validation_errors('<p class="error">'); ?>
	<?php echo form_open("student/register"); ?>
	  <div>
	  <label for="name">Name:</label>
	  <input type="text" id="name" name="name" value="<?php echo set_value('name'); ?>" />
	  </div>
	  <div>
	  <label for="email_address">Your Email:</label>
	  <input type="text" id="email_address" name="email_address" value="<?php echo set_value('email_address'); ?>" />
	  </div>
	  <div>
	  <label for="password">Password:</label>
	  <input type="password" id="password" name="password" value="<?php echo set_value('password'); ?>" />
	  </div>
	  <div>
	  <label for="con_password">Confirm Password:</label>
	  <input type="password" id="con_password" name="con_password" value="<?php echo set_value('con_password'); ?>" />
	  </div>
	  <div class="submit">
	  <input type="submit" class="button" value="Register" />
	  </div>
	<?php echo form_close(); ?>
</div><!--<div class="reg_form">-->