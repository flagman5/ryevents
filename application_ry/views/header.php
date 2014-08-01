<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to Direct Resumes</title>
	<style> @import url('<?php print base_url();?>assets/css/default.css'); </style>
</head>
<body>
<div id="custom_header">
<?php if($this->session->userdata('user_id')) {
		if('recruiter' == $this->uri->segment(1)) { ?>
			<a href="<?php print site_url("/recruiter/home");?>"><img src="<?php print base_url();?>assets/images/logo.png"></a>
			<div class="rightstuff">
				<a href="<?php print site_url("/recruiter/home");?>" style="margin-right:5px;">Account Home</a> | 
				<a href="<?php print site_url('recruiter/logout')?>" style="margin-left:5px;">Log Out </a>
			</div>
		<?php } 
		else { ?>
			<a href="<?php print site_url("/student/home");?>"><img src="<?php print base_url();?>assets/images/logo.png"></a>
			<div class="rightstuff">
				<a href="<?php print site_url("/student/home");?>" style="margin-right:5px;">Account Home</a> |
				<a href="<?php print site_url('student/logout')?>" style="margin-left:5px;">Log Out </a>
			</div>
		<?php }
	}
	else { 
		if('recruiter' == $this->uri->segment(1)) { ?>
			<a href="<?php print site_url("/recruiter/");?>"><img src="<?php print base_url();?>assets/images/logo.png"></a>
	<?php }
		else { ?>
			<a href="<?php print site_url("/student/");?>"><img src="<?php print base_url();?>assets/images/logo.png"></a>
	<?php } 
	}?>
</div>

<div id="container">