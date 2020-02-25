<html>
<head>
	<title>Delete thumbs</title>
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
		
		$chatnames = scandir('Media');
		$chatnames_nr = count($chatnames);
		$deletecount = 0;

		echo "<div id=bottom>";

		for ($i = 0; $i < $chatnames_nr; $i++) { 
			if ($chatnames[$i] != '.' && $chatnames[$i] != '..' && $chatnames[$i] != '.DS_Store' ) {

				$files = scandir('Media/'.$chatnames[$i]);
				$files_nr = count($files);

				for ($x = 0; $x < $files_nr; $x++) {
					if ($files[$x] != '.' && $files[$x] != '..' && $files[$x] != '.DS_Store' ) { 

						if (strpos($files[$x], '.thumb') !== false) {
							if(unlink('Media/'.$chatnames[$i].'/'.$files[$x])){
								echo "Deleted Media/".$chatnames[$i].'/'.$files[$x].'<br>';
								$deletecount = $deletecount + 1;
							}
						}

					}
				}
	
			}
		}

		echo "</div><div id=top><br><h2>Script finished</h2>";
		echo "------------------------------------------------------------<br>";
		echo "Deleted ".$deletecount." thumb files<br>";
		echo "------------------------------------------------------------<br><br>";
		echo "<a href=\"whatsapp.html\">Back to home</a><br><br>";
		echo "------------------------------------------------------------<br></div>";

	?>

</body>