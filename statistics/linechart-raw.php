<?

	//include('../admin/variables.php');
    
	$link = new mysqli("localhost", "tsgadmin_beta", "luxenst", "tsgadmin_chipin_db");

	$eventid = mysqli_query($link, "SELECT event_id FROM dc_events WHERE current = 1");
	$row = mysqli_fetch_array($eventid);
	$event = $row["event_id"];

	$pull = mysqli_query($link, "SELECT * FROM stats WHERE event_id = '$event' ORDER BY hour DESC");
	$totals = array();
	while ($donations = mysqli_fetch_assoc($pull)) {
		$totals[] = $donations;
	}	
	
	mysqli_free_result($pull);
	mysqli_close($link);
ob_start();

?>
<span class="loading" style="text-align:center;"><img src="http://cdn1.thespeedgamers.com/pokemon-list/poke-images/ajax-loader.gif" /></span>
<html>

	<head>
		<link rel="stylesheet" href="http://donate.thespeedgamers.com/statistics/style.css?2" type="text/css" media="screen" />
<!-- LATEST -->

    <!--Load the AJAX API-->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
    
    <script type="text/javascript">
      function drawVisualization() {
  // Some raw data (not necessarily accurate)
  var data = google.visualization.arrayToDataTable([
        ['Hour', 'Hourly Donations', 'Total Donations'],
        <? foreach ($totals as $c) { ?>
          [<? echo $c['hour']; ?>, <? echo $c['don_diff']; ?>, <? echo $c['don_total']; ?>],
          <? } ?>
        ]);

  // Create and draw the visualization.
  var ac = new google.visualization.AreaChart(document.getElementById('chart_div1'));
  ac.draw(data, {
    isStacked: false,
    width: 950,
    height: 250,
    vAxis: {title: "Donations"},
    hAxis: {title: "Hours"},
    colors: ['#32851D','#e0440e'],
    backgroundColor: { fill:'transparent'}
  });
}

      google.setOnLoadCallback(drawVisualization);
    </script>
	</head>
	<body>

<!--Div that will hold the pie chart-->
	<span>Donation Statistics:</span>
    <div id="chart_div1" style="margin:-40px 0 0;">
    
    </div>
    <script type="text/javascript">
    $(window).load(function() {
    	$('.loading').remove();
    });
    </script>
	</body>
</html>

<?php
  $output = ob_get_contents();
  ob_end_clean();
  echo $output;

  //unlink('/home/tsgweb/donate.thespeedgamers.com/statistics/linechart.html');
  //unlink('/home/tsgweb/thespeedgamers.com/streaming/external/linechart.html');
  $file = fopen('/home/tsgadmin/public_html/donate.thespeedgamers.com/statistics/linechart.html', 'w');
  //$file2 = fopen('/home/tsgweb/thespeedgamers.com/streaming/external/linechart.html', 'w');
  fwrite($file, $output);
  //fwrite($file2, $output);
  fclose($file);
  //fclose($file2);
?>