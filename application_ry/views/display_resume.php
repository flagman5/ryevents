<?php if($student == 0) { ?> <h1>Evaluate Resume</h1> <?php } else { ?> <h1>Resume</h1> <?php }?>

<div id="resume" style="height: 800px">
<?php if($student == 0) { ?>
	<div id="comments">
	Comments from career fair:
	<?php print $comments ?>
	</div>
<?php } ?>
<object data="<?php print base_url("resumes/".$file); ?>" type="application/pdf" width="100%" height="100%">
  <p>Your web browser doesn't have a PDF plugin. Please install it</p>
</object>


</div>
<?php if($rated != 1 AND $student == 0) { ?>
<div id="evaluation" style="margin-top:50px;">
	<h3>Rate this candidate:</h3>
	 <?php echo form_open("recruiter/evaluate"); ?>
	 <input type="radio" name="rating" value="3" /><label for="positive">Definitely Interview</label>
	 
	 <input type="radio" name="rating" value="2" /><label for="neutral">Maybe Interview</label>
	 
	 <input type="radio" name="rating" value="1" /><label for="negative">Do not Interview</label>
	 <br/>
	 <input type="submit" class="" value="Rate" />
	 <?php 
		$data = array(
					  'evaluator'  => $user_id,
					  'resume_file' => $file,
					);

		echo form_hidden($data);
		?>
	 <?php echo form_close(); ?>
</div>
<?php } ?>