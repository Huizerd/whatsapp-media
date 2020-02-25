<html>
<head>
	<title>Import csv into database</title>
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

		.green{
			color: green;
		}
	</style>
</head>

<body>

	<?php
		
		$servername = "localhost";
		$username = "root";
		$password = "";

		$failcount = 0;
		$succescount = 0;

		echo "<div id=bottom>";

		// Create connection
		$conn = new mysqli($servername, $username, $password);
		mysqli_options($conn, MYSQLI_OPT_LOCAL_INFILE, true);
		// Check connection
		if ($conn->connect_error) {
		    die("<span class=red>Connection failed:</span> " . $conn->connect_error);
		    $failcount = $failcount + 1;
		} 

		// Delete old database if exists
		$sql = "DROP DATABASE IF EXISTS WhatsappMedia";
		if ($conn->query($sql) === TRUE) {
		    echo "<span class=green>Deleted old data successfully (if existed)</span>";
		    $succescount = $succescount + 1;
		} else {
		    echo "<span class=red>Error deleting database old database (if existed):</span> " . $conn->error;
		    $failcount = $failcount + 1;
		}

		echo "<br>";

		// Create database
		$sql = "CREATE DATABASE WhatsappMedia";
		if ($conn->query($sql) === TRUE) {
		    echo "<span class=green>New database created successfully</span>";
		    $succescount = $succescount + 1;
		} else {
		    echo "<span class=red>Error creating new database:</span> " . $conn->error;
		    $failcount = $failcount + 1;
		}

		echo "<br>";

		/////////////////////////////////////////////////////////////
		//                   Create Tables                         //
		/////////////////////////////////////////////////////////////

		// Create chats table
		$sql = "CREATE TABLE WhatsappMedia.chats(ZCONTACTJID VARCHAR(30), ZPARTNERNAME VARCHAR(30))";
		if ($conn->query($sql) === TRUE) {
		    echo "<span class=green>Table 'chats' created successfully</span>";
		    $succescount = $succescount + 1;
		} else {
		    echo "<span class=red>Error creating table 'chats':</span> " . $conn->error;
		    $failcount = $failcount + 1;
		}

		echo "<br>";

		// Create media table
		$sql = "CREATE TABLE WhatsappMedia.media(Z_PK VARCHAR(15), ZMEDIALOCALPATH VARCHAR(110), ZMESSAGE VARCHAR(15))";
		if ($conn->query($sql) === TRUE) {
		    echo "<span class=green>Table 'media' created successfully</span>";
		    $succescount = $succescount + 1;
		} else {
		    echo "<span class=red>Error creating table 'media':</span> " . $conn->error;
		    $failcount = $failcount + 1;

		}

		echo "<br>";

		// Create messages table
		$sql = "CREATE TABLE WhatsappMedia.messages(ZMEDIASECTIONID VARCHAR(30), Z_PK VARCHAR(15), ZMESSAGEDATE VARCHAR(30))";
		if ($conn->query($sql) === TRUE) {
		    echo "<span class=green>Table 'messages' created successfully</span>";
		    $succescount = $succescount + 1;
		} else {
		    echo "<span class=red>Error creating table 'messages':</span> " . $conn->error;
		    $failcount = $failcount + 1;
		}

		echo "<br>";

		/////////////////////////////////////////////////////////////
		//                 Import CSV Files                        //
		/////////////////////////////////////////////////////////////

		//Chats
		$file = fopen('chats.csv', 'r');
		$data = fgetcsv($file);
		$columns = '@'.implode(',@', $data);

		$sql = "LOAD DATA LOCAL INFILE 'chats.csv' INTO TABLE WhatsappMedia.chats
				FIELDS TERMINATED BY ',' 
				LINES TERMINATED BY '\r\n'
				IGNORE 1 LINES
				(".$columns.")
				SET ZCONTACTJID=@ZCONTACTJID, ZPARTNERNAME=@ZPARTNERNAME";
		if ($conn->query($sql) === TRUE) {
		    echo "<span class=green>Imported chats.csv successfully</span>";
		    $succescount = $succescount + 1;
		} else {
		    echo "<span class=red>Error importing chats.csv:</span> " . $conn->error;
		    $failcount = $failcount + 1;
		}

		echo "<br>";

		//Media
		$file = fopen('media.csv', 'r');
		$data = fgetcsv($file);
		$columns = '@'.implode(',@', $data);

		$sql = "LOAD DATA LOCAL INFILE 'media.csv' INTO TABLE WhatsappMedia.media
				FIELDS TERMINATED BY ',' 
				LINES TERMINATED BY '\r\n'
				IGNORE 1 LINES
				(".$columns.")
				SET Z_PK=@Z_PK, ZMEDIALOCALPATH=@ZMEDIALOCALPATH, ZMESSAGE=@ZMESSAGE";
		if ($conn->query($sql) === TRUE) {
		    echo "<span class=green>Imported media.csv successfully</span>";
		    $succescount = $succescount + 1;
		} else {
		    echo "<span class=red>Error importing media.csv:</span> " . $conn->error;
		    $failcount = $failcount + 1;
		}

		echo "<br>";

		//Messages
		$file = fopen('messages.csv', 'r');
		$data = fgetcsv($file);
		$columns = '@'.implode(',@', $data);

		$sql = "LOAD DATA LOCAL INFILE 'messages.csv' INTO TABLE WhatsappMedia.messages
				FIELDS TERMINATED BY ',' 
				LINES TERMINATED BY '\r\n'
				IGNORE 1 LINES
				(".$columns.")
				SET ZMEDIASECTIONID=@ZMEDIASECTIONID, Z_PK=@Z_PK, ZMESSAGEDATE=@ZMESSAGEDATE";
		if ($conn->query($sql) === TRUE) {
		    echo "<span class=green>Imported messages.csv successfully</span>";
		    $succescount = $succescount + 1;
		} else {
		    echo "<span class=red>Error importing messages.csv:</span> " . $conn->error;
		    $failcount = $failcount + 1;
		}

		echo "</div><div id=top><br><h2>Script finished</h2>";
		echo "------------------------------------------------------------<br>";
		echo "<span class=green>Number of successes: ".$succescount."</span><br>";
		echo "<span class=red>Number of failures: ".$failcount."</span><br>";
		echo "------------------------------------------------------------<br><br>";
		echo "<a href=\"whatsapp.html\">Back to home</a><br><br>";
		echo "------------------------------------------------------------<br></div>";



		$conn->close();

	?>

</body>
