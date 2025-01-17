<?php

/**
 * This file is used for displaying the website.
 *
 * @author  Matthew Drennan
 *
 */

// Include config file
include 'config.php';

// Include Scripts
echo '
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Meta Data -->
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	
	<!-- Title -->
	<title>501st '.garrison.' - Troop Tracker</title>
	
	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600&display=swap" rel="stylesheet" />
	
	<!-- Main Style Sheets -->
	<link href="fontawesome/css/all.min.css" rel="stylesheet" />';
	
	echo '
	<!-- Style Sheets -->
	<link href="css/main.css" rel="stylesheet" />
	<link rel="stylesheet" href="script/lib/jquery-ui.min.css">
	<link rel="stylesheet" href="script/lib/jquery-ui-timepicker-addon.css">
	<link href="css/dropzone.min.css" type="text/css" rel="stylesheet" />
	<link href="css/lightbox.min.css" rel="stylesheet" />
	<link href="css/calendar.css" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
	
	<!-- Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	
	<!-- Setup Variable -->
	<script>
	var placeholder = '.placeholder.';
	var clubArray = [';

	/* CHECK IF MEMBER CLUB DB VALUE */

	// Club count
	$clubCount = count($clubArray);

	// Club step
	$i = 0;

	// Loop through clubs
	foreach($clubArray as $club => $club_value)
	{
		echo '"'.$club_value['db'].'"';

		// Add comma
		if($i < ($clubCount - 1))
		{
			echo ',';
		}

		// Increment
		$i++;
	}

	echo'];';

	/* DB LIMIT CLUBS */

	// Club step
	$i = 0;

	echo '
	var clubDBLimitArray = [';

	// Loop through clubs
	foreach($clubArray as $club => $club_value)
	{
		echo '"'.$club_value['dbLimit'].'"';

		// Add comma
		if($i < ($clubCount - 1))
		{
			echo ',';
		}

		// Increment
		$i++;
	}

	echo'];';

	/* CLUB SPECIAL FORUM VALUE */

	// Club count with value
	$clubCount = array_filter($clubArray, function($x) { return !empty($x['db3Name']); });

	// Club step
	$i = 0;

	echo '
	var clubDB3Array = [';

	// Loop through clubs
	foreach($clubArray as $club => $club_value)
	{
		// Don't allow empty result
		if($club_value['db3Name'] != "")
		{
			echo '"'.$club_value['db3'].'"';

			// Add comma
			if($i < count($clubCount) - 1)
			{
				echo ',';
			}

			// Increment
			$i++;
		}
	}

	echo'];';

	echo '
	// Clear limits
	function clearLimit()
	{';

	// Loop through clubs
	foreach($clubArray as $club => $club_value)
	{
		echo '
		$("#'.$club_value['dbLimit'].'").val(500);';
	}

	echo '
	}
	</script>

	<!-- JQUERY -->
	<script src="script/lib/jquery-3.4.1.min.js"></script>

	<!-- JQUERY UI -->
	<script src="script/lib/jquery-ui.min.js"></script>

	<!-- JQUERY SELECT -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<!-- Addons -->
	<script src="script/lib/jquery-ui-timepicker-addon.js"></script>
	<script src="script/js/validate/jquery.validate.min.js"></script>
	<script src="script/js/validate/validate.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
	
	<!-- Drop Zone -->
	<script src="script/lib/dropzone.min.js"></script>
	
	<!-- LightBox -->
	<script src="script/lib/lightbox.min.js"></script>

	<script>
 	$(function() {
		$("#datepicker").datetimepicker();
		$("#datepicker2").datetimepicker();
		$("#datepicker3").datetimepicker();
		$("#datepicker4").datetimepicker();
	});
	</script>
</head>

<body class="'.myTheme().'">

<div class="tm-container">
<div class="tm-text-white tm-page-header-container">
	<img src="images/logo.png" />
</div>
<div class="tm-main-content">
<section class="tm-section">

<div class="topnav" id="myTopnav">
<a href="index.php" '.isPageActive("home").'>Home</a>
<a href="https://fl501st.com/boards/">Forums</a>';

if(!isWebsiteClosed() || isAdmin())
{
	echo '
	<a href="index.php?action=trooptracker" '.isPageActive("trooptracker").'>Troop Tracker</a>';
}

// If not logged in
if(!loggedIn())
{
	// If website is not closed
	if(!isWebsiteClosed())
	{
		// If sign ups are not closed
		if(!isSignUpClosed())
		{
			echo '
			<a href="index.php?action=requestaccess" '.isPageActive("requestaccess").'>Request Access</a>
			<a href="index.php?action=setup" '.isPageActive("setup").'>Account Setup</a>
			<a href="index.php?action=faq" '.isPageActive("faq").'>FAQ</a>';
		}
	}
	
	echo '
	<a href="index.php?action=login" '.isPageActive("login").'>Login</a>';
}
else
{
	// Logged in
	echo '
	<a href="index.php?action=account" '.isPageActive("account").'>Manage Account</a>';

	// If is admin
	if(isAdmin())
	{
		echo '
		<a href="index.php?action=commandstaff" '.isPageActive("commandstaff").'>Command Staff Portal</a>';
	}

	echo '
	<a href="index.php?action=logout" '.isPageActive("logout").'>Logout</a>';
}

// Icon for mobile phones
echo '
<a href="javascript:void(0);" class="icon" onclick="myFunction()"><i class="fa fa-bars"></i></a>
</div>';

// Show support graph
echo drawSupportGraph();

// Show tip
echo dailyTip();

// Show the account page
if(isset($_GET['action']) && $_GET['action'] == "account" && loggedIn())
{
	// Theme Button Submit
	if(isset($_POST['themeButton']))
	{
		$conn->query("UPDATE troopers SET theme = '".cleanInput($_POST['themeselect'])."' WHERE id = '".$_SESSION['id']."'");
		
		echo '<p>Your theme has been changed. Please <a href="index.php?action=account">refresh</a> the page to see the changes.</p>';
	}
	
	// Account Page
	echo '
	<h2 class="tm-section-header">Manage Account</h2>

	<a href="#/" id="emailSettingLink" class="button">E-mail Settings</a> 
	<a href="#/" id="changephoneLink" class="button">Change Phone</a> 
	<a href="#/" id="changenameLink" class="button">Change Name</a> 
	<a href="#/" id="changethemeLink" class="button">Change Theme</a> 
	<a href="#/" id="favoriteCostumes" class="button">Favorite Costumes</a> 
	<a href="index.php?action=donation" class="button">Donate</a> 
	<a href="index.php?profile='.$_SESSION['id'].'" class="button">View Your Profile</a>
	<br /><br />
	
	<div id="favoriteCostumesArea" style="display:none;">
		<p>Select your favorite costumes, and they will display at the top of the costume drop down boxes.</p>
		
		<select multiple style="height: 500px;" id="favoriteCostumeSelect" name="favoriteCostumeSelect">';
		
		$query = "SELECT costumes.id AS id, costumes.costume, favorite_costumes.costumeid, favorite_costumes.trooperid FROM costumes LEFT JOIN favorite_costumes ON favorite_costumes.costumeid = costumes.id ORDER BY costume";

		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				$isFavorite = ($db->trooperid == $_SESSION['id']) ? echoSelect($db->id, $db->costumeid) : "";

				echo '
				<option value="'.$db->id.'" '. $isFavorite .'>'.$db->costume .'</option>';
			}
		}
		
	echo '
		</select>
	</div>

	<div id="unsubscribe" style="display:none;">
		<h2 class="tm-section-header">E-mail Subscription</h2>
		<form action="process.php?do=unsubscribe" method="POST" name="unsubscribeForm" id="unsubscribeForm">';
		$query = "SELECT subscribe FROM troopers WHERE id = '".$_SESSION['id']."'";
		
		// Is the trooper subscribed to e-mail?
		$subscribe = "";

		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				if($db->subscribe == 1)
				{
					echo '
					<input type="submit" name="unsubscribeButton" id="unsubscribeButton" value="Unsubscribe All" />';
				}
				else
				{
					echo '
					<input type="submit" name="unsubscribeButton" id="unsubscribeButton" value="Subscribe" />';
					
					// Set is subscribed
					$subscribe = "style = \"display: none;\"";
				}
			}
		}
		echo '
		</form>
		
		<div id="emailSettingsOptions" '.$subscribe.'>
			<h3>Squads / Clubs</h3>
			<form action="process.php?do=emailsettings" method="POST" id="emailsettingsForm" name="emailsettingsForm">';

			// Florida Garrison
			echo '
			<input type="checkbox" name="esquad0" id="esquad0" ' . emailSettingStatus("esquad0", true) . ' />501st / Florida Garrison<br />';
			
			// Squad count
			$i = 1;
			
			// Loop through squads
			foreach($squadArray as $squad => $squad_value)
			{
				echo '
				<input type="checkbox" name="esquad'.$i.'" id="esquad'.$i.'" ' . emailSettingStatus("esquad" . $i, true) . ' />' . $squad_value['name'] . '<br />';
				
				// Increment squad count
				$i++;
			}
			
			// Loop through clubs
			foreach($clubArray as $club => $club_value)
			{
				echo '
				<input type="checkbox" name="esquad'.$i.'" id="esquad'.$i.'" ' . emailSettingStatus("esquad" . $i, true) . ' />' . $club_value['name'] . '<br />';
				
				// Increment squad count
				$i++;
			}
			
			echo '
				<p style="font-size: 12px;">
					<i>Note: Events are categorized by 501st squad territory. To receive event notifications for a particular area, ensure you subscribed to the appropriate squad(s). Club notifications are used in command staff e-mails, to send command staff information on trooper milestones based on squad or club.</i>
				</p>

				<h3>Website</h3>
				<input type="checkbox" name="efast" id="efast" ' . emailSettingStatus("efast", true) . ' />Instant Event Notification<br />
				<input type="checkbox" name="ecomments" id="ecomments" ' . emailSettingStatus("ecomments", true) . ' />Comments<br />
				<input type="checkbox" name="econfirm" id="econfirm" ' . emailSettingStatus("econfirm", true) . ' />Confirm Attendance Notification<br />
				<input type="checkbox" name="ecommandnotify" id="ecommandnotify" ' . emailSettingStatus("ecommandnotify", true) . ' />Command Staff Notifications<br />
			</form>
		</div>
	</div>
	
	<div id="changetheme" style="display:none;">
		<h2 class="tm-section-header">Change Theme</h2>
		<form action="index.php?action=account" method="POST" name="changethemeForm" id="changethemeForm">
			<select name="themeselect" id="themeselect">';
			$query = "SELECT theme FROM troopers WHERE id = '".$_SESSION['id']."'";

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					echo '
					<option value="0" '.echoSelect(0, $db->theme).'>Florida Garrison Theme (Dark Theme)</option>
					<option value="1" '.echoSelect(1, $db->theme).'>Everglades Theme</option>
					<option value="2" '.echoSelect(2, $db->theme).'>Makaze Theme</option>
					<option value="3" '.echoSelect(3, $db->theme).'>Florida Garrison Theme</option>';
				}
			}
		echo '
				<input type="submit" name="themeButton" id="themeButton" value="Change Theme" />
			</select>
		</form>
	</div>

	<div id="changename" style="display:none;">
		<h2 class="tm-section-header">Change Your Name</h2>

		<form action="process.php?do=changename" method="POST" name="changeNameForm" id="changeNameForm">';
		$query = "SELECT name FROM troopers WHERE id = '".$_SESSION['id']."'";

		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				echo '
				<input type="text" name="name" id="name" value="'.$db->name.'" />
				<input type="submit" name="nameButton" id="nameButton" value="Update" />';
			}
		}
		echo '
		</form>
	</div>

	<div id="changephone" style="display:none;">
		<h2 class="tm-section-header">Change Phone Number</h2>
		<form action="process.php?do=changephone" method="POST" name="changePhoneForm" id="changePhoneForm">';
		$query = "SELECT phone FROM troopers WHERE id = '".$_SESSION['id']."'";

		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				echo '
				<input type="text" name="phone" id="phone" value="'.$db->phone.'" />
				<input type="submit" name="phoneButton" id="phoneButton" value="Update" />';
			}
		}
		echo '
		</form>
	</div>';
}

// Show the request access page
if(isset($_GET['action']) && $_GET['action'] == "requestaccess" && !isSignUpClosed() && !loggedIn())
{
	echo '
	<h2 class="tm-section-header">Request Access</h2>
	
	<div name="requestAccessFormArea" id="requestAccessFormArea">
		<p style="text-align: center;">New to the 501st and/or '.garrison.'? Or are you solely a member of another club? Use this form below to start signing up for troops. Command Staff will need to approve your account prior to use.</p>
		
		<form action="process.php?do=requestaccess" name="requestAccessForm" id="requestAccessForm" method="POST">
			First & Last Name: <input type="text" name="name" id="name" />
			<br /><br />
			TKID (numbers only): <input type="number" min="0" name="tkid" id="tkid" />
			<p><i>Non-501st clubs, please enter an ID number of your choosing.</i></p>
			<input type="radio" name="accountType" value="1" CHECKED> Regular <input type="radio" name="accountType" value="4"> Handler 
			<br /><br />
			Phone (Optional): <input type="text" name="phone" id="phone" />
			<br /><br />
			FL Garrison Forum Username: <input type="text" name="forumid" id="forumid" />
			<br /><br />
			FL Garrison Forum Password: <input type="password" name="forumpassword" id="forumpassword" />
			<br /><br />';

			// Loop through clubs
			foreach($clubArray as $club => $club_value)
			{
				// If DB3 defined
				if($club_value['db3Name'] != "")
				{
					echo '
					'.$club_value['db3Name'].' (if applicable): <input type="text" name="'.$club_value['db3'].'" id="'.$club_value['db3'].'" />
					<br /><br />';
				}
			}

			echo '
			<p>Squad/Club:</p>
			<select name="squad" id="squad">
				'.squadSelectList().'
				<option value="0">'.garrison.' / 501st Visitor</option>
			</select>
			<br /><br />
			<input type="submit" name="submitRequest" value="Request" />
			<br />
			<b>If you are a dual member, you will only need one account. Make sure your account is registered as a 501st Legion member.</b>
		</form>
	</div>

	<div name="requestAccessFormArea2" id="requestAccessFormArea2"></div>';
}

// Show the profile page
if(isset($_GET['profile']))
{
	// Hold value
	$profile = cleanInput($_GET['profile']);
	
	// Convert TKID to profile
	if(isset($_GET['tkid']))
	{
		$profile = getIDFromTKNumber($_GET['tkid']);
	}
	
	// Get data
	$query = "SELECT event_sign_up.trooperid, event_sign_up.troopid, event_sign_up.costume, event_sign_up.status, events.name AS eventName, events.id AS eventId, events.charityDirectFunds, events.charityIndirectFunds, events.dateStart, events.dateEnd, troopers.id, troopers.name, troopers.forum_id, troopers.tkid, troopers.squad, troopers.phone FROM events LEFT JOIN event_sign_up ON events.id = event_sign_up.troopid JOIN troopers ON troopers.id = event_sign_up.trooperid WHERE troopers.id = '".$profile."' AND troopers.id != ".placeholder." AND events.closed = '1' AND event_sign_up.status = '3' ORDER BY events.dateEnd DESC";
	$i = 0;
	
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			if($i == 0)
			{
				// Show profile information
				echo 
				profileTop($db->id, $db->tkid, $db->name, $db->squad, $db->forum_id, $db->phone);
				
				echo  '
				<span style="text-align: center;">' . getTroopCounts($profile) . '</span>
				<div style="overflow-x: auto;">
				'.pendingTroopsDisplay($profile).'

				<h2 class="tm-section-header">Troop History</h2>
				<table border="1">
				<tr>
					<th>Event Name</th>	<th>Date</th>	<th>Attended Costume</th>
				</tr>';
			}
			
			// Set add to title if linked event
			$add = "";
			
			// If linked event
			if(isLink($db->eventId) > 0)
			{
				$add = "[<b>" . date("l", strtotime($db->dateStart)) . "</b> : ".date("m/d - h:i A", strtotime($db->dateStart))." - ".date("h:i A", strtotime($db->dateEnd))."] ";
			}

			echo '
			<tr>
				<td><a href="index.php?event='.$db->troopid.'">'.$add.''.$db->eventName.'</a></td>';
				
			$dateFormat = date('m-d-Y', strtotime($db->dateEnd));

			echo '
				<td>'.$dateFormat.'</td>	<td>'.ifEmpty('<a href="index.php?action=costume&costumeid='.$db->costume.'">' . getCostume($db->costume) . '</a>', "N/A").'</td>
			</tr>';

			// Increment i
			$i++;
		}
	}

	// If profile does not exist
	if(!profileExist($profile))
	{
		echo '
		<p style="text-align: center;">
			<b>This trooper does not exist.</b>
		</p>';
	}
	// Check if placeholder
	else if($profile == placeholder)
	{
		echo '
		<p style="text-align: center;">
			<b>This is a placeholder account. A placeholder is used to fill space when a trooper does not have Troop Tracker access.</b>
		</p>';
	}
	else
	{
		// Profile exists - if nothing to show
		if($i == 0)
		{
			echo profileTop($profile, getTKNumber($profile), getName($profile), getTrooperSquad($profile), getTrooperForum($profile), getPhone($profile));
		}
		else
		{
			// Get count for awards
			$troops_get = $conn->query("SELECT COUNT(*) FROM event_sign_up WHERE status = '3' AND trooperid = '".$profile."'");
			$count = $troops_get->fetch_row();

			// Set up award count
			$j = 0;

			echo '
			</table>
			</div>
			
			<div class="profile-awards">
			
			<h2 class="tm-section-header">Awards</h2>';
			
			// Check if supporter
			if(isSupporter($profile))
			{
				echo '<img src="images/flgdonate.png" />';
			}
			
			echo'
			<ul>';

			if($count[0] >= 1)
			{
				echo '<li>First Troop Completed!</li>';
				$j++;
			}

			if($count[0] >= 10)
			{
				echo '<li>10 Troops</li>';
			}

			if($count[0] >= 25)
			{
				echo '<li>25 Troops</li>';
			}

			if($count[0] >= 50)
			{
				echo '<li>50 Troops</li>';
			}

			if($count[0] >= 75)
			{
				echo '<li>75 Troops</li>';
			}

			if($count[0] >= 100)
			{
				echo '<li>100 Troops</li>';
			}

			if($count[0] >= 150)
			{
				echo '<li>150 Troops</li>';
			}

			if($count[0] >= 200)
			{
				echo '<li>200 Troops</li>';
			}

			if($count[0] >= 250)
			{
				echo '<li>250 Troops</li>';
			}

			if($count[0] >= 300)
			{
				echo '<li>300 Troops</li>';
			}

			if($count[0] >= 400)
			{
				echo '<li>400 Troops</li>';
			}

			if($count[0] >= 500)
			{
				echo '<li>500 Troops</li>';
			}

			if($count[0] >= 501)
			{
				echo '<li>501 Troops Award</li>';
			}

			// Get data from custom awards - load award user data
			$query2 = "SELECT award_troopers.awardid, award_troopers.trooperid, awards.id, awards.title, awards.icon FROM award_troopers LEFT JOIN awards ON awards.id = award_troopers.awardid WHERE award_troopers.trooperid = '".$profile."'";
			if ($result2 = mysqli_query($conn, $query2))
			{
				while ($db2 = mysqli_fetch_object($result2))
				{
					// If has icon...
					if($db2->icon == "")
					{
						echo '<li>'.$db2->title.'</li>';
					}
					else
					{
						echo '<li style="list-style-image: url(\'images/icons/'.$db2->icon.'\');">'.$db2->title.'</li>';
					}

					$j++;
				}
			}

			if($j == 0)
			{
				echo '<li>No awards yet!</li>';
			}
			
			echo '
			</ul>
			</div>
			
			<div class="profile-donations">
			
			<h2 class="tm-section-header">Donations</h2>
			
			<ul>';
			
			// Reset for donations
			$j = 0;
		
			// Get data from custom awards - load award user data
			$query2 = "SELECT * FROM donations WHERE trooperid = '".cleanInput($_GET['profile'])."' ORDER BY datetime DESC";
			if ($result2 = mysqli_query($conn, $query2))
			{
				while ($db2 = mysqli_fetch_object($result2))
				{
					$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
					
					echo '<li>' . $formatter->formatCurrency($db2->amount, 'USD') . ' on '.date("m/d/Y", strtotime($db2->datetime)).'</li>';
					$j++;
				}
			}

			if($j == 0)
			{
				echo '<li>No donations yet!</li>';
			}
			echo '
			</ul>
			
			</div>';
		}
		
		echo '
		<h2 class="tm-section-header">Costumes</h2>';
		
		// Show 501st costumes
		showCostumes(getTKNumber($profile), getTrooperSquad($profile));
		
		// Show Rebel Legion costumes
		showRebelCostumes(getRebelInfo(getRebelLegionUser($profile))['id']);
		
		// Show Mando Mercs costumes
		showMandoCostumes(getMandoLegionUser($profile));

		// Show Saber Guild costumes
		showSGCostumes(getSGUser($profile));
		
		// Show Droid Builder costumes
		showDroids(getTrooperForum($profile));
	}
}

// Show the donation page
if(isset($_GET['action']) && $_GET['action'] == "donation" && loggedIn())
{
	// If donated...
	if(isSupporter($_SESSION['id']))
	{
		echo '
		<p style="text-align: center;">
			<b>Thank you for supporting the '.garrison.'!</b>
		</p>
		<hr />';
	}
	
	echo '
	<h2 class="tm-section-header">Support the '.garrison.'!</h2>
	
	<p style="text-align: center;">With a monthly contribution of only $5.00, you can help support paying for the website server, prop storage, and general expenses of the '.garrison.'. Without your assistance, other members are left to pay for all expenses out of their pocket. Donations are only available to '.garrison.' members, and all the money goes to garrison expenses.</p>
	
	<h2 class="tm-section-header">What you get...</h2>
	
	<ul>
		<li>"'.garrison.' Supporter" award on your troop tracker profile</li>
		<li>"'.garrison.' Supporter" icon on troop sign ups</li>
	</ul>
	
	<h2 class="tm-section-header">Donate Below!</h2>
	<form action="https://www.paypal.com/donate" method="post" target="_top" style="text-align: center;">
		<input type="hidden" name="notify_url" value="'.ipn.'?trooperid='.$_SESSION['id'].'">
		<input type="hidden" name="custom" value="'.$_SESSION['id'].'">
		<input type="hidden" name="hosted_button_id" value="ULH54MMQKGL5Q" />
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
	</form>';
}

// Photo Page
if(isset($_GET['action']) && $_GET['action'] == "photos")
{
	// Print
	echo '
	<h3>Recent Events With Photos</h3>';

	// Start photo count
	$i = 0;

	// Build query
	$query = "SELECT uploads.troopid, events.dateStart, events.dateEnd FROM uploads LEFT JOIN events ON uploads.troopid = events.id WHERE admin = '0' GROUP BY uploads.troopid ORDER BY events.dateEnd DESC LIMIT 100";

	// Loop through query
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			// First loop
			if($i == 0)
			{
				echo '
				<ul>';
			}

			$troopCount = $conn->query("SELECT id FROM uploads WHERE troopid = '".$db->troopid."' AND admin = '0'");

			echo '
			<li>
				<a href="index.php?event='.$db->troopid.'"><b>('.$troopCount->num_rows.')</b> ['.date("m-d-Y h:i A", strtotime($db->dateStart)).' - '.date("h:i A", strtotime($db->dateEnd)).'] - '.getEventTitle($db->troopid).'</a>
			</li>';

			// Increment photo count
			$i++;
		}
	}

	// If photos exist
	if($i > 0)
	{
		echo '
		</ul>';
	}
	else
	{
		echo '
		<p style="text-align: center;">No photos to display.</p>';
	}
}

// Show the costume count page
if(isset($_GET['action']) && $_GET['action'] == "costume" && isset($_GET['costumeid']) && $_GET['costumeid'] >= 0)
{
	// Get data
	$query = "SELECT (SELECT COUNT(event_sign_up.trooperid)) AS troopCount, troopers.name AS trooperName, troopers.tkid, troopers.squad, event_sign_up.trooperid, event_sign_up.troopid, event_sign_up.trooperid, event_sign_up.costume, event_sign_up.status, events.name AS eventName, events.id AS eventId, events.charityDirectFunds, events.charityIndirectFunds, events.dateStart, events.dateEnd, (TIMESTAMPDIFF(HOUR, events.dateStart, events.dateEnd) + events.charityAddHours) AS charityHours FROM events LEFT JOIN event_sign_up ON events.id = event_sign_up.troopid LEFT JOIN troopers ON troopers.id = event_sign_up.trooperid WHERE event_sign_up.costume = '".cleanInput($_GET['costumeid'])."' AND status = 3 AND events.closed = '1' GROUP BY event_sign_up.trooperid ORDER BY troopCount DESC";

	// Troop count
	$i = 0;

	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			if($i == 0)
			{
				echo '
				<h2 class="tm-section-header">'.getCostume($db->costume).'</h2>
				<div style="overflow-x: auto;">
				<table border="1">
				<tr>
					<th>Trooper Name</th>	<th>Trooper TKID</th>	<th>Costume Troop Count</th>
				</tr>';
			}

			echo '
			<tr>
				<td><a href="index.php?profile='.$db->trooperid.'">'.$db->trooperName.'</a></td>	<td>'.readTKNumber($db->tkid, $db->squad).'</td>	<td>'.$db->troopCount.'</td>
			</tr>';

			$i++;
		}
	}

	if($i > 0)
	{
		echo '
		</table>
		</div>';
	}
}

