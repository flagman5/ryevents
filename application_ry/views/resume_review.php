<h1>Here is a list of resumes collected  </h1>
<div id="list_of_resumes">
<table id="resumes" border="2">
	<th>
	Collected Resumes
	</th>
	<?php 
	foreach($resumes as $resume) {
		print "<tr><td>";
		print "<a href=".site_url('recruiter/display')."/".$resume->resume_file."/>".$resume->name."</a>";
		for($i=0; $i< sizeof($checkRated);$i++) {
			if($checkRated[$i] == $resume->resume_file) {
				print "<img style='float:right;' src='".base_url()."assets/images/check.png'></a>";
			}
		}
		print "</td></tr>";
	}
	?>
</table>
</div>
<?php if($this->session->userdata('hr')) { ?>
<h3 style="margin-left:20px;"><a href="<?php print site_url('recruiter/display/stats/');?>">Click here to review statistics </a> </h3>
<?php } ?>