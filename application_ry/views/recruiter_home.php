<h1>Welcome </h1>
<div id="list_of_events">
<h2>Please select from the list of recruiting events to view collected resumes</h2>
<table id="events" border="2">
	<th>
	Recruiting Events
	</th>
	<?php 
	foreach($events as $event) {
		print "<tr><td>";
		print "<a href=".site_url('recruiter/getResumes')."/".$event->event_id."/>".rawurldecode($event->event)."</a>";
		print "</td></tr>";
	}
	?>
</table>
</div>