// Show the search page
if(isset($_GET['action']) && $_GET['action'] == "search")
{
	echo '
	<h2 class="tm-section-header">Search</h2>
	<div name="searchForm" id="searchForm">
		<form action="index.php?action=search" method="POST">';
			// Get our search type, and show certain fields
			if(!isset($_POST['searchType']) || $_POST['searchType'] == "regular")
			{
				echo '
				Search Troop Name: <input type="text" name="searchName" id="searchName" value="'. (!isset($_POST['searchName']) ? '' : cleanInput($_POST['searchName'])) .'" />
				<br /><br />
				Search Trooper Name: <input type="text" name="searchTrooperName" id="searchTrooperName" value="'. (!isset($_POST['searchTrooperName']) ? '' : cleanInput($_POST['searchTrooperName'])) .'" />
				<br /><br />';
			}
			
			echo '
			Date Start: <input type="text" name="dateStart" id="datepicker3" value="'. (!isset($_POST['dateStart']) ? '' : cleanInput($_POST['dateStart'])) .'" />
			<br /><br />
			Date End: <input type="text" name="dateEnd" id="datepicker4" value="'. (!isset($_POST['dateEnd']) ? '' : cleanInput($_POST['dateEnd'])) .'" />
			<br /><br />';
			
			// Get our search type, and show certain fields
			if(!isset($_POST['searchType']) || $_POST['searchType'] == "regular")
			{
				echo '
				Search TKID: <input type="text" name="tkID" id="tkID" value="'. (!isset($_POST['tkID']) ? '' : cleanInput($_POST['tkID'])) .'" />
				<br /><br />';
			}
			
			// Set search type
			echo '
			<input type="hidden" name="searchType" value="'. (!isset($_POST['searchType']) ? 'regular' : cleanInput($_POST['searchType'])) .'" />';
			
			// If trooper search, include searchType for another search
			if(isset($_POST['searchType']) && ($_POST['searchType'] == "trooper" || $_POST['searchType'] == "donations"))
			{
				echo '
				<select name="squad" id="squad">
					<option value="0" '.echoSelect(0, cleanInput($_POST['squad'])).'>All</option>
					'.squadSelectList(true, "select").'
				</select>
				<br /><br />';
				
				if($_POST['searchType'] == "trooper")
				{
					// If active only set
					if(isset($_POST['activeonly']) && $_POST['activeonly'] == 1)
					{
						echo '
						<input type="checkbox" name="activeonly" value="1" CHECKED /> Active Members Only?';	
					}
					else
					{
						echo '
						<input type="checkbox" name="activeonly" value="1" /> Active Members Only?';	
					}

					echo '
					<br /><br />';
				}
			}

			// If costume search, include searchType for another search
			if(isset($_POST['searchType']) && ($_POST['searchType'] == "trooper" || $_POST['searchType'] == "costumecount"))
			{
				echo '
				<select multiple style="height: 500px;" id="costumes_choice_search_box" name="costumes_choice_search_box[]">';
				
				$query = "SELECT costumes.id AS id, costumes.costume FROM costumes ORDER BY costume";

				if ($result = mysqli_query($conn, $query))
				{
					while ($db = mysqli_fetch_object($result))
					{
						$check = "";

						// Should we check the select
						if(isset($_POST['costumes_choice_search_box']))
						{
							if(in_array($db->id, $_POST['costumes_choice_search_box']))
							{
								$check = "SELECTED";
							}
						}

						echo '
						<option value="'.$db->id.'" '.$check.'>'.$db->costume .'</option>';
					}
				}
				
				echo '
				</select>';
			}
			
			echo '
			<input type="submit" name="submitSearch" id="submitSearch" value="Search!" />
		</form>
	
	<br /><br />
	<hr />
	<br /><br />';
	
	// Regular search
	if(isset($_POST['searchType']) && $_POST['searchType'] == "regular")
	{
		// Query for search
		$query = "SELECT event_sign_up.trooperid, event_sign_up.troopid, event_sign_up.costume, event_sign_up.status, events.name AS eventName, events.id AS eventId, events.charityDirectFunds, events.charityIndirectFunds, events.dateStart, events.dateEnd FROM events LEFT JOIN event_sign_up ON events.id = event_sign_up.troopid LEFT JOIN troopers ON troopers.id = event_sign_up.trooperid WHERE troopers.id != ".placeholder." AND ";
		
		if(strlen($_POST['tkID']) > 0)
		{
			$query .= " troopers.tkid = '".cleanInput($_POST['tkID'])."'";
		}

		if(strlen($_POST['searchName']) > 0)
		{
			if(strlen($_POST['tkID']) > 0)
			{
				$query .= " AND";
			}

			$query .= " events.name LIKE '%".cleanInput($_POST['searchName'])."%'";
		}

		if(strlen($_POST['dateStart']) > 0)
		{
			if(strlen($_POST['searchName']) > 0)
			{
				$query .= " AND";
			}

			$date = strtotime(cleanInput($_POST['dateStart']));
			$dateF = date('Y-m-d H:i:s', $date);

			$query .= " events.dateStart >= '".$dateF."'";
		}

		if(strlen($_POST['dateEnd']) > 0)
		{
			if(strlen($_POST['dateStart']) > 0)
			{
				$query .= " AND";
			}

			$date = strtotime(cleanInput($_POST['dateEnd']));
			$dateF = date('Y-m-d H:i:s', $date);

			$query .= " events.dateEnd <= '".$dateF."'";
		}
		
		if(strlen($_POST['searchTrooperName']) > 0)
		{
			if(strlen($_POST['dateEnd']) > 0)
			{
				$query .= " AND";
			}

			$query .= " troopers.name LIKE '%".cleanInput($_POST['searchTrooperName'])."%'";
		}
	}
	else if(isset($_POST['searchType']) && $_POST['searchType'] == "trooper")
	{	
		// Format date start
		$date = strtotime(cleanInput($_POST['dateStart']));
		$dateF = date('Y-m-d H:i:s', $date);
		
		// Format date end
		$date = strtotime(cleanInput($_POST['dateEnd']));
		$dateE = date('Y-m-d H:i:s', $date);

		// Query for search
		$query = "SELECT * FROM troopers";
		
		// Get the squad search type
		// If All
		if($_POST['squad'] == 0)
		{
			// Add to query
			$query .= " WHERE troopers.id != ".placeholder."";

			// Get troop counts
			$troops_get = $conn->query("SELECT COUNT(id) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."'");
			$troop_count = $troops_get->fetch_row();
			
			// Get charity counts
			$charity_get = $conn->query("SELECT SUM(charityDirectFunds) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."'");
			$charity_count = $charity_get->fetch_row();

			$charity_get2 = $conn->query("SELECT SUM(charityIndirectFunds) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."'");
			$charity_count2 = $charity_get2->fetch_row();
		}
		
		// If 501st
		if(($_POST['squad'] >= 1 && $_POST['squad'] <= count($squadArray)))
		{
			// Add to query
			$query .= " WHERE squad = '".cleanInput($_POST['squad'])."' AND troopers.id != ".placeholder."";
			
			// Get troop counts
			$troops_get = $conn->query("SELECT COUNT(id) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."' AND squad = '".cleanInput($_POST['squad'])."'");
			$troop_count = $troops_get->fetch_row();
			
			// Get charity counts
			$charity_get = $conn->query("SELECT SUM(charityDirectFunds) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."' AND squad = '".cleanInput($_POST['squad'])."'");
			$charity_count = $charity_get->fetch_row();

			$charity_get2 = $conn->query("SELECT SUM(charityIndirectFunds) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."' AND squad = '".cleanInput($_POST['squad'])."'");
			$charity_count2 = $charity_get2->fetch_row();
			
			// If active only set
			if(isset($_POST['activeonly']) && $_POST['activeonly'] == 1)
			{
				$query .= " AND p501 = '1' OR p501 = '2'";
			}
		}
		
		// Set up Squad ID
		$clubID = count($squadArray) + 1;
		
		// Loop through clubs
		foreach($clubArray as $club => $club_value)
		{
			// Check if squad ID matches value of search
			if($clubID == $_POST['squad'])
			{
				// Get troop counts
				$troops_get = $conn->query("SELECT COUNT(total) FROM (SELECT event_sign_up.troopid AS total FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' AND ".getCostumeQueryValues($clubID)." GROUP BY event_sign_up.troopid) AS ABC");
				$troop_count = $troops_get->fetch_row();
				
				// Get charity counts
				$charity_get = $conn->query("SELECT SUM(total) FROM (SELECT events.charityDirectFunds AS total FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' AND ".getCostumeQueryValues($clubID)." GROUP BY event_sign_up.troopid) AS ABC");
				$charity_count = $charity_get->fetch_row();

				$charity_get2 = $conn->query("SELECT SUM(total) FROM (SELECT events.charityIndirectFunds AS total FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' AND ".getCostumeQueryValues($clubID)." GROUP BY event_sign_up.troopid) AS ABC");
				$charity_count2 = $charity_get2->fetch_row();
				
				// If active only set
				if(isset($_POST['activeonly']) && $_POST['activeonly'] == 1)
				{
					$query .= " WHERE (".$club_value['db']." = '1' OR ".$club_value['db']." = '2') AND troopers.id != ".placeholder." ";
				}
			}
			
			// Increment
			$clubID++;
		}
	}
	else if(isset($_POST['searchType']) && $_POST['searchType'] == "donations")
	{	
		// Format date start
		$date = strtotime(cleanInput($_POST['dateStart']));
		$dateF = date('Y-m-d H:i:s', $date);
		
		// Format date end
		$date = strtotime(cleanInput($_POST['dateEnd']));
		$dateE = date('Y-m-d H:i:s', $date);

		// Query for search
		$query = "SELECT events.id AS id, events.dateStart, events.dateEnd, events.name, events.charityDirectFunds, events.charityAddHours, events.charityDirectFunds, events.charityIndirectFunds, events.charityName, events.charityNote FROM events LEFT JOIN event_sign_up ON events.id = event_sign_up.troopid WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."'";
		
		// Get the squad search type
		// If All
		if($_POST['squad'] == 0)
		{
			// Get troop counts
			$troops_get = $conn->query("SELECT COUNT(id) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."'");
			$troop_count = $troops_get->fetch_row();
			
			// Get charity counts
			$charity_get = $conn->query("SELECT SUM(charityDirectFunds) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."'");
			$charity_count = $charity_get->fetch_row();

			$charity_get2 = $conn->query("SELECT SUM(charityIndirectFunds) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."'");
			$charity_count2 = $charity_get2->fetch_row();
		}
		
		// If 501st
		if(($_POST['squad'] >= 1 && $_POST['squad'] <= count($squadArray)))
		{
			// Add to query
			$query .= " AND squad = '".cleanInput($_POST['squad'])."'";
			
			// Get troop counts
			$troops_get = $conn->query("SELECT COUNT(id) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."' AND squad = '".cleanInput($_POST['squad'])."'");
			$troop_count = $troops_get->fetch_row();
			
			// Get charity counts
			$charity_get = $conn->query("SELECT SUM(charityDirectFunds) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."' AND squad = '".cleanInput($_POST['squad'])."'");
			$charity_count = $charity_get->fetch_row();

			$charity_get2 = $conn->query("SELECT SUM(charityIndirectFunds) FROM events WHERE dateStart >= '".$dateF."' AND dateEnd <= '".$dateE."' AND squad = '".cleanInput($_POST['squad'])."'");
			$charity_count2 = $charity_get2->fetch_row();
		}
		
		// Set up Squad ID
		$clubID = count($squadArray) + 1;
		
		// Loop through clubs
		foreach($clubArray as $club => $club_value)
		{
			// Check if squad ID matches value of search
			if($clubID == $_POST['squad'])
			{
				// Get troop counts
				$troops_get = $conn->query("SELECT COUNT(total) FROM (SELECT event_sign_up.troopid AS total FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' AND ".getCostumeQueryValues($clubID)." GROUP BY event_sign_up.troopid) AS ABC");
				$troop_count = $troops_get->fetch_row();
				
				// Get charity counts
				$charity_get = $conn->query("SELECT SUM(total) FROM (SELECT events.charityDirectFunds AS total FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' AND ".getCostumeQueryValues($clubID)." GROUP BY event_sign_up.troopid) AS ABC");
				$charity_count = $charity_get->fetch_row();

				$charity_get2 = $conn->query("SELECT SUM(total) FROM (SELECT events.charityIndirectFunds AS total FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' AND ".getCostumeQueryValues($clubID)." GROUP BY event_sign_up.troopid) AS ABC");
				$charity_count2 = $charity_get2->fetch_row();

				// Add to query
				$query .= " AND ".getCostumeQueryValues($clubID)."";
			}
			
			// Increment
			$clubID++;
		}

		$query .= " GROUP BY events.id";
	}
	else if(isset($_POST['searchType']) && $_POST['searchType'] == "costumecount")
	{	
		// Format date start
		$date = strtotime(cleanInput($_POST['dateStart']));
		$dateF = date('Y-m-d H:i:s', $date);
		
		// Format date end
		$date = strtotime(cleanInput($_POST['dateEnd']));
		$dateE = date('Y-m-d H:i:s', $date);

		// Loop through clubs
		foreach($_POST['costumes_choice_search_box'] as $costume)
		{
			echo '
			<h2 class="tm-section-header">'.getCostume($costume).'</h2>';

			// Get data
			$query = "SELECT (SELECT COUNT(event_sign_up.trooperid)) AS troopCount, troopers.name AS trooperName, troopers.tkid, troopers.squad, event_sign_up.trooperid, event_sign_up.troopid, event_sign_up.trooperid, event_sign_up.costume, event_sign_up.status, events.name AS eventName, events.id AS eventId, events.charityDirectFunds, events.charityIndirectFunds, events.dateStart, events.dateEnd, (TIMESTAMPDIFF(HOUR, events.dateStart, events.dateEnd) + events.charityAddHours) AS charityHours FROM events LEFT JOIN event_sign_up ON events.id = event_sign_up.troopid LEFT JOIN troopers ON troopers.id = event_sign_up.trooperid WHERE event_sign_up.costume = '".cleanInput($costume)."' AND status = 3 AND events.closed = '1' AND events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' GROUP BY event_sign_up.trooperid ORDER BY troopCount DESC";

			// Troop count
			$i = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					if($i == 0)
					{
						echo '
						<div style="overflow-x: auto;">
						<table border="1">
						<tr>
							<th>Trooper Name</th>	<th>Trooper TKID</th>	<th>Costume Troop Count</th>
						</tr>';
					}

					echo '
					<tr>
						<td><a href="index.php?profile='.$db->trooperid.'">'.$db->trooperName.'</a></td>	<td>'.readTKNumber($db->tkid, $db->squad).'</td>	<td>'.$db->troopCount.'</td>
					</tr>';

					$i++;
				}
			}

			if($i == 0)
			{
				echo '
				<p style="text-align: center;">
					No troops found.
				</p>';
			}

			if($i > 0)
			{
				echo '
				</table>
				</div>';
			}
		}
	}

	// Get our search type, and show certain fields
	// Regular search
	if(isset($_POST['searchType']) && $_POST['searchType'] == "regular")
	{
		// Get data
		$i = 0;
		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				if($i == 0)
				{
					echo '
					<div style="overflow-x: auto;">
					<table border="1">
					<tr>
						<th>Event Name</th>	<th>Date</th>	<th>Trooper TKID</th>	<th>Attended Costume</th>	<th>Status</th>
					</tr>';
				}
				
				$dateFormat = date('m-d-Y', strtotime($db->dateEnd));

				echo '
				<tr>
					<td><a href="index.php?event='.$db->eventId.'">'.$db->eventName.'</a></td>	<td>'.$dateFormat.'</td>';
					
					// Prevent search from showing blank trooper
					if(isset($db->trooperid))
					{
						echo '
						<td><a href="index.php?profile='.$db->trooperid.'">'.readTKNumber(getTKNumber($db->trooperid), getTrooperSquad($db->trooperid)).'</a></td>';
					}
					else
					{
						echo '
						<td>N/A</td>';
					}
					
					echo '
					<td>'.ifEmpty('<a href="index.php?action=costume&costumeid='.$db->costume.'">' . getCostume($db->costume) . '</a>', "N/A").'</td>	<td>'.getStatus($db->status).'</td>
				</tr>';

				$i++;
			}
		}
	}
	// Trooper search
	else if(isset($_POST['searchType']) && $_POST['searchType'] == "trooper")
	{
		// Get data
		$i = 0;
		
		// Trooper array
		$troopArray = array();
		
		// Format numbers to prevent errors - charity
		if(!isset($charity_count[0]))
		{
			$charity_count[0] = 0;
		}

		if(!isset($charity_count2[0]))
		{
			$charity_count2[0] = 0;
		}
		
		// Format numbers to prevent errors - troop
		if(!isset($troop_count[0]))
		{
			$troop_count[0] = 0;
		}
		
		// Start going through troopers
		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				// Show table
				if($i == 0)
				{
					echo '
					<p>
						Total Troops: '.$troop_count[0].'
					</p>
					
					<p>
						Direct Charity: $'.number_format($charity_count[0]).'
					</p>

					<p>
						Indirect Charity: $'.number_format($charity_count2[0]).'
					</p>
					
					<div style="overflow-x: auto;">
					<table border="1">
					<tr>
						<th>Trooper TKID</th>	<th>Troop Count</th>
					</tr>';
				}

				// Increment $i
				$i++;
				
				// If All
				if($_POST['squad'] == 0)
				{
					// Get troop counts - All
					$troops_get = $conn->query("SELECT COUNT(event_sign_up.id), events.id FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE event_sign_up.trooperid = '".$db->id."' AND events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' AND event_sign_up.status = '3' AND events.closed = '1'");
					$count = $troops_get->fetch_row();
				}
				
				// If 501st
				if(($_POST['squad'] >= 1 && $_POST['squad'] <= count($squadArray)))
				{
					// Get troop counts - 501st
					$troops_get = $conn->query("SELECT COUNT(event_sign_up.id), events.id FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE event_sign_up.trooperid = '".$db->id."' AND events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' AND ('0' = (SELECT costumes.club FROM costumes WHERE id = event_sign_up.costume) OR '5' = (SELECT costumes.club FROM costumes WHERE id = event_sign_up.costume) OR EXISTS(SELECT events.id FROM events WHERE events.id = event_sign_up.troopid))");

					$count = $troops_get->fetch_row();
				}
				
				// Set up Squad ID
				$clubID = count($squadArray) + 1;
				
				// Loop through clubs
				foreach($clubArray as $club => $club_value)
				{
					// Does club match
					if(getSquadName($_POST['squad']) == $club_value['name'])
					{
						// Get troop counts
						$troops_get = $conn->query("SELECT COUNT(event_sign_up.id), events.id FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE event_sign_up.status = '3' AND events.closed = '1' AND event_sign_up.trooperid = '".$db->id."' AND events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' AND ".getCostumeQueryValues($clubID)."");
						$count = $troops_get->fetch_row();
					}
					
					// Increment
					$clubID++;
				}
				
				// Create an array of our count
				$tempArray = array($db->tkid, $count[0], $db->name, $db->id, $db->squad);
				
				// Push to main array
				array_push($troopArray, $tempArray);
			}
		}
		
		// Sort array for display
		$keys = array_column($troopArray, 1);
		array_multisort($keys, SORT_DESC, $troopArray);

		// Loop through array
		foreach($troopArray as $value)
		{
			// Display
			echo '
			<tr>
				<td><a href="index.php?profile='.$value[3].'">'.readTKNumber($value[0], $value[4]).'</a> - '.$value[2].'</td>	<td>'.$value[1].'</td>
			</tr>';
		}
	}
	// Donation search
	else if(isset($_POST['searchType']) && $_POST['searchType'] == "donations")
	{
		// Get data
		$i = 0;
		
		// Troop array
		$troopArray = array();

		// Format numbers to prevent errors - charity
		if(!isset($charity_count[0]))
		{
			$charity_count[0] = 0;
		}

		if(!isset($charity_count2[0]))
		{
			$charity_count2[0] = 0;
		}
		
		// Format numbers to prevent errors - troop
		if(!isset($troop_count[0]))
		{
			$troop_count[0] = 0;
		}
		
		// Start going through troopers
		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				// Show table
				if($i == 0)
				{
					echo '
					<p>
						Total Troops: '.$troop_count[0].'
					</p>
					
					<p>
						Direct Charity: $'.number_format($charity_count[0]).'
					</p>

					<p>
						Indirect Charity: $'.number_format($charity_count2[0]).'
					</p>
					
					<div style="overflow-x: auto;">
					<table border="1">
					<tr>
						<th>Event</th>	<th>Direct</th>	<th>Indirect</th>	<th>Charity Name</th>	<th>Hours</th>	<th>Notes</th>
					</tr>';
				}

				// Increment $i
				$i++;
				
				// If All
				if($_POST['squad'] == 0)
				{
					// Get troop counts - All
					$troops_get = $conn->query("SELECT COUNT(event_sign_up.id), events.id FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE event_sign_up.trooperid = '".$db->id."' AND events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."'");
					$count = $troops_get->fetch_row();
				}
				
				// If 501st
				if(($_POST['squad'] >= 1 && $_POST['squad'] <= count($squadArray)))
				{
					// Get troop counts - 501st
					$troops_get = $conn->query("SELECT COUNT(event_sign_up.id), events.id FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE event_sign_up.trooperid = '".$db->id."' AND events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' AND ('0' = (SELECT costumes.club FROM costumes WHERE id = event_sign_up.costume) OR '5' = (SELECT costumes.club FROM costumes WHERE id = event_sign_up.costume) OR EXISTS(SELECT events.id FROM events WHERE events.id = event_sign_up.troopid))");
					$count = $troops_get->fetch_row();
				}
				
				// Set up Squad ID
				$clubID = count($squadArray) + 1;
				
				// Loop through clubs
				foreach($clubArray as $club => $club_value)
				{
					// Does club match
					if(getSquadName($_POST['squad']) == $club_value['name'])
					{
						// Get troop counts
						$troops_get = $conn->query("SELECT COUNT(event_sign_up.id), events.id FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE event_sign_up.trooperid = '".$db->id."' AND events.dateStart >= '".$dateF."' AND events.dateEnd <= '".$dateE."' AND ".getCostumeQueryValues($clubID)."");
						$count = $troops_get->fetch_row();
					}
					
					// Increment
					$clubID++;
				}

				$hours = timeBetweenDates($db->dateStart, $db->dateEnd);
				$hours += intval($db->charityAddHours);
				
				// Create an array of our count
				$tempArray = array($db->id, $db->name, $db->charityDirectFunds, $db->charityIndirectFunds, $db->charityName, $hours, $db->charityNote);
				
				// Push to main array
				array_push($troopArray, $tempArray);
			}
		}
		
		// Sort array for display
		$keys = array_column($troopArray, 1);
		array_multisort($keys, SORT_DESC, $troopArray);

		// Loop through array
		foreach($troopArray as $value)
		{
			// Display
			echo '
			<tr>
				<td><a href="index.php?event='.$value[0].'">'.$value[1].'</a></td>	<td>$'.number_format($value[2]).'</td>	<td>$'.number_format($value[3]).'</td>	<td>'.ifEmpty($value[4], "N/A").'</td>	<td>'.number_format($value[5]).'</td>	<td>'.ifEmpty(nl2br($value[6]), "N/A").'</td>
			</tr>';
		}
	}

	// Don't use for this search type
	if(isset($_POST['searchType']) && $_POST['searchType'] != "costumecount")
	{
		// What to do if we have more than one field
		if($i > 0)
		{
			echo '
			</table>
			</div>';
		}
		else
		{
			// Nothing to show
			echo '
			<p style="text-align: center;">
				<b>No results</b>
			</p>';
		}
	}
}

// Show the troop tracker page
if(isset($_GET['action']) && $_GET['action'] == "trooptracker")
{
	// If logged in
	if(loggedIn())
	{
		echo '
		<h2 class="tm-section-header">My Stats</h2>
		
		<p style="text-align: center;">
			<a href="#/" class="button" id="showstats" name="showstats">Show My Stats</a> 
			<a href="index.php?profile='.$_SESSION['id'].'" class="button">View My Profile</a>
		</p>

		<p style="text-align: center;">
			<a href="#/" class="button" id="show-top-troops">Top Troopers</a> 
		</p>

		<div class="top-troops" id="top-troops">
			<h2 class="tm-section-header">Top Troopers</h2>

			<h4><u>All Time</u></h4>

			<ol>';

			$query = "SELECT trooperid, COUNT(trooperid) AS total FROM event_sign_up LEFT JOIN events ON event_sign_up.trooperid = events.id WHERE event_sign_up.trooperid != ".placeholder." AND events.closed = '1' AND event_sign_up.status = '3' GROUP BY trooperid ORDER BY total DESC LIMIT 25";

			$i = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					echo '<li><a href="index.php?profile='.$db->trooperid.'">' . getName($db->trooperid) . ' - '.$db->total.'</a></li>';

					$i++;
				}
			}

			// If none to display
			if($i == 0)
			{
				echo '<li>Nothing to display.</li>';
			}
			
			echo '
			</ol>

			<h4><u>Last 365 Days</u></h4>

			<ol>';

			$query = "SELECT trooperid, COUNT(trooperid) AS total FROM event_sign_up LEFT JOIN events ON event_sign_up.troopid = events.id WHERE events.dateEnd > NOW() - INTERVAL 365 DAY AND event_sign_up.trooperid != ".placeholder." AND events.closed = '1' AND event_sign_up.status = '3' GROUP BY trooperid ORDER BY total DESC LIMIT 25";

			$i = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					echo '<li><a href="index.php?profile='.$db->trooperid.'">' . getName($db->trooperid) . ' - '.$db->total.'</a></li>';

					$i++;
				}
			}

			// If none to display
			if($i == 0)
			{
				echo '<li>Nothing to display.</li>';
			}
			
			echo '
			</ol>

			<h4><u>Last 30 Days</u></h4>

			<ol>';

			$query = "SELECT trooperid, COUNT(trooperid) AS total FROM event_sign_up LEFT JOIN events ON event_sign_up.troopid = events.id WHERE events.dateEnd > NOW() - INTERVAL 30 DAY AND event_sign_up.trooperid != ".placeholder." AND events.closed = '1' AND event_sign_up.status = '3' GROUP BY trooperid ORDER BY total DESC LIMIT 25";

			$i = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					echo '<li><a href="index.php?profile='.$db->trooperid.'">' . getName($db->trooperid) . ' - '.$db->total.'</a></li>';

					$i++;
				}
			}

			// If none to display
			if($i == 0)
			{
				echo '<li>Nothing to display.</li>';
			}
			
			echo '
			</ol>
		</div>

		<div id="mystats" name="mystats" style="display: none;">';

		// Get data
		$query = "SELECT event_sign_up.trooperid, event_sign_up.troopid, event_sign_up.costume, event_sign_up.status, events.name AS eventName, events.id AS eventId, events.charityDirectFunds, events.charityIndirectFunds, events.dateStart, events.dateEnd, (TIMESTAMPDIFF(HOUR, events.dateStart, events.dateEnd) + events.charityAddHours) AS charityHours FROM events LEFT JOIN event_sign_up ON events.id = event_sign_up.troopid WHERE event_sign_up.trooperid = '".$_SESSION['id']."' AND status = 3 AND events.closed = '1' ORDER BY events.dateEnd DESC";

		// Troop count
		$i = 0;

		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				if($i == 0)
				{
					echo getTroopCounts(cleanInput($_SESSION['id'])) . '
					<div style="overflow-x: auto;">
					<table border="1">
					<tr>
						<th>Troop</th>	<th>Costume</th>	<th>Charity</th>
					</tr>';
				}
				
				// Set add to title if linked event
				$add = "";
				
				// If linked event
				if(isLink($db->eventId) > 0)
				{
					$add = "[<b>" . date("l", strtotime($db->dateStart)) . "</b> : ".date("m/d - h:i A", strtotime($db->dateStart))." - ".date("h:i A", strtotime($db->dateEnd))."] ";
				}

				echo '
				<tr>
					<td><a href="index.php?event='.$db->eventId.'">'.$add.''.$db->eventName.'</a></td>	<td>'.ifEmpty(getCostume($db->costume), "N/A").'</td>	<td>Direct: $'.number_format($db->charityDirectFunds).'<br />Indirect: $'.number_format($db->charityIndirectFunds).'<br />Hours: '.$db->charityHours.'</td>
				</tr>';

				// Increment troop count
				$i++;
			}
		}

		if($i > 0)
		{
			echo '
			</table>
			</div>';
		}
		else
		{
			// No troops attended
			echo '
			<p><b>You have not attended any troops. Get out there and troop!</b></p>';
		}
		
		echo '
		</div>';
	}

	// Show troop tracker for everyone
	echo '
	<h2 class="tm-section-header">Troop Tracker</h2>';

	// If squad is not set
	if(!isset($_GET['squad']))
	{
		// Get data
		$query = "SELECT event_sign_up.trooperid, event_sign_up.troopid, event_sign_up.costume, event_sign_up.status, events.name AS eventName, events.id AS eventId, events.charityDirectFunds, events.charityIndirectFunds, events.dateStart, events.dateEnd, (TIMESTAMPDIFF(HOUR, events.dateStart, events.dateEnd) + events.charityAddHours) AS charityHours FROM events LEFT JOIN event_sign_up ON events.id = event_sign_up.troopid WHERE events.closed = '1' AND events.dateEnd > CURRENT_DATE - INTERVAL 60 DAY GROUP BY events.id ORDER BY events.dateEnd DESC LIMIT 20";
	}
	else
	{
		// Set up query for specific squad
		$add = "";
		$add2 = "";
		
		// Check if squad is not all
		if($_GET['squad'] != 0)
		{
			// If is a squad, add to query
			$add = "WHERE squad = '".cleanInput($_GET['squad'])."' AND closed = 1";
			$add2 = "AND events.squad = '".cleanInput($_GET['squad'])."'";
		}
		
		// Set results per page
		$results = 20;
		
		// Get total results - query
		$sql = "SELECT COUNT(id) AS total FROM events ".$add.""; 
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		
		// Set total pages
		$total_pages = ceil($row["total"] / $results);
		
		// If page set
		if(isset($_GET['page']))
		{
			// Get page
			$page = cleanInput($_GET['page']);
			
			// Start from
			$startFrom = ($page - 1) * $results;
		}
		else
		{
			// Default page
			$page = 1;
			
			// Start from - default
			$startFrom = ($page - 1) * $results;
		}
		
		// Squad is set, show only that data
		$query = "SELECT event_sign_up.trooperid, event_sign_up.troopid, event_sign_up.costume, event_sign_up.status, events.name AS eventName, events.id AS eventId, events.charityDirectFunds, events.charityIndirectFunds, events.dateStart, events.dateEnd, (TIMESTAMPDIFF(HOUR, events.dateStart, events.dateEnd) + events.charityAddHours) AS charityHours FROM events LEFT JOIN event_sign_up ON events.id = event_sign_up.troopid WHERE events.closed = '1' ".$add2." GROUP BY events.id ORDER BY events.dateEnd DESC LIMIT ".$startFrom.", ".$results."";
	}

	// Query count
	$i = 0;
	
	// Total time spent
	$timeSpent = 0;
	
	// Query
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			if($i == 0)
			{
				// Get squad for squadLink function
				if(isset($_GET['squad']))
				{
					$squadLink = $_GET['squad'];
				}
				else
				{
					$squadLink = -1;
				}

				echo '
				<p style="text-align: center;">
					'.displaySquadLinks($squadLink).'
				</p>

				<div style="overflow-x: auto;">
				<table border="1">
				<tr>
					<th>Troop</th>	<th>Troopers Attended</th>	<th>Charity</th>
				</tr>';
			}

			// How many troopers attended
			$trooperCount_get = $conn->query("SELECT COUNT(*) FROM event_sign_up WHERE troopid = '".$db->troopid."' AND status = '3'");
			
			$count = $trooperCount_get->fetch_row();
			
			// Set up text for linked event
			$add = "";
			
			// If linked event
			if(isLink($db->eventId) > 0)
			{
				$add = "[<b>" . date("l", strtotime($db->dateStart)) . "</b> : ".date("m/d - h:i A", strtotime($db->dateStart))." - ".date("h:i A", strtotime($db->dateEnd))."] ";
			}

			echo '
			<tr>
				<td><a href="index.php?event='.$db->eventId.'">'.$add.''.$db->eventName.'</a></td>	<td>'.$count[0].'</td>	<td>Direct: $'.number_format($db->charityDirectFunds).'<br />Indirect: $'.number_format($db->charityIndirectFunds).'<br />Hours: '.number_format($db->charityHours).'</td>
			</tr>';

			$i++;
		}
	}

	if($i > 0)
	{
		// How many troops did the user attend
		$favoriteCostume_get = $conn->query("SELECT costume, COUNT(*) FROM event_sign_up WHERE costume != 706 AND costume != 720 AND costume != 721 GROUP BY costume ORDER BY COUNT(costume) DESC LIMIT 1");
		$favoriteCostume = mysqli_fetch_array($favoriteCostume_get);

		// Prevent notice error
		if($favoriteCostume == "")
		{
			$favoriteCostume['costume'] = 0;
		}

		// How many troops did the user attend
		$attended_get = $conn->query("SELECT COUNT(*) FROM event_sign_up WHERE status = '3'");
		$count1 = $attended_get->fetch_row();
		// How many regular troops
		$regular_get = $conn->query("SELECT COUNT(*) FROM events WHERE label = '0'");
		$count2 = $regular_get->fetch_row();
 		// How many armor party troops
		$armorparty_get = $conn->query("SELECT COUNT(*) FROM events WHERE label = '10'");
		$count13 = $armorparty_get->fetch_row();
 		// How many PR troops
		$charity_get = $conn->query("SELECT COUNT(*) FROM events WHERE label = '1'");
		$count3 = $charity_get->fetch_row();
		// How many Disney troops
		$pr_get = $conn->query("SELECT COUNT(*) FROM events WHERE label = '2'");
		$count4 = $pr_get->fetch_row();
		// How many convention troops
		$disney_get = $conn->query("SELECT COUNT(*) FROM events WHERE label = '3'");
		$count5 = $disney_get->fetch_row();
		// How many hospital troops
		$hospital_get = $conn->query("SELECT COUNT(*) FROM events WHERE label = '9'");
		$count12 = $hospital_get->fetch_row();
		// How many wedding troops
		$convention_get = $conn->query("SELECT COUNT(*) FROM events WHERE label = '4'");
		$count6 = $convention_get->fetch_row();
		// How many birthday party troops
		$wedding_get = $conn->query("SELECT COUNT(*) FROM events WHERE label = '5'");
		$count7 = $wedding_get->fetch_row();
		// How many wedding troops
		$birthday_get = $conn->query("SELECT COUNT(*) FROM events WHERE label = '6'");
		$count8 = $birthday_get->fetch_row();
		// How many virtual troops
		$virtual_get = $conn->query("SELECT COUNT(*) FROM events WHERE label = '7'");
		$count9 = $virtual_get->fetch_row();
		// How many other troops
		$other_get = $conn->query("SELECT COUNT(*) FROM events WHERE label = '8'");
		$count10 = $other_get->fetch_row();
		// How many total troops
		$total_get = $conn->query("SELECT COUNT(*) FROM events WHERE closed = '1'");
		$count11 = $total_get->fetch_row();
		// How much total money was raised
		$money_get = $conn->query("SELECT SUM(charityDirectFunds) FROM events WHERE closed = '1'");
		$countMoney = $money_get->fetch_row();
		$money_get2 = $conn->query("SELECT SUM(charityIndirectFunds) FROM events WHERE closed = '1'");
		$countMoney2 = $money_get2->fetch_row();
 
		echo '
		</table>
		</div>';
		
		// If squad is set
		if(isset($_GET['squad']))
		{
			echo '
			<p style="margin-left: 100px; text-align: right;">';
			
			// Loop through pages
			for ($i = 1; $i <= $total_pages; $i++)
			{
				// If we are on this page...
				if($page == $i)
				{
					echo '
					'.$i.'';
				}
				else
				{
					echo '
					<a href="index.php?action=trooptracker&squad='.cleanInput($_GET['squad']).'&page='.$i.'">'.$i.'</a>';
				}
				
				// If not that last page, add a comma
				if($i != $total_pages)
				{
					echo ', ';
				}
			}
			
			echo '
			</p>';
		}
		
		// If squad is not set
		if(!isset($_GET['squad']))
		{
			echo '
			<p><b>Favorite Costume:</b> '.ifEmpty(getCostume($favoriteCostume['costume']), "N/A").'</p>
			<p><b>Volunteers at Troops:</b> '.number_format($count1[0]).'</p>
			<p><b>Direct Donations Raised:</b> $'.number_format($countMoney[0]).'</p>
			<p><b>Indirect Donations Raised:</b> $'.number_format($countMoney2[0]).'</p>
			<p><b>Regular Troops:</b> '.number_format($count2[0]).'</p>
			<p><b>Armor Parties:</b> '.number_format($count13[0]).'</p>
			<p><b>Charity Troops:</b> '.number_format($count3[0]).'</p>
			<p><b>PR Troops:</b> '.number_format($count4[0]).'</p>
			<p><b>Disney Troops:</b> '.number_format($count5[0]).'</p>
			<p><b>Convention Troops:</b> '.number_format($count6[0]).'</p>
			<p><b>Hospital Troops:</b> '.number_format($count12[0]).'</p>
			<p><b>Wedding Troops:</b> '.number_format($count7[0]).'</p>
			<p><b>Birthday Troops:</b> '.number_format($count8[0]).'</p>
			<p><b>Virtual Troops:</b> '.number_format($count9[0]).'</p>
			<p><b>Other Troops:</b> '.number_format($count10[0]).'</p>
			<p><b>Total Finished Troops:</b> '.number_format($count11[0]).'</p>';
		}
	}
	else
	{
		// No troops attended
		echo '
		<p><b>No one has attended a troop recently!</b></p>';
	}

	echo '
	<hr />
	<h2 class="tm-section-header">Search</h2>
	<div name="searchForm" id="searchForm">
		<form action="index.php?action=search" method="POST">
			<div id="searchNameDiv">
			Search Troop Name: <input type="text" name="searchName" id="searchName" />
			<br /><br />
			Search Trooper Name: <input type="text" name="searchTrooperName" id="searchTrooperName" />
			<br /><br />
			</div>
			Date Start: <input type="text" name="dateStart" id="datepicker3" />
			<br /><br />
			Date End: <input type="text" name="dateEnd" id="datepicker4" />
			<br /><br />
			<div id="tkIDDiv">
			Search TKID: <input type="text" name="tkID" id="tkID" />
			<br /><br />
			</div>
			Search Type:
			<br />
			<input type="radio" name="searchType" value="regular" CHECKED />Default
			<br />
			<input type="radio" name="searchType" value="trooper" />Troop Count Per Trooper
			<br />
			<input type="radio" name="searchType" value="donations" />Donation Count Per Event
			<br />
			<input type="radio" name="searchType" value="costumecount" />Costume Count Per Trooper
			<br /><br />
			<div id="trooper_count_radio" style="display: none;">
				<select name="squad" id="squad">
					<option value="0" SELECTED>All</option>
					'.squadSelectList().'
				</select>
				<br /><br />
				<span name="activeRadios">
				<input type="checkbox" name="activeonly" value="1" /> Active Members Only?
				<br /><br />
				</span>
			</div>
			<div id="costumes_choice_search" style="display: none;">
				<select multiple style="height: 500px;" id="costumes_choice_search_box" name="costumes_choice_search_box[]">';
				
				$query = "SELECT costumes.id AS id, costumes.costume FROM costumes ORDER BY costume";

				if ($result = mysqli_query($conn, $query))
				{
					while ($db = mysqli_fetch_object($result))
					{
						echo '
						<option value="'.$db->id.'">'.$db->costume .'</option>';
					}
				}
				
				echo '
				</select>
			</div>
			<input type="submit" name="submitSearch" id="submitSearch" value="Search!" />
		</form>
	</div>';
}

