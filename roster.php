<?php

/**
 * This file is used for displaying the garrison roster.
 *
 * @author  Matthew Drennan
 *
 */

include 'config.php';

echo '
<style>
.container {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
}

.container .title {
	max-width: 150px;
	flex: 1;
	text-align: center;
	border: 1px dashed #000;
	margin: 20px;
}

.container div {
	max-width: 75px;
	flex: 1;
	text-align: center;
	border: 1px dashed #000;
	margin: 20px;
}

a {
	color: #323f4e;
}

h1 {
	text-align: center;
}

.thumbnail {
	width: 75px;
	height 101px;
}

.rank {
	width: 150px;
	height: 30px;
}
</style>

<h1>Member Titles</h1>

<div class="container">';

// Show all super admins
$query = "SELECT troopers.id, troopers.name, troopers.tkid, troopers.squad, titles.title, titles.icon FROM troopers LEFT JOIN title_troopers ON troopers.id = title_troopers.trooperid LEFT JOIN titles ON title_troopers.titleid = titles.id WHERE p501 = '1' AND (permissions = 1 OR permissions = 2) AND titles.title != '' ORDER BY name";

// Set up added array
$added = array();

// Trooper count set up
$i = 0;

if ($result = mysqli_query($conn, $query))
{
	while ($db = mysqli_fetch_object($result))
	{
		if(!in_array($db->id, $added))
		{
			echo '
			<div class="title">';
			// Show 501 thumbnail
			if(getTrooperSquad($db->tkid) <= count($squadArray))
			{
				// Get 501st thumbnail Info
				$thumbnail_get = $conn->query("SELECT thumbnail FROM 501st_troopers WHERE legionid = '".$db->tkid."'");
				$thumbnail = $thumbnail_get->fetch_row();
			}
			// No thumbnail
			if(!isset($thumbnail[0]))
			{
				echo '<img src="images/tk_head.jpg" class="thumbnail" />';
			}
			else
			{
				// Thumbnail exists
				echo '
				<img src="'.$thumbnail[0].'" class="thumbnail" />';
			}
			echo '
			<br />
			<a href="index.php?profile='.$db->id.'" target="_blank">'.readInput($db->name).' - '.readTKNumber($db->tkid, $db->squad).'</a>';
			array_push($added, $db->id);
			
			if($db->title != "" && !is_null($db->title))
			{
				echo '
				<p><img src="images/ranks/'.$db->icon.'" class="rank" /></p>';
			}
			
			echo '
			</div>';
		}
		
		// Increment
		$i++;
	}
}

// No troopers
if($i == 0)
{
	echo '<li>No member titles to display.</li>';
}

echo '
</div>

<hr />

<h1>Members</h1>

<div class="container">';

// Show all super admins
$query = "SELECT troopers.id, troopers.name, troopers.tkid, troopers.squad, titles.title, titles.icon FROM troopers LEFT JOIN title_troopers ON troopers.id = title_troopers.trooperid LEFT JOIN titles ON title_troopers.titleid = titles.id WHERE p501 = '1' AND troopers.id != ".placeholder." ORDER BY name";

// Trooper count set up
$i = 0;

if ($result = mysqli_query($conn, $query))
{
	while ($db = mysqli_fetch_object($result))
	{
		if(!in_array($db->id, $added))
		{
			echo '
			<div>';
				// Show 501 thumbnail
				if(getTrooperSquad($db->tkid) <= count($squadArray))
				{
					// Get 501st thumbnail Info
					$thumbnail_get = $conn->query("SELECT thumbnail FROM 501st_troopers WHERE legionid = '".$db->tkid."'");
					$thumbnail = $thumbnail_get->fetch_row();
				}
				// No thumbnail
				if(!isset($thumbnail[0]))
				{
					echo '<img src="images/tk_head.jpg" class="thumbnail" />';
				}
				else
				{
					// Thumbnail exists
					echo '
					<img src="'.$thumbnail[0].'" class="thumbnail" />';
				}
				echo '
				<br />
				<a href="index.php?profile='.$db->id.'" target="_blank">'.readInput($db->name).' - '.readTKNumber($db->tkid, $db->squad).'</a>
			</div>';
		}
		
		// Increment
		$i++;
	}
}

// No troopers
if($i == 0)
{
	echo '<li>No members display.</li>';
}

echo '
</div>';

?>
