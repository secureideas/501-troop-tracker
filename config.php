<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Unlimited time to execute
ini_set('max_execution_time', '0');
set_time_limit(0);

// PHP Mail namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHP Mail
require 'script/lib/phpmail/src/Exception.php';
require 'script/lib/phpmail/src/PHPMailer.php';
require 'script/lib/phpmail/src/SMTP.php';

// Twitter namespace
use DG\Twitter\Twitter;

// Twitter
require_once 'script/lib/twitter/twitter.class.php';

// Calendar
require 'script/lib/php-calendar/src/phpCalendar/Calendar.php';

// Calendar Namespace
use benhall14\phpCalendar\Calendar;

// Start Calendar
$calendar = new Calendar();

// Include credential file
require 'cred.php';

// Start session
session_start();

// Connect to server
$conn = new mysqli(dbServer, dbUser, dbPassword, dbName);
 
// Check connection to server
if ($conn->connect_error)
{
	trigger_error('Database connection failed: ' . $conn->connect_error, E_USER_ERROR);
}

// displaySquadLinks: Returns links for each garrison for troop tracker
function displaySquadLinks($squadLink)
{
	global $squadArray;
	
	// Return var
	$returnVar = '';
	
	// Set count
	$squadID = 1;
	
	// Set up garrison link
	$returnVar .= addSquadLink(0, $squadLink, "All");
	
	// Loop through squads
	foreach($squadArray as $squad => $squad_value)
	{
		// Add to return var
		$returnVar .= 
		' | ' . addSquadLink($squadID, $squadLink, $squad);
		
		// Increment
		$squadID++;
	}
	
	return $returnVar;
}

// showSquadButtons: Returns garrison and squad images on front page
function showSquadButtons()
{
	global $squadArray;
	
	// Return var
	$returnVar = '';
	
	// Set count
	$squadID = 1;
	
	// Set up garrison link
	$returnVar .= '<a href="index.php"><img src="images/'.garrisonImage.'" alt="'.garrison.' Troops" '.isSquadActive(0).' /></a>';
	
	// Loop through squads
	foreach($squadArray as $squad => $squad_value)
	{
		// Add to return var
		$returnVar .= '
		<a href="index.php?squad='.$squadID.'"><img src="images/'.$squad_value.'" alt="'.$squad.' Troops" '.isSquadActive($squadID).' /></a>';
		
		// Increment
		$squadID++;
	}
	
	return $returnVar;
}

// squadSelectList: Returns options for select tag of squads
function squadSelectList($clubs = true, $insideElement = "", $eid = 0, $squadP = 0, $rebelOnly = false)
{
	global $squadArray, $clubArray;
	
	// Set count
	$squadID = 1;
	
	// Return var
	$returnVar = '';
	
	// Loop through squads
	foreach($squadArray as $squad => $squad_value)
	{
		// If insideElement is nothing
		if($insideElement == "")
		{
			// Add to return var
			$returnVar .= '
			<option value="'.$squadID.'">'.$squad.'</option>';
		}
		// If insideElement is copy
		else if($insideElement == "copy")
		{
			// Add to return var
			$returnVar .= '
			<option value="'.$squadID.'" '.copyEventSelect($eid, $squadP, $squadID).'>'.$squad.'</option>';
		}
		// If insideElement is select
		else if($insideElement == "select")
		{
			// Add to return var
			$returnVar .= '
			<option value="'.$squadID.'" '.echoSelect($squadID, cleanInput($_POST['squad'])).'>'.$squad.'</option>';
		}
		
		// Increment
		$squadID++;
	}
	
	// If clubs set to true, show clubs
	if($clubs)
	{
		// Loop through clubs
		foreach($clubArray as $squad => $squad_value)
		{
			// If insideElement is nothing
			if($insideElement == "")
			{
				// Add to return var
				$returnVar .= '
				<option value="'.$squadID.'">'.$squad.'</option>';
			}
			// If insideElement is copy
			else if($insideElement == "copy")
			{
				// Add to return var
				$returnVar .= '
				<option value="'.$squadID.'" '.copyEventSelect($eid, $squadP, $squadID).'>'.$squad.'</option>';
			}
			// If insideElement is select
			else if($insideElement == "select")
			{
				// Add to return var
				$returnVar .= '
				<option value="'.$squadID.'" '.echoSelect($squadID, cleanInput($_POST['squad'])).'>'.$squad.'</option>';
			}

			// Stop at Rebels
			if($rebelOnly)
			{
				break;
			}
			
			// Increment
			$squadID++;
		}
	}
	
	return $returnVar;
}

// addSquadLink: Returns a href link for a squad based on selection
function addSquadLink($squad, $match, $name)
{
	// Set up link
	$link = "";
	
	// If squad's don't match show link
	if($squad != $match)
	{
		$link = '<a href="index.php?action=trooptracker&squad='.$squad.'">'.$name.'</a>';
	}
	else
	{
		$link = $name;
	}
	
	// Return
	return $link;
}

// email_check: Checks if e-mail is verified
function email_check()
{
	global $conn;
	
	$query = "SELECT * FROM troopers WHERE id='".$_SESSION['id']."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			if($db->email_verify == 0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
	}
}

// showBBcodes: Converts text to BB Code
function showBBcodes($text)
{
	$text = strip_tags($text);

	// BBcode array
	$find = array(
		'~\[b\](.*?)\[/b\]~s',
		'~\[i\](.*?)\[/i\]~s',
		'~\[u\](.*?)\[/u\]~s',
		'~\[quote\](.*?)\[/quote\]~s',
		'~\[size=(.*?)\](.*?)\[/size\]~s',
		'~\[color=(.*?)\](.*?)\[/color\]~s',
		'~\[url\]((?:ftp|https?)://.*?)\[/url\]~s',
		'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s'
	);

	// HTML tags to replace BBcode
	$replace = array(
		'<b>$1</b>',
		'<i>$1</i>',
		'<span style="text-decoration:underline;">$1</span>',
		'<pre>$1</'.'pre>',
		'<span style="font-size:$1px;">$2</span>',
		'<span style="color:$1;">$2</span>',
		'<a href="$1">$1</a>',
		'<img src="$1" alt="" />'
	);

	// Replacing the BBcodes with corresponding HTML tags
	return preg_replace($find,$replace,$text);
}

// countDonations: Count the number of donations between the two dates - can specify a user
function countDonations($trooperid = "*", $dateStart = "1900-12-1", $dateEnd = "9999-12-1")
{
	global $conn;
	
	// Query
	if($trooperid != "*")
	{
		// Trooper ID specified
		$getNumOfDonators = $conn->query("SELECT * FROM donations WHERE trooperid = ".$trooperid." AND datetime > '".$dateStart."' AND datetime < '".$dateEnd."'");
	}
	else
	{
		// Trooper ID not specified - wild card
		$getNumOfDonators = $conn->query("SELECT * FROM donations WHERE datetime > '".$dateStart."' AND datetime < '".$dateEnd."'");
	}
	
	// Return rows
	return $getNumOfDonators->num_rows;
}