// Show the command staff page
if(isset($_GET['action']) && $_GET['action'] == "commandstaff")
{
	// If the user is logged in and is an admin
	if(loggedIn() && isAdmin())
	{
		$getTrooperNotifications = $conn->query("SELECT id FROM troopers WHERE approved = '0'");

		echo '
		<h2 class="tm-section-header">Command Staff Welcome Area</h2>

		<p>
			<a href="index.php?action=commandstaff&do=createevent" class="button">Create an Event</a> 
			<a href="index.php?action=commandstaff&do=editevent" class="button">Edit an Event</a> 
			<a href="index.php?action=commandstaff&do=roster" class="button">Roster</a> 
			<a href="index.php?action=commandstaff&do=notifications" class="button">Notifications</a> 
			<a href="index.php?action=commandstaff&do=approvetroopers" class="button" id="trooperRequestButton" name="trooperRequestButton">Approve Trooper Requests - ('.$getTrooperNotifications->num_rows.')</a> ';
			
			if(hasPermission(1))
			{
				echo ' 
				<a href="index.php?action=commandstaff&do=managecostumes" class="button">Costume Management</a> 
				<a href="index.php?action=commandstaff&do=managetroopers" class="button">Trooper Management</a> 
				<a href="index.php?action=commandstaff&do=assignawards" class="button">Award Management</a>
				<a href="index.php?action=commandstaff&do=assigntitles" class="button">Title Management</a>
				<a href="index.php?action=commandstaff&do=stats" class="button">Statistics</a>
				<a href="index.php?action=commandstaff&do=sitesettings" class="button">Site Settings</a>';
			}

			// If have special permission for trooper management
			if(hasSpecialPermission("spTrooper"))
			{
				echo '<a href="index.php?action=commandstaff&do=managetroopers" class="button">Trooper Management</a> ';
			}

			// If have special permission for costume management
			if(hasSpecialPermission("spCostume"))
			{
				echo '<a href="index.php?action=commandstaff&do=managecostumes" class="button">Costume Management</a> ';
			}

			// If have special permission for award management
			if(hasSpecialPermission("spAward"))
			{
				echo '<a href="index.php?action=commandstaff&do=assignawards" class="button">Award Management</a> ';
			}
			
		echo '
		</p>';
		
		/**************************** Site Settings *********************************/
		
		if(isset($_GET['do']) && $_GET['do'] == "sitesettings")
		{
			echo '
			<h3>Site Settings</h3>';
			
			// Get data
			$query = "SELECT * FROM settings LIMIT 1";
			$i = 0;
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					echo '
					<form action="process.php?do=changesettings" method="POST" name="changeSettingsForm" id="changeSettingsForm">';
					
					// If site closed, show button
					if($db->siteclosed == 0)
					{
						// Close website button
						echo '
						<input type="submit" name="submitCloseSite" id="submitCloseSite" value="Close Website" />';
					}
					else
					{
						// Open website button
						echo '
						<input type="submit" name="submitCloseSite" id="submitCloseSite" value="Open Website" />';
					}
					
					// If sign up closed, show button
					if($db->signupclosed == 0)
					{
						// Close sign up button
						echo '
						<input type="submit" name="submitCloseSignUps" id="submitCloseSignUps" value="Close Sign Ups" />';
					}
					else
					{
						// Open sign up button
						echo '
						<input type="submit" name="submitCloseSignUps" id="submitCloseSignUps" value="Open Sign Ups" />';
					}
					
					// Change donation support
					echo '
					<input type="submit" name="submitSupportGoal" id="submitSupportGoal" value="Change Support Goal" />
					
					<div id="settingsEditArea" name="settingsEditArea"></div>';
						
					echo '
					</form>';
					
					$i++;
				}
			}
			
			if($i == 0)
			{
				echo '<p>ERROR: Settings not correctly set. Check database.</p>';
			}
		}

		/**************************** Trooper Check *********************************/
		
		if(isset($_GET['do']) && $_GET['do'] == "stats")
		{
			// Set up count
			$squadCount = 1;
			
			// Loop through squads
			foreach($squadArray as $squad => $squad_value)
			{
				// Set up name
				$squadName = str_replace(" ", "", $squad_value['name']);
				
				// Set variable
				${"totalAccountsSetUp" . $squadName} = $conn->query("SELECT id FROM troopers WHERE password != '' AND approved = '1' AND squad = ".$squadCount."");
				
				// Increment
				$squadCount++;
			}
			
			// Set up count
			$clubCount = count($squadArray) + 1;
			
			// Loop through clubs
			foreach($clubArray as $club => $club_value)
			{
				// Set up name
				$clubName = str_replace(" ", "", $club_value['name']);
				
				// Set variable
				${"totalAccountsSetUp" . $clubName} = $conn->query("SELECT id FROM troopers WHERE password != '' AND approved = '1' AND squad = ".$clubCount."");
				
				// Count active members
				${"totalActive" . $clubName} = $conn->query("SELECT id FROM troopers WHERE ".$club_value['db']." = '1'");
				
				// Count reserve members
				${"totalReserve" . $clubName} = $conn->query("SELECT id FROM troopers WHERE ".$club_value['db']." = '2'");
				
				// Count retired members
				${"totalRetired" . $clubName} = $conn->query("SELECT id FROM troopers WHERE ".$club_value['db']." = '3'");
				
				// Increment
				$clubCount++;
			}
			
			// Count number of users with set up accounts - 501
			$totalAccountsSetUp501 = $conn->query("SELECT id FROM troopers WHERE password != '' AND approved = '1' AND squad <= ".count($squadArray)."");
			
			// Count number of users with set up accounts (TOTAL)
			$totalAccountsSetUp = $conn->query("SELECT id FROM troopers WHERE password != '' AND approved = '1'");

			// Total number of accounts
			$totalAccounts = $conn->query("SELECT id FROM troopers");

			// Total accounts not set up
			$totalNotSet = $totalAccounts->num_rows - $totalAccountsSetUp->num_rows;
			
			// Count active members - 501
			$totalActive501 = $conn->query("SELECT id FROM troopers WHERE p501 = '1'");
			
			// Count reserve members - 501
			$totalReserve501 = $conn->query("SELECT id FROM troopers WHERE p501 = '2'");
			
			// Count retired members - 501
			$totalRetired501 = $conn->query("SELECT id FROM troopers WHERE p501 = '3'");

			echo '
			<h2>Important People</h2>
			<h3>Super Admin</h3>
			<div class="stat-container">';

			// Show all super admins
			$query = "SELECT troopers.id, troopers.name, troopers.tkid, troopers.squad FROM troopers WHERE (permissions = '1') ORDER BY name";

			// Trooper count set up
			$i = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					echo '
					<div class="title">

					<a href="index.php?profile='.$db->id.'" target="_blank">'.$db->name.' - '.readTKNumber($db->tkid, $db->squad).'</a>';

					// Show titles
					$query2 = "SELECT titles.title FROM title_troopers LEFT JOIN titles ON title_troopers.titleid = titles.id WHERE title_troopers.trooperid = '".$db->id."'";

					if ($result2 = mysqli_query($conn, $query2))
					{
						while ($db2 = mysqli_fetch_object($result2))
						{
							echo '
							<p>'.$db2->title.'</p>';
						}
					}

					echo '
					</div>';
					
					// Increment
					$i++;
				}
			}
			
			// No troopers
			if($i == 0)
			{
				echo '<p>No troopers to display.</p>';
			}

			echo '
			</div>

			<h3>Moderator</h3>
			<div class="stat-container">';

			// Show all super admins
			$query = "SELECT troopers.id, troopers.name, troopers.tkid, troopers.squad FROM troopers WHERE (permissions = '2') ORDER BY name";

			// Trooper count set up
			$i = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					echo '
					<div class="title">

					<a href="index.php?profile='.$db->id.'" target="_blank">'.$db->name.' - '.readTKNumber($db->tkid, $db->squad).'</a>';

					// Show titles
					$query2 = "SELECT titles.title FROM title_troopers LEFT JOIN titles ON title_troopers.titleid = titles.id WHERE title_troopers.trooperid = '".$db->id."'";

					if ($result2 = mysqli_query($conn, $query2))
					{
						while ($db2 = mysqli_fetch_object($result2))
						{
							echo '
							<p>'.$db2->title.'</p>';
						}
					}

					echo '
					</div>';
					
					// Increment
					$i++;
				}
			}
			
			// No troopers
			if($i == 0)
			{
				echo '<p>No troopers to display.</p>';
			}

			echo '
			</div>
			
			<h3>Other</h3>
			<div class="stat-container">';

			// Show all super admins
			$query = "SELECT troopers.id, troopers.name, troopers.tkid, troopers.squad FROM troopers LEFT JOIN title_troopers ON troopers.id = title_troopers.trooperid WHERE (troopers.permissions != 1 AND troopers.permissions != 2) AND title_troopers.trooperid = troopers.id ORDER BY name";

			// Trooper count set up
			$i = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					echo '
					<div class="title">

					<a href="index.php?profile='.$db->id.'" target="_blank">'.$db->name.' - '.readTKNumber($db->tkid, $db->squad).'</a>';

					// Show titles
					$query2 = "SELECT titles.title FROM title_troopers LEFT JOIN titles ON title_troopers.titleid = titles.id WHERE title_troopers.trooperid = '".$db->id."'";

					if ($result2 = mysqli_query($conn, $query2))
					{
						while ($db2 = mysqli_fetch_object($result2))
						{
							echo '
							<p>'.$db2->title.'</p>';
						}
					}

					echo '
					</div>';
					
					// Increment
					$i++;
				}
			}
			
			// No troopers
			if($i == 0)
			{
				echo '<p>No troopers to display.</p>';
			}

			echo '
			</div>
			
			<h2>Statistics</h2>';

			// Show all troopers with titles
			$query = "SELECT * FROM settings";

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					echo '
					<p>
						<b>501st Sync Date:</b> '.$db->syncdate.'
					</p>
					<p>
						<b>Rebel Legion Sync Date:</b> '.$db->syncdaterebels.'
					</p>';
				}
			}

			echo '
			<h3>Troop Tracker Usage</h3>

			<p><b>501st Total Accounts (Set Up):</b> '.number_format($totalAccountsSetUp501->num_rows).'</p>';
			
			// Loop through squads
			foreach($squadArray as $squad => $squad_value)
			{
				// Set up name
				$squadName = str_replace(" ", "", $squad_value['name']);
				
				echo '
				<p><b>'.$squad_value['name'].' Total Accounts (Set Up):</b> '.number_format(${"totalAccountsSetUp" . $squadName}->num_rows).'</p>';
				
				// Set variable
				${"totalAccountsSetUp" . $squadName} = $conn->query("SELECT id FROM troopers WHERE password != '' AND approved = '1' AND squad = ".$squadCount."");
			}
			
			echo '
			<br />';
			
			// Loop through clubs
			foreach($clubArray as $club => $club_value)
			{
				// Set up name
				$clubName = str_replace(" ", "", $club_value['name']);
				
				echo '
				<p><b>'.$club_value['name'].' Total Accounts (Set Up):</b> ' . number_format(${"totalAccountsSetUp" . $clubName}->num_rows) . '</p>';
			}
			
			echo '
			<br />
			
			<p><b>Total Accounts (Set Up):</b> '.number_format($totalAccountsSetUp->num_rows).'</p>
			<p><b>Total Accounts (Not Set Up):</b> '.number_format($totalNotSet).'</p>
			<p><b>Total Accounts:</b> '.number_format($totalAccounts->num_rows).'</p>
			
			<br />
			
			<h3>Active/Reserve/Retired Members</h3>
			
			<p><b>501st Total Accounts (Active):</b> '.number_format($totalActive501->num_rows).'</p>
			<p><b>501st Total Accounts (Reserve):</b> '.number_format($totalReserve501->num_rows).'</p>
			<p><b>501st Total Accounts (Retired):</b> '.number_format($totalRetired501->num_rows).'</p>
			
			<br />';
			
			// Loop through clubs
			foreach($clubArray as $club => $club_value)
			{
				// Set up name
				$clubName = str_replace(" ", "", $club_value['name']);
				
				echo '
				<p><b>'.$club_value['name'].' Total Accounts (Active):</b> ' . number_format(${"totalActive" . $clubName}->num_rows) . '</p>
				<p><b>'.$club_value['name'].' Total Accounts (Reserve):</b> ' . number_format(${"totalReserve" . $clubName}->num_rows) . '</p>
				<p><b>'.$club_value['name'].' Total Accounts (Retired):</b> ' . number_format(${"totalRetired" . $clubName}->num_rows) . '</p>
				
				<br />';
			}
		}

		/**************************** Roster - Trooper Confirmation *********************************/
		
		if(isset($_GET['do']) && $_GET['do'] == "trooperconfirmation" && isAdmin())
		{
			echo '
			<h3>Trooper Confirmation</h3>
			
			<p>
				<i>The following troopers have not confirmed a troop.</i>
			</p>';
			
			// Squad count
			$i = 1;
			
			echo '
			<a href="index.php?action=commandstaff&do=trooperconfirmation" class="button">All</a>';

			foreach($squadArray as $squad => $squad_value)
			{
				if(getSquadID($_SESSION['id']) == $i || hasPermission(1))
				{
					echo '<a href="index.php?action=commandstaff&do=trooperconfirmation&squad='.$i.'" class="button">' . $squad_value['name'] . '</a> ';
				}

				$i++;
			}
			
			foreach($clubArray as $club => $club_value)
			{
				if(isClubMember($club_value['db']) > 0 || hasPermission(1))
				{
					echo '<a href="index.php?action=commandstaff&do=trooperconfirmation&squad='.$i.'" class="button">' . $club_value['name'] . '</a> ';
				}

				$i++;
			}
			
			echo '<br /><hr />';
			
			// Set up
			$queryAdd = "";
			
			// Check if squad is requested
			if(isset($_GET['squad']))
			{
				// Which club to get
				if($_GET['squad'] <= count($squadArray))
				{
					$queryAdd .= "troopers.squad = '".cleanInput($_GET['squad'])."' AND";

					// Check if a member of club
					if(getSquadID($_SESSION['id']) != cleanInput($_GET['squad']) && hasPermission(2))
					{
						die("<p>Not a member of this squad / club.</p>");
					}
				}
				
				// Set up count
				$clubCount = count($squadArray) + 1;
				
				// Loop through clubs
				foreach($clubArray as $club => $club_value)
				{
					// Match
					if($_GET['squad'] == $clubCount)
					{
						$queryAdd .= "(troopers.".$club_value['db']." > 0) AND";

						// Check if a member of club
						if(isClubMember($club_value['db']) == 0 && hasPermission(2))
						{
							die("<p>Not a member of this squad / club.</p>");
						}
					}
					
					// Increment
					$clubCount++;
				}
			}
			
			// Query database
			$query = "SELECT events.id AS eventId, events.name, events.dateStart, events.dateEnd, event_sign_up.id AS signupId, event_sign_up.troopid, event_sign_up.trooperid, event_sign_up.status, event_sign_up.costume, troopers.name AS trooperName FROM events LEFT JOIN event_sign_up ON event_sign_up.troopid = events.id LEFT JOIN troopers ON event_sign_up.trooperid = troopers.id WHERE ".$queryAdd." events.dateEnd < NOW() AND event_sign_up.status < 3 AND events.closed = 1 AND troopers.id != 0 ORDER BY troopers.name";
			
			// Query count
			$i = 0;

			// Set trooper name
			$trooperName = "";
			
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Set up string to add to title if a linked event
					$add = "";
					
					// If this a linked event?
					if(isLink($db->eventId) > 0)
					{
						$add .= "[<b>" . date("l", strtotime($db->dateStart)) . "</b> : <i>" . date("m/d - h:i A", strtotime($db->dateStart)) . " - " . date("h:i A", strtotime($db->dateEnd)) . "</i>] ";
					}

					// Check if this is a different trooper
					if($trooperName != $db->trooperName)
					{
						// If not first
						if($i != 0)
						{
							echo '<hr />';
						}

						echo '
						<h2 class="tm-section-header">'.$db->trooperName.'</h2>';

						// Reset
						$trooperName = $db->trooperName;
					}

					echo '
					<p class="trooper-confirmation-box" signid="'.$db->signupId.'">
						<a href="index.php?event='.$db->eventId.'" target="_blank">'.$add.''.$db->name.' '.$db->trooperName.'</a>
						<br />
						<b>Attended As:</b> '.getCostume($db->costume).'
						<br /><br />
						<a href="#/" class="button" name="attend-button" status="3" signid="'.$db->signupId.'">Y</a>	<a href="#/" class="button" name="attend-button" status="4" signid="'.$db->signupId.'">N</a>
					</p>';

					// Increment
					$i++;
				}
			}
			
			// If data exists
			if($i > 0)
			{
				echo '
				</table>';
			}
			else
			{
				echo '
				<p style="text-align: center;">Nothing to display for this squad / club.</p>';
			}
		}

		/**************************** Roster *********************************/
		
		if(isset($_GET['do']) && $_GET['do'] == "roster" && isAdmin())
		{
			echo '
			<h3>Roster</h3>';
			
			// Squad count
			$i = 1;
			
			echo '
			<a href="index.php?action=commandstaff&do=roster" class="button">All</a> 
			<a href="index.php?action=commandstaff&do=roster&squad=all" class="button">All (501st)</a> ';
			
			foreach($squadArray as $squad => $squad_value)
			{
				if(getSquadID($_SESSION['id']) == $i || hasPermission(1))
				{
					echo '<a href="index.php?action=commandstaff&do=roster&squad='.$i.'" class="button">' . $squad_value['name'] . '</a> ';
				}

				$i++;
			}
			
			foreach($clubArray as $club => $club_value)
			{
				if(isClubMember($club_value['db']) > 0 || hasPermission(1))
				{
					echo '<a href="index.php?action=commandstaff&do=roster&squad='.$i.'" class="button">' . $club_value['name'] . '</a> ';
				}

				$i++;
			}
			
			echo '
			<a href="index.php?action=commandstaff&do=troopercheck" class="button">Trooper Check</a>
			<a href="index.php?action=commandstaff&do=trooperconfirmation" class="button">Trooper Confirmation</a>';
			
			echo '
			<br /><hr />';
			
			// Check if on a squad
			if(isset($_GET['squad']) && $_GET['squad'] != "all")
			{
				echo '
				<a href="#addtrooper" class="button">Add Trooper</a>';
			}
			
			// Set up
			$queryAdd = "WHERE ";
			
			// Check if squad is requested
			if(isset($_GET['squad']))
			{	
				// Which club to get
				if($_GET['squad'] == "all")
				{
					$queryAdd .= "p501 > 0";
				}
				else if($_GET['squad'] <= count($squadArray))
				{
					$queryAdd .= "squad = '".cleanInput($_GET['squad'])."' AND p501 > 0";

					// Check if a member of club
					if(getSquadID($_SESSION['id']) != cleanInput($_GET['squad']) && hasPermission(2))
					{
						die("<p>Not a member of this squad / club.</p>");
					}
				}
				
				// Set up count
				$clubCount = count($squadArray) + 1;
				
				// Loop through clubs
				foreach($clubArray as $club => $club_value)
				{
					// Match
					if($_GET['squad'] == $clubCount)
					{
						// Add to query
						$queryAdd .= " ".$club_value['db']." > 0";

						// Check if a member of club
						if(isClubMember($club_value['db']) == 0 && hasPermission(2))
						{
							die("<p>Not a member of this squad / club.</p>");
						}
					}
					
					// Increment
					$clubCount++;
				}
				
				$queryAdd .= " AND";
			}
			
			// Query database
			$query = "SELECT * FROM troopers ".$queryAdd." id != ".placeholder." AND approved = '1' ORDER BY name";
			
			// Query count
			$i = 0;
			
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// If first data
					if($i == 0)
					{
						// If squad set, have hidden field
						if(isset($_GET['squad']))
						{
							echo '
							<input type="hidden" name="club" id="club" value="'.cleanInput($_GET['squad']).'" />';
						}
						
						echo '
						<div style="overflow-x: auto;">
						<table id="masterRosterTable">
							<tr>
								<th>Name</th>	<th>Board Name</th>	<th>TKID</th>';
								
								// Only show if squad set
								if(isset($_GET['squad']))
								{
									echo '
									<th><a href="#/" id="sort-roster">Status</a></th>';
								}
								
							echo '
							</tr>';
					}
					
					echo '
					<tr name="row_'.$db->id.'">
						<td>
							<a href="index.php?profile='.$db->id.'" target="_blank">'.$db->name.'</a>
						</td>

						<td>
							'.$db->forum_id.'
						</td>
						
						<td>
							<a href="#/" name="roster-edit-tkid" tkid="'.$db->tkid.'">'.readTKNumber($db->tkid, $db->squad).'</a>
							<span id="roster-'.$db->tkid.'" trooperid="'.$db->id.'"></span>
						</td>';
						
						// Only show if squad set
						if(isset($_GET['squad']))
						{
							// Set up permission variable
							$permission = "";
							
							// Which club to get
							if($_GET['squad'] <= count($squadArray) || $_GET['squad'] == "all")
							{
								$permission = $db->p501;
							}
							
							// Set up count
							$clubCount = count($squadArray) + 1;
							
							// Loop through clubs
							foreach($clubArray as $club => $club_value)
							{
								// Match
								if($_GET['squad'] == $clubCount)
								{
									// Set permission
									$permission = $db->{$club_value['db']};
								}
								
								// Increment
								$clubCount++;
							}
							
							// If a moderator
							if($db->permissions == 1 || $db->permissions == 2)
							{
								// Don't allow to edit
								echo '
								<td name="permission-box">
									'.getPermissionName($db->permissions).'
								</td>';
							}
							else
							{
								// Not moderator - allow edit
								echo '
								<td name="permission-box">
									<select name="changepermission" trooperid="'.$db->id.'">
										<option value="0" '.echoSelect(0, $permission).'>Not A Member</option>
										<option value="1" '.echoSelect(1, $permission).'>Regular Member</option>
										<option value="2" '.echoSelect(2, $permission).'>Reserve Member</option>
										<option value="3" '.echoSelect(3, $permission).'>Retired Member</option>
										<option value="4" '.echoSelect(4, $permission).'>Handler</option>
									</select>
								</td>';
							}
						}
		
					echo '
					</tr>';
					
					// Increment
					$i++;
				}
			}
			
			// If data exists
			if($i > 0)
			{
				echo '
				</table>
				</div>';
			}
			else
			{
				echo '
				<p style="text-align: center;">Nothing to display for this squad / club.</p>';
			}
			
			if(isset($_GET['squad']) && $_GET['squad'] != "all")
			{
				// Set up query add
				$queryAdd = "";
				
				// Which club to get
				if($_GET['squad'] <= count($squadArray))
				{
					$queryAdd = "p501";
				}
				
				// Set up count
				$clubCount = count($squadArray) + 1;
				
				// Loop through clubs
				foreach($clubArray as $club => $club_value)
				{
					// Match
					if($_GET['squad'] == $clubCount)
					{
						$queryAdd = "".$club_value['db']."";
					}
					
					// Increment
					$clubCount++;
				}
	
				// Get data
				$query = "SELECT * FROM troopers WHERE ".$queryAdd." = 0 AND id != ".placeholder." ORDER BY name";

				// Amount of users
				$i = 0;

				if($result = mysqli_query($conn, $query))
				{
					while ($db = mysqli_fetch_object($result))
					{
						// Formatting
						if($i == 0)
						{
							echo '
							<h3 id="addtrooper">Add Trooper</h3>
							<form action="process.php?do=addmasterroster" method="POST" id="addMasterRosterForm">
							<input type="hidden" name="squad" value="'.cleanInput($_GET['squad']).'" />
							<select name="userID" id="userID">';
						}

						echo '<option value="'.$db->id.'" tkid="'.readTKNumber($db->tkid, $db->squad).'" troopername="'.$db->name.'" forum_id="'.$db->forum_id.'">'.$db->name.' - '.readTKNumber($db->tkid, $db->squad).'</option>';

						// Increment
						$i++;
					}
				}
				
				// If troopers exist
				if($i > 0)
				{
					echo '
					</select>
					
					<input type="submit" value="Add Trooper" id="addTrooperMaster" />
					</form>';
				}
				else
				{
					echo '
					<p id="addtrooper"><i>No troopers to add. Make sure they have a Rebel Legion Forum username assigned to their account.</i></p>';
				}
			}
		}
		
		/**************************** Trooper Check *********************************/
		
		if(isset($_GET['do']) && $_GET['do'] == "troopercheck" && isAdmin())
		{
			echo '
			<h3>Trooper Check</h3>
			
			<p>
				<i>The following troopers do not have a documented troop from the past year. Retired members do not show on this list.</i>
			</p>';
			
			// Squad count
			$i = 1;
			
			echo '
			<a href="index.php?action=commandstaff&do=troopercheck" class="button">All</a>';

			foreach($squadArray as $squad => $squad_value)
			{
				if(getSquadID($_SESSION['id']) == $i || hasPermission(1))
				{
					echo '<a href="index.php?action=commandstaff&do=troopercheck&squad='.$i.'" class="button">' . $squad_value['name'] . '</a> ';
				}

				$i++;
			}
			
			foreach($clubArray as $club => $club_value)
			{
				if(isClubMember($club_value['db']) > 0 || hasPermission(1))
				{
					echo '<a href="index.php?action=commandstaff&do=troopercheck&squad='.$i.'" class="button">' . $club_value['name'] . '</a> ';
				}

				$i++;
			}
			
			echo '<br /><hr />';
			
			// Set up
			$queryAdd = "";
			
			// Check if squad is requested
			if(isset($_GET['squad']))
			{
				// Which club to get
				if($_GET['squad'] <= count($squadArray))
				{
					$queryAdd .= "troopers.squad = '".cleanInput($_GET['squad'])."' AND (p501 = 1 OR p501 = 2) AND";

					// Check if a member of club
					if(getSquadID($_SESSION['id']) != cleanInput($_GET['squad']) && hasPermission(2))
					{
						die("<p>Not a member of this squad / club.</p>");
					}
				}
				
				// Set up count
				$clubCount = count($squadArray) + 1;
				
				// Loop through clubs
				foreach($clubArray as $club => $club_value)
				{
					// Match
					if($_GET['squad'] == $clubCount)
					{
						$queryAdd .= "(troopers.".$club_value['db']." = 1 OR troopers.".$club_value['db']." = 2) AND";

						// Check if a member of club
						if(isClubMember($club_value['db']) == 0 && hasPermission(2))
						{
							die("<p>Not a member of this squad / club.</p>");
						}
					}
					
					// Increment
					$clubCount++;
				}
			}
			
			// Query database
			$query = "SELECT * FROM troopers WHERE ".$queryAdd." approved = '1' AND troopers.permissions = 0 AND troopers.id NOT IN (SELECT event_sign_up.trooperid FROM event_sign_up LEFT JOIN events ON events.id = event_sign_up.troopid WHERE event_sign_up.status = '3' AND events.dateEnd > NOW() - INTERVAL 1 YEAR) ORDER BY troopers.name";
			
			// Query count
			$i = 0;
			
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// If first data
					if($i == 0)
					{
						echo '
						<form action="process.php?do=troopercheck" method="POST" name="trooperCheckForm" id="trooperCheckForm">';
						
						// If squad set, have hidden field
						if(isset($_GET['squad']))
						{
							echo '
							<input type="hidden" name="club" value="'.cleanInput($_GET['squad']).'" />';
						}
						
						echo '
						<div style="overflow-x: auto;">
						<table>
							<tr>';
								// If squad set
								if(isset($_GET['squad']))
								{
									echo '
									<th>Selection</th>';
								}
								
								echo '
								<th>Name</th>	<th>Board Name</th>	<th>TKID</th>';
								
								// If squad set
								if(isset($_GET['squad']))
								{
									echo '
									<th>Tracker Status</th>';
								}
							
							echo '
							</tr>';
					}
					
					echo '
					<tr name="row_'.$db->id.'">';
					
						// If squad set
						if(isset($_GET['squad']))
						{
							echo '
							<td>
								<input type="checkbox" name="trooper[]" value="'.$db->id.'" />
							</td>';
						}
						
						echo '
						<td>
							<a href="index.php?profile='.$db->id.'" target="_blank">'.$db->name.'</a>
						</td>

						<td>
							'.$db->forum_id.'
						</td>
						
						<td>
							'.readTKNumber($db->tkid, $db->squad).'
						</td>';
						
						// If squad set
						if(isset($_GET['squad']))
						{
							// Set up permission variable
							$permission = "";
							
							// Which club to get
							if($_GET['squad'] <= count($squadArray))
							{
								$permission = $db->p501;
							}
							
							// Set up count
							$clubCount = count($squadArray) + 1;
							
							// Loop through clubs
							foreach($clubArray as $club => $club_value)
							{
								// Match
								if($_GET['squad'] == $clubCount)
								{
									// Set permission
									$permission = $db->{$club_value['db']};
								}
								
								// Increment
								$clubCount++;
							}
						
							echo '
							<td name="permission">
								'.getClubPermissionName($permission).'
							</td>';
						}
						
					echo '
					</tr>';
					
					// Increment
					$i++;
				}
			}
			
			// If data exists
			if($i > 0)
			{
				echo '
				</table>
				</div>';
				
				if(isset($_GET['squad']))
				{
					echo '
					<input type="submit" name="submitTroopCheckReserve" id="submitTroopCheckReserve" value="Change to Reserve" />
					<input type="submit" name="submitTroopCheckRetired" id="submitTroopCheckRetired" value="Change to Retired" />';
				}
				
				echo '
				</form>';
			}
			else
			{
				echo '
				<p style="text-align: center;">Nothing to display for this squad / club.</p>';
			}
		}
		
		/**************************** Notifications *********************************/
		
		if(isset($_GET['do']) && $_GET['do'] == "notifications")
		{
			echo '
			<h3>Notifications</h3>
			
			<p>
				<a href="index.php?action=commandstaff&do=notifications" class="button">All</a>
				<a href="index.php?action=commandstaff&do=notifications&s=system" class="button">System</a>
				<a href="index.php?action=commandstaff&do=notifications&s=troopers" class="button">Troopers</a>
			</p>';
			
			// Set results per page
			$results = 100;
		
			// Add to query if in URL
			if(isset($_GET['s']) && $_GET['s'] == "system")
			{
				// Get data
				$query = "SELECT * FROM notifications WHERE message NOT LIKE '%now has%' ORDER BY id DESC ";
				
				// Get total results - query
				$sqlPage = "SELECT COUNT(id) AS total FROM notifications WHERE message NOT LIKE '%now has%'";
			}
			else if(isset($_GET['s']) && $_GET['s'] == "troopers")
			{
				// Get data
				$query = "SELECT * FROM notifications WHERE message LIKE '%now has%' ORDER BY id DESC ";
				
				// Get total results - query
				$sqlPage = "SELECT COUNT(id) AS total FROM notifications WHERE message LIKE '%now has%'";
			}
			else
			{
				// Get data
				$query = "SELECT * FROM notifications ORDER BY id DESC ";
				
				// Get total results - query
				$sqlPage = "SELECT COUNT(id) AS total FROM notifications";
			}
			
			// Page SQL
			$resultPage = $conn->query($sqlPage);
			$rowPage = $resultPage->fetch_assoc();
			
			// Set total pages
			$total_pages = ceil($rowPage["total"] / $results);
			
			// If page set
			if(isset($_GET['page']))
			{
				// Get page
				$page = cleanInput($_GET['page']);
				
				// Start from
				$startFrom = ($page - 1) * $results;
			}
			else
			{
				// Default page
				$page = 1;
				
				// Start from - default
				$startFrom = ($page - 1) * $results;
			}
			
			// Add to query
			$query .= "LIMIT ".$startFrom.", ".$results."";
			
			// Set notification count
			$i = 0;
			
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					if($i == 0)
					{
						echo '<ul>';
					}
					
					// Format Date
					$dateF = formatTime($db->datetime, "m-d-Y H:i:s");

					// Set JSON value
					$json = "";

					// Check JSON value if blank
					if($db->json == "")
					{
						// Set up
						$add = "";

						// Does not contain now has
						if(strpos($db->message, "now has") === false && strpos($db->message, "donated") === false)
						{
							$add = "Staff ";
						}

						// Nothing to show
						$json = '<a href="index.php?profile='.$db->trooperid.'" target="_blank" class="button">View '.$add.'Profile</a>';
					}
					else
					{
						// Show value
						$json = '
						<textarea width="100%" rows="5" disabled>' . $db->json . '</textarea>
						<br />
						<a href="index.php?profile='.$db->trooperid.'" target="_blank" class="button">View Staff Profile</a>';

						// Decode JSON data - Avoid null results
						$data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $db->json));

						// If array or object
						if(is_array($data) || is_object($data))
						{
							// Loop through JSON data
							foreach($data as $key => $value)
							{
								// Troop ID
								if($key == "trooperid")
								{
									$json .= ' <a href="index.php?profile='.$value.'" target="_blank" class="button">View Trooper</a>';
								}

								// Troop ID
								if($key == "troopid")
								{
									$json .= ' <a href="index.php?event='.$value.'" target="_blank" class="button">View Troop</a>';
								}
								// Troop ID #2
								else if($key == "id" && ($db->type == 13 || $db->type == 14 || $db->type == 19 || $db->type == 17) && !isset($data->troopid))
								{
									$json .= ' <a href="index.php?event='.$value.'" target="_blank" class="button">View Troop</a>';
								}
							}
						}
					}
					
					echo '
					<li>
						<a href="#/" name="jsonshow" json="'.$db->id.'">'.$db->message.'</a> on '.$dateF.'.

						<p>
							<div style="display: none;" name="json'.$db->id.'">'.$json.'</div>
						</p>
					</li>';
					
					$i++;
				}
			}
			
			// No notifications to display
			if($i == 0)
			{
				echo '<p>No notifications to display.</p>';
			}
			else
			{
				// Finish HTML list - notifications exist
				echo '</ul>';
			}
			
			// If notifications
			if($total_pages > 1)
			{
				echo '<p>Pages: ';
				
				// Loop through pages
				for ($j = 1; $j <= $total_pages; $j++)
				{
					// If we are on this page...
					if($page == $j)
					{
						echo '
						'.$j.'';
					}
					else
					{
						// Set up preference
						$s = "";
						
						// Add preference to search if set
						if(isset($_GET['s']))
						{
							$s = "&s=" . cleanInput($_GET['s']);
						}
						echo '
						<a href="index.php?action=commandstaff&do=notifications&page='.$j.''.$s.'">'.$j.'</a>';
					}
					
					// If not that last page, add a comma
					if($j != $total_pages)
					{
						echo ', ';
					}
				}
				
				echo '</p>';
			}
		}

		/**************************** COSTUMES *********************************/

		// Manage costumes - allow command staff to add, edit, and delete costumes
		if(isset($_GET['do']) && $_GET['do'] == "managecostumes" && (hasPermission(1) || hasSpecialPermission("spCostume")))
		{
			echo '
			<h3>Add Costume</h3>
			
			<form action="process.php?do=managecostumes" method="POST" name="addCostumeForm" id="addCostumeForm">
			
				<p>
					<b>Costume Name:</b></br />
					<input type="text" name="costumeName" id="costumeName" />
				</p>
				
				<p>
					<b>Costume Era:</b></br />
					<select name="costumeEra" id="costumeEra">
						<option value="0">Prequel</option>
						<option value="1" SELECTED>Original</option>
						<option value="2">Sequel</option>
						<option value="3">Expanded</option>
						<option value="4">All</option>
					</select>
				</p>
				
				<p>
					<b>Costume Club:</b></br />
					<select name="costumeClub" id="costumeClub">
						<option value="0" SELECTED>501st Legion</option>';

						// Loop through clubs
						foreach($clubArray as $club => $club_value)
						{
							echo '
							<option value="'.$club_value['costumes'][0].'">'.$club_value['name'].'</option>';
						}

						echo '
						<option value="'.$dualCostume.'">Dual (501st + Rebel)</option>
					</select>
				</p>
				
				<input type="submit" name="addCostumeButton" id="addCostumeButton" value="Add Costume" />
				
			</form>
				
			<br />
			<hr />
			<br />
			
			<div id="costumearea" name="costumearea">
			
			<h3>Edit Costume</h3>';

			// Get data
			$query = "SELECT * FROM costumes ORDER BY costume";

			$i = 0;
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Formatting
					if($i == 0)
					{
						echo '
						<form action="process.php?do=managecostumes" method="POST" name="costumeEditForm" id="costumeEditForm">

						<select name="costumeIDEdit" id="costumeIDEdit">

							<option value="0" SELECTED>Please select a costume...</option>';
					}

					echo '
					<option value="'.$db->id.'" costumeName="'.$db->costume.'" costumeID="'.$db->id.'" costumeEra="'.$db->era.'" costumeClub="'.$db->club.'">'.$db->costume.'</option>';


					// Increment
					$i++;
				}
			}

			if($i == 0)
			{
				echo 'No costumes to display.';
			}
			else
			{
				echo '
				</select>

				<br /><br />

				<div id="editCostumeList" name="editCostumeList" style="display: none;">

				<p>
					<b>Costume Name:</b></br />
					<input type="text" name="costumeNameEdit" id="costumeNameEdit" />
				</p>
				
				<p>
					<b>Costume Era:</b></br />
					<select name="costumeEraEdit" id="costumeEraEdit">
						<option value="0">Prequel</option>
						<option value="1" SELECTED>Original</option>
						<option value="2">Sequel</option>
						<option value="3">Expanded</option>
						<option value="4">All</option>
					</select>
				</p>

				<p>
					<b>Costume Club:</b></br />
					<select name="costumeClubEdit" id="costumeClubEdit">
						<option value="0" SELECTED>501st Legion</option>';

						// Loop through clubs
						foreach($clubArray as $club => $club_value)
						{
							echo '
							<option value="'.$club_value['costumes'][0].'">'.$club_value['name'].'</option>';
						}

						echo '
						<option value="'.$dualCostume.'">Dual (501st + Rebel)</option>
					</select>
				</p>

				<input type="submit" name="submitEditCostume" id="submitEditCostume" value="Edit Costume" />

				</div>
				</form>';
			}
			
			echo '
			<br />
			<hr />
			<br />
		
			<h3>Delete Costume</h3>';

			// Get data
			$query = "SELECT * FROM costumes ORDER BY costume";

			$i = 0;
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Formatting
					if($i == 0)
					{
						echo '
						<form action="process.php?do=managecostumes" method="POST" name="costumeDeleteForm" id="costumeDeleteForm">

						<select name="costumeID" id="costumeID">';
					}

					echo '<option value="'.$db->id.'">'.$db->costume.'</option>';

					// Increment
					$i++;
				}
			}

			if($i == 0)
			{
				echo 'No costumes to display.';
			}
			else
			{
				echo '
				</select>

				<input type="submit" name="submitDeleteCostume" id="submitDeleteCostume" value="Delete Costume" />
				</form>';
			}
			
			echo '
			</div>';
		}

		/**************************** TITLES *********************************/

		// Assign a title to troopers
		if(isset($_GET['do']) && $_GET['do'] == "assigntitles" && hasPermission(1))
		{
			echo '<h3>Assign Titles</h3>
			
			<div name="assignarea" id="assignarea">';

			// Get data
			$query = "SELECT * FROM troopers WHERE approved = 1 ORDER BY name";

			// Amount of users
			$i = 0;
			$getId = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Formatting
					if($i == 0)
					{
						$getId = $db->id;

						echo '
						<form action="process.php?do=assigntitles" method="POST" name="titleUser" id="titleUser">

						<select name="userIDTitle" id="userIDTitle">';
					}

					echo '<option value="'.$db->id.'">'.readInput($db->name).' - '.readTKNumber($db->tkid, $db->squad).' - '.$db->forum_id.'</option>';

					// Increment
					$i++;
				}
			}

			// If no events
			if($i == 0)
			{
				echo 'There are no troopers to display.';
			}
			else
			{
				echo '
				</select>

				<br /><br />';

				// Get data
				$query2 = "SELECT * FROM titles ORDER BY title";

				// Amount of titles
				$j = 0;

				if ($result2 = mysqli_query($conn, $query2))
				{
					while ($db = mysqli_fetch_object($result2))
					{
						// Formatting
						if($j == 0)
						{
							$getId2 = $db->id;

							echo '<select id="titleIDAssign" name="titleIDAssign">';
						}

						echo '<option value="'.$db->id.'">'.readInput($db->title).'</option>';

						// Increment $j
						$j++;
					}
				}

				// If titles exist
				if($j > 0)
				{
					echo '
					</select>
					
					<input type="submit" name="title" id="title" value="Assign" '.hasTitle($getId, $getId2, true).' />
					<input type="submit" name="titleRemove" id="titleRemove" value="Remove" '.hasTitle($getId, $getId2, true, true).' />';
				}
				else
				{
					echo 'No titles to display.';
				}
			}
			
			echo '</form></div>';

			echo '<br /><hr /><br /><h3>Create Title</h3>

			<form action="process.php?do=assigntitles" method="POST" name="addTitle" id="addTitle">
				<p>
					<b>Title Name:</b>
					<input type="text" name="titleName" id="titleName" />
				</p>

				<p>
					<b>Title Image (example.png):</b></br />
					<input type="text" name="titleImage" id="titleImage" />
				</p>

				<p>
					<b>Corresponding Forum Title ID:</b></br />
					<input type="number" name="titleForumID" id="titleForumID" value="0" />
				</p>
				<input type="submit" name="submitTitleAdd" id="submitTitleAdd" value="Add Title" />
			</form>';

			echo '
			<div id="titlearea">
			<br /><hr /><br />
			<h3>Edit Title</h3>';

			// Get data
			$query = "SELECT * FROM titles ORDER BY title";

			$i = 0;
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Formatting
					if($i == 0)
					{
						echo '
						<form action="process.php?do=assigntitles" method="POST" name="titleEdit" id="titleEdit">

						<select name="titleIDEdit" id="titleIDEdit">

							<option value="0" SELECTED>Please select a title...</option>';
					}

					echo '<option value="'.$db->id.'" title="'.readInput($db->title).'" titleID="'.$db->id.'" titleImage="'.$db->icon.'" titleForumID="'.$db->forum_id.'">'.readInput($db->title).'</option>';

					// Increment
					$i++;
				}
			}

			if($i == 0)
			{
				echo 'No titles to display.';
			}
			else
			{
				echo '
				</select>

				<br /><br />

				<div id="editTitleList" name="editTitleList" style="display: none;">

				<p>
					<b>Title:</b><br />
					<input type="text" name="editTitleTitle" id="editTitle" />
				</p>

				<p>
					<b>Image:</b><br />
					<input type="text" name="editTitleImage" id="editTitleImage" />
				</p>

				<p>
					<b>Corresponding Forum ID:</b><br />
					<input type="number" name="editTitleForumID" id="editTitleForumID" />
				</p>

				<input type="submit" name="submitEditTitle" id="submitEditTitle" value="Edit Title" />

				</div>
				</form>';
			}

			echo '<br /><hr /><br /><h3>Delete Title</h3>';

			// Get data
			$query = "SELECT * FROM titles ORDER BY title";

			$i = 0;
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Formatting
					if($i == 0)
					{
						echo '
						<form action="process.php?do=assigntitles" method="POST" name="titleUserDelete" id="titleUserDelete">
						<select name="titleID" id="titleID">';
					}

					echo '<option value="'.$db->id.'">'.readInput($db->title).'</option>';

					// Increment
					$i++;
				}
			}

			if($i == 0)
			{
				echo 'No titles to display.';
			}
			else
			{
				echo '
				</select>

				<input type="submit" name="submitDeleteTitle" id="submitDeleteTitle" value="Delete Title" />
				</form>';
			}
			
			echo '
			</div>';
		}
		
		/**************************** AWARDS *********************************/

		// Assign an award to users
		if(isset($_GET['do']) && $_GET['do'] == "assignawards" && (hasPermission(1) || hasSpecialPermission("spTrooper")))
		{
			echo '<h3>Assign Awards</h3>
			
			<div name="assignarea" id="assignarea">';

			// Get data
			$query = "SELECT * FROM troopers WHERE approved = 1 ORDER BY name";

			// Amount of users
			$i = 0;
			$getId = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Formatting
					if($i == 0)
					{
						$getId = $db->id;

						echo '
						<form action="process.php?do=assignawards" method="POST" name="awardUser" id="awardUser">

						<select name="userIDAward" id="userIDAward">';
					}

					echo '<option value="'.$db->id.'">'.readInput($db->name).' - '.readTKNumber($db->tkid, $db->squad).' - '.$db->forum_id.'</option>';

					// Increment
					$i++;
				}
			}

			// If no events
			if($i == 0)
			{
				echo 'There are no troopers to display.';
			}
			else
			{
				echo '
				</select>

				<br /><br />';

				// Get data
				$query2 = "SELECT * FROM awards ORDER BY title";

				// Amount of awards
				$j = 0;

				if ($result2 = mysqli_query($conn, $query2))
				{
					while ($db = mysqli_fetch_object($result2))
					{
						// Formatting
						if($j == 0)
						{
							$getId2 = $db->id;

							echo '<select id="awardIDAssign" name="awardIDAssign">';
						}

						echo '<option value="'.$db->id.'">'.readInput($db->title).'</option>';

						// Increment $j
						$j++;
					}
				}

				// If awards exist
				if($j > 0)
				{
					echo '
					</select>

					<input type="submit" name="award" id="award" value="Assign" '.hasAward($getId, $getId2, true).' /> <input type="submit" name="awardRemove" id="awardRemove" value="Remove" '.hasAward($getId, $getId2, true, true).' />';
				}
				else
				{
					echo 'No awards to display.';
				}
			}

			echo '</form></div>';

			echo '<br /><hr /><br /><h3>Create Award</h3>

			<form action="process.php?do=assignawards" method="POST" name="addAward" id="addAward">
				<p>
					<b>Award Name:</b></br />
					<input type="text" name="awardName" id="awardName" />
				</p>

				<p>
					<b>Award Image (example.png):</b></br />
					<input type="text" name="awardImage" id="awardImage" />
				</p>
				<input type="submit" name="submitAwardAdd" id="submitAwardAdd" value="Add Award" />
			</form>';

			echo '
			<div id="awardarea">
			<br /><hr /><br />
			<h3>Edit Award</h3>';

			// Get data
			$query = "SELECT * FROM awards ORDER BY title";

			$i = 0;
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Formatting
					if($i == 0)
					{
						echo '
						<form action="process.php?do=assignawards" method="POST" name="awardEdit" id="awardEdit">

						<select name="awardIDEdit" id="awardIDEdit">

							<option value="0" SELECTED>Please select an award...</option>';
					}

					echo '<option value="'.$db->id.'" awardTitle="'.readInput($db->title).'" awardID="'.$db->id.'" awardImage="'.$db->icon.'">'.readInput($db->title).'</option>';

					// Increment
					$i++;
				}
			}

			if($i == 0)
			{
				echo 'No awards to display.';
			}
			else
			{
				echo '
				</select>

				<br /><br />

				<div id="editAwardList" name="editAwardList" style="display: none;">

				<p>
					<b>Award Title:</b><br />
					<input type="text" name="editAwardTitle" id="editAwardTitle" />
				</p>

				<p>
					<b>Award Image:</b><br />
					<input type="text" name="editAwardImage" id="editAwardImage" />
				</p>

				<input type="submit" name="submitEditAward" id="submitEditAward" value="Edit Award" />

				</div>
				</form>';
			}

			echo '<br /><hr /><br /><h3>Delete Award</h3>';

			// Get data
			$query = "SELECT * FROM awards ORDER BY title";

			$i = 0;
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Formatting
					if($i == 0)
					{
						echo '
						<form action="process.php?do=assignawards" method="POST" name="awardUserDelete" id="awardUserDelete">
						<select name="awardID" id="awardID">';
					}

					echo '<option value="'.$db->id.'">'.readInput($db->title).'</option>';

					// Increment
					$i++;
				}
			}

			if($i == 0)
			{
				echo 'No awards to display.';
			}
			else
			{
				echo '
				</select>

				<input type="submit" name="submitDeleteAward" id="submitDeleteAward" value="Delete Award" />
				</form>';
			}
			
			echo '
			</div>';
		}
		
		/************** EVENTS ******************/

		// Update an event form
		if(isset($_GET['do']) && $_GET['do'] == "editevent")
		{
			echo '
			<h3>Edit an Event</h3>';
			
			// If admin is visting this page from the event page
			
			// We use this value to determine which event is selected
			$eid = -1;
			
			// If eid set, set eid
			if(isset($_GET['eid']))
			{
				$eid = $_GET['eid'];
			}

			// Get data
			// If edid set - this makes sure that old events are shown in the edit screen
			if($eid >= 0)
			{
				$query = "(SELECT * FROM events WHERE id = ".$eid." LIMIT 1) UNION (SELECT * FROM events ORDER BY dateStart DESC LIMIT 500)";
			}
			else
			{
				// If eid is not set
				$query = "SELECT * FROM events ORDER BY dateStart DESC LIMIT 500";
			}

			// Amount of events
			$i = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Formatting
					if($i == 0)
					{
						echo '
						<form action="process.php?do=editevent" method="POST" name="editEvents" id="editEvents">
						<select name="eventId" id="eventId">';
					}
					
					// Set up string to add to title if a linked event
					$add = "";
					
					// If this a linked event?
					if(isLink($db->id) > 0)
					{
						$add .= "[" . date("l", strtotime($db->dateStart)) . " : " . date("m/d - h:i A", strtotime($db->dateStart)) . " - " . date("h:i A", strtotime($db->dateEnd)) . "] ";
					}

					echo '<option value="'.$db->id.'" link="'.isLink($db->id).'" '.echoSelect($db->id, $eid).'>'.$add.''.$db->name.'</option>';

					// Increment
					$i++;
				}
			}

			// If no events
			if($i == 0)
			{
				echo 'There are no events to display.';
			}
			else
			{
				echo '
				</select>

				<br /><br />';

				if(hasPermission(1))
				{
					echo '
					<input type="submit" name="submitDelete" id="submitDelete" value="Delete" />';
				}
				
				echo '
				<input type="submit" name="submitEventStatus" id="submitEventStatus" value="Event Status" /> <input type="submit" name="submitEdit" id="submitEdit" value="Edit" /> <input type="submit" name="submitRoster" id="submitRoster" value="Roster" /> <input type="submit" name="submitCharity" id="submitCharity" value="Charity" /> <input type="submit" name="submitAdvanced" id="submitAdvanced" value="Advanced Options" /> <input type="submit" name="viewEvent" id="viewEvent" value="View Event" />
				</form>

				<div name="editEventStatus" id="editEventStatus" style="display:none;">
					<br />
					<form action="process.php?do=editeventstatus" method="POST">
						<select name="eventStatus">
							<option value="0">Open</option>
							<option value="3">Lock</option>
							<option value="2">Cancel</option>
							<option value="4">Full</option>
							<option value="1">Finish</option>
						</select>
						<br />
						<input type="submit" id="editEventStatusSave" value="Set" />
					</form>
				</div>

				<div name="advancedOptions" id="advancedOptions" style="display:none;">
					<br />
					<form action="process.php?do=editadvanced" method="POST">
						Thread ID: <input type="number" id="threadIDA" name="threadIDA" />
						<br /><br />
						Post ID: <input type="number" id="postIDA" name="postIDA" />
						<br />
						<input type="submit" id="advancedOptionsSave" value="Set" />
					</form>
				</div>
				
				<div name="charityAmount" id="charityAmount" style="display:none;">
					<br />
					<form action="process.php?do=editevent" id="editcharityForm" name="editcharityForm" method="POST">
						<p>
							Direct Charity Raised: $<input type="number" name="charityDirectFunds" id="charityDirectFunds" />
						</p>
						<p>
							Indirect Charity Raised: $<input type="number" name="charityIndirectFunds" id="charityIndirectFunds" />
						</p>
						<p>
							Charity Name: <input type="text" name="charityName" id="charityName" />
						</p>
						<p>
							Charity Add Hours: <input type="number" name="charityAddHours" id="charityAddHours" />
						</p>
						<p>
							Charity Note: <textarea width="100%" rows="5" name="charityNote" id="charityNote"></textarea>
						</p>

						<input type="submit" name="charityAmountSave" id="charityAmountSave" value="Set" />
					</form>
				</div>

				<div name="rosterInfo" id="rosterInfo" style="display:none;">
				</div>

				<div name="editEventInfo" id="editEventInfo" style="display:none;">
					<form action="process.php?do=editevent" id="editEventForm" name="editEventForm" method="POST">
						<input type="hidden" name="eventLink" id="eventLink" value="" />
						<input type="hidden" name="eventIdE" id="eventIdE" value="" />

						<p>Name of the event:</p>
						<input type="text" name="eventName" id="eventName" />

						<p>Venue of the event:</p>
						<input type="text" name="eventVenue" id="eventVenue" />

						<p>Location:</p>
						<input type="text" name="location" id="location" />
						<input type="button" name="getLocation" id="getLocation" value="Get Squad Based On Location" />
						
						<p>Squad</p>
						<select name="squadm" id="squadm">
							<option value="null" SELECTED>Please choose an option...</option>
							'.squadSelectList(false).'
							<option value="0">'.garrison.'</option>
						</select>		

						<p>Date/Time Start:</p>
						<input type="text" name="dateStart" id="datepicker" />

						<p>Date/Time End:</p>
						<input type="text" name="dateEnd" id="datepicker2" />
						
						<div name="datetimeadd" id="datetimeadd"></div>
						
						<input type="submit" name="addshift" id="addshift" value="Add Shift" />
						
						<p>
							<i>Please note: This is to add additional shifts. To edit a shift, go to the shift event page, and edit it there.</i>
						</p>

						<div id="options">
							<p>Website:</p>
							<input type="text" name="website" id="website" />

							<p>Number of Attendees:</p>
							<input type="number" name="numberOfAttend" id="numberOfAttend" />

							<p>Requested Number of Characters:</p>
							<input type="number" name="requestedNumber" id="requestedNumber" />

							<p>Requested Character Types:</p>
							<input type="text" name="requestedCharacter" id="requestedCharacter" />

							<p>Secure Changing?</p>
							<select name="secure" id="secure">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>

							<p>Blasters Allowed?</p>
							<select name="blasters" id="blasters">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>

							<p>Lightsabers Allowed?</p>
							<select name="lightsabers" id="lightsabers">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>

							<p>Parking?</p>
							<select name="parking" id="parking">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>

							<p>People with limited mobility access?</p>
							<select name="mobility" id="mobility">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
						</div>

						<p>Amenities?</p>
						<input type="text" name="amenities" id="amenities" />

						<p>Additional Comments:</p>
						<a href="javascript:void(0);" onclick="javascript:bbcoder(\'B\', \'comments\')" class="button">Bold</a>
						<a href="javascript:void(0);" onclick="javascript:bbcoder(\'I\', \'comments\')" class="button">Italic</a>
						<a href="javascript:void(0);" onclick="javascript:bbcoder(\'U\', \'comments\')" class="button">Underline</a>
						<a href="javascript:void(0);" onclick="javascript:bbcoder(\'Q\', \'comments\')" class="button">Quote</a>
						<a href="javascript:void(0);" onclick="javascript:bbcoder(\'COLOR\', \'comments\')" class="button">Color</a>
						<a href="javascript:void(0);" onclick="javascript:bbcoder(\'SIZE\', \'comments\')" class="button">Size</a>
						<a href="javascript:void(0);" onclick="javascript:bbcoder(\'URL\', \'comments\')" class="button">URL</a>
						<a href="#/" class="button" name="addSmiley">Add Smiley</a>
						<textarea rows="10" cols="50" name="comments" id="comments"></textarea>

						<span name="smileyarea" style="display: block;">
						</span>

						<p>Label:</p>
						<select name="label" id="label">
							<option value="0">Regular</option>
							<option value="10">Armor Party</option>
							<option value="1">Charity</option>
							<option value="2">PR</option>
							<option value="3">Disney</option>
							<option value="4">Convention</option>
							<option value="9">Hospital</option>
							<option value="5">Wedding</option>
							<option value="6">Birthday Party</option>
							<option value="7">Virtual Troop</option>
							<option value="8">Other</option>
						</select>
						
						<p>
							<a href="#/" class="button" id="limitChange">Change Limits</a>
						</p>
						
						<div id="limitChangeArea" style="display: none;">
						
						<p>How many friends can a trooper add?</p>
						<input type="text" name="friendLimit" id="friendLimit" />
						
						<p>Allow tentative troopers?</p>
						<select name="allowTentative" id="allowTentative">
							<option value="1">Yes</option>
							<option value="0">No</option>
						</select>

						<p>Is this a manual selection event?</p>
						<select name="limitedEvent" id="limitedEvent">
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>

						<p>Do you wish to limit the era of the costume?</p>
						<select name="era" id="era">
							<option value="0">Prequel</option>
							<option value="1" SELECTED>Original</option>
							<option value="2">Sequel</option>
							<option value="3">Expanded</option>
							<option value="4" SELECTED>All</option>
						</select>

						<p>Limit of 501st Troopers:</p>
						<input type="number" name="limit501st" value="500" id="limit501st" class="limitClass" />';

						// Loop through clubs
						foreach($clubArray as $club => $club_value)
						{
							echo '
							<p>Limit of '.$club_value['name'].':</p>
							<input type="number" name="'.$club_value['dbLimit'].'" value="500" id="'.$club_value['dbLimit'].'" class="limitClass" />';
						}

						echo '
						<p>Limit of Total Handlers:</p>
						<input type="number" name="limitHandlers" value="500" id="limitHandlers" class="limitClass" />
						
						<p>Limit of Total Troopers:</p>
						<input type="number" name="limitTotalTroopers" value="500" id="limitTotalTroopers" class="limitClass" />

						<p>
							<a href="#/" class="button" id="resetDefaultCount">Reset Default</a>
						</p>
						
						</div>

						<p>Referred By:</p>
						<input type="text" name="referred" id="referred" />

						<input type="submit" name="submitEventEdit" id="submitEventEdit" value="Edit!" />
					</form>
				</div>';
			}
		}

		// Approve troopers
		if(isset($_GET['do']) && $_GET['do'] == "approvetroopers" && hasPermission(1, 2))
		{
			echo '
			<h3>Approve Trooper Requests</h3>';

			// Get data
			$query = "SELECT * FROM troopers WHERE approved = 0 ORDER BY datecreated";

			// Amount of users
			$i = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Formatting
					if($i == 0)
					{
						echo '
						<form action="process.php?do=approvetroopers" method="POST" name="approveTroopers" id="approveTroopers">

						<select name="userID2" id="userID2">
							<option value="-1" SELECTED>Please select a trooper...</option>';
					}

					echo '<option value="'.$db->id.'">'.$db->name.'</option>';

					// Increment
					$i++;
				}
			}

			// If no events
			if($i == 0)
			{
				echo 'There are no troopers to display.';
			}
			else
			{
				echo '
				</select>

				<br /><br />

					<div id="approveButtons" style="display: none;">
						<input type="submit" name="submitApproveUser" id="submitApproveUser" value="Approve" /> <input type="submit" name="submitDenyUser" id="submitDenyUser" value="Deny" />
					</div>
				</form>

				<div style="overflow-x: auto;">
				<table border="1" id="userListTable" name="userListTable">
				<tr>
					<th>Name</th>	<th>E-mail</th>	<th>Forum ID (FG)</th>	<th>Forum ID (RL)</th>	<th>Mando CAT</th>	<th>SG #</th>	<th>Phone</th>	<th>Squad</th>	<th>TKID</th>
				</tr>
					<tr id="userList" name="userList">
						<td id="nameTable"></td>	<td id="emailTable"></td> <td id="forumTable"></td> <td id="rebelforumTable"></td> <td id="mandoidTable"></td>	<td id="sgidTable"></td>	<td id="phoneTable"></td>	<td id="squadTable"></td>	<td id="tkTable"></td>
					</tr>
				</table>
				</div>';
			}
		}

		// Manage users
		if(isset($_GET['do']) && $_GET['do'] == "managetroopers" && (hasPermission(1) || hasSpecialPermission("spTrooper")))
		{
			// If admin is visting this page from the event page
			
			// We use this value to determine which event is selected
			$uid = -1;
			
			// If eid set, set eid
			if(isset($_GET['uid']))
			{
				$uid = $_GET['uid'];
			}
			
			echo '
			<h3>Manage Troopers</h3>';

			// Get data
			$query = "SELECT * FROM troopers ORDER BY name";

			// Amount of users
			$i = 0;

			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					// Formatting
					if($i == 0)
					{
						echo '
						<form action="process.php?do=managetroopers" method="POST" name="editUser" id="editUser">

						<select name="userID" id="userID">';
					}

					echo '<option value="'.$db->id.'" '.echoSelect($db->id, $uid).'>'.$db->name.' - '.readTKNumber($db->tkid, $db->squad).' - '.$db->forum_id.'</option>';

					// Increment
					$i++;
				}
			}

			// If no events
			if($i == 0)
			{
				echo 'There are no troopers to display.';
			}
			else
			{
				echo '
				</select>

				<br /><br />

				<input type="submit" name="submitDeleteUser" id="submitDeleteUser" value="Delete" /> <input type="submit" name="submitViewProfile" id="submitViewProfile" value="View Profile" /> <input type="submit" name="submitEditUser" id="submitEditUser" value="Edit" />
				</form>

				<div name="editUserInfo" id="editUserInfo" style="display:none;">
					<form action="process.php?do=managetroopers" id="editUserForm" name="editUserForm" method="POST">
						<input type="hidden" name="userIDE" id="userIDE" value="" />

						<p>Name of the user:</p>
						<input type="text" name="user" id="user" />

						<p>Phone (Optional):</p>
						<input type="text" name="phone" id="phone" maxlength="10" />

						<p>Squad/Club:</p>
						<select name="squad" id="squad">
							<option value="0">'.garrison.'</option>
							'.squadSelectList().'
						</select>';

						if(hasPermission(1))
						{
							echo '
							<p>General Permissions:</p>
							<select name="permissions" id="permissions">
								<option value="0">Regular Member</option>
								<option value="3">RIP Member</option>
								<option value="2">Moderator</option>
								<option value="1">Super Admin</option>
							</select>';
						}

						echo '
						<span name="specialPermissions" style="display: none;">';

						if(hasPermission(1))
						{
							echo '
							<p>Special Permissions:</p>

							<p>
								<input type="checkbox" name="spTrooper" /> Trooper Management
							</p>

							<p>
								<input type="checkbox" name="spCostume" /> Costume Management
							</p>

							<p>
								<input type="checkbox" name="spAward" /> Award Management
							</p>';
						}

						echo '
						</span>

						<p>
							<a href="#/" class="button" id="trooperInformationButton">Show Trooper Information</a>
						</p>

						<span name="trooperInformation" style="display: none;">

						<p>
							<hr />
						</p>
						
						<p>501st Member Status:</p>
						<select name="p501" id="p501">
							<option value="0">Not A Member</option>
							<option value="1">Regular Member</option>
							<option value="2">Reserve Member</option>
							<option value="3">Retired Member</option>
							<option value="4">Handler</option>
						</select>';
						
						// Set up count
						$clubCount = count($squadArray) + 1;
						
						// Loop through clubs
						foreach($clubArray as $club => $club_value)
						{
							echo '
							<p>'.$club_value['name'].' Member Status:</p>
							<select name="'.$club_value['db'].'" id="'.$club_value['db'].'" class="clubs">
								<option value="0">Not A Member</option>
								<option value="1">Regular Member</option>
								<option value="2">Reserve Member</option>
								<option value="3">Retired Member</option>
								<option value="4">Handler</option>
							</select>';
							
							// Increment
							$clubCount++;
						}

						echo '
						<p>TKID:</p>
						<input type="text" name="tkid" id="tkid" />
						
						<p>Forum ID ('.garrison.'):</p>
						<input type="text" name="forumid" id="forumid" />';

						// Loop through clubs
						foreach($clubArray as $club => $club_value)
						{
							// If DB3 defined
							if($club_value['db3Name'] != "")
							{
								echo '
								<p>'.$club_value['db3Name'].':</p>
								<input type="text" name="'.$club_value['db3'].'" id="'.$club_value['db3'].'" />';
							}
						}
						
						echo '
						<p>
							<hr />
						</p>
						</span>

						<p>Supporter:</p>
						<select name="supporter" id="supporter">
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
						
						<br /><br />

						<input type="submit" name="submitUserEdit" id="submitUserEdit" value="Edit!" />
					</form>
				</div>';
			}
		}

		// Create an event form
		if(isset($_GET['do']) && $_GET['do'] == "createevent")
		{
			// Set up eid
			$eid = 0;
			
			// Set up variables for copy feature
			$name = "";
			$venue = "";
			$dateStart = "";
			$dateEnd = "";
			$website = "";
			$numberOfAttend = "";
			$requestNumber = "";
			$requestedCharacter = "";
			$secureChanging = "";
			$blasters = "";
			$lightsabers = "";
			$parking = "";
			$mobility = "";
			$amenities = "";
			$referred = "";
			$comments = "";
			$location = "";
			$label = "";
			$postComment = "";
			$notes = "";
			$limitedEvent = "";
			$limitTo = "";
			$limit501st = "";
			$limitTotalTroopers = "";
			$limitHandlers = "";
			$friendLimit = "";
			$allowTentative = "";

			// Loop through clubs
			foreach($clubArray as $club => $club_value)
			{
				// Add vars
				${$club_value['dbLimit']} = "";
			}

			$closed = "";
			$charityDirectFunds = "";
			$squad = "";
			
			// If edid set - lets load events
			if(isset($_GET['eid']) && $_GET['eid'] >= 0)
			{
				// Get data for copy troop
				$eid = cleanInput($_GET['eid']);
				
				$query = "SELECT * FROM events WHERE id = '".$eid."' LIMIT 1";
				
				// Event found
				$i = 0;

				if ($result = mysqli_query($conn, $query))
				{
					while ($db = mysqli_fetch_object($result))
					{
						$name = $db->name;
						$venue = $db->venue;
						$dateStart = $db->dateStart;
						$dateEnd = $db->dateEnd;
						$website = $db->website;
						$numberOfAttend = $db->numberOfAttend;
						$requestNumber = $db->requestedNumber;
						$requestedCharacter = $db->requestedCharacter;
						$secureChanging = $db->secureChanging;
						$blasters = $db->blasters;
						$lightsabers = $db->lightsabers;
						$parking = $db->parking;
						$mobility = $db->mobility;
						$amenities = $db->amenities;
						$referred = $db->referred;
						$comments = $db->comments;
						$location = $db->location;
						$label = $db->label;
						$postComment = $db->postComment;
						$notes = $db->notes;
						$limitedEvent = $db->limitedEvent;
						$limitTo = $db->limitTo;
						$limit501st = $db->limit501st;
						$limitTotalTroopers = $db->limitTotalTroopers;
						$limitHandlers = $db->limitHandlers;
						$friendLimit = $db->friendLimit;
						$allowTentative = $db->allowTentative;

						// Loop through clubs
						foreach($clubArray as $club => $club_value)
						{
							${$club_value['dbLimit']} = $db->{$club_value['dbLimit']};
						}

						$closed = $db->closed;
						$squad = $db->squad;

						// Increment
						$i++;
					}
				}
			}

			// JQUERY Easy Form Filler
			echo '
			<a href="#/" class="button" id="easyfilltoolbutton" name="easyfilltoolbutton">Easy Fill Tool</a>
			
			<div name="easyfilltoolarea" id="easyfilltoolarea" style="display: none;">
			<p>Easy Fill Tool:</p>
			<form action="index.php?action=commandstaff&do=createevent" method="POST" name="easyFillTool" id="easyFillTool">
				<textarea rows="10" cols="50" name="easyFill" id="easyFill"></textarea>
				<br />
				<input type="submit" name="submit" name="easyFillButton" id="easyFillButton" value="Fill!" />
			</form>

			<p><i>Copy and paste event requests in the textbox above. Please ensure all information is accurate.</i></p>
			</div>';

			// Set up show options
			$style = '';

			// If armor party
			if($label == 10)
			{
				$style = 'style="display: none;"';
			}

			// Display create event form
			echo '
			<h3>Create an Event</h3>

			<form action="process.php?do=createevent" id="createEventForm" name="createEventForm" method="POST">
				<p>Name of the event:</p>
				<input type="text" name="eventName" id="eventName" value="'.copyEvent($eid, $name).'" />

				<p>Venue of the event:</p>
				<input type="text" name="eventVenue" id="eventVenue" value="'.copyEvent($eid, $venue).'" />

				<p>Location:</p>
				<input type="text" name="location" id="location" value="'.copyEvent($eid, $location).'" />
				<input type="button" name="getLocation" id="getLocation" value="Get Squad Based On Location" />
				
				<p>Squad</p>
				<select name="squadm" id="squadm">
					<option value="null" '.copyEventSelect($eid, $squad, "null").'>Please choose an option...</option>
					'.squadSelectList(false, "copy", $eid, $squad).'
					<option value="0" '.copyEventSelect($eid, $squad, 0).'>'.garrison.'</option>
				</select>

				<p>Date/Time Start:</p>
				<input type="text" name="dateStart" id="datepicker" value="'.copyEvent($eid, $dateStart).'" />

				<p>Date/Time End:</p>
				<input type="text" name="dateEnd" id="datepicker2" value="'.copyEvent($eid, $dateEnd).'" />
				
				<div name="datetimeadd" id="datetimeadd"></div>
				
				<input type="submit" name="addshift" id="addshift" value="Add Shift" />
				
				<p>
					<i>Please note: The first date and time is considered a shift. If you add another shift with the same date and time, it will be a duplicate.</i>
				</p>

				<div id="options" '.$style.'>
					<p>Website:</p>
					<input type="text" name="website" id="website" value="'.copyEvent($eid, $website).'" />

					<p>Number of Attendees:</p>
					<input type="number" name="numberOfAttend" id="numberOfAttend" value="'.copyEvent($eid, $numberOfAttend).'" />

					<p>Requested Number of Characters:</p>
					<input type="number" name="requestedNumber" id="requestedNumber" value="'.copyEvent($eid, $requestNumber).'" />

					<p>Requested Character Types:</p>
					<input type="text" name="requestedCharacter" id="requestedCharacter" value="'.copyEvent($eid, $requestedCharacter).'" />

					<p>Secure Changing?</p>
					<select name="secure" id="secure">
						<option value="null" '.copyEventSelect($eid, $secureChanging, "null").'>Please choose an option...</option>
						<option value="1" '.copyEventSelect($eid, $secureChanging, 1).'>Yes</option>
						<option value="0" '.copyEventSelect($eid, $secureChanging, 0).'>No</option>
					</select>

					<p>Blasters Allowed?</p>
					<select name="blasters" id="blasters">
						<option value="null" '.copyEventSelect($eid, $blasters, "null").'>Please choose an option...</option>
						<option value="1" '.copyEventSelect($eid, $blasters, 1).'>Yes</option>
						<option value="0" '.copyEventSelect($eid, $blasters, 0).'>No</option>
					</select>
					
					<p>Lightsabers Allowed?</p>
					<select name="lightsabers" id="lightsabers">
						<option value="null" '.copyEventSelect($eid, $lightsabers, "null").'>Please choose an option...</option>
						<option value="1" '.copyEventSelect($eid, $lightsabers, 1).'>Yes</option>
						<option value="0" '.copyEventSelect($eid, $lightsabers, 0).'>No</option>
					</select>

					<p>Parking?</p>
					<select name="parking" id="parking">
						<option value="null" '.copyEventSelect($eid, $parking, "null").'>Please choose an option...</option>
						<option value="1" '.copyEventSelect($eid, $parking, 1).'>Yes</option>
						<option value="0" '.copyEventSelect($eid, $parking, 0).'>No</option>
					</select>

					<p>People with limited mobility access?</p>
					<select name="mobility" id="mobility">
						<option value="null" '.copyEventSelect($eid, $mobility, "null").'>Please choose an option...</option>
						<option value="1" '.copyEventSelect($eid, $mobility, 1).'>Yes</option>
						<option value="0" '.copyEventSelect($eid, $mobility, 0).'>No</option>
					</select>
				</div>

				<p>Amenities?</p>
				<input type="text" name="amenities" id="amenities" value="'.copyEvent($eid, $amenities).'" />

				<p>Additional Comments:</p>
				<a href="javascript:void(0);" onclick="javascript:bbcoder(\'B\', \'comments\')" class="button">Bold</a>
				<a href="javascript:void(0);" onclick="javascript:bbcoder(\'I\', \'comments\')" class="button">Italic</a>
				<a href="javascript:void(0);" onclick="javascript:bbcoder(\'U\', \'comments\')" class="button">Underline</a>
				<a href="javascript:void(0);" onclick="javascript:bbcoder(\'Q\', \'comments\')" class="button">Quote</a>
				<a href="javascript:void(0);" onclick="javascript:bbcoder(\'COLOR\', \'comments\')" class="button">Color</a>
				<a href="javascript:void(0);" onclick="javascript:bbcoder(\'SIZE\', \'comments\')" class="button">Size</a>
				<a href="javascript:void(0);" onclick="javascript:bbcoder(\'URL\', \'comments\')" class="button">URL</a>
				<a href="#/" class="button" name="addSmiley">Add Smiley</a>
				<textarea rows="10" cols="50" name="comments" id="comments">'.copyEvent($eid, $comments).'</textarea>

				<span name="smileyarea" style="display: block;">
				</span>

				<p>Label:</p>
				<select name="label" id="label">
					<option value="null" '.copyEventSelect($eid, $label, "null").'>Please choose an option...</option>
					<option value="0" '.copyEventSelect($eid, $label, 0).'>Regular</option>
					<option value="10" '.copyEventSelect($eid, $label, 10).'>Armor Party</option>
					<option value="1" '.copyEventSelect($eid, $label, 1).'>Charity</option>
					<option value="2" '.copyEventSelect($eid, $label, 2).'>PR</option>
					<option value="3" '.copyEventSelect($eid, $label, 3).'>Disney</option>
					<option value="4" '.copyEventSelect($eid, $label, 4).'>Convention</option>
					<option value="9" '.copyEventSelect($eid, $label, 9).'>Hospital</option>
					<option value="5" '.copyEventSelect($eid, $label, 5).'>Wedding</option>
					<option value="6" '.copyEventSelect($eid, $label, 6).'>Birthday Party</option>
					<option value="7" '.copyEventSelect($eid, $label, 7).'>Virtual Troop</option>
					<option value="8" '.copyEventSelect($eid, $label, 8).'>Other</option>
				</select>
				
				<p>
					<a href="#/" class="button" id="limitChange">Change Limits</a>
				</p>

				<div id="limitChangeArea" style="display: none;">

				<p>Post to boards?</p>
				<select name="postToBoards" id="postToBoards">
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
				
				<p>How many friends can a trooper add?</p>
				<input type="text" name="friendLimit" id="friendLimit" value="'.copyEvent($eid, $friendLimit, 4).'" />
				
				<p>Allow tentative troopers?</p>
				<select name="allowTentative" id="allowTentative">
					<option value="1" '.copyEventSelect($eid, $allowTentative, 1).'>Yes</option>
					<option value="0" '.copyEventSelect($eid, $allowTentative, 0).'>No</option>
				</select>

				<p>Is this a manual selection event?</p>
				<select name="limitedEvent" id="limitedEvent">
					<option value="0" '.copyEventSelect($eid, $limitedEvent, 0).'>No</option>
					<option value="1" '.copyEventSelect($eid, $limitedEvent, 1).'>Yes</option>
				</select>

				<p>Do you wish to limit the era of the costume?</p>
				<select name="era" id="era">
					<option value="0" '.copyEventSelect($eid, $limitTo, 0).'>Prequel</option>
					<option value="1" '.copyEventSelect($eid, $limitTo, 1).'>Original</option>
					<option value="2" '.copyEventSelect($eid, $limitTo, 2).'>Sequel</option>
					<option value="3" '.copyEventSelect($eid, $limitTo, 3).'>Expanded</option>
					<option value="4" '.copyEventSelect($eid, $limitTo, 4, 4).'>All</option>
				</select>
				
				<p>Limit of 501st Troopers:</p>
				<input type="number" name="limit501st" value="'.copyEvent($eid, $limit501st, 500).'" id="limit501st" class="limitClass" />';

				// Loop through clubs
				foreach($clubArray as $club => $club_value)
				{
					echo '
					<p>Limit of '.$club_value['name'].':</p>
					<input type="number" name="'.$club_value['dbLimit'].'" value="'.copyEvent($eid, ${$club_value['dbLimit']}, 500).'" id="'.$club_value['dbLimit'].'" class="limitClass" />';
				}

				echo '
				<p>Limit of Total Handlers:</p>
				<input type="number" name="limitHandlers" id="limitHandlers" class="limitClass" value="'.copyEvent($eid, $limitHandlers, 500).'" />
				
				<p>Limit of Total Troopers:</p>
				<input type="number" name="limitTotalTroopers" id="limitTotalTroopers" class="limitClass" value="'.copyEvent($eid, $limitTotalTroopers, 500).'" />

				<p>
					<a href="#/" class="button" id="resetDefaultCount">Reset Default</a>
				</p>
				
				</div>

				<p>Referred By:</p>
				<input type="text" name="referred" id="referred" value="'.copyEvent($eid, $referred).'" />

				<input type="submit" name="submitEvent" value="Create!" />
			</form>';
		}
	}
	else
	{
		// If the user is not an admin or logged in
		echo 'You are not command staff. Access denied!';
	}
}

