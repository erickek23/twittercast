<?php

include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';

#Consumer key token
$consumer_key = 'BOliPLC90gWGLGKiE4HdCMhI1';

#Consumer secret token
$consumer_secret = ' P8Vmem2XEJRAE1F6W7rriZKH7JzzT1P6TLTygsYKusnL4t4ezO';

#Access Token
$token = '3313525672-IyEaxZiALKTuzZmaQUtrkdIGFpZIr6LgoFSjWZT';

#Access Token Secret 
$secret= 'NIBqxRDMZvan3xqay1SXSoV7kyQE3dw1CwESBxABvXMbj';

$refresh = "60";  // Page refresh time in seconds. Put 0 for no refresh. (only used if updating via browser)
$timeout = "5"; // Number of seconds before connecton times out.

$ip[1] = "50.22.218.73"; // 
$port[1] = "11286"; // 
//END CONFIGURATION
$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth = new EpiTwitter($consumer_key, $consumer_secret);

$servers = count($ip);
$i = "1";
while($i<=$servers)
	{
	$fp = @fsockopen($ip[$i],$port[$i],$errno,$errstr,$timeout);
	if (!$fp) 
		{ 
		$listeners[$i] = "0";
		$msg[$i] = "<span class=\"red\">ERROR [Connection refused / Server down]</span>";
		$error[$i] = "1";
		} 
	else
		{ 
		fputs($fp, "GET /7.html HTTP/1.0\r\nUser-Agent: Mozilla\r\n\r\n");
		while (!feof($fp)) 
			{
			$info = fgets($fp);
			}
		$info = str_replace('<HTML><meta http-equiv="Pragma" content="no-cache"></head><body>', "", $info);
		$info = str_replace('</body></html>', "", $info);
		$stats = explode(',', $info);
		if (empty($stats[1]) )
			{
			$listeners[$i] = "0";
			$msg[$i] = "<span class=\"red\">ERROR [There is no source connected]</span>";
			$error[$i] = "1";
			}
		else
			{
			if ($stats[1] == "1")
				{
				$song[$i] = $stats[6];
				$listeners[$i] = $stats[4];
				$max[$i] =  $stats[3];
				if ($stats[0] == $max[$i]) 
					{ 
					$msg[$i] .= "<span class=\"red\">";
					}
				if ($stats[0] == $max[$i]) 
					{ 
					$msg[$i] .= "</span>";
					}
				}
			else
				{
				$listeners[$i] = "0";
				$msg[$i] = "    <span class=\"red\">ERROR [Cannot get info from server]</span>";
				$error[$i] = "1";
				}
			}
		}
	$i++;
	}

print "$song[1]"; // so you can see what the output looks like

$twitterObj->post('/statuses/update.json', array('status' => $song[1])); // Tweet that shit. 

?>