// drawSupportBadge: A function that draws a support badge if the user is a supporter
function drawSupportBadge($id)
{
	global $conn;
	
	// Set up value
	$value = "";
	
	// Get data
	$query = "SELECT supporter FROM troopers WHERE id = '".$id."' AND supporter = '1'";
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			// Set
			$value = '<img src="images/FLGHeart_small.png" width="32px" height="32px" /><br />';
		}
	}
	
	// Return
	return $value;
}

// drawSupportGraph: A function that draws a visual graph for troopers to see what we need to support the garrison
function drawSupportGraph()
{
	global $conn;
	
	// Set return value
	$return = "";
	
	// Check if user is logged in and don't show for command staff
	if(loggedIn())
	{
		// Count number of troopers supporting
		$getNumOfSupport = $conn->query("SELECT SUM(amount) FROM donations WHERE datetime > date_add(date_add(LAST_DAY(NOW()),interval 1 DAY),interval -1 MONTH)");
		$getSupportNum = $getNumOfSupport->fetch_row();
		
		// Count times contributed
		$didSupportCount = $conn->query("SELECT trooperid FROM donations WHERE datetime > date_add(date_add(LAST_DAY(NOW()),interval 1 DAY),interval -1 MONTH) AND trooperid = '".$_SESSION['id']."'");
		
		// Get goal from site settings
		$getGoal = $conn->query("SELECT supportgoal FROM settings");
		$getGoal_value = $getGoal->fetch_row();
		
		// Set goal
		$goal = $getGoal_value[0];
		
		// Hide for command staff
		if(isset($_GET['action']) && $_GET['action'] == "commandstaff")
		{
			// Set goal to 0 to hide
			$goal = 0;
		}
		
		// If goal is 0, there is no goal and do not show
		if($goal != 0)
		{
			// Find percent
			$percent = floor(($getSupportNum[0]/$goal) * 100);
			
			// Don't allow over 100
			if($percent > 100)
			{
				$percent = 100;
			}
			
			$return .= '
			<style>
				.bargraph
				{
					background-color: rgb(192, 192, 192);
					width: 80%;
					border-radius: 15px;
					margin: auto;
				}
			  
				.progress
				{
					background-color: rgb(116, 194, 92);
					color: white;
					padding: 1%;
					text-align: right;
					font-size: 20px;
					border-radius: 15px;
					width: '.$percent.'%;
				}
			</style>
			
			<h2 class="tm-section-header">'.date("F", strtotime('m')).' - Donation Goal</h2>
			
			<p style="text-align: center;">
				<div class="bargraph">
					<div class="progress">'.$percent.'%</div>
				</div>
			</p>';
			
			// Don't show link on donation page
			if(isset($_GET['action']) && $_GET['action'] == "donation")
			{
				// Blank
			}
			else
			{
				// Don't show link if they are a supporter
				if($didSupportCount->num_rows == 0)
				{
					// If not 100%, show learn more
					if($percent != 100)
					{
						$return .= '
						<p style="text-align: center;">
							<a href="index.php?action=donation">The '.garrison.' needs your support! Click here to learn more.</a>
						</p>';
					}
					else
					{
						// Reached 100%
						$return .= '
						<p style="text-align: center;">
							<a href="index.php?action=donation">Thank you for helping the garrison reach it\'s goal! Click here to help contribute.</a>
						</p>';
					}
				}
				else
				{
					// Did support
					$return .= '
					<p style="text-align: center;">
						<a href="index.php?action=donation">Thank you for your contribution! Click here to help contribute further.</a>
					</p>';
				}
			}
			
			$return .= '<hr />';
		}
	}
	
	return $return;
}

// isSupporter: A function to determine if a trooper is a supporter
function isSupporter($id)
{
	global $conn;
	
	// Set up value
	$value = 0;
	
	// Get data
	$query = "SELECT supporter FROM troopers WHERE id = '".$id."'";
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			// Set
			$value = $db->supporter;
		}
	}
	
	// Return
	return $value;
}

// getRebelLegionUser: A function that returns a troopers Rebel Legion forum username
function getRebelLegionUser($id)
{
	global $conn;
	
	// Set up value
	$forumName = "";
	
	// Get data
	$query = "SELECT rebelforum FROM troopers WHERE id = '".$id."'";
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			// Set
			$forumName = $db->rebelforum;
		}
	}
	
	// Return
	return $forumName;
}

// getRebelInfo: A function which returns an array of info about trooper - Rebel Legion
function getRebelInfo($forumid)
{
	global $conn;
	
	// Setup array
	$array = [];
	$array['id'] = '';
	$array['name'] = '';
	
	// Get data
	$query = "SELECT * FROM rebel_troopers WHERE rebelforum = '".$forumid."'";
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			$array['id'] = $db->rebelid;
			$array['name'] = $db->name;
		}
	}
	
	// Return
	return $array;
}

// getMandoLegionUser: A function that returns a troopers Mando Mercs CAT #
function getMandoLegionUser($id)
{
	global $conn;
	
	// Set up value
	$mandoid = 0;
	
	// Get data
	$query = "SELECT mandoid FROM troopers WHERE id = '".$id."'";
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			// Set
			$mandoid = $db->mandoid;
		}
	}
	
	// Return
	return $mandoid;
}

// getMandoInfo: A function which returns an array of info about trooper - Mando Mercs
function getMandoInfo($mandoid)
{
	global $conn;
	
	// Setup array
	$array = [];
	$array['id'] = '';
	$array['name'] = '';
	
	// Get data
	$query = "SELECT * FROM mando_troopers WHERE mandoid = '".$mandoid."'";
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			$array['id'] = $db->mandoid;
			$array['name'] = $db->name;
			$array['costume'] = $db->name;
		}
	}
	
	// Return
	return $array;
}

// getSGUser: A function that returns a troopers SG #
function getSGUser($id)
{
	global $conn;
	
	// Set up value
	$sgid = 0;
	
	// Get data
	$query = "SELECT sgid FROM troopers WHERE id = '".$id."'";
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			// Set
			$sgid = $db->sgid;
		}
	}
	
	// Return
	return $sgid;
}

// getSGINfo: A function which returns an array of info about trooper - Saber Guild
function getSGINfo($sgid)
{
	global $conn;
	
	// Setup array
	$array = [];
	$array['sgid'] = '';
	$array['name'] = '';
	$array['image'] = '';
	$array['link'] = '';
	
	// Get data
	$query = "SELECT * FROM sg_troopers WHERE sgid = '".$sgid."'";
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			$array['sgid'] = $db->sgid;
			$array['name'] = $db->name;
			$array['image'] = $db->image;
			$array['link'] = $db->link;
		}
	}
	
	// Return
	return $array;
}

// get501Info: A function which returns an array of info about trooper - 501st
function get501Info($id, $squad)
{
	global $conn, $squadArray;
	
	// Setup array
	$array = [];
	$array['link'] = '';
	
	// Check if 501st member
	if($squad <= count($squadArray))
	{
		// Get data
		$query = "SELECT * FROM 501st_troopers WHERE legionid = '".$id."'";
		
		// Run query...
		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				$array['link'] = $db->link;
			}
		}
	}
	
	// Return
	return $array;
}