// Show the FAQ page
if(isset($_GET['action']) && $_GET['action'] == "faq")
{
	echo '
	<h3>Troop Tracker - Overview Video (After Account Setup)</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/vFP6posJt70" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>Troop Tracker - How To Video</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/j0Z5SB6TVOg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>Troop Tracker - For Command Staff</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/ycwFdQQvGoc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to add an iOS shortcut</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/_UhtyHbL8uY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to add an Android shortcut</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/S4Xu_N4ByBs" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to upload photos</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/aODHyWMMVUQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to view milestones and awards</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/W-wcceu6xzI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to add a friend to a troop</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/C0WCxIRZafQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to search for troops and troop counts</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/-pXqGZLiVpM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to view past troops</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/17UPK4AoKxg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to sort by squad</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/H-nnM5jndZA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to show troops your signed up for</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/Rn3EnhudHyc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to use calendar view</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/02ERoFw7XlY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to search troops on the homepage</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/y_I8ssRjek8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to subscribe to events</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/5pp7_FKg7cI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to add an event to your calendar</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/cefnojYUy-Y" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to add discussion to a troop</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/tS-bCXbCzs4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to change the theme</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/IPykBoeDGcg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>How to search costumes</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/YLjiVGgqe-Y" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>Command Staff - Create an Event</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/MFdLu9aWwlI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>Command Staff - Edit an Event</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/WADjtDcmeJo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>Command Staff - Edit Roster</h3>
	<p>
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/c8IT_s0qSxc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>

	<h3>Troop Tracker Manual</h3>
	<p>
		<a href="https://github.com/MattDrennan/501-troop-tracker/blob/master/manual/troop_tracker_manual.pdf" target="_blank">Click here to view PDF manual.</a>
	</p>
	
	<h3>I cannot login / I forgot my password</h3>
	<p>
		The Troop Tracker has been integrated with the boards. You must use your Florida Garrison boards username and password to login to Troop Tracker. To recover your password, use password recovery on the Florida Garrison forum. If you continue to have issues logging into your account, your '.garrison.' forum username, may not match the Troop Tracker records. Contact the '.garrison.' Webmaster or post a help thread on the forums to get this corrected.
	</p>

	<h3>I am missing troop data / My troop data is incorrect</h3>
	<p>
		Please refer to your squad leader to get this corrected.
	</p>
	
	<h3>I am now a member of another club and need access to their costumes.</h3>
	<p>
		Please refer to your squad / club leader to get added to the roster.
	</p>

	<h3>My costumes are not showing on my profile / I am missing a costume on my profile</h3>
	<p>
		The troop tracker automatically scrapes several different club databases for your costume data. If your costume data is not showing, make sure your ID numbers and forum username\'s are accurate. If the aforementioned information is correct, than refer to your squad / club leadership, as this data is missing on their end.
	</p>

	<h3>How do I know I confirmed a troop?</h3>
	<p>
		The troop will be listed on your troop tracker profile, or under your stats on the troop tracker page. When you confirm a troop, your status will change from "Going" to "Attended".
	</p>

	<h3>I need a costume added to the troop tracker.</h3>
	<p>
		Please notify your squad leader, or e-mail the Garrison Web Master directly. See below for e-mail.
	</p>

	<h3>I need information on joining the 501st Legion.</h3>
	<p>
		<a href="https://databank.501st.com/databank/Join_Us" target="_blank">Click here to learn how to join.</a>
	</p>

	<h3>Contact Garrison Web Master</h3>
	<p>If you have read and reviewed all the material above and are still experiencing issues, or have noticed a bug on the website, please <a href="mailto: gwm@fl501st.com">send an e-mail here</a>.</p>';
}

