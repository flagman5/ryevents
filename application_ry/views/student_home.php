<h1>Welcome</h1>
<div id="list_of_resumes">
	<div id="upload_section">
		 <?php echo validation_errors('<p class="error">'); ?>
		<?php if($error_msg) { ?> <div class="error"> <?php echo "Bad file, please try again. <br/>Max size is 2MB; Must be PDF file"; ?></div> <?php } ?>
		<?php if($upload_msg) { ?> <div class="success"> <?php echo $upload_msg; ?></div> <?php } ?>
	<label>Upload a new resume</label>
		<?php echo form_open_multipart('student/upload');?>
		Resume Name: <input type="text" name="resume_name" id='resume_name'>
		<div>
		<input type="file" name="userfile" size="20" />
		</div>
		<br /><br />

		<input type="submit" value="Upload" />
		<?php echo form_close(); ?>
	</div>
	
	<div id="list_of_resumes">
		<table id="resumes" border="2">
			<th>
			Current Resumes on File
			</th>
			<?php 
			foreach($resumes as $resume) {
				print "<tr><td>";
				print "<a href=".site_url('student/display')."/".$resume->resume_file."/>".$resume->resume_name."</a>";
				print "<a href=".site_url('student/delete')."/".$resume->resume_file." style='float:right;'/><img src=".base_url()."assets/images/delete.png></a>";
				print "</td></tr>";
			}
			?>
		</table>
	</div>
</div>