// getMyRebelCostumes: A function which returns a string of costumes assigned to user in synced database - Rebel Legion
function getMyRebelCostumes($id)
{
	global $conn;
	
	// Setup string
	$costume = "";
	
	// Get data
	$query = "SELECT costumename FROM rebel_costumes WHERE rebelid = '".$id."'";
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			$costume .= ", '" . $db->costumename . "'";
		}
	}
	
	// Return
	return $costume;
}

// getMyCostumes: A function which returns a string of costumes assigned to user in synced database
function getMyCostumes($id, $squad)
{
	global $conn, $squadArray;
	
	// Setup string
	$costume = "";
	
	// Check if 501st member
	if($squad <= count($squadArray))
	{
		// Get data
		$query = "SELECT costumename FROM 501st_costumes WHERE legionid = '".$id."'";
		
		// Run query...
		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				$costume .= ", '" . $db->costumename . "'";
			}
		}
	}
	
	// Return
	return $costume;
}

// showRebelCostumes: A function which displays all the users costumes in synced database - Rebel Legion
function showRebelCostumes($id)
{
	global $conn;
	
	// Get data
	$query = "SELECT * FROM rebel_costumes WHERE rebelid = '".$id."'";
	
	// Set up count
	$i = 0;
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			echo '
			<div style="text-align: center;">
				<h3>'.$db->costumename.'<h3>
				<p>
					<img src="'.$db->costumeimage.'" />
				</p>
			</div>';
			
			// Increment
			$i++;
		}
	}
	
	// If no results
	if($i == 0)
	{
		echo '
		<p style="text-align: center;">
			No Rebel Legion costumes to display!
		</p>';
	}
}

// showMandoCostumes: A function which displays all the users costumes in synced database - Mando Mercs
function showMandoCostumes($id)
{
	global $conn;
	
	// Get data
	$query = "SELECT * FROM mando_costumes WHERE mandoid = '".$id."'";
	
	// Set up count
	$i = 0;
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			echo '
			<div style="text-align: center;">
				<p>
					<img src="'.$db->costumeurl.'" />
				</p>
			</div>';
			
			// Increment
			$i++;
		}
	}
	
	// If no results
	if($i == 0)
	{
		echo '
		<p style="text-align: center;">
			No Mando Mercs costumes to display!
		</p>';
	}
}

// showSGCostumes: A function which displays all the users costumes in synced database - Saber Guild
function showSGCostumes($id)
{
	global $conn;
	
	// Get data
	$query = "SELECT * FROM sg_troopers WHERE sgid = '".$id."'";
	
	// Set up count
	$i = 0;
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			echo '
			<div style="text-align: center;">
					<h3>
						<a href="'.$db->link.'" target="_blank">'.$db->name.'</a>
					</h3>
					
					<img src="'.$db->image.'" />
				</p>
			</div>';
			
			// Increment
			$i++;
		}
	}
	
	// If no results
	if($i == 0)
	{
		echo '
		<p style="text-align: center;">
			No Saber Guild costumes to display!
		</p>';
	}
}

// showDroids: A function which displays all the users droids in synced database - Droid Builders
function showDroids($forum)
{
	global $conn;
	
	// Get data
	$query = "SELECT * FROM droid_troopers WHERE forum_id = '".$forum."'";
	
	// Set up count
	$i = 0;
	
	// Run query...
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			echo '
			<div style="text-align: center;">
					<h3>
						'.$db->droidname.'
					</h3>
					
					<img src="'.$db->imageurl.'" />
				</p>
			</div>';
			
			// Increment
			$i++;
		}
	}
	
	// If no results
	if($i == 0)
	{
		echo '
		<p style="text-align: center;">
			No Droid Builder droids to display!
		</p>';
	}
}

// showCostumes: A function which displays all the users costumes in synced database
function showCostumes($id, $squad)
{
	global $conn, $squadArray;
	
	// Get data
	$query = "SELECT * FROM 501st_costumes WHERE legionid = '".$id."'";
	
	// Set up count
	$i = 0;
	
	// Check if 501st member
	if($squad <= count($squadArray))
	{
		// Run query...
		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				echo '
				<div style="text-align: center;">
					<h3>'.$db->costumename.'<h3>
					<p>';
						// Set up image count
						$iC = 0;
						
						// Check if image is available
						if(@getimagesize($db->photo)[0])
						{
							echo '
							<img src="'.$db->photo.'" />';
							
							// Increment
							$iC++;
						}
						
						// Check if image is available
						if(@getimagesize($db->bucketoff)[0])
						{
							echo '
							<img src="'.$db->bucketoff.'" />';
							
							// Increment
							$iC++;
						}
						
						// If no image available
						if($iC == 0)
						{
							echo '
							No images available for costume.';
						}
					echo '
					</p>
				</div>';
				
				// Increment
				$i++;
			}
		}
	}
	
	// If no results
	if($i == 0)
	{
		echo '
		<p style="text-align: center;">
			No 501st Legion costumes to display!
		</p>';
	}
}

// postTweet: Posts a tweet to Twitter (FLGUPDATES)
function postTweet($message)
{
	// Credentials
	$twitter = new Twitter(consumerKey, consumerSecret, accessToken, accessTokenSecret);

	try
	{
		// Send tweet
		$tweet = $twitter->send($message);
	}
	catch (DG\Twitter\TwitterException $e)
	{
		// Do nothing
	}
}


// squadToDiscord: Converts squad ID to Discord
function squadToDiscord($squad)
{
	if($squad == 1)
	{
		return '<@&914344158678900766>';
	}
	else if($squad == 2)
	{
		return '<@&914343663474200597>';
	}
	else if($squad == 3)
	{
		return '<@&914344264253718568>';
	}
	else if($squad == 4)
	{
		return '<@&914344334776737822>';
	}
	else if($squad == 5)
	{
		return '<@&914344438472527912>';
	}
	else
	{
		return 'Florida Garrison';
	}
}