/**************************** Edit Photo *********************************/

if(isset($_GET['action']) && $_GET['action'] == "editphoto")
{
	// Get data
	$query = "SELECT * FROM uploads WHERE id = '".cleanInput($_GET['id'])."'";
	$i = 0;
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			// If admin or trooper that uploaded it
			if(isAdmin() || $db->trooperid == $_SESSION['id'])
			{
				echo '
				<h3>Edit Photo: '.$db->filename.'</h3>
				
				<p>
					<img src="images/uploads/'.$db->filename.'" width="200px" height="200px" />
				</p>';

				// If is admin
				if(isAdmin())
				{
					echo '
					<p>
						<b>Uploaded by:</b> <a href="index.php?profile='.$db->trooperid.'">'.getName($db->trooperid).' - '.getTKNumber($db->trooperid, true).'</a>
					</p>';
				}

				// Check if admin photo
				if($db->admin == 0)
				{
					echo '
					<p>
						<a href="#/" photoid="'.$db->id.'" class="button" name="adminphoto">Make Troop Instruction Photo</a>
					</p>';
				}
				else
				{
					echo '
					<p>
						<a href="#/" photoid="'.$db->id.'" class="button" name="adminphoto">Make Regular Photo</a>
					</p>';
				}
				
				echo '
				<p>
					<a href="#/" photoid="'.$db->id.'" troopid="'.$db->troopid.'" class="button" name="deletephoto">Delete Photo</a>
				</p>
				
				<p>
					<a href="index.php?event='.$db->troopid.'" class="button">View Event</a>
				</p>';
			}
		}
	}
}

