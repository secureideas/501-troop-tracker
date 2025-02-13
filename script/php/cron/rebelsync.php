<?php

/**
 * This file is used for scraping Rebel Legion data.
 * 
 * This should be run weekly by a cronjob.
 *
 * @author  Matthew Drennan
 *
 */

// Include config
include(dirname(__DIR__) . '/../../config.php');

// Get Simple PHP DOM Tool - just a note, for this code to work, $stripRN must be false in tool
include(dirname(__DIR__) . '/../../tool/dom/simple_html_dom.php');

// Check date time for sync
$query = "SELECT syncdaterebels FROM settings";
if ($result = mysqli_query($conn, $query))
{
	while ($db = mysqli_fetch_object($result))
	{
		// Compare dates
		if(strtotime($db->syncdaterebels) >= strtotime("-7 day"))
		{
			// Prevent script from continuing
			die("Already updated recently.");
		}
	}
}

// Purge rebel troopers
$conn->query("DELETE FROM rebel_troopers");

// Purge rebel costumes
$conn->query("DELETE FROM rebel_costumes");

// Costume image array (duplicate check)
$costumeImagesG = array();

// Loop through all records
for($i = 0; $i <= 1000; $i += 10)
{
	// get DOM from URL or file
	$html = file_get_html('https://www.forum.rebellegion.com/baza2.php?b=10&start=' . $i);
	
	// Did we find an array
	$isArrayContained = false;

	// Loop through comments
	foreach($html->find('comment') as $e)
	{
		// If comment contains array
		if(contains($e, "Array"))
		{
			// Find where it says array
			$arrayI = strpos($e, "Array");
			
			// Find where array ends
			$arrayI2 = strrpos($e, ")");
			
			// If there is a start and end of an array
			if($arrayI != 0 && $arrayI2 != 0)
			{
				// Set up array string
				$stringArray = str_replace("-->", "", trim(substr($e, $arrayI, $arrayI2)));
				
				// Convert string (print_r) to array
				$array = print_r_reverse($stringArray);
				
				// Loop through arrays
				foreach($array as $innerRow => $innerArray)
				{
					// Rebel ID - Setup
					$rebelID = 0;
					
					// Rebel Name - Setup
					$rebelName = "";
					
					// Rebel Forum - Setup
					$rebelForum = "";
					
					// Loop through inner arrays
					foreach($innerArray as $key => $a)
					{
						// If first value (ID)
						if($key == 0)
						{
							// Set Rebel ID
							$rebelID = $innerArray[0];
							
							// Search ID for profile
							$html2 = file_get_html('https://www.forum.rebellegion.com/forum/profile.php?mode=viewprofile&u=' . $innerArray[0]);
							
							// Costume name array
							$costumeNames = array();
							
							// Costume image array
							$costumeImages = array();
							
							// Set should add to prevent duplicates
							$addTo = true;
							
							// Loop through costume images on profile
							foreach($html2->find('img[height=125]') as $r)
							{
								//echo $r->src;
								
								// Check to see if exists in duplicate array
								if(in_array(str_replace("sm", "-A", $r->src), $costumeImagesG))
								{
									$addTo = false;
								}
								
								// Check to see if we can add (duplicates)
								if($addTo)
								{
									// Push to array
									array_push($costumeImages, str_replace("sm", "-A", $r->src));
									
									// Push to array (to check for duplicates)
									array_push($costumeImagesG, str_replace("sm", "-A", $r->src));
								}
							}
							
							// Loop through costume names
							foreach($html2->find('span[class=gen]') as $s)
							{
								// Get bolds
								foreach($s->find('b') as $b)
								{
									// Get links
									foreach($b->find('a') as $a)
									{
										//echo $a->innertext . '<br />';
										
										// Prevent an issue where it inserts a 1
										if($a->innertext != 1 && !contains($a->innertext, "http"))
										{
											// Check to see if we can add (duplicates)
											if($addTo)
											{
												// Push to array
												array_push($costumeNames, $a->innertext);
											}
										}
									}
								}
							}
							
							// Start i count
							$cc = 0;

							// Loop through created arrays
							foreach($costumeNames as $c)
							{
								// Query
								$conn->query("INSERT INTO rebel_costumes (rebelid, costumename, costumeimage) VALUES ('".cleanInput($innerArray[0])."', '".cleanInput($costumeNames[$cc])."', '".cleanInput($costumeImages[$cc])."')");
								
								echo $innerArray[0] . ' - ' . $costumeNames[$cc] . ' - ' . $costumeImages[$cc] . ' <br />';
								
								// Increment
								$cc++;
							}
						}
						// Rebel Forum
						else if($key == 1)
						{
							// Set Rebel Forum
							$rebelForum = $a;
						}
						else if($key == 2)
						{
							// Set Rebel Name
							$rebelName = $a;
						}
					}
					
					// Query
					$conn->query("INSERT INTO rebel_troopers (rebelid, name, rebelforum) VALUES ('".cleanInput($rebelID)."', '".cleanInput($rebelName)."', '".cleanInput($rebelForum)."')");
				}
			}
			
			// Found an array
			$isArrayContained = true;
		}
	}
	
	// An array was not contained
	if(!$isArrayContained)
	{
		// Stop loop
		break;
	}
}

