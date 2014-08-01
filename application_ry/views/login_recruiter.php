	<h1>Please Log into your Direct Resume Account!</h1>
	<div id="body">
	 <?php echo form_open("recruiter/login"); ?>
	 <div id="login">
		<?php if($msg) { ?> <div class="error"> <?php echo $msg; ?></div> <?php } ?>
		<div>
			<label for="email">Email:</label>
			<input type="text" id="email" name="email" value="" />
		</div>
		<div>
			<label for="password">Password:</label>
			<input type="password" id="password" name="password" value="" />
		</div>
		<div class="submit">
		<input type="submit" class="button" value="Log in" />
		</div>
	 <?php echo form_close(); ?>
		<div class="register">
		 No account? <a href="<?php print site_url('recruiter/register');?>">Register Here</a>
		 </div>
	 </div>
	 
	</div>