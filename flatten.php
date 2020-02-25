<html>
<head>
	<title>Flatten Media folder</title>
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

		.blue{
			color: blue;
		}
	</style>
</head>

<body>

	<?php
		
		//if (strpos($folders1, '.') !== false) {
		//	echo "true";
		//}
	/*

		$chatnames = scandir('Media');
		$chatnames_nr = count($chatnames);

		for ($i = 0; $i < $chatnames_nr; $i++) { 
			if ($chatnames[$i] != '.' && $chatnames[$i] != '..' && $chatnames[$i] != '.DS_Store' ) {

				$folders1 = scandir('Media/'.$chatnames[$i]);
				$folders1_nr = count($folders1);

				for ($x = 0; $x < $folders1_nr; $x++) {
					if ($folders1[$x] != '.' && $folders1[$x] != '..' && $folders1[$x] != '.DS_Store' ) { 

						$folders2 = scandir('Media/'.$chatnames[$i].'/'.$folders1[$x]);
						$folders2_nr = count($folders2);

						for ($y = 0; $y < $folders2_nr; $y++) {
							if ($folders2[$y] != '.' && $folders2[$y] != '..' && $folders2[$y] != '.DS_Store' ) { 

								$files = scandir('Media/'.$chatnames[$i].'/'.$folders1[$x].'/'.$folders2[$y]);
								$files_nr = count($files);

								for ($z = 0; $z < $files_nr; $z++) {
									if ($files[$z] != '.' && $files[$z] != '..' && $files[$z] != '.DS_Store' ) { 

										rename('Media/'.$chatnames[$i].'/'.$folders1[$x].'/'.$folders2[$y].'/'.$files[$z], 'Media/'.$chatnames[$i].'/'.$files[$z]);
										
									}
								}

								rmdir('Media/'.$chatnames[$i].'/'.$folders1[$x].'/'.$folders2[$y]);
								
							}
						}

						rmdir('Media/'.$chatnames[$i].'/'.$folders1[$x]);

					}
				}
	
			}
		} 

		$chatnames = scandir('Media/Familie');
		$chatnames_nr = count($chatnames);
		$chats = glob("Media/Familie/*", GLOB_ONLYDIR);
		echo "<pre>";
		print_r($chats);
		echo "</pre><pre>";
		print_r($chatnames);
		echo "</pre>";

		for ($i = 0; $i < $chatnames_nr; $i++) { 
			if ($chatnames[$i] != '.' && $chatnames[$i] != '..' && $chatnames[$i] != '.DS_Store' ) {

				if (count(glob("Media/".$chatnames[$i]."/*"))===0) {
					rmdir('Media/'.$chatnames[$i]);
					echo "Deleted the folder '".$chatnames[$i]."' because it did not contain media files<br>";
				}
	
			}
		} 


		echo "<h2>Script finished</h2>";
		echo "<a href=\"whatsapp.html\">Back to home</a>";
*/

		$chatnames = glob('Media/*', GLOB_ONLYDIR);
		$chatnames_nr = count($chatnames);
		$movecount = 0;

		echo "<div id=bottom>";

		for ($i = 0; $i < $chatnames_nr; $i++) { 

			$folders1 = glob($chatnames[$i].'/*', GLOB_ONLYDIR);
			$folders1_nr = count($folders1);

			for ($x = 0; $x < $folders1_nr; $x++) {
				
				$folders2 = glob($folders1[$x].'/*', GLOB_ONLYDIR);
				$folders2_nr = count($folders2);

				for ($y = 0; $y < $folders2_nr; $y++) {
		
					$files = scandir($folders2[$y]);
					$files_nr = count($files);

					for ($z = 0; $z < $files_nr; $z++) {
						if ($files[$z] != '.' && $files[$z] != '..' && $files[$z] != '.DS_Store' ) { 

							if(rename($folders2[$y].'/'.$files[$z], $chatnames[$i].'/'.$files[$z])){
								echo "<span class=blue>Moved ".$folders2[$y].'/'.$files[$z].' to '.$chatnames[$i].'/'.$files[$z].'</span><br>';
								$movecount = $movecount + 1;
							}
										
						}
					}

					if (rmdir($folders2[$y])){
						echo "<span class=red>Deleted folder ".$folders2[$y]."</span><br>";
					}
								
				}

				if (rmdir($folders1[$x])){
					echo "<span class=red>Deleted folder ".$folders1[$x]."</span><br>";
				}


			}


		}

		echo "</div><div id=top><br><h2>Script finished</h2>";
		echo "------------------------------------------------------------<br>";
		echo "<span class=blue>Moved ".$movecount." files</span><br>";
		
		$chatnames = glob("Media/*", GLOB_ONLYDIR);
		$chatnames_nr = count($chatnames);

		for ($i = 0; $i < $chatnames_nr; $i++) { 
			
			if (count(glob($chatnames[$i]."/*"))===0) {
				
				if (rmdir($chatnames[$i])){
					echo "<span class=red>Deleted the folder '".$chatnames[$i]."' because it did not contain media files</span><br>";
				}
				
			}
		
		} 

		echo "------------------------------------------------------------<br><br>";
		echo "<a href=\"whatsapp.html\">Back to home</a><br><br>";
		echo "------------------------------------------------------------<br></div>";

	?>

</body>
