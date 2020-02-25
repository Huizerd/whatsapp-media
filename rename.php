<html>
<head>
	<title>MediaRename for Whatsapp media</title>
	<style type="text/css">
		*{
			font-family: Arial;
		}

		#top{
			display: table-header-group;
		}

		#middle{
			display: table-row-group;
		}

		#bottom{
			display: table-footer-group;
		}

		.red{
			color: red;
		}
	</style>
</head>

<body>

	<?php
	
		//Variables in order to connect to database
		$host_connect = "localhost";
		$user_connect = "root";
		$password_connect = "";
		$database_connect = "WhatsappMedia";
		
		//Connect to mysql
		$connection = mysqli_connect($host_connect, $user_connect, $password_connect)
		or die ("Can't connect to MySQL: " . mysql_error());
		
		//Connect to database
		$db = mysqli_select_db($connection, $database_connect)
		or die ("Can't connect to database: " . mysql_error());



		/////////////////////////////////////////////////////////////
		//                   Rename Files                          //
		/////////////////////////////////////////////////////////////

		//Query to select necessary data
		$sql="SELECT media.Z_PK, media.ZMEDIALOCALPATH, messages.ZMESSAGEDATE 
				FROM media
				INNER JOIN messages
				ON media.ZMESSAGE=messages.Z_PK
                                WHERE media.ZMEDIALOCALPATH!=''
				ORDER BY media.Z_PK;";

		//Run query and save in $result
		$result = mysqli_query($connection, $sql);

		if (!$result) { // add this check.
		    die('Invalid query: ' . mysql_error());
		}

		$filerenamescount = 0;

		echo "<div id=bottom>";
		echo "------------------------------------------------------------<br>";
		echo "<span class=red>Renamed media files:</span><br>";

		// TO DO: testing on the number of rows is probably a better test than
		// the test whether $result exists
		echo "Number of rows is ".mysqli_num_rows($result)."<br>";

		//For every mediaobject...
		while ($row = mysqli_fetch_array($result)){
			
			//Get variables from database
			$timestamp = $row["ZMESSAGEDATE"];
			$path = $row["ZMEDIALOCALPATH"];

			//Convert timestamp into date and time
			$timestamp = $timestamp + 978307200;
			$date = date('Y-m-d H.i.s', $timestamp);

			//Remove the '/' that sometimes excists at the beginning of the path
			$old_name_array = preg_split('@/@', $path, NULL, PREG_SPLIT_NO_EMPTY);
			$old_name = implode('/', $old_name_array);
			//Get the extension of the file
			$a = end($old_name_array);
			$a = explode('.', $a);
			$ext = end($a);

			//New name
			$length = count($old_name_array);
			$length = $length - 1;
			$new_name_array = array_slice($old_name_array, 0 , $length);
			$new_name = implode('/', $new_name_array);
			$new_name = $new_name . "/" . $date . "." . $ext;

			//Rename
			if (file_exists($old_name)) {
				rename($old_name, $new_name);
				$filerenamescount = $filerenamescount + 1;
				echo "Renamed ".$old_name." to ".$new_name."<br>";
			}

		}
		
		echo "------------------------------------------------------------<br>";
		echo "</div>";


		/////////////////////////////////////////////////////////////
		//                   Rename Folders                        //
		/////////////////////////////////////////////////////////////

		//Query to select necessary data
		$sql="SELECT ZPARTNERNAME, ZCONTACTJID
				FROM chats;";

		//Run query and save in $result
		$result = mysqli_query($connection, $sql);
		$unknowncount = 1;
		$broadcastcount = 1;
		$folderrenamescount = 0;

		echo "<div id=middle>";
		echo "<span class=red>Renamed folders:</span><br>";

		//For every group...
		while ($row = mysqli_fetch_array($result)){
			
			//Get variables from database
			$chatname = $row["ZPARTNERNAME"];
			$map = $row["ZCONTACTJID"];

			if (strpos($map, '@broadcast') !== false) {
				$chatname = 'broadcast' . $broadcastcount;
				$broadcastcount = $broadcastcount + 1;
			}

			//Remove unwanted characters
			$chatname = preg_replace("/[^A-Za-z0-9 ]/","",$chatname);

			//Rename empty folders to 'unknown'
			if (empty($chatname)) {
				$chatname = "unknown" . $unknowncount;
				$unknowncount = $unknowncount + 1;
			}

			//Old en new path
			$old_path = "Media/" . $map;
			$new_path = "Media/" . $chatname;

			//Rename to chatname
			if (file_exists($old_path)) {
				rename($old_path, $new_path);
				$folderrenamescount = $folderrenamescount + 1;
				echo "Renamed ".$old_path." to ".$new_path."<br>";
			}

		}
		
		echo "</div><div id=top><br><h2>Script finished</h2>";
		echo "------------------------------------------------------------<br>";
		echo "Renamed ".$folderrenamescount." folders<br>";
		echo "Renamed ".$filerenamescount." files<br>";
		echo "------------------------------------------------------------<br><br>";
		echo "<a href=\"whatsapp.html\">Back to home</a><br><br>";
		echo "------------------------------------------------------------<br></div>";


	?>

</body>