// Show the login page
if(isset($_GET['action']) && $_GET['action'] == "login" && !loggedIn())
{
	echo '
	<h2 class="tm-section-header">Login</h2>';

	// Display submission for register account, otherwise show the form
	if(isset($_POST['loginWithTK']))
	{
		// Login with forum
		$forumLogin = loginWithForum($_POST['tkid'], $_POST['password']);
		
		// Check credentials
		if(isset($forumLogin['success']) && $forumLogin['success'] == 1)
		{
			// Update username if changed
			$conn->query("UPDATE troopers SET forum_id = '".$forumLogin['user']['username']."' WHERE user_id = '".$forumLogin['user']['user_id']."'");
		}

		// Get data
		$query = "SELECT * FROM troopers WHERE forum_id = '".filter_var($_POST['tkid'], FILTER_SANITIZE_ADD_SLASHES)."' LIMIT 1";
		
		// Trooper count
		$i = 0;

		if ($result = mysqli_query($conn, $query))
		{
			while ($db = mysqli_fetch_object($result))
			{
				// Increment trooper count
				$i++;

				// Check if banned
				if(isset($forumLogin['success']) && $forumLogin['user']['is_banned'] == 1) {
					echo '
					<p>
						You are currently banned. Please refer to command staff for additional information.
					</p>';

					break;
				}

				// Check credentials
				if(isset($forumLogin['success']) && $forumLogin['success'] == 1 || (password_verify($_POST['password'], $db->password) && $db->permissions == 1))
				{
					if($db->approved != 0)
					{
						if(canAccess($db->id))
						{
							// Set session
							$_SESSION['id'] = $db->id;
							$_SESSION['tkid'] = $db->tkid;

							// If logged in with forum details, and password does not match
							if(isset($forumLogin['success']) && $forumLogin['success'] == 1)
							{
								// Update password, e-mail, and user ID
								$conn->query("UPDATE troopers SET password = '".password_hash($_POST['password'], PASSWORD_DEFAULT)."', email = '".$forumLogin['user']['email']."', user_id = '".$forumLogin['user']['user_id']."' WHERE id = '".$db->id."'");
							}
							
							// Set log in cookie, if set to keep logged in
							if(isset($_POST['keepLog']) && $_POST['keepLog'] == 1)
							{
								// Set cookies
								setcookie("TroopTrackerUsername", $db->forum_id, time() + (10 * 365 * 24 * 60 * 60));
								setcookie("TroopTrackerPassword", $_POST['password'], time() + (10 * 365 * 24 * 60 * 60));
							}

							// Cookie set
							if(isset($_COOKIE["TroopTrackerLastEvent"]))
							{
								echo '
								<meta http-equiv="refresh" content="5; URL=index.php?event='.cleanInput($_COOKIE["TroopTrackerLastEvent"]).'" />
								You have now logged in! <a href="index.php?event='.cleanInput($_COOKIE["TroopTrackerLastEvent"]).'">Click here to view the event</a> or you will be redirected shortly.';
								
								// Clear cookie
								setcookie("TroopTrackerLastEvent", "", time() - 3600);
							}
							else
							{
								// Cookie not set
								echo '
								You have now logged in! <a href="index.php">Click here to go home.</a>';
							}
						}
						else
						{
							echo '
							Your account is retired. Please contact your squad / club leader for further instructions on how to get re-approved.';
						}
					}
					else
					{
						echo '
						Your access has not been approved yet.';
					}
				}
				else
				{
					echo '
					<p>
						Incorrect username or password. <a href="index.php?action=login">Try again?</a>
					</p>

					<p>
						If you are unable to access your account, please contact the '.garrison.' Webmaster, or post a help request on the forums. Your FL Garrison boards name may not match the Troop Tracker records.
					</p>';
				}
			}
		}

		// An account does not exist
		if($i == 0)
		{
			echo '
			<p>Account not found. <a href="index.php?action=login">Try again?</a></p>
			
			<p>Please contact the Garrison Webmaster or post a help request on the forums, if you continue to have issues. Your FL Garrison boards name may not match the Troop Tracker records.</p>';
		}
	}
	else
	{
		echo '
		<form action="index.php?action=login" method="POST" name="loginForm" id="loginForm">
			<p>Board Name:</p>
			<input type="text" name="tkid" id="tkid" />

			<p>Password:</p>
			<input type="password" name="password" id="password" />
			
			<br /><br />
			
			<input type="checkbox" name="keepLog" value="1" /> Keep me logged in

			<br /><br />

			<input type="submit" value="Login!" name="loginWithTK" />
		</form>
		
		<p>
			<small>
				<b>Remember:</b><br />Login with your '.garrison.' board username and password.
			</small>
		</p>';
	}
}

// Show the setup page
if(isset($_GET['action']) && $_GET['action'] == "setup" && !isSignUpClosed() && !loggedIn())
{
	echo '
	<h2 class="tm-section-header">Set Up Your Account</h2>';

	// Display submission for register account, otherwise show the form
	if(isset($_POST['registerAccount']))
	{
		// Does this TK ID exist?
		if(doesTKExist(cleanInput($_POST['tkid']), cleanInput($_POST['squad'])))
		{
			// Is this TK ID registered?
			if(!isTKRegistered(cleanInput($_POST['forum_id']), cleanInput($_POST['squad'])))
			{
				// Login with forum
				$forumLogin = loginWithForum($_POST['forum_id'], $_POST['password']);

				// Verify forum login
				if(isset($forumLogin['success']) && $forumLogin['success'] == 1)
				{
					// If 501st
					if(cleanInput($_POST['squad']) <= count($squadArray))
					{
						// Query the database
						$conn->query("UPDATE troopers SET user_id = '".$forumLogin['user']['user_id']."', email = '".$forumLogin['user']['email']."', password = '".password_hash($_POST['password'], PASSWORD_DEFAULT)."', squad = '".cleanInput($_POST['squad'])."' WHERE forum_id = '".filter_var($_POST['forum_id'], FILTER_SANITIZE_ADD_SLASHES)."'");
						
						// Display output
						echo 'Your account has been registered. Please <a href="index.php?action=login">login</a>.';
					}
					else
					{
						$tkIDTaken = $conn->query("SELECT id FROM troopers WHERE tkid = '".cleanInput($_POST['tkid2'])."' AND squad = '".cleanInput($_POST['squad'])."'");
						
						if($tkIDTaken->num_rows == 0)
						{
							if(cleanInput($_POST['tkid2']) != "")
							{
								// If a club
								// Query the database
								$conn->query("UPDATE troopers SET user_id = '".$forumLogin['user']['user_id']."', email = '".$forumLogin['user']['email']."', tkid = '".cleanInput($_POST['tkid2'])."', password = '".password_hash($_POST['password'], PASSWORD_DEFAULT)."', squad = '".cleanInput($_POST['squad'])."' WHERE rebelforum = '".filter_var($_POST['tkid'], FILTER_SANITIZE_ADD_SLASHES)."'");
								
								// Display output
								echo 'Your account has been registered. Please <a href="index.php?action=login">login</a>.';
							}
							else
							{
								echo 'Please enter an ID.';
							}
						}
						else
						{
							echo 'The ID you have chosen is already in use. Please pick another.';
						}
					}
				}
				else
				{
					echo 'Incorrect username or password.';
				}
			}
			else
			{
				echo 'This TK ID or Rebel Legion user is already registred! Please contact an admin if this issue persists.';
			}
		}
		else
		{
			echo 'This TK ID or Rebel Legion user does not exist! Please contact an admin if this issue persists.';
		}
	}
	else
	{
		// Display form to register an account
		echo '
		<p style="text-align: center;">Were you already using the old trooper tracker? Set up your account by using the form below.</p>
		
		<form method="POST" action="index.php?action=setup" name="registerForm" id="registerForm">
			<p>What is your TKID (numbers only) or Rebel Forum username (if Rebel Legion member only):</p>
			<input type="text" name="tkid" id="tkid" />

			<p>'.garrison.' Board Username:</p>
			<input type="text" name="forum_id" id="forum_id" />

			<p>'.garrison.' Board Password:</p>
			<input type="password" name="password" id="password" />
			
			<p>Squad/Club</p>
			
			<select name="squad" id="squad">
				'.squadSelectList(true, "", 0, 0, true).'
			</select>

			<div style="display: none;" id="rebelid">
				<p>Choose an ID number (1 to 11 digits):</p>
				<input type="text" maxlength="11" name="tkid2" id="tkid2" />
			</div>
			
			<br /><br />

			<input type="submit" value="Set Up!" name="registerAccount" />
		</form>';
	}
}

// Show the logout page
if(isset($_GET['action']) && $_GET['action'] == "logout")
{
	// Destroy session
	session_destroy();
	
	// Make sure cookies are set before destroying
	if(isset($_COOKIE['TroopTrackerUsername']) && isset($_COOKIE['TroopTrackerPassword']))
	{
		// Destroy cookies
		setcookie("TroopTrackerUsername", "", time() - 3600);
		setcookie("TroopTrackerPassword", "", time() - 3600);
	}

	// Show logout message
	echo '
	<div style="margin-top: 25px;">
		You have logged out! <a href="index.php">Click here to go home.</a>
	</div>';
}