// Pull extra data from spreadsheet
$values = getSheet("1I3FuS_uPg2nuC80PEA6tKYaVBd1Qh1allTOdVz3M6x0", "Troopers");

// Set up count
$i = 0;

foreach($values as $value)
{
	// If not first
	if($i != 0)
	{
		// Query
		$conn->query("INSERT INTO rebel_troopers (rebelid, name, rebelforum) VALUES ('".$value[0]."', '".$value[1]."', '".$value[2]."')");
	}

	// Increment
	$i++;
}

// Pull extra data from spreadsheet
$values = getSheet("1I3FuS_uPg2nuC80PEA6tKYaVBd1Qh1allTOdVz3M6x0", "Costumes");

// Set up count
$i = 0;

foreach($values as $value)
{
	// If not first
	if($i != 0)
	{
		// Insert into database
		$conn->query("INSERT INTO rebel_costumes (rebelid, costumename, costumeimage) VALUES ('".$value[0]."', '".$value[1]."', '".$value[2]."')");
	}

	// Increment
	$i++;
}

echo '
COMPLETE!';

// Update date time for last sync
$conn->query("UPDATE settings SET syncdaterebels = NOW()");

/**
 * Convert a string (print_r) back to an array value
 * 
 * @param string $input The string value to be formatted
 * @return array Returns PHP array
 */
function print_r_reverse($input)
{
    $lines = preg_split('#\r?\n#', trim($input));
    if (trim($lines[0]) != 'Array' && trim($lines[0] != 'stdClass Object'))
    {
        // bottomed out to something that isn't an array or object
        if ($input === '')
        {
            return null;
        }
        return $input;
    }
    else
    {
        // this is an array or object, lets parse it
        $match = array();
        if (preg_match("/(\s{5,})\(/", $lines[1], $match))
        {
            // this is a tested array/recursive call to this function
            // take a set of spaces off the beginning
            $spaces = $match[1];
            $spaces_length = strlen($spaces);
            $lines_total = count($lines);
            for ($i = 0; $i < $lines_total; $i++)
            {
                if (substr($lines[$i], 0, $spaces_length) == $spaces)
                {
                    $lines[$i] = substr($lines[$i], $spaces_length);
                }
            }
        }
        $is_object = trim($lines[0]) == 'stdClass Object';
        array_shift($lines); // Array
        array_shift($lines); // (
        array_pop($lines); // )
        $input = implode("\n", $lines);
        $matches = array();
        // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one)
        preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $input, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        $pos = array();
        $previous_key = '';
        $in_length = strlen($input);
        // store the following in $pos:
        // array with key = key of the parsed array's item
        // value = array(start position in $in, $end position in $in)
        foreach($matches as $match)
        {
            $key = $match[1][0];
            $start = $match[0][1] + strlen($match[0][0]);
            $pos[$key] = array($start, $in_length);
            if ($previous_key != '')
            {
                $pos[$previous_key][1] = $match[0][1] - 1;
            }
            $previous_key = $key;
        }
        $ret = array();
        foreach($pos as $key => $where)
        {
            // recursively see if the parsed out value is an array too
            $ret[$key] = print_r_reverse(substr($input, $where[0], $where[1] - $where[0]));
        }

        return $is_object ? (object) $ret : $ret;
    }
}

/**
 * Checks if string is inside another string
 * 
 * This is used due to PHP version issues.
 * 
 * @param string $haystack The string value to search
 * @param string $needle The string value to find
 * @return boolean Returns if found
 */
function contains($haystack, $needle)
{
	return $needle !== '' && mb_strpos($haystack, $needle) !== false;
}

?>