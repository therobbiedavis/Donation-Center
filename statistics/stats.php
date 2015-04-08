<?php
$id = $_GET['eid'];
?>
<html>
	<head>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	</head>
	
	<body style="margin:0;padding:0;">
								<script type="text/javascript">
									function loadStats(){   
										var url = "http://donate.thespeedgamers.com/statistics/loadStats.php?eid=<?=$id?>";

										$('#Stats').attr('src', url);
									}

										window.setInterval("loadStats()",60000 );
								
										document.write("<iframe scrolling='no' style='border:none;margin:0;padding:0;' frameBorder='0' width='100%' height='100%' style='' id='Stats' src='http://donate.thespeedgamers.com/statistics/loadStats.php?eid=<?=$id?>'></iframe>");
								
										$(window).load(function() {
											$('.loading').remove();
											});
								</script>
								
	</body>