// sendEventNotifty: Send's a notification to the event channel
function sendEventNotify($id, $name, $description, $squad)
{
	$webhookurl = discordWeb1;

	//=======================================================================================================
	// Compose message. You can use Markdown
	// Message Formatting -- https://discordapp.com/developers/docs/reference#message-formatting
	//========================================================================================================

	$timestamp = date("c", strtotime("now"));

	$json_data = json_encode([
	    // Message
	    "content" => "".$name." has been added in ".squadToDiscord($squad).".",
	    
	    // Username
	    "username" => "Event Bot",

	    // Text-to-speech
	    "tts" => false,

	    // Embeds Array
	    "embeds" => [
	        [
	            // Embed Title
	            "title" => $name,

	            // Embed Type
	            "type" => "rich",

	            // Embed Description
	            "description" => $description,

	            // URL of title link
	            "url" => "https://www.fl501st.com/troop-tracker/index.php?event=" . $id,

	            // Timestamp of embed must be formatted as ISO8601
	            "timestamp" => $timestamp,

	            // Embed left border color in HEX
	            "color" => hexdec("3366ff")
	        ]
	    ]

	], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


	$ch = curl_init( $webhookurl );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec( $ch );
	// If you need to debug, or find out why you can't send message uncomment line below, and execute script.
	// echo $response;
	curl_close( $ch );
}

// getSquad: Gets squad by location
function getSquad($address)
{
	// Squad code
	$squad = 0;

	// Request
	$geocode = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false&key=".googleKey."");
    $output = json_decode($geocode);

    // Get Data
    if(isset($output->results[0]->address_components[4]->long_name))
    {
    	$county = $output->results[0]->address_components[4]->long_name;

	    // Parjai
	    if($county == "Escambia County" || $county == "Santa Rosa" || $county == "Okaloosa County" || $county == "Walton County" || $county == "Holmes County" || $county == "Washington County" || $county == "Jackson County" || $county == "Calhoun County" || $county == "Bay County" || $county == "Gulf County" || $county == "Gadsen County" || $county == "Liberty County" || $county == "Leon County" || $county == "Wakulla County" || $county == "Franklin County")
	    {
	    	$squad = 3;
	    }

	    // Squad 7
	    else if($county == "Jefferson County" || $county == "Madison County" || $county == "Taylor County" || $county == "Hamilton County" || $county == "Suwannee County" || $county == "Lafayette County" || $county == "Dixie County" || $county == "Columbia County" || $county == "Gilchrist County" || $county == "Baker County" || $county == "Union County" || $county == "Bradford County" || $county == "Alachua County" || $county == "Levy County" || $county == "Nassau County" || $county == "Duval County" || $county == "Clay County" || $county == "St. Johns County" || $county == "Putnam County" || $county == "Flagler County" || $county == "Marion County")
	    {
	    	$squad = 4;
	    }

	    // Makaze
	    else if($county == "Volusia County" || $county == "Citrus County" || $county == "Lake County" || $county == "Seminole County" || $county == "Orange County" || $county == "Brevard County" || $county == "Osceola County" || $county == "Highlands County" || $county == "Okeechobee County" || $county == "Indian River County")
	    {
	    	$squad = 2;
	    }

	    // Tampa Bay
	    else if($county == "Charlotte County" || $county == "Lee County" || $county == "Desolo County" || $county == "Hardee County" || $county == "Sarasota County" || $county == "Manatee County" || $county == "Hillsborough County" || $county == "Polk County" || $county == "Pasco County" || $county == "Pinellas County" || $county == "Sumter County" || $county == "Hernando County")
	    {
	    	$squad = 5;
	    }

	    // Everglades
	    else if($county == "Hendry County" || $county == "Palm Beach County" || $county == "Broward County" || $county == "Collier County" || $county == "Monroe County" || $county == "Dade County" || $county == "Glades County" || $county == "Martin County" || $county == "St. Lucie County")
	    {
	    	$squad = 1;
	    }
	    else
	    {
	    	$squad = 2;
	    }
	}

    return $squad;
}

// getSquadName: Returns the squad name / club name
function getSquadName($value)
{
	global $squadArray, $clubArray;
	
	// Set return value
	$returnValue = "";
	
	// Set squad ID
	$squadID = 1;
	
	// Loop through squads
	foreach($squadArray as $squad => $squad_value)
	{
		// Check if squad ID matches value
		if($squadID == $value)
		{
			// Set
			$returnValue = $squad;
		}
		
		// Increment
		$squadID++;
	}
	
	// Loop through clubs
	foreach($clubArray as $club => $club_value)
	{
		// Check if squad ID matches value
		if($squadID == $value)
		{
			// Set
			$returnValue = $club;
		}
		
		// Increment
		$squadID++;
	}

	return $returnValue;
}

function isImportant($value, $text)
{
	if($value == 1)
	{
		return "<div style='color:red;'>".$text."</div>";
	}
	else
	{
		return $text;
	}
}

function loggedIn()
{
	if(isset($_SESSION['id']))
	{
		return true;
	}
	return false;
}

// sendNotification: Sends a notification to the log
function sendNotification($message, $trooperid)
{
	global $conn;
	
	$conn->query("INSERT INTO notifications (message, trooperid) VALUES ('".$message."', '".$trooperid."')");
}

// troopCheck: Checks the troop counts of all clubs
function troopCheck($id)
{
	global $conn;
	
	// Notify how many troops did a trooper attend - 501st
	$trooperCount_get = $conn->query("SELECT COUNT(*) FROM event_sign_up WHERE trooperid = '".$id."' AND status = '3' AND ('0' = (SELECT costumes.club FROM costumes WHERE id = event_sign_up.attended_costume) OR '5' = (SELECT costumes.club FROM costumes WHERE id = event_sign_up.attended_costume) OR EXISTS(SELECT events.id, events.oldid FROM events WHERE events.oldid != 0 AND events.id = event_sign_up.troopid))") or die($conn->error);
	$count = $trooperCount_get->fetch_row();
	
	// 501st
	checkTroopCounts($count[0], "501ST: " . getName($id) . " now has [COUNT] troop(s)", $id, "501ST");
	
	// Notify how many troops did a trooper attend - Rebel Legion
	$trooperCount_get = $conn->query("SELECT COUNT(*) FROM event_sign_up WHERE trooperid = '".$id."' AND status = '3' AND ('1' = (SELECT costumes.club FROM costumes WHERE id = event_sign_up.attended_costume) OR '5' = (SELECT costumes.club FROM costumes WHERE id = event_sign_up.attended_costume))") or die($conn->error);
	$count = $trooperCount_get->fetch_row();
	
	// Rebel Legion
	checkTroopCounts($count[0], "REBEL LEGION: " . getName($id) . " now has [COUNT] troop(s)", $id, "REBEL LEGION");
	
	// Notify how many troops did a trooper attend - Mando Mercs
	$trooperCount_get = $conn->query("SELECT COUNT(*) FROM event_sign_up WHERE trooperid = '".$id."' AND status = '3' AND ('2' = (SELECT costumes.club FROM costumes WHERE id = event_sign_up.attended_costume))") or die($conn->error);
	$count = $trooperCount_get->fetch_row();
	
	// Mando Mercs
	checkTroopCounts($count[0], "MANDO MERCS: " . getName($id) . " now has [COUNT] troop(s)", $id, "MANDO MERCS");
	
	// Notify how many troops did a trooper attend - Droid Builders
	$trooperCount_get = $conn->query("SELECT COUNT(*) FROM event_sign_up WHERE trooperid = '".$id."' AND status = '3' AND ('3' = (SELECT costumes.club FROM costumes WHERE id = event_sign_up.attended_costume))") or die($conn->error);
	$count = $trooperCount_get->fetch_row();
	
	// Droid Builders
	checkTroopCounts($count[0], "DROID BUILDERS: " . getName($id) . " now has [COUNT] troop(s)", $id, "DROID BUILDERS");
	
	// Notify how many troops did a trooper attend - Other
	$trooperCount_get = $conn->query("SELECT COUNT(*) FROM event_sign_up WHERE trooperid = '".$id."' AND status = '3' AND ('4' = (SELECT costumes.club FROM costumes WHERE id = event_sign_up.attended_costume))") or die($conn->error);
	$count = $trooperCount_get->fetch_row();
	
	// Other
	checkTroopCounts($count[0], "OTHER: " . getName($id) . " now has [COUNT] troop(s)", $id, "OTHER");
}

// checkTroopCounts: Checks the troop counts, and puts the information into notifications
function checkTroopCounts($count, $message, $trooperid, $club)
{
	global $conn;
	
	// Counts to check
	$counts = [1, 10, 25, 50, 75, 100, 150, 200, 250, 300, 400, 500, 501];
	
	// Search notifications for previous notifications, so we don't duplicate - check message for club name
	$query = "SELECT * FROM notifications WHERE trooperid = '".$trooperid."' AND message LIKE '%".$club."%'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			foreach($counts as $value)
			{
				if(strpos($db->message, "now has " . $value) !== false)
				{
					// Find in array
					$pos = array_search($value, $counts);
					
					// Remove from array
					unset($counts[$pos]);
				}
			}
		}
	}
	
	// Loop through remaining counts to check
	foreach($counts as $value)
	{
		if($count >= $value)
		{
			// Replace [COUNT] with actual count
			$tempMessage = $message;
			$tempMessage = str_replace("[COUNT]", $value, $tempMessage);
			
			$conn->query("INSERT INTO notifications (message, trooperid) VALUES ('".cleanInput($tempMessage)."', '".cleanInput($trooperid)."')");
		}
	}
}