// If we are viewing an event, hide all other info
if(isset($_GET['event']))
{
	// Set cookie for login
	if(!loggedIn())
	{
		setcookie("TroopTrackerLastEvent", cleanInput($_GET['event']), time() + 3600);
	}
	
	// Delete Comment
	if(isset($_POST['deleteComment']) && isAdmin())
	{
		// Delete from thread forum
		deletePost(getCommentPostID(cleanInput($_POST['comment'])), true);

		// Delete
		$conn->query("DELETE FROM comments WHERE id = '".cleanInput($_POST['comment'])."'");
	}

	// Globals
	$eventClosed = 0;
	$limitedEvent = 0;
	$limitTotal = 0;
	$friendLimit = 0;
	$allowTentative = 0;
	
	// Merged troop
	$isMerged = false;
	
	// Does the event exist
	$eventExist = false;
			
	// Query database for event info
	$query = "SELECT * FROM events WHERE id = '".strip_tags(addslashes($_GET['event']))."'";
	if ($result = mysqli_query($conn, $query))
	{
		while ($db = mysqli_fetch_object($result))
		{
			// Update globals
			$eventClosed = $db->closed;
			$limitedEvent = $db->limitedEvent;
			$limitTo = $db->limitTo;
			$friendLimit = $db->friendLimit;
			$allowTentative = $db->allowTentative;
			
			// Set total
			$limitTotal = $db->limit501st;

			// Loop through clubs
			foreach($clubArray as $club => $club_value)
			{
				// Add
				$limitTotal += $db->{$club_value['dbLimit']};
			}

			// Check for total limit set, if it is, replace limit with it
			if($db->limitTotalTroopers > 500 || $db->limitTotalTroopers < 500)
			{
				$limitTotal = $db->limitTotalTroopers;
			}
			
			// Set event exist
			$eventExist = true;
					
			// Admin Area
			if(isAdmin())
			{
				echo '
				<h2 class="tm-section-header">Admin Controls</h2>
				<p style="text-align: center;"><a href="index.php?action=commandstaff&do=editevent&eid='.$db->id.'" class="button">Edit/View Event in Command Staff Area</a></p>
				<p style="text-align: center;"><a href="index.php?action=commandstaff&do=createevent&eid='.$db->id.'" class="button">Copy Event in Command Staff Area</a></p>
				<br />
				<hr />';
			}
			
			// Format dates
			$date1 = date("m/d/Y - h:i A", strtotime($db->dateStart)); 
			$date2 = date("m/d/Y - h:i A", strtotime($db->dateEnd));

			// Only show if logged in
			if(loggedIn())
			{
				// Query to see if trooper is subscribed
				$isSubscribed = $conn->query("SELECT * FROM event_notifications WHERE trooperid = '".$_SESSION['id']."' AND troopid = '".cleanInput($_GET['event'])."'");

				// Set default subscribe button text
				$subscribeText = "Subscribe Updates";

				// Check if we are subscribed
				if($isSubscribed->num_rows > 0)
				{
					$subscribeText = "Unsubscribe Updates";
				}
				
				// Create button variable
				$button = '
				<p style="text-align: center;" aria-label="Get updates on sign ups, cancellations, and discussion." data-balloon-pos="up" data-balloon-length="fit">
					<a href="#/" class="button" id="subscribeupdates" event="'.cleanInput($_GET['event']).'">'.$subscribeText.'</a>
				</p>';

				// If this event is over, don't show it
				if(strtotime($db->dateEnd) >= strtotime("-1 day") && $db->closed == 0)
				{					
					// Subscribe button
					echo $button;
					
					// Add to calendar links
					echo showCalendarLinks($db->name, $db->location, "Troop Tracker Event", $db->dateStart, $db->dateEnd);
				}
				// If subscribed, allow to unsubscribe
				else if($subscribeText == "Unsubscribe Updates")
				{
					// Subscribe button
					echo $button;
				}
			}
			
			// Is this merged data?
			if($db->venue == NULL && $db->numberOfAttend == NULL && $db->requestedCharacter == NULL && $db->secureChanging == NULL && $db->lightsabers == NULL && $db->parking == NULL && $db->mobility == NULL && $db->amenities == NULL && $db->referred == NULL)
			{	
				echo '
				<h2 class="tm-section-header">'.$db->name.'</h2>';
				
				if(loggedIn())
				{
					echo '
					<p><b>Event Date:</b> '.$date1.' ('.date('l', strtotime($db->dateStart)).')</p>
					<p><b>Comments:</b> '.ifEmpty($db->comments, "N/A").'</p>';
				}
				
				// Set is merged
				$isMerged = true;
			}
			else
			{
				// If canceled, show trooper
				if($db->closed == 2)
				{
					echo '
					<div style="text-align:center; color: red; margin-top: 25px;">
						<b>This event was CANCELED by Command Staff.</b>
					</div>';
				}
				// If locked, show trooper
				else if($db->closed == 3)
				{
					echo '
					<div style="text-align:center; color: red; margin-top: 25px;">
						<b>This event was LOCKED by Command Staff.</b>
						<br />
						<i>You will be unable to sign up at this time.</i>
					</div>';
				}
				// If full, show trooper
				else if($db->closed == 4)
				{
					echo '
					<div style="text-align:center; color: red; margin-top: 25px;">
						<b>This event was marked FULL by Command Staff.</b>
						<br />
						<i>You will be unable to sign up at this time.</i>
					</div>';
				}
				
				// Set add to title
				$add = "";
				
				if(isLink($db->id) > 0)
				{
					$add = "<b>" . date("l", strtotime($db->dateStart)) . "</b><br />".date("m/d - h:i A", strtotime($db->dateStart))." - ".date("h:i A", strtotime($db->dateEnd))."<br />";
				}
			
				// Display event info
				echo '
				<h2 class="tm-section-header">'.$add.''.$db->name.'</h2>';
				
				if(loggedIn())
				{
					// If event closed
					if($db->closed == 1)
					{
						$hours = timeBetweenDates($db->dateStart, $db->dateEnd);
						$hours += intval($db->charityAddHours);

						echo '
						<div class="charitybox"><b>Event Raised:</b><br />Direct: $'.number_format($db->charityDirectFunds).'<br />Indirect: $'.number_format($db->charityIndirectFunds).'<br />Charity Name: '.ifEmpty($db->charityName, "N/A").'<br />Charity Hours: '.$hours.'<br />Charity Note:<br />'.ifEmpty(nl2br($db->charityNote), "N/A").'</div>';
					}

					// Set up show options
					$style = '';

					// If armor party
					if($db->label == 10)
					{
						$style = 'style="display: none;"';
					}
					
					echo '
					<p><b>Venue:</b> '.$db->venue.'</p>
					<p><b>Address:</b> <a href="https://www.google.com/maps/search/?api=1&query='.$db->location.'" target="_blank">'.$db->location.'</a></p>
					<p><b>Event Start:</b> '.$date1.' ('.date('l', strtotime($db->dateStart)).')</p>
					<p><b>Event End:</b> '.$date2.' ('.date('l', strtotime($db->dateEnd)).')</p>
					<div id="options" '.$style.'>
						<p><b>Website:</b> '.validate_url($db->website).'</p>
						<p><b>Expected number of attendees:</b> '.number_format($db->numberOfAttend).'</p>
						<p><b>Requested number of characters:</b> '.number_format($db->requestedNumber).'</p>
						<p><b>Requested character types:</b> '.$db->requestedCharacter.'</p>
						<p><b>Secure changing/staging area:</b> '.yesNo($db->secureChanging).'</p>
						<p><b>Can troopers bring blasters:</b> '.yesNo($db->blasters).'</p>
						<p><b>Can troopers bring/carry prop like lightsabers:</b> '.yesNo($db->lightsabers).'</p>
						<p><b>Is parking available:</b> '.yesNo($db->parking).'</p>
						<p><b>Is venue accessible to those with limited mobility:</b> '.yesNo($db->mobility).'</p>
					</div>
					<p><b>Amenities available at venue:</b> '.ifEmpty($db->amenities, "No amenities for this event.").'</p>
					<p><b>Comments:</b><br />'.ifEmpty(nl2br(showBBcodes($db->comments)), "No comments for this event.").'</p>
					<p><b>Referred by:</b> '.ifEmpty($db->referred, "Not available").'</p>';

					// If attached to a forum thread
					if($db->thread_id > 0)
					{
						echo '
						<p><b>View post on forum:</b> <a href="https://www.fl501st.com/boards/index.php?threads/'.$db->thread_id.'" target="_blank">https://www.fl501st.com/forums/index.php?threads/'.$db->thread_id.'</a></p>';
					}
				
					// Get linked event
					$link = isLink($db->id);
					
					// If has links to event, or is linked, show shift data
					if($link > 0)
					{						
						echo '
						<h2 class="tm-section-header">Other Shifts</h2>';
						
						// Query database for photos
						$query2 = "SELECT * FROM events WHERE (id = '".$link."' OR link = '".$link."') AND id != '".$db->id."' ORDER BY dateStart DESC";
						
						if ($result2 = mysqli_query($conn, $query2))
						{
							while ($db2 = mysqli_fetch_object($result2))
							{
								echo '
								<div style="border: 1px solid gray; margin-bottom: 10px; text-align: center;">
								<a href="index.php?event=' . $db2->id . '"><b>'.date('l', strtotime($db2->dateStart)).'</b> - ' . date('M d, Y', strtotime($db2->dateStart)) . '
								<br />' .
								date('h:i A', strtotime($db2->dateStart)) . ' - ' . date('h:i A', strtotime($db2->dateEnd)) .
								'</a>
								</div>';
							}
						}
					}

					// Set up add to query
					$add = "";

					// Add to query if this is a linked event
					if($link > 0)
					{
						$add = " OR troopid = '".$link."'";
					}
					
					// Don't show photos, if merged data
					if(!$isMerged)
					{
						// Query database for photos
						$query2 = "SELECT * FROM uploads WHERE admin = '1' AND (troopid = '".cleanInput($_GET['event'])."'".$add.") ORDER BY date DESC";
						
						// Query count
						$j = 0;
						
						if ($result2 = mysqli_query($conn, $query2))
						{
							while ($db2 = mysqli_fetch_object($result2))
							{
								// If first result...
								if($j == 0)
								{
									echo '
									<hr />';
								}
								
								echo '
								<div class="container-image">
									<a href="images/uploads/'.$db2->filename.'" data-lightbox="photosadmin" data-title="Uploaded by '.getName($db2->trooperid).'" id="photo'.$db2->id.'"><img src="images/uploads/'.$db2->filename.'" width="200px" height="200px" class="image-c" /></a>
									
									<p class="container-text">';
									
										// If owned by trooper or admin
										if(isAdmin() || $db2->trooperid == $_SESSION['id'])
										{
											echo '<a href="index.php?action=editphoto&id='.$db2->id.'">Edit</a>';
										}
										
									echo '
									</p>
								</div>';
								
								// Increment photo count
								$j++;
							}
						}
					}
			
					// If this event is limited to era
					if($db->limitTo != 4)
					{
						echo '
						<br />
						<hr />
						<br />
						
						<div style="color: red;">
							This event is limited to ' . getEra($db->limitTo) . ' era.
						</div>';
					}

					// Set up is limited event?
					$isLimited = false;

					// Loop through clubs
					foreach($clubArray as $club => $club_value)
					{
						// Check if limited
						if($db->{$club_value['dbLimit']} < 500)
						{
							$isLimited = true;
						}
					}

					// Check for total limit set, if it is, set event as limited
					if($db->limitTotalTroopers > 500 || $db->limitTotalTroopers < 500)
					{
						$isLimited = true;
					}
					
					// Check for total limit set, if it is, set event as limited
					if($db->limitHandlers > 500 || $db->limitHandlers < 500)
					{
						$isLimited = true;
					}
				
					// If this event is limited in troopers
					if($db->limit501st < 500 || $isLimited)
					{
						echo '
						<br />
						<hr />
						<br />
						
						<div style="color: red;" name="troopersRemainingDisplay">
							<ul>
								<li>This event is limited to '.$limitTotal.' troopers. ';

								// Check for total limit set, if it is, add remaining troopers
								if($db->limitTotalTroopers > 500 || $db->limitTotalTroopers < 500)
								{
									echo '
									' . troopersRemaining($limitTotal, eventClubCount($db->id, "all")) . '</li>';
								}
								else
								{
									echo '
									</li>

									<li>This event is limited to '.$db->limit501st.' 501st troopers. '.troopersRemaining($db->limit501st, eventClubCount($db->id, 0)).' </li>';

									// Set up club count
									$clubCount = 1;

									// Loop through clubs
									foreach($clubArray as $club => $club_value)
									{
										echo '
										<li>This event is limited to '.$db->{$club_value['dbLimit']}.' '. $club_value['name'] .' troopers. '.troopersRemaining($db->{$club_value['dbLimit']}, eventClubCount($db->id, $clubCount)).'</li>';

										// Increment club count
										$clubCount++;
									}
								}
								
								// Check for total limit set, if it is, set event as limited
								if($db->limitHandlers > 500 || $db->limitHandlers < 500)
								{
									echo '
									<li>This event is limited to '.$db->limitHandlers.' handlers. <b>'.($db->limitHandlers - handlerEventCount($db->id)).' handlers remaining.</b></li>';
								}
						echo '
							</ul>
						</div>';
					}
					else
					{
						// If is a admin and a limited event
						if(isAdmin() && $db->limitedEvent == 1)
						{
							// All other events show counts of sign ups
							echo '
							<br />
							<hr />
							<br />
							
							<div name="troopersRemainingDisplay">
								<h3>Admin Trooper Counts</h3>

								<ul>
									<li>501st troopers: '.eventClubCount($db->id, 0).' </li>
									<li>Rebel Legion: '.eventClubCount($db->id, 1).' </li>
									<li>Mando Mercs: '.eventClubCount($db->id, 2).' </li>
									<li>Droid Builders: '.eventClubCount($db->id, 3).' </li>
									<li>Other troopers: '.eventClubCount($db->id, 4).' </li>
								</ul>
							</div>';
						}
						
						if($db->limitedEvent == 1)
						{
							echo '
							<p>
								<b>Reminder:</b> This event has been set as a <i>manual selection</i> event. When a trooper needs to make a change to their attending status or costume, troopers must comment below what changes need to be made, and command staff will make the changes. Please note, this only applies to manual selection events.
							</p>';
						}
					}
				}
			}
			
			echo '
			<div id="hr1" name="hr1">
				<br />
				<hr />
				<br />
			</div>

			<div style="overflow-x: auto;" id="signuparea1" name="signuparea1">
				'.getRoster($db->id)[0].'
			</div>

			<p>
				<a href="script/php/gencsv.php?troopid='.cleanInput($_GET['event']).'" class="button">Generate CSV</a>
			</p>';

			// HR Fix for formatting
			if(loggedIn())
			{
				echo '<hr />';
			}

			// For rosterTableNoData - If no data, this is for the AJAX of a submitted sign up form
			if($i == 0)
			{
				echo '</div>';
			}

			// If logged in and assigned to event
			if(loggedIn() && !$isMerged)
			{
				// Is the user in the event?
				$eventCheck = inEvent($_SESSION['id'], strip_tags(addslashes($_GET['event'])));

				if(strtotime($db->dateEnd) < strtotime("NOW"))
				{
					echo '
					<p style="text-align: center;">
						<b>This event is closed for editing.</b>
					</p>';
				}
				else
				{
					// TROOPER IN TROOP
					if($eventCheck['inTroop'] == 1)
					{
						if($eventCheck['status'] == 4)
						{	
							echo '
							<div name="signeduparea" id="signeduparea">
								<p>
									<b>You have canceled this troop.</b>
								</p>
							</div>';
						}
						else
						{
							// If open or locked
							if($db->closed == 0 || $db->closed == 3)
							{
								echo '
								<div name="signeduparea" id="signeduparea">
									<p>
										<b>You are signed up for this troop!</b>
									</p>
								</div>';
							}
							else
							{
								// Closed for editing
								echo '
								<p>This event is closed for editing.</p>';
							}
						}
					}
					else
					{
						// Sign up area - NOT IN TROOP
						echo '
						<div name="signuparea" id="signuparea">
							<h2 class="tm-section-header">Sign Up</h2>';
						
						// If event is not closed...
						if($db->closed == 0)
						{
							if(hasPermission(0, 1, 2, 3))
							{
								// Is this a hand picked event?
								if($db->limitedEvent == 1)
								{
									echo '<b>This is a locked event. When you sign up, you will be placed in a pending status until command staff approves you. Please check for updates.</b>';
								}

								// Get troop count
								$getNumOfTroopers = $conn->query("SELECT id FROM event_sign_up WHERE troopid = '".strip_tags(addslashes($_GET['event']))."' AND status != '4' AND status != '1'");

								// Set up total troopers
								$totalTroopers = $db->limit501st;

								// Loop through clubs
								foreach($clubArray as $club => $club_value)
								{
									$totalTroopers += $db->{$club_value['dbLimit']};
								}

								// Is the event full?
								if($getNumOfTroopers->num_rows >= $totalTroopers)
								{
									echo '
									<b>This event is full, you will be placed on the stand by list.</b>';
								}
								
								echo '
									<form action="process.php?do=signup" method="POST" name="signupForm2" id="signupForm2">
										<input type="hidden" name="event" value="'.cleanInput($_GET["event"]).'" />
										
										<p>What costume will you wear?</p>
										<select name="costume" id="costume">
											<option value="null" SELECTED>Please choose an option...</option>';

										$query3 = "SELECT * FROM costumes WHERE club != ".$dualCostume." AND";
										
										// If limited to certain costumes, only show certain costumes...
										if($db->limitTo < 4)
										{
											$query3 .= " era = '".$db->limitTo."' OR era = '4' AND ";
										}
										
										$query3 .= costume_restrict_query() . " ORDER BY FIELD(costume, ".$mainCostumes."".mainCostumesBuild($_SESSION['id'])."".getMyCostumes(getTKNumber($_SESSION['id']), getTrooperSquad($_SESSION['id'])).") DESC, costume";
										
										if ($result3 = mysqli_query($conn, $query3))
										{
											while ($db3 = mysqli_fetch_object($result3))
											{
												echo '
												<option value="'. $db3->id .'"  club="'. $db3->club .'">'.$db3->costume.'</option>';
											}
										}

									echo '
										</select>

										<br />

										<p>Select a status:</p>

										<select name="status">
											<option value="null" SELECTED>Please choose an option...</option>';

										if($db->limitedEvent != 1)
										{
											echo '
												<option value="0">I\'ll be there!</option>';
												
											// Check if tentative allowed
											if($db->allowTentative == 1)
											{
												echo '
												<option value="2">Tentative</option>';
											}
										}
										else
										{
											echo '
												<option value="5">Request to attend (Pending)</option>';								
										}

										echo '
										</select>

										<p>Back up costume (if applicable):</p>

										<select name="backupcostume" id="backupcostume">';

										// Display costumes
										$query2 = "SELECT * FROM costumes WHERE club != ".$dualCostume." AND ";
										
										// If limited to certain costumes, only show certain costumes...
										if($db->limitTo < 4)
										{
											$query2 .= " era = '".$db->limitTo."' OR era = '4' AND ";
										}
										
										$query2 .= costume_restrict_query() . " ORDER BY FIELD(costume, ".$mainCostumes."".mainCostumesBuild($_SESSION['id'])."".getMyCostumes(getTKNumber($_SESSION['id']), getTrooperSquad($_SESSION['id'])).") DESC, costume";
										// Amount of costumes
										$c = 0;
										if ($result2 = mysqli_query($conn, $query2))
										{
											while ($db2 = mysqli_fetch_object($result2))
											{
												if($c == 0)
												{
													echo '<option value="0">Select a costume...</option>';
												}

												// Display costume
												echo '<option value="'.$db2->id.'">'.$db2->costume.'</option>';

												$c++;
											}
										}

										echo '
										</select>
										
										<br />
										<br />

										<input type="submit" value="Sign Up!" name="submitSignUp" />
									</form>
								</div>';
							}
							else
							{
								echo '
								You do not have permission to sign up for events. Please refer to the boards for assistance.';
							}
						}
						else
						{
							echo '
							<p>This event is closed for editing.</p>';
						}
					}
				}
			}
		}
		
		// If event does not exist
		if(!$eventExist)
		{
			echo '
			<p style="text-align: center;">
				<b>This event does not exist.</b>
			</p>';
		}
		else
		{
			// Don't show photos, if merged data
			if(!$isMerged)
			{
				// Set up add to query
				$add = "";

				// Add to query if this is a linked event
				if(isLink(cleanInput($_GET['event'])) > 0)
				{
					$add = " OR troopid = '".isLink(cleanInput($_GET['event']))."'";
				}
				
				// Set results per page
				$results = 5;
				
				// Get total results - query
				$sqlPage = "SELECT COUNT(id) AS total FROM uploads WHERE admin = 0 AND (troopid = '".cleanInput($_GET['event'])."'".$add.")"; 
				$resultPage = $conn->query($sqlPage);
				$rowPage = $resultPage->fetch_assoc();
				
				// HR Fix for formatting
				if(loggedIn() || $rowPage["total"] > 0)
				{
					echo '<hr />';
				}
				
				// Set total pages
				$total_pages = ceil($rowPage["total"] / $results);
				
				// If page set
				if(isset($_GET['page']))
				{
					// Get page
					$page = cleanInput($_GET['page']);
					
					// Start from
					$startFrom = ($page - 1) * $results;
				}
				else
				{
					// Default page
					$page = 1;
					
					// Start from - default
					$startFrom = ($page - 1) * $results;
				}
				
				// Query database for photos
				$query = "SELECT * FROM uploads WHERE admin = '0' AND (troopid = '".cleanInput($_GET['event'])."'".$add.") ORDER BY date DESC LIMIT ".$startFrom.", ".$results."";
				
				// Count photos
				$i = 0;
				
				if ($result = mysqli_query($conn, $query))
				{
					while ($db = mysqli_fetch_object($result))
					{
						// If first result
						if($i == 0)
						{
							echo '
							<h2 class="tm-section-header" id="photo_section">Photos</h2>';
						}
						
						echo '
						<div class="container-image">
							<a href="images/uploads/'.$db->filename.'" data-lightbox="photosadmin" data-title="Uploaded by '.getName($db->trooperid).'" id="photo'.$db->id.'"><img src="images/uploads/resize/'.getFileName($db->filename).'.jpg" width="200px" height="200px" class="image-c" /></a>
							
							<p class="container-text">';
							
								// If owned by trooper or is admin
								if(isAdmin() || isset($_SESSION['id']) && $db->trooperid == $_SESSION['id'])
								{
									echo '<a href="index.php?action=editphoto&id='.$db->id.'">Edit</a>';
								}
								
							echo '
							</p>
						</div>';
						
						$i++;
					}

					// If photos
					if($i > 0)
					{
						echo '
						<p class="center-content">
							<i>Press photos for full resolution version.</i>
						</p>';
					}
				}
				
				// If photos
				if($total_pages > 1)
				{
					echo '<p>Pages: ';
					
					// Loop through pages
					for ($j = 1; $j <= $total_pages; $j++)
					{
						// If we are on this page...
						if($page == $j)
						{
							echo '
							'.$j.'';
						}
						else
						{
							echo '
							<a href="index.php?event='.cleanInput($_GET['event']).'&page='.$j.'#photo_section">'.$j.'</a>';
						}
						
						// If not that last page, add a comma
						if($j != $total_pages)
						{
							echo ', ';
						}
					}
					
					echo '</p>';
				}
				
				// If trooper logged in show uploader
				if(loggedIn())
				{
					echo '
					<p>
						<a href="#/" class="button" id="changeUpload" aria-label="Regular Upload: Share photos from troops / Instructional Image: Help troopers with troop information" data-balloon-pos="down" data-balloon-length="fit">Change To: Troop Instructional Image Upload</a>

						<form action="script/php/upload.php" class="dropzone" id="photoupload">
							<input type="hidden" name="admin" value="0" />
							<input type="hidden" name="troopid" value="'.cleanInput($_GET['event']).'" />
							<input type="hidden" name="trooperid" value="'.$_SESSION['id'].'" />
						</form>

						<!-- Image Uploader JS -->
						<script type="text/javascript">
						  
						    Dropzone.autoDiscover = false;
						  
						    var myDropzone = new Dropzone(".dropzone", { 
						       maxFilesize: 10,
						       acceptedFiles: ".jpeg,.jpg,.png,.gif"
						    });
						      
						</script>
					</p>';
				}
			}

			// HR Fix for formatting
			if(loggedIn())
			{
				echo '<hr />';
			}

			if(loggedIn() && !$isMerged)
			{
				// Check to see if this event is full
				$getNumOfTroopers = $conn->query("SELECT id FROM event_sign_up WHERE troopid = '".cleanInput($_GET['event'])."' AND status != '4' AND status != '1'");

				if($eventClosed == 0)
				{
					if(hasPermission(0, 1, 2, 3))
					{
						// Get number of troopers that trooper signed up for event
						$numFriends = $conn->query("SELECT id FROM event_sign_up WHERE addedby = '".$_SESSION['id']."' AND troopid = '".cleanInput($_GET['event'])."'");
						
						// Only show add a friend if main user is in event
						if(inEvent($_SESSION['id'], cleanInput($_GET['event']))["inTroop"] == 1 && $numFriends->num_rows < $friendLimit)
						{
							echo '
							<div id="addfriend" name="addfriend">';
						}
						else
						{
							echo '
							<div id="addfriend" name="addfriend" style="display: none;">';
						}

						// If event is full
						if($getNumOfTroopers->num_rows >= $limitTotal)
						{
							echo '
							<b>This event is full. Your friend will be placed on the stand by list.</b>';
						}
						
						echo '
						<h2 class="tm-section-header">Add a Friend</h2>';
						
						echo '
						<form action="process.php?do=signup" method="POST" name="signupForm3" id="signupForm3">
							<input type="hidden" name="event" value="'.cleanInput($_GET["event"]).'" />';
								
						// Load all users
						$query = "SELECT troopers.id AS troopida, troopers.name AS troopername, troopers.tkid, troopers.squad FROM troopers WHERE NOT EXISTS (SELECT event_sign_up.trooperid FROM event_sign_up WHERE event_sign_up.trooperid = troopers.id AND event_sign_up.troopid = '".cleanInput($_GET['event'])."' AND event_sign_up.trooperid != ".placeholder.") AND troopers.approved = 1 ORDER BY troopers.name";

						$i = 0;
						if ($result = mysqli_query($conn, $query))
						{
							while ($db = mysqli_fetch_object($result))
							{
								// First add this to make a list
								if($i == 0)
								{
									echo '
									<form action="process.php?do=editevent" method="POST" name="troopRosterFormAdd" id="troopRosterFormAdd">
										<input type="hidden" name="troopid" id="troopid" value="'.cleanInput($_GET['event']).'" />

										<p>Select a trooper to add:</p>
										<select name="trooperSelect" id="trooperSelect">';
								}
								
								// Get TKID
								$tkid = readTKNumber($db->tkid, $db->squad);

								// List troopers
								echo '
								<option value="'.$db->troopida.'" tkid="'.$tkid.'" troopername="'.$db->troopername.'">'.$db->troopername.' - '.$tkid.'</option>';
								$i++;
							}
						}

						// If no troopers
						if($i == 0)
						{
							echo 'No troopers to add.';
						}
						else
						{
							echo '
							</select>';
						}
								
						echo '
						<p>What costume will they wear?</p>
						<select name="costume" id="costume">
							<option value="null" SELECTED>Please choose an option...</option>';

						$query3 = "SELECT * FROM costumes WHERE club != ".$dualCostume."";
						
						// If limited to certain costumes, only show certain costumes...
						if($limitTo < 4)
						{
							$query3 .= " AND era = '".$limitTo."' OR era = '4'";
						}
						
						$query3 .= " ORDER BY FIELD(costume, ".$mainCostumes."".mainCostumesBuild($_SESSION['id']).") DESC, costume";
						
						if ($result3 = mysqli_query($conn, $query3))
						{
							while ($db3 = mysqli_fetch_object($result3))
							{
								echo '
								<option value="'. $db3->id .'" club="'. $db3->club .'">'.$db3->costume.'</option>';
							}
						}

						echo '
						</select>

						<br />

						<p>Select a status:</p>

						<select name="status">
							<option value="null" SELECTED>Please choose an option...</option>';

						if($limitedEvent != 1)
						{
							echo '
								<option value="0">I\'ll be there!</option>';
								
							// Check if tentative allowed
							if($allowTentative == 1)
							{
								echo '
								<option value="2">Tentative</option>';
							}
						}
						else
						{
							echo '
							<option value="5">Request to attend (Pending)</option>';								
						}

						echo '
						</select>

						<p>Back up costume (if applicable):</p>

						<select name="backupcostume" id="backupcostume">';

						// Display costumes
						$query2 = "SELECT * FROM costumes WHERE club != ".$dualCostume." AND ";
						
						// If limited to certain costumes, only show certain costumes...
						if($limitTo < 4)
						{
							$query2 .= " era = '".$limitTo."' OR era = '4' AND ";
						}
						
						$query2 .= costume_restrict_query() . " ORDER BY FIELD(costume, ".$mainCostumes."".mainCostumesBuild($_SESSION['id']).") DESC, costume";
						// Amount of costumes
						$c = 0;
						if ($result2 = mysqli_query($conn, $query2))
						{
							while ($db2 = mysqli_fetch_object($result2))
							{
								if($c == 0)
								{
									echo '<option value="0">Select a costume...</option>';
								}

								// Display costume
								echo '<option value="'.$db2->id.'">'.$db2->costume.'</option>';

								$c++;
							}
						}

						echo '
						</select>
						
						<br />
						<br />

						<input type="submit" value="Add Friend" name="submitSignUp" />
						</form>
						
						<hr />
						</div>';
					}
					else
					{
						echo '
						You do not have permission to sign up for events. Please refer to the boards for assistance.';
					}
				}
				else
				{
					//echo '
					//<p>This event is closed for editing.</p>';
				}

				echo '
				<form aciton="process.php?do=postcomment" name="commentForm" id="commentForm" method="POST">
					<input type="hidden" name="eventId" id="eventId" value="'.cleanInput($_GET['event']).'" />

					<h2 class="tm-section-header">Discussion</h2>
					<div style="text-align: center;">

					<a href="javascript:void(0);" onclick="javascript:bbcoder(\'B\', \'comment\')" class="button">Bold</a>
					<a href="javascript:void(0);" onclick="javascript:bbcoder(\'I\', \'comment\')" class="button">Italic</a>
					<a href="javascript:void(0);" onclick="javascript:bbcoder(\'U\', \'comment\')" class="button">Underline</a>
					<a href="javascript:void(0);" onclick="javascript:bbcoder(\'Q\', \'comment\')" class="button">Quote</a>
					<a href="javascript:void(0);" onclick="javascript:bbcoder(\'COLOR\', \'comment\')" class="button">Color</a>
					<a href="javascript:void(0);" onclick="javascript:bbcoder(\'SIZE\', \'comment\')" class="button">Size</a>
					<a href="javascript:void(0);" onclick="javascript:bbcoder(\'URL\', \'comment\')" class="button">URL</a>
					<a href="#/" class="button" name="addSmiley">Add Smiley</a>

					<textarea cols="30" rows="10" name="comment" id="comment"></textarea>

					<br />

					<p aria-label="This will highlight and notify command staff of your comment." data-balloon-pos="up" data-balloon-length="fit">Notify command staff?</p>
					<select name="important" id="important">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>

					<br /><br />

					<span name="smileyarea" style="display: block;">
					</span>

					<input type="submit" name="submitComment" value="Post!" />
					</div>
				</form>

				<div name="commentArea" id="commentArea">';
				
				// Set up query string
				$troops = "";
				
				// Make sure this is a linked event
				if($link > 0)
				{
					// Query database for shifts to display all comments for linked events
					$query = "SELECT * FROM events WHERE link = '".$link."'";
					
					// Set up count so we don't start with an operator
					$j = 0;
					
					// Query loop
					if ($result = mysqli_query($conn, $query))
					{
						while ($db = mysqli_fetch_object($result))
						{
							// If not first result
							if($j != 0)
							{
								$troops .= "OR ";
							}
							
							// Add to query
							$troops .= "troopid = '".$db->id."' ";
							
							// Increment j
							$j++;
						}
					}

					// Add a last OR at end if linked data
					if($j > 0)
					{
						$troops .= "OR ";
					}
				}
				
				// Check which type of link
				if(!isset($link) || isset($link) && $link <= 0)
				{
					$link = cleanInput($_GET['event']);
				}

				// Query database for event info
				$query = "SELECT * FROM comments WHERE ".$troops."troopid = '".$link."' ORDER BY posted DESC";
				
				// Count comments
				$i = 0;
				if ($result = mysqli_query($conn, $query))
				{
					while ($db = mysqli_fetch_object($result))
					{
						echo '
						<table border="1" name="comment_'.$db->id.'" id="comment_'.$db->id.'">';
						
						// Set up admin variable
						$admin = '';
						
						// If is admin, set up admin options
						if(isAdmin())
						{
							$admin = '<span style="margin-right: 15px;"><a href="#/" id="deleteComment_'.$db->id.'" name="'.$db->id.'"><img src="images/trash.png" alt="Delete Comment" /></a></span>';
						}
						
						// If is trooper, set up edit option
						if($db->trooperid == $_SESSION['id'])
						{
							$admin .= '<span style="margin-right: 15px;"><a href="#/" id="editComment_'.$db->id.'" name="'.$db->id.'"><img src="images/edit.png" alt="Edit Comment" /></a></span>';
						}
						
						// Convert date/time
						$newdate = formatTime($db->posted, "F j, Y, g:i a");

						echo '
						<tr>
							<td><span style="float: left;">'.$admin.'<a href="#/" id="quoteComment_'.$db->id.'" name="'.$db->id.'" troopername="'.getTrooperForum($db->trooperid).'" user_id="'.getUserID($db->trooperid).'" post_id="'.$db->post_id.'"><img src="images/quote.png" alt="Quote Comment"></a></span> <a href="index.php?profile='.$db->trooperid.'">'.getName($db->trooperid).' - '.readTKNumber(getTKNumber($db->trooperid), getTrooperSquad($db->trooperid)).'</a>'.getForumAvatar($db->trooperid).''.$newdate.'</td>
						</tr>
						
						<tr>
							<td name="insideComment">'.nl2br(isImportant($db->important, showBBcodes($db->comment))).'</td>
						</tr>

						</table>

						<br />';

						// Increment
						$i++;
					}
				}

				if($i == 0)
				{
					echo '
					<br />
					<b>No discussion to display.</b>';
				}
			}
		}
	}
}
else
{
	// Only show home page when it is loaded
	if(!isset($_GET['action']) && !isset($_GET['profile']) && !isset($_GET['tkid']) && !isset($_GET['event']))
	{
		if(!isWebsiteClosed())
		{
			// Show options for squad choice
			if(!loggedIn())
			{
				echo '
				<h2 class="tm-section-header">Welcome</h2>';
				
				// If sign ups are not closed
				if(!isSignUpClosed())
				{
					echo '
					<p style="text-align: center;">Welcome to the '.garrison.' troop tracker!<br /><br /><a href="index.php?action=requestaccess">Are you new to the '.garrison.' and/or 501st? Or are you solely a member of another club? Click here.</a><br /><br /><a href="index.php?action=setup">Have you used the old troop tracker and need to set up your account? Click here.</a></p>';
				}
				else
				{
					// If sign ups are closed
					echo '
					<p style="text-align: center;">Sign ups are currently locked for maintenance and testing. You can view your troops <a href="index.php?action=trooptracker">here</a>.</p>';
				}
			}
			
			if(loggedIn())
			{
				echo '
				<h2 class="tm-section-header">Troops</h2>
				<div style="text-align: center;" aria-label="Press an image to sort by squad / garrison." data-balloon-pos="down" data-balloon-length="fit">'
					. showSquadButtons() . '
				</div>
				
				<p style="text-align: center;">
					<a href="index.php?squad=mytroops" class="button">My Troops</a>
				</p>

				<hr /><br />

				<div style="text-align: center;">';
				
				// Get number of troops that need confirmed
				$numberOfConfirmTroops = $conn->query("SELECT events.id AS eventId, events.name, events.dateStart, events.dateEnd, event_sign_up.id AS signupId, event_sign_up.troopid, event_sign_up.trooperid, event_sign_up.status FROM events LEFT JOIN event_sign_up ON event_sign_up.troopid = events.id WHERE event_sign_up.trooperid = '".$_SESSION['id']."' AND events.dateEnd < NOW() AND event_sign_up.status < 3 AND events.closed = 1");
				
				// Show need to confirm if exist
				if($numberOfConfirmTroops->num_rows > 0)
				{
					echo '
					<span id="confirmTroopNotification">
						<p>
							<a href="#confirmtroops">You have '.$numberOfConfirmTroops->num_rows.' troops to confirm. Click to confirm.</a>
						</p>
						<br />
						<hr />
					</span>';
				}

				// Set up add to query
				$addToQuery = "";

				// Loop through clubs
				foreach($clubArray as $club => $club_value)
				{
					// Add
					$addToQuery .= "events.".$club_value['dbLimit'].", ";
				}
				
				// Was a squad defined? (Prevents displays div when not needed)
				if(isset($_GET['squad']) && $_GET['squad'] == "mytroops")
				{
					// Query
					$query = "SELECT events.id AS id, events.name, events.location, events.dateStart, events.dateEnd, events.squad, event_sign_up.id AS signupId, event_sign_up.troopid, event_sign_up.trooperid, events.link, events.limit501st, ".$addToQuery."events.limitTotalTroopers, events.limitHandlers, events.closed FROM events LEFT JOIN event_sign_up ON event_sign_up.troopid = events.id WHERE event_sign_up.trooperid = '".$_SESSION['id']."' AND events.dateEnd > NOW() - INTERVAL 1 DAY AND event_sign_up.status < 3 AND (events.closed = 0 OR events.closed = 3 OR events.closed = 4) ORDER BY events.dateStart";
				}
				else if(isset($_GET['squad']) && $_GET['squad'] == "canceledtroops")
				{
					// Query
					$query = "SELECT * FROM events WHERE dateStart >= CURDATE() AND (closed = '2') ORDER BY dateStart";
				}
				else if(isset($_GET['squad']))
				{
					// Query
					$query = "SELECT * FROM events WHERE dateStart >= CURDATE() AND squad = '".cleanInput($_GET['squad'])."' AND (closed = '0' OR closed = '3' OR closed = '4') ORDER BY dateStart";
				}
				else
				{
					// Query
					$query = "SELECT * FROM events WHERE dateStart >= CURDATE() AND (closed = '0' OR closed = '3' OR closed = '4') ORDER BY dateStart";
				}

				// Number of events loaded
				$i = 0;
				
				// Number of canceled events loaded
				$j = 0;
				
				// Get canceled events
				$query2 = "SELECT * FROM events WHERE DATE_FORMAT(dateStart, '%Y-%m-%d') = CURDATE() AND (closed = '2') ORDER BY dateStart";
				
				// Load events that are today or in the future
				if ($result = mysqli_query($conn, $query2))
				{
					while ($db = mysqli_fetch_object($result))
					{
						// If first result
						if($j == 0)
						{
							// Show message
							echo '
							<p>
								<b>NOTICE!! The following troops have been canceled:</b>
							</p>
							
							<ul style="display:inline-table;">';
						}
						
						echo '
						<li><a href="index.php?event='.$db->id.'">'.$db->name.'</a></li>';
						
						// Increment canceled events
						$j++;
					}
				}
				
				// If canceled event results
				if($j > 0)
				{
					echo '
					</ul>';
				}
				
				echo '
				<p><a href="#/" id="changeview" class="button">Calendar View</a></p>
				
				<div id="listview">

				<p><input type="text" id="controlf" placeholder="Type your search here..." style="text-align: center;" /></p>';
				
				// Event calendar
				$events = array();

				// Load events that are today or in the future
				if ($result = mysqli_query($conn, $query))
				{
					while ($db = mysqli_fetch_object($result))
					{
						// Get number of troopers at event
						$getNumOfTroopers = $conn->query("SELECT id FROM event_sign_up WHERE troopid = '".$db->id."' AND (status = '0' OR status = '2')");
						
						// Get number of events with link
						$getNumOfLinks = $conn->query("SELECT id FROM events WHERE link = '".$db->id."'");

						echo '
						<div style="border: 1px solid gray; margin-bottom: 10px;">
						
						<a href="index.php?event=' . $db->id . '">' . date('M d, Y', strtotime($db->dateStart)) . '' . '<br />';
						
						// If has links to event, or is linked, show shift data
						if($getNumOfLinks->num_rows > 0 || $db->link != 0)
						{
							echo '
							<b>' . date('l', strtotime($db->dateStart)) . '</b> - ' . date('h:i A', strtotime($db->dateStart)) . ' - ' . date('h:i A', strtotime($db->dateEnd)) .
							'<br />';
						}
						
						echo '
						' . $db->name . '</a>
						<br />
						<span style="font-size: 11px;"><a href="https://www.google.com/maps/search/?api=1&query='.$db->location.'" target="_blank">'.$db->location.'</a></span>';

						// Prevent on canceled events
						if($db->closed != 2)
						{
							// Set total
							$limitTotal = $db->limit501st;

							// Loop through clubs
							foreach($clubArray as $club => $club_value)
							{
								// Add
								$limitTotal += $db->{$club_value['dbLimit']};
							}

							// Check for total limit set, if it is, replace limit with it
							if($db->limitTotalTroopers > 500 || $db->limitTotalTroopers < 500)
							{
								$limitTotal = $db->limitTotalTroopers;
							}

							// Troop set to full
							if($db->closed == 4) {
								echo '
								<br />
								<span style="color:green;"><b>THIS TROOP IS FULL!</b></span>';		
							}
							// If not enough troopers
							else if($getNumOfTroopers->num_rows <= 1)
							{
								echo '
								<br />
								<span style="color:red;"><b>NOT ENOUGH TROOPERS FOR THIS EVENT!</b></span>';
							}
							// If full (w/ handlers)
							else if($getNumOfTroopers->num_rows >= $limitTotal && ($db->limitHandlers > 500 || $db->limitHandlers < 500) && (handlerEventCount($db->id) >= $db->limitHandlers))
							{
								echo '
								<br />
								<span style="color:green;"><b>THIS TROOP IS FULL!</b></span>';
							}
							// If full
							else if($getNumOfTroopers->num_rows >= $limitTotal && $db->limitHandlers == 500)
							{
								// Check handler count
								if($db->limitHandlers == 500) {
									echo '
									<br />
									<span style="color:green;"><b>THIS TROOP IS FULL!</b></span>';
								} else {
									$getNumOfHandlers = $conn->query("SELECT id FROM event_sign_up WHERE (status = '0' OR status = '2') AND troopid = '".$db->id."' AND (SELECT costume FROM costumes WHERE id = event_sign_up.costume) LIKE '%handler%'");

									// Check if handlers full
									if($getNumOfHandlers->num_rows >= $db->limitHandlers) {
										echo '
										<br />
										<span style="color:green;"><b>THIS TROOP IS FULL!</b></span>';
									} else {
										// Show troopers attending
										echo '
										<br />
										<span>'.$getNumOfTroopers->num_rows.' Troopers Attending</span>';
									}
								}
							}
							// Everything else
							else
							{
								echo '
								<br />
								<span>'.$getNumOfTroopers->num_rows.' Troopers Attending</span>';
							}
						}

						// Increment number of events
						$i++;
						
						// Set up string to add to title if a linked event
						$add = "";
						
						// If this a linked event?
						if(isLink($db->id) > 0)
						{
							$add .= "[" . date("h:i A", strtotime($db->dateStart)) . " - " . date("h:i A", strtotime($db->dateEnd)) . "] ";
						}
						
						// Add to calendar
						$events[] = array(
							'start' => date('Y-m-d', strtotime($db->dateStart)),
							'end' => date('Y-m-d', strtotime($db->dateEnd)),
							'summary' => '<a href="index.php?event='.$db->id.'" title="'.$db->name.'">' . $add . '' . $db->name . '</a><br /><br />',
							'mask' => true,
						);

						echo '
						</div>';
					}
				}
				
				// Add events to calendar
				$calendar->addEvents($events);
				
				// One month from today
				$datec1 = date('Y-m-d', strtotime('first day of +1 month'));
				
				// Two months from today
				$datec2 = date('Y-m-d', strtotime('first day of +2 month'));
				
				// Show calendars
				echo '
				</div>
				
				<div id="calendarview" style="display: none;">'
				. $calendar->draw(date('Y-m-d'))
				. '<br />' .
				$calendar->draw($datec1)
				. '<br />' .
				$calendar->draw($datec2)
				. '</div>';

				// Home page, no events
				if($i == 0)
				{
					echo 'There are no events to display.';
				}			

				echo '
				</div>';
			}

			if(loggedIn())
			{
				// Hide canceled troops button if we are on the page
				if((isset($_GET['squad']) && $_GET['squad'] != "canceledtroops") || !isset($_GET['squad']))
				{
					echo '
					<p style="text-align: center;">
						<a href="index.php?squad=canceledtroops" class="button">Canceled Troop Noticeboard</a>
					</p>';

					echo '
					<h2 class="tm-section-header">Recently Finished</h2>
					
					<ul>';
					
					// If on my troops
					if(isset($_GET['squad']) && $_GET['squad'] == "mytroops")
					{
						// Set up add to query
						$addToQuery = "";

						// Loop through clubs
						foreach($clubArray as $club => $club_value)
						{
							// Add
							$addToQuery .= "events.".$club_value['dbLimit'].", ";
						}

						// Query
						$query = "SELECT events.id AS id, events.name, events.dateStart, events.dateEnd, events.squad, event_sign_up.id AS signupId, event_sign_up.troopid, event_sign_up.trooperid, event_sign_up.status, events.link, ".$addToQuery."events.limit501st FROM events LEFT JOIN event_sign_up ON event_sign_up.troopid = events.id WHERE event_sign_up.trooperid = '".$_SESSION['id']."' AND events.closed = 1 ORDER BY dateEnd DESC LIMIT 20";
					}
					// If on squad
					else if(isset($_GET['squad']))
					{
						// Get recently closed troops by squad
						$query = "SELECT * FROM events WHERE closed = '1' AND squad = '".cleanInput($_GET['squad'])."' ORDER BY dateEnd DESC LIMIT 20";
					}
					// If on default
					else
					{
						// Get recently closed troops
						$query = "SELECT * FROM events WHERE closed = '1' ORDER BY dateEnd DESC LIMIT 20";
					}
					
					// Load events that are today or in the future
					if ($result = mysqli_query($conn, $query))
					{
						while ($db = mysqli_fetch_object($result))
						{
							// Set up string to add to title if a linked event
							$add = "";
							
							// If this a linked event?
							if(isLink($db->id) > 0)
							{
								$add .= "[<b>" . date("l", strtotime($db->dateStart)) . "</b> : <i>" . date("m/d - h:i A", strtotime($db->dateStart)) . " - " . date("h:i A", strtotime($db->dateEnd)) . "</i>] ";
							}

							// Show strikethrough if troop is canceled
							$add2 = "";

							if(isset($db->status) && $db->status == 4) {
								$add2 = 'class = "canceled-troop"';
							}
							
							echo '
							<li><a href="index.php?event='.$db->id.'" '.$add2.'>'.$add.''.$db->name.'</a></li>';
						}
					}
					
					echo '
					</ul>';
				}
				
				// Load events that need confirmation
				$query = "SELECT events.id AS eventId, events.name, events.dateStart, events.dateEnd, event_sign_up.id AS signupId, event_sign_up.troopid, event_sign_up.trooperid, event_sign_up.status FROM events LEFT JOIN event_sign_up ON event_sign_up.troopid = events.id WHERE event_sign_up.trooperid = '".$_SESSION['id']."' AND events.dateEnd < NOW() AND event_sign_up.status < 3 AND events.closed = 1";

				if ($result = mysqli_query($conn, $query))
				{
					// Number of results total
					$i = 0;

					while ($db = mysqli_fetch_object($result))
					{
						// If data
						if($i == 0)
						{
							echo '
							<br />
							<hr />
							<div name="confirmArea" id="confirmArea">
							<h2 class="tm-section-header" id="confirmtroops">Confirm Troops</h2>
							<form action="process.php?do=confirmList" method="POST" name="confirmListForm" id="confirmListForm">
							<div name="confirmArea2" id="confirmArea2">';
						}
						
						// Set up string to add to title if a linked event
						$add = "";
						
						// If this a linked event?
						if(isLink($db->eventId) > 0)
						{
							$add .= "[<b>" . date("l", strtotime($db->dateStart)) . "</b> : <i>" . date("m/d - h:i A", strtotime($db->dateStart)) . " - " . date("h:i A", strtotime($db->dateEnd)) . "</i>] ";
						}

						echo '
						<div name="confirmListBox_'.$db->eventId.'" id="confirmListBox_'.$db->eventId.'">
							<input type="checkbox" name="confirmList[]" id="confirmList_'.$db->eventId.'" value="'.$db->eventId.'" /> '.$add.''.$db->name.'<br /><br />
						</div>';
						
						// If a shift exists to attest to
						$i++;
					}
				}

				// If data
				if($i > 0)
				{
					echo '
						</div>
						<input type="submit" name="submitConfirmList" id="submitConfirmList" value="I attended these troops" />
						<input type="submit" name="submitConfirmListDelete" id="submitConfirmListDelete" value="I did NOT attend these troops" />
						<p>Attended Costume:</p>
						<select name="costume" id="costumeChoice">';

						$query3 = "SELECT * FROM costumes " . costume_restrict_query(true) . " ORDER BY FIELD(costume, ".$mainCostumes."".mainCostumesBuild($_SESSION['id'])."".getMyCostumes(getTKNumber($_SESSION['id']), getTrooperSquad($_SESSION['id'])).") DESC, costume";
						
						$l = 0;
						if ($result3 = mysqli_query($conn, $query3))
						{
							while ($db3 = mysqli_fetch_object($result3))
							{
								if($l == 0)
								{
									echo '
									<option value="">Please choose an option...</option>';
								}

								echo '
								<option value="'. $db3->id .'">'.$db3->costume.'</option>';

								$l++;
							}
						}

					echo '
						</select>
					</form>
					<p>If your costume is not listed, please notify the garrison web master before confirming.</p>
					</div>';
				}
			}
			
			echo '
			<h2 class="tm-section-header">Recent Photos</h2>';
			
			// Load photos
			$query = "SELECT * FROM uploads WHERE admin = '0' ORDER BY id DESC LIMIT 10";
			
			// Setup count
			$i = 0;
			
			// Loop through photos
			if ($result = mysqli_query($conn, $query))
			{
				while ($db = mysqli_fetch_object($result))
				{
					echo '
					<a href="images/uploads/'.$db->filename.'" data-lightbox="photo" data-title="Uploaded by '.getName($db->trooperid).' on '.getEventTitle($db->troopid, true).'."><img src="images/uploads/resize/'.getFileName($db->filename).'.jpg" width="200px" height="200px" /></a>';
					
					// Increment
					$i++;
				}
			}
			
			// If no photos
			if($i == 0)
			{
				echo '
				<p style="text-align: center;">
					No photos to display.
				</p>';
			}
			else
			{
				echo '
				<p style="text-align: center;">
					<i>Press photos for full resolution version.</i>
					<br />
					<a href="index.php?action=photos" class="button">Recent events with photos</a>
				</p>';
			}
		}
		else
		{
			echo '
			<h2 class="tm-section-header">Sorry...</h2>
			
			<p style="text-align: center;">
				The Troop Tracker is temporarily down for maintenance. Please check back later.
			</p>';
		}
	}
}

