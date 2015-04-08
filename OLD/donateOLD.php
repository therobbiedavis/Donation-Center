<?php

require "config.php";
require "connect.php";

// Determining the URL of the page:
$url = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"]);

// Fetching the number and the sum of the donations:
list($number,$sum) = mysql_fetch_array(mysql_query("SELECT COUNT(*),SUM(amount) FROM donations"));

// Calculating how many percent of the goal were met:
$percent = round(min(100*($sum/$goal),100));

// Building a URL with Google's Chart API:
$chartURL = 'http://chart.apis.google.com/chart?chf=bg,s,f9faf7&amp;cht=p&amp;chd=t:'.$percent.',-'.(100-$percent).'&amp;chs=200x200&amp;chco=639600&amp;chp=1.57';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Donation Center</title>

<link rel="stylesheet" type="text/css" href="styles.css" />
<script type="text/javascript">
     <!--
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if ( charCode > 31 && (charCode < 46 || charCode > 57) || charCode === 47)
            return false;

         return true;
      }
      //-->
</script>
</head>

<body>

<div id="main">
    
    <div class="lightSection">    
        
        <!-- The PayPal Donation Button -->
        
      <form action="<?php echo $payPalURL?>" method="post" class="payPalForm">
        <div>
            <input type="hidden" name="cmd" value="_xclick" />
            <input type="hidden" name="item_name" value="Contribution to TSG Costs Feb 2013" />

            <!-- Your PayPal email: -->
            <input type="hidden" name="business" value="<?php echo $myPayPalEmail?>" />

            <!-- PayPal will send an IPN notification to this URL: -->
            <input type="hidden" name="notify_url" value="<?php echo $url.'/ipn.php'?>" /> 

            <!-- The return page to which the user is navigated after the donations is complete: -->
            <input type="hidden" name="return" value="<?php echo $url.'/thankyou.php'?>" /> 

            <!-- Signifies that the transaction data will be passed to the return page by POST -->
            <input type="hidden" name="rm" value="2" /> 


			<!-- 	General configuration variables for the paypal landing page. Consult 
            		http://www.paypal.com/IntegrationCenter/ic_std-variable-ref-donate.html for more info  -->
            		
            <input type="hidden" name="business" value="Donations@TheSpeedGamers.com">
            <input type="hidden" name="lc" value="US">
            <input type="hidden" name="button_subtype" value="services">
            <input type="hidden" name="no_note" value="1">
            <input type="hidden" name="no_shipping" value="1">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="cbt" value="Go Back To The Site" />



			<!-- The amount of the transaction: -->


<select name="amount" id="amount">
<option value="5.00">$5.00</option>
<option value="5.00">$10.00</option>
<option value="25.00">$25.00</option>
<option value="50.00">$50.00</option>
</select> <br/>or Enter Custom Amount<br/>
<input type="text" name="amount" onkeypress="return isNumberKey(event)" id="amount"><br/><br/>



            <input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHostedGuest" />

            <!-- You can change the image of the button: -->
            <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" />

          <img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
        </div>
        </form>

    </div>
    
    <!-- Setting the Google Chart API address as the background image of the div: -->
    
    <div class="chart" style="background:url('<?php echo $chartURL?>');">
    	Our Goal
    </div>
    
    <div class="donations">
    	<?php echo $percent?>% done
    </div>
    
    
    <div class="clear"></div>
    
    <div class="donors">
        <h3>The Donor List</h3>
        <h4>Folks Who Showed Their Support</h4>
        
        <div class="comments">
        
        <?php
			$comments = mysql_query("SELECT * FROM comments ORDER BY id DESC");
			
			// Building the Donor List:
			
			if(mysql_num_rows($comments))
			{
				while($row = mysql_fetch_assoc($comments))
				{
					?>
                    
                       	<div class="entry">
                            <p class="comment">
                            <?php 
								echo nl2br($row['message']); // Converting the newlines of the comment to <br /> tags
							?>
                            <span class="tip"></span>
                            </p>
                            
                            <div class="name">
                                <?php echo $row['name']?> <a class="url" href="<?php echo $row['url']?>"><?php echo $row['url']?></a>
                            </div>
                        </div>
                    
					<?php
				}
			}
		?>
        
            
        </div> <!-- Closing the comments div -->
        
    </div> <!-- Closing the donors div -->
    
</div> <!-- Closing the main div -->


</body>
</html>