// myEmail: gets users email
function myEmail()
{
	global $conn;
	
	$query = "SELECT email FROM troopers WHERE id='".$_SESSION['id']."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			return $db->email;
		}
	}
}

// myTheme: gets users theme
function myTheme()
{
	global $conn;
	
	$query = "SELECT theme FROM troopers WHERE id='".$_SESSION['id']."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			return $db->theme;
		}
	}
}

// getEventTitle: gets event title
function getEventTitle($id, $link = false)
{
	global $conn;
	
	$query = "SELECT * FROM events WHERE id='".$id."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			if($link)
			{
				return '<a href=\'index.php?event='. $db->id .'\'>' . $db->name . '</a>';
			}
			else
			{
				return $db->name;
			}
		}
	}
}

// loginWithTKID: Converts TK number into squad
function loginWithTKID($tkid)
{
	global $clubArray, $squadArray, $conn;
	
	// Set club count
	$clubCount = 0;
	
	// Check if in club
	$inClub = false;
	
	// Set squad return
	$squad = 0;
	
	// Loop through squads
	foreach($clubArray as $club => $club_value)
	{
		// Get first letter of club
		$firstLetter = strtoupper(substr($club, 0, 1));
		
		// Check if ID starts with a club
		if(substr($tkid, 0, 1) === $firstLetter)
		{
			// Set club
			$squad = count($squadArray) + ($clubCount) + 1;
			
			// Set club check
			$inClub = true;
		}
		
		// Increment
		$clubCount++;
	}
	
	// If not in club, set default
	if(!$inClub)
	{
		if(substr($tkid, 0, 2) === 'TK')
		{
			// Remove TK
			$tkid = substr($tkid, 2);
		}
		
		// Get squad from database
		$getSquad = $conn->query("SELECT squad FROM troopers WHERE tkid = '".$tkid."'");
		$getSquad_value = $getSquad->fetch_row();
		
		// To prevent warnings, make sure value is set
		if(isset($getSquad_value[0]))
		{
			// Set squad
			$squad = $getSquad_value[0];
		}
	}
	
	// Return
	return $squad;
}

// removeLetters: Removes letters from string
function removeLetters($string)
{
	return preg_replace('/[^0-9,.]+/', '', $string);
}

// readTKNumber: Converts other club ID numbers to a readable format
function readTKNumber($tkid, $squad)
{
	global $conn, $clubArray, $squadArray;
	
	// Is the trooper in a club?
	$inClub = false;

	// Based on squad ID, is the trooper in a club
	if($squad > count($squadArray))
	{
		// Get first letter of club
		$firstLetter = strtoupper(substr(getSquadName($squad), 0, 1));
		
		// Set TKID return
		$tkid = $firstLetter . $tkid;
		
		// Set inClub
		$inClub = true;
	}
	
	// If not in club, set default
	if(!$inClub)
	{
		$prefix = "TK";
		
		// Get TK prefix from database
		$getPrefix = $conn->query("SELECT prefix FROM 501st_costumes WHERE legionid = '".$tkid."' LIMIT 1");
		$getPrefix_value = $getPrefix->fetch_row();
		
		// Make sure TK prefix was found
		if(isset($getPrefix_value[0]) && $getPrefix_value[0] != "")
		{
			$prefix = $getPrefix_value[0];
		}
		
		$tkid = $prefix . $tkid;
	}

	return $tkid;
}

// Returns if page is active
function isPageActive($page)
{
	if(isset($_GET['action']))
	{
		if($_GET['action'] == $page)
		{
			return 'class="active"';
		}
	}
	else
	{
		if($page == "home")
		{
			return 'class="active"';
		}
	}
}

// Returns if squad is active
function isSquadActive($squad)
{
	if(isset($_GET['squad']))
	{
		if($squad == $_GET['squad'] && $_GET['squad'] != "mytroops")
		{
			// Squad
			return 'class="squadlink"';
		}
	}
	else
	{
		// Whole state
		if($squad == 0)
		{
			return 'class="squadlink"';
		}
	}
}

// getTKNumber: gets TK number
function getTKNumber($id)
{
	global $conn;
	
	$query = "SELECT * FROM troopers WHERE id='".$id."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			return $db->tkid;
		}
	}
}

// getTrooperSquad: gets squad of trooper
function getTrooperSquad($id)
{
	global $conn;
	
	$query = "SELECT * FROM troopers WHERE id='".$id."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			return $db->squad;
		}
	}
}

// getTrooperForum: gets forum of trooper
function getTrooperForum($id)
{
	global $conn;
	
	$query = "SELECT * FROM troopers WHERE id='".$id."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			return $db->forum_id;
		}
	}
}

// getCostumeClub: gets the costumes club
function getCostumeClub($id)
{
	global $conn;
	
	$query = "SELECT * FROM costumes WHERE id = '".$id."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			return $db->club;
		}
	}
}

// profileTop: Display's user information at top of profile page
function profileTop($id, $tkid, $name, $squad, $forum, $phone)
{
	global $conn, $squadArray;
	
	// Command Staff Edit Link
	if(isAdmin())
	{
		echo '
		<h2 class="tm-section-header">Admin Controls</h2>
		<p style="text-align: center;"><a href="index.php?action=commandstaff&do=managetroopers&uid='.$id.'">Edit/View Member in Command Staff Area</a></p>';
	}
	
	// Only show 501st thumbnail, if a 501st member
	if(getTrooperSquad($tkid) <= count($squadArray))
	{
		// Get 501st thumbnail Info
		$thumbnail_get = $conn->query("SELECT thumbnail FROM 501st_troopers WHERE legionid = '".$tkid."'");
		$thumbnail = $thumbnail_get->fetch_row();
	}
	
	// Get Rebel Legion thumbnail info
	$thumbnail_get_rebel = $conn->query("SELECT costumeimage FROM rebel_costumes WHERE rebelid = '".getRebelInfo(getRebelLegionUser(cleanInput($id)))['id']."' LIMIT 1");
	$thumbnail_rebel = $thumbnail_get_rebel->fetch_row();
	
	echo '
	<h2 class="tm-section-header">'.$name.' - '.readTKNumber($tkid, $squad).'</h2>';
	
	// Avatar
	
	// Does have a avatar?
	$haveAvatar = false;
	
	// 501
	if(isset($thumbnail[0]))
	{
		echo '
		<p style="text-align: center;">
			<img src="'.$thumbnail[0].'" />
		</p>';
		
		// Set
		$haveAvatar = true;
	}
	
	// Rebel
	if(isset($thumbnail_rebel[0]))
	{
		echo '
		<p style="text-align: center;">
			<img src="'.str_replace("-A", "sm", $thumbnail_rebel[0]).'" />
		</p>';
		
		// Set
		$haveAvatar = true;
	}
	
	// If does not have an avatar
	if(!$haveAvatar)
	{
		echo '
		<p style="text-align: center;">
			<img src="https://www.501st.com/memberdata/templates/tk_head.jpg" />
		</p>';
	}
	
	echo '
	<p style="text-align: center;"><a href="https://www.fl501st.com/boards/memberlist.php?mode=viewprofile&un='.urlencode($forum).'" target="_blank">View Boards Profile</a></p>';
	
	if(isAdmin() && $phone != "")
	{
		echo '
		<p style="text-align: center;"><b>Phone Number:</b><br />'.formatPhoneNumber($phone).'</p>';
	}
}

