<h1>Here is the current review stastics </h1>
<div id="list_of_resumes">
<table id="resumes" border="2">
	<th>
	Candidate
	</th>
	<th>Score</th>
	<?php 
	foreach($resumes as $resume) {
		print "<tr><td>";
		print $resume->name;
		print "</td><td>".$resume->total."</td></tr>";
	}
	?>
</table>
</div>