echo '
</section>';

if(!isWebsiteClosed())
{
	// User's online
	echo '
	<hr />
	<section class="tm-section tm-section-small">
	<h2 class="tm-section-header">Users Online</h2>
	<p style="text-align: center;">';

	// Load users online
	$query = "SELECT * FROM troopers WHERE last_active >= NOW() - INTERVAL 5 MINUTE ORDER BY tkid";
	if ($result = mysqli_query($conn, $query))
	{
		$i = 0;
		while ($db = mysqli_fetch_object($result))
		{
			if($i != 0)
			{
				echo ', ';
			}

			echo '<a href="index.php?profile='.$db->id.'">' . readTKNumber($db->tkid, $db->squad) . '</a>';

			$i++;
		}
	}

	if($i == 0)
	{
		echo 'No users online!';
	}

	echo '
	</p>
	</section>';
}

echo '
<hr />

<section class="tm-section tm-section-small">
<p class="tm-mb-0">
Website created by <a href="index.php?profile=644">Matthew Drennan (TK52233)</a>. If you encounter any technical issues with this site, please refer to the <a href="index.php?action=faq">FAQ page</a> for guidance.
</p>

<p class="tm-mb-0">
If you are missing troops or notice incorrect data, please refer to your squad leader.
</p>

<p class="footer-icons">
	<a href="https://github.com/MattDrennan/501-troop-tracker" target="_blank"><img src="images/github.png" alt="GitHub" title="Help contribute to the Troop Tracker project!" /></a> ';
	// Discord / Twitter
	if(loggedIn())
	{
		echo '
		<a href="https://discord.gg/C6bCB33gp3" target="_blank"><img src="images/discord.png" alt="Discord" title="Get event notifications and more on Discord!" /></a> 
		<a href="https://twitter.com/FLTroopUpdates" target="_blank"><img src="images/twitter.png" alt="Twitter" title="Get event notifications and more on Twitter!" /></a>';
	}
echo '
</p>
</section>

<script>
$(document).ready(function()
{';

// Loop through clubs - add rules for validation
foreach($clubArray as $club => $club_value)
{
	// Get array
	$getArray = explode(",", $club_value['db3Require']);

	// If squad set
	if($getArray[2] != "0")
	{
		// Get squad
		$getSquad = explode(":", $getArray[2])[1];

		echo '
	    // Add rules to clubs - IDs
	    $(\'#'.$club_value['db3'].'\').each(function()
	    {
	        $(this).rules(\'add\',
	        {';
	        	// If digits
	        	if($getArray[1] == "digits")
	        	{
					echo '
					digits: true,';
				}
				else
				{
					echo '
					digits: false,';
				}

				// If squad
				if($getArray[2] != "0")
				{
					echo '
					required: function()
					{
						return $(\'#squad\').val() == '.$getSquad.';
					}';
				}
				else
				{
					echo '
					required: false';
				}

			echo '
	        })
	    });';
	}
	else
	{
		// If value set
		if($club_value['db3'] != "")
		{
			// Squad not set
			echo '
		    // Add rules to clubs - IDs
		    $(\'#'.$club_value['db3'].'\').each(function()
		    {
		        $(this).rules(\'add\',
		        {';
		        	// If digits
		        	if($getArray[1] == "digits")
		        	{
						echo '
						digits: true,';
					}
					else
					{
						echo '
						digits: false,';
					}

					echo '
					required: false
		        })
		    });';
		}
	}
}

echo '
});
</script>';

echo '
<!-- External JS File -->
<script type="text/javascript" src="script/js/main.js"></script>
</body>
</html>';

?>