// formatPhoneNumber: Show the phone number properly
function formatPhoneNumber($phoneNumber)
{
	$phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);

	if(strlen($phoneNumber) > 10)
	{
		$countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
		$areaCode = substr($phoneNumber, -10, 3);
		$nextThree = substr($phoneNumber, -7, 3);
		$lastFour = substr($phoneNumber, -4, 4);

		$phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
	}
	else if(strlen($phoneNumber) == 10)
	{
		$areaCode = substr($phoneNumber, 0, 3);
		$nextThree = substr($phoneNumber, 3, 3);
		$lastFour = substr($phoneNumber, 6, 4);

		$phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
	}
	else if(strlen($phoneNumber) == 7)
	{
		$nextThree = substr($phoneNumber, 0, 3);
		$lastFour = substr($phoneNumber, 3, 4);

		$phoneNumber = $nextThree.'-'.$lastFour;
	}

	return $phoneNumber;
}

// profileExist: get's if user exists
function profileExist($id)
{
	global $conn;
	
	// Set up return var
	$doesExist = false;
	
	$query = "SELECT * FROM troopers WHERE id='".$id."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			// Found
			$doesExist = true;
		}
	}
	
	// Return
	return $doesExist;
}

// getName: gets the user's name
function getName($id)
{
	global $conn;
	
	$query = "SELECT * FROM troopers WHERE id='".$id."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			return $db->name;
		}
	}
}

// getPhone: gets the user's phone
function getPhone($id)
{
	global $conn;
	
	$query = "SELECT * FROM troopers WHERE id='".$id."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			return $db->phone;
		}
	}
}

// getSquadID: gets the user's squad
function getSquadID($id)
{
	global $conn;
	
	$query = "SELECT * FROM troopers WHERE id='".$id."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			return $db->squad;
		}
	}
}

// copyEvent: Helps with copying event values to create an event page
function copyEvent($eid, $value, $default = -1)
{
	// Check eid
	if($eid > 0)
	{
		// Return value if eid set
		return $value;
	}
	else
	{
		// Check if default value
		if($default > -1)
		{
			return $default;
		}
		else
		{
			// Return nothing if eid not set
			return '';
		}
	}
}

// copyEventSelect: Helps with copying event values to create an event page - this function selects from select list
function copyEventSelect($eid, $value, $value2, $default = -1)
{
	// Check eid
	if($eid > 0)
	{
		// Check if value is NULL
		if($value === NULL)
		{
			// If null compare values
			if($value == NULL && $value != 0 && $value2 == "null")
			{
				return 'SELECTED';
			}
			else if($default > -1)
			{			
				if($value2 == $default)
				{
					return 'SELECTED';
				}
			}
		}
		else
		{
			// Checks if this is the select option
			if($value == $value2)
			{
				// Return value if eid set
				return 'SELECTED';
			}
			// If both values null, no data
			else if($value == "" && $value2 == "null")
			{
				return 'SELECTED';
			}
		}
	}
	else
	{
		if($default > -1)
		{			
			if($value2 == $default)
			{
				return 'SELECTED';
			}
		}
		else
		{
			// Return nothing if eid not set and not a null value
			return '';
		}
	}
}

// If the user ID is assigned to an event
function inEvent($id, $event)
{
	global $conn;

	$array = ["inTroop" => "0", "status" => ""];
	$status = "";
	
	$query = "SELECT * FROM event_sign_up WHERE trooperid = '".$id."' AND troopid = '".$event."'";
	$i = 0;
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			$i++;
			$status = $db->status;
		}
	}

	// If in an event
	if($i > 0)
	{
		$array = ["inTroop" => "1", "status" => $status];
	}

	return $array;
}

// getStatus: gets status of trooper - 0 = Going, 1 = Stand by, 2 = Tentative, 3 = Attended, 4 = Canceled, 5 = Pending, 6 = Not Picked
function getStatus($value)
{
	$returnValue = "";

	if($value == 0)
	{
		$returnValue = "Going";
	}
	else if($value == 1)
	{
		$returnValue = "Stand By";
	}
	else if($value == 2)
	{
		$returnValue = "Tentative";
	}
	else if($value == 3)
	{
		$returnValue = "Attended";
	}
	else if($value == 4)
	{
		$returnValue = "Canceled";
	}
	else if($value == 5)
	{
		$returnValue = "Pending";
	}
	else if($value == 6)
	{
		$returnValue = "Not Picked";
	}

	return $returnValue;
}

// getDatesFromRange: Get date ranges
function getDatesFromRange($start, $end, $format = 'M-d-Y')
{
    $array = array();
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach($period as $date) { 
        $array[] = $date->format($format); 
    }

    return $array;
}

// validate_url: Check if URL is valid
function validate_url($url)
{
	$path = parse_url($url, PHP_URL_PATH);
	$encoded_path = array_map('urlencode', explode('/', $path));
	$url = str_replace($path, implode('/', $encoded_path), $url);

	if(filter_var(addHttp($url), FILTER_VALIDATE_URL) && strpos($url, "."))
	{
		return '<span style="word-wrap: break-word;"><a href="'.addHttp($url).'" target="_blank">'.$url.'</a></span>';
		
	}
	else
	{
		return 'No website available.';
	}
}

// ifEmpty: Show empty - if no value, show message. Default is EMPTY
function ifEmpty($value, $message = "EMPTY")
{
	if($value == "")
	{
		return $message;
	}
	else
	{
		return $value;
	}
}

// getCostume: What was the costume?
function getCostume($value)
{
	global $conn;
	
	$query = "SELECT * FROM costumes WHERE id='".$value."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			return $db->costume;
		}
	}
}

// echoSelect: Selects the users set value
function echoSelect($value1, $value2)
{
	$returnValue = "";

	if($value1 == $value2)
	{
		$returnValue = "SELECTED";
	}

	return $returnValue;
}

// yesNo: Display yes or no
function yesNo($value)
{
	$returnValue = "";

	if($value == 0)
	{
		$returnValue = "No";
	}
	else
	{
		$returnValue = "Yes";
	}

	return $returnValue;
}

// addHttp: Adds http if does not exist
function addHttp($url)
{
	if (!preg_match("~^(?:f|ht)tps?://~i", $url))
	{
		$url = "http://" . $url;
	}
	return $url;
}

// isAdmin: Is the user an admin or squad leader?
function isAdmin()
{
	global $conn;
	
	$isAdmin = false;
	
	if(isset($_SESSION['id']))
	{
		$query = "SELECT * FROM troopers WHERE id='".$_SESSION['id']."'";
		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				if($db->permissions == 1 || $db->permissions == 2)
				{
					$isAdmin = true;
				}
			}
		}
	}
	
	return $isAdmin;
}

// hasPermission: Does the user have permission to access the data?
// 0 = 501st Member, 1 = Super Admin, 2 = Squad Leader, 3 = Reserve Member, 4 = Retired Member
function hasPermission($permissionLevel1, $permissionLevel2 = -1, $permissionLevel3 = -1, $permissionLevel4 = -1)
{
	global $conn;
	
	$isAllowed = false;
	
	if(isset($_SESSION['id']))
	{
		$query = "SELECT * FROM troopers WHERE id='".$_SESSION['id']."'";
		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				if($db->permissions == $permissionLevel1)
				{
					$isAllowed = true;
				}
				
				if($db->permissions == $permissionLevel2)
				{
					$isAllowed = true;
				}
				
				if($db->permissions == $permissionLevel3)
				{
					$isAllowed = true;
				}
				
				if($db->permissions == $permissionLevel4)
				{
					$isAllowed = true;
				}
			}
		}
	}
	
	return $isAllowed;
}

// isWebsiteClosed: Is the website closed?
function isWebsiteClosed()
{
	global $conn;
	
	$isWebsiteClosed = false;
	
	$query = "SELECT * FROM settings LIMIT 1";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			if($db->siteclosed)
			{
				$isWebsiteClosed = true;
				
				if(loggedIn() && !hasPermission(1))
				{
					session_destroy();
				}
			}
		}
	}
	
	return $isWebsiteClosed;
}

// isSignUpClosed: Are the website sign ups closed?
function isSignUpClosed()
{
	global $conn;
	
	$isWebsiteClosed = false;
	
	$query = "SELECT * FROM settings LIMIT 1";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			if($db->signupclosed)
			{
				$isWebsiteClosed = true;
			}
		}
	}
	
	return $isWebsiteClosed;
}

// Does the TK ID exist?
function doesTKExist($tk, $squad = 0)
{
	global $conn, $squadArray;
	
	// Set up variables
	$exist = false;
	
	// If a 501st squad
	if($squad < count($squadArray))
	{
		$query = "SELECT * FROM troopers WHERE tkid = '".$tk."' AND squad <= ".count($squadArray)."";
	}
	else
	{
		// If a club
		$query = "SELECT * FROM troopers WHERE rebelforum = '".$tk."' AND squad = ".$squad."";
	}

	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			$exist = true;
		}
	}

	return $exist;
}

// Is the TK ID registered?
function isTKRegistered($tk, $squad = 0)
{
	global $conn, $squadArray;
	
	// Set up variables
	$registered = false;
	
	// If a 501st squad
	if($squad < count($squadArray))
	{
		$query = "SELECT * FROM troopers WHERE tkid = '".$tk."' AND squad <= ".count($squadArray)."";
	}
	else
	{
		// If a club
		$query = "SELECT * FROM troopers WHERE rebelforum = '".$tk."' AND squad = ".$squad."";
	}

	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			if($db->password != '')
			{
				$registered = true;
			}
		}
	}

	return $registered;
}

// cleanInput: Prevents hack by cleaning input
function cleanInput($value)
{
	$value = strip_tags(addslashes($value));
	return $value;
}

// sendEventUpdate: Send's an e-mail (if subscribed) to user
function sendEventUpdate($troopid, $trooperid, $subject, $message)
{
	global $conn;

	// Add footer to message
	$message = $message . "https://www.fl501st.com/troop-tracker/index.php?event=".$troopid."\n\nYou can opt out of e-mails under: \"Manage Account\"\n\nhttps://trooptracking.com\n\nTo turn off this notification, go to the event page, and press the \"Unsubscribe\" button.";

	// Query database for trooper information and make sure they are subscribed to e-mail
	$query = "SELECT troopers.email, troopers.name, troopers.subscribe FROM troopers LEFT JOIN event_notifications ON troopers.id = event_notifications.trooperid WHERE event_notifications.troopid = '".$troopid."' AND troopers.subscribe = '1' AND troopers.email != ''";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			@sendEmail($db->email, $db->name, $subject, $message);
		}
	}
}

// sendEmail: Send's an e-mail to specified user
function sendEmail($SendTo, $Name, $Subject, $Message)
{
	// MAIL
	$mail = new PHPMailer(TRUE);

	/* Set the mail sender. */
	$mail->setFrom(emailFrom, 'Troop Tracker');

	/* Add a recipient. */
	$mail->addAddress($SendTo, $Name);

	/* Tells PHPMailer to use SMTP. */
	$mail->isSMTP();

	/* SMTP server address. */
	$mail->Host = emailServer;

	/* Use SMTP authentication. */
	$mail->SMTPAuth = TRUE;

	/* Set the encryption system. */
	$mail->SMTPSecure = 'tls';

	/* SMTP authentication username. */
	$mail->Username = emailUser;

	/* SMTP authentication password. */
	$mail->Password = emailPassword;

	/* Set the SMTP port. */
	$mail->Port = emailPort;

	/* Set the subject. */
	$mail->Subject = $Subject;

	/* Set the mail message body. */
	$mail->Body = $Message;

	/* Finally send the mail. */
	if (!$mail->send())
	{
	   /* PHPMailer error. */
	   //echo $mail->ErrorInfo;
	}
	// END MAIL
}

// getEra: what is the era?
function getEra($number)
{
	// Return value
	$text = "";
	
	if($number == 0)
	{
		$text = "Prequel";
	}
	else if($number == 1)
	{
		$text = "Original";
	}
	else if($number == 2)
	{
		$text = "Sequel";
	}
	else if($number == 3)
	{
		$text = "Expanded";
	}
	else if($number == 4)
	{
		$text = "All";
	}
	
	// Return
	return $text;
}

// convertNumber: convert number to unlimited if 500
function convertNumber($number, $total)
{
	// Number is high enough return unlimited and if total is less than unlimited
	if($number == 500 && $total == 500)
	{
		$number = "unlimited";
	}
	
	// If total troopers allowed is set less than other trooper counts
	if($total < $number)
	{
		$number = $total;
	}
	
	// Return
	return $number;
}

// eraCheck: Check to see if the event is limited to certain costumes
function eraCheck($eventID, $costumeID)
{
	global $conn;

	// Variables
	$eventFail = false;	// Is this costume allowed?

	// Query database for event info
	$query = "SELECT * FROM events WHERE id = '".$eventID."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			// Query costume database to get information on the users costume
			$query4 = "SELECT * FROM costumes WHERE id = '".$costumeID."'";
			if ($result4 = mysqli_query($conn, $query4))
			{
				while ($db4 = mysqli_fetch_object($result4))
				{
					// Make sure event and costume era isn't set to "All" and check if the era and limited to match
					if($db->limitTo != 4 && $db4->era != 4 && $db->limitTo != $db4->era)
					{
						// Did not fail
						$eventFail = true;
					}
				}
			}
		}
	}

	// Return
	return $eventFail;
}

// troopersRemaining: Returns the number of troopers remaining
function troopersRemaining($value1, $value2)
{
	// Subtract values
	$remaining = $value1 - $value2;
	
	// Return remaining
	return '<b>' . $remaining . ' spots remaining.</b>';
}

// eventClubCount: Returns number of troopers signed up for this event based on costume
function eventClubCount($eventID, $club)
{
	global $conn;

	// Variables
	$i = 0;	// 501st
	$rl = 0;	// Rebel Legion
	$droidb = 0;	// Droid builders
	$mandos = 0;	// Mandos
	$other = 0;	// Others
	$total = 0; // Total count
	$eventFull = false;	// Is the event full?
	$returnVal = 0; // Number to return

	// Query database for roster info
	$query = "SELECT event_sign_up.id AS signId, event_sign_up.costume_backup, event_sign_up.costume, event_sign_up.reason, event_sign_up.attended_costume, event_sign_up.status, event_sign_up.troopid, troopers.id AS trooperId, troopers.name, troopers.tkid FROM event_sign_up JOIN troopers ON troopers.id = event_sign_up.trooperid WHERE troopid = '".$eventID."' AND status != '1' AND status != '4'";

	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			// Query costume database to add to club counts
			$query2 = "SELECT * FROM costumes WHERE id = '".$db->costume."'";
			if ($result2 = mysqli_query($conn, $query2))
			{
				while ($db2 = mysqli_fetch_object($result2))
				{
					// 501st
					if($db2->club == 0)
					{
						$i++;
						$total++;
					}
					// Rebel Legion
					else if($db2->club == 1)
					{
						$rl++;
						$total++;
					}
					// Mandos
					else if($db2->club == 2)
					{
						$mandos++;
						$total++;
					}
					// Droid Builders
					else if($db2->club == 3)
					{
						$droidb++;
						$total++;
					}
					// Other
					else if($db2->club == 4)
					{
						$other++;
						$total++;
					}
					// All
					else if($db2->club == 5)
					{
						$i++;
						$rl++;
						$mandos++;
						$droidb++;
						$other++;
						$total++;
					}							
				}
			}
		}
	}
	
	if($club == 0)
	{
		$returnVal = $i;
	}
	else if($club == 1)
	{
		$returnVal = $rl;
	}
	else if($club == 2)
	{
		$returnVal = $mandos;
	}
	else if($club == 3)
	{
		$returnVal = $droidb;
	}
	else if($club == 4)
	{
		$returnVal = $other;
	}
	else if($club == 5)
	{
		$returnVal = $total;
	}

	// Return
	return $returnVal;
}

// isEventFull: Check to see if the event is full ($eventID = ID of the event, $costumeID = costume they are going to wear)
function isEventFull($eventID, $costumeID)
{
	global $conn;

	// Set up variables
	$eventFull = false;

	// Set up limits
	$limitRebels = "";
	$limit501st = "";
	$limitMando = "";
	$limitDroid = "";
	$limitOther = "";

	// Set up limit totals
	$limitRebelsTotal = eventClubCount($eventID, 1);
	$limit501stTotal = eventClubCount($eventID, 0);
	$limitMandoTotal = eventClubCount($eventID, 2);
	$limitDroidTotal = eventClubCount($eventID, 3);
	$limitOtherTotal = eventClubCount($eventID, 4);

	// Query to get limits
	$query = "SELECT * FROM events WHERE id = '".$eventID."'";

	// Output
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			$limitRebels = $db->limitRebels;
			$limit501st = $db->limit501st;
			$limitMando = $db->limitMando;
			$limitDroid = $db->limitDroid;
			$limitOther = $db->limitOther;
		}
	}

	// Check if troop is full
	if(((getCostumeClub($costumeID) == 0 && ($limit501st - eventClubCount($eventID, 0)) <= 0) || (getCostumeClub($costumeID) == 1 && ($limitRebels - eventClubCount($eventID, 1)) <= 0) || (getCostumeClub($costumeID) == 2 && ($limitMando - eventClubCount($eventID, 2)) <= 0) || (getCostumeClub($costumeID) == 3 && ($limitDroid - eventClubCount($eventID, 3)) <= 0) || (getCostumeClub($costumeID) == 4 && ($limitOther - eventClubCount($eventID, 4)) <= 0)))
	{
		// Set event full
		$eventFull = true;
	}

	// Return
	return $eventFull;
}

// getPermissionName: Converts value to title string of permission
function getPermissionName($value)
{
	if($value == 0)
	{
		return 'Regular Member';
	}
	else if($value == 1)
	{
		return 'Super Admin';
	}
	else if($value == 2)
	{
		return 'Moderator';
	}
	else if($value == 3)
	{
		return 'Reserve Member';
	}
	else if($value == 4)
	{
		return 'Retired Member';
	}
	else if($value == 5)
	{
		return 'Handler';
	}
	else
	{
		return 'Unknown';
	}
}

// emailSettingStatus: Is the setting on or off
function emailSettingStatus($column, $print = false)
{
	global $conn;
	
	// Set status
	$status = 0;
	
	// Get email setting
	$getStatus = $conn->query("SELECT ".$column." FROM troopers WHERE id = '".$_SESSION['id']."'");
	$getStatus_get = $getStatus->fetch_row();
	
	// Set status to query
	$status = $getStatus_get[0];
	
	// If print not set, return status
	if(!$print)
	{
		return $status;
	}
	else
	{
		// If print set, print checked
		if($status == 1)
		{
			return 'CHECKED';
		}
	}
}

// isLink: Is this a linked event?
function isLink($id)
{
	global $conn;
	
	// Set link
	$link = 0;
	
	// Get number of events with link
	$getNumOfLinks = $conn->query("SELECT id FROM events WHERE link = '".$id."'");
	
	// Get link ID
	$getLinkID = $conn->query("SELECT link FROM events WHERE id = '".$id."'");
	$getLinkID_get = $getLinkID->fetch_row();
	
	// If has links to event, or is linked, show shift data
	if($getNumOfLinks->num_rows > 0 || $id != 0)
	{
		// If this event is the link
		if($getNumOfLinks->num_rows > 0)
		{
			$link = $id;
		}
		else if($getLinkID_get[0] != 0)
		{
			$link = $getLinkID_get[0];
		}
	}
	
	return $link;
}

// If logged in, update active status
if(loggedIn())
{
	$conn->query("UPDATE troopers SET last_active = NOW() WHERE id='".$_SESSION['id']."'") or die($conn->error);
}

// Check for events that need to be closed
$query = "SELECT * FROM events WHERE dateEnd < NOW() - INTERVAL 1 HOUR AND closed != '2' AND closed != '1'";
if ($result = mysqli_query($conn, $query))
{
	while ($db = mysqli_fetch_object($result))
	{
		// Close them
		$conn->query("UPDATE events SET closed = '1' WHERE id = '".$db->id."'");
	}
}

?>
