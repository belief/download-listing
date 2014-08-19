<?php 
	$currentDir = ".";
	if (isset($_GET['dir']) && is_dir($_GET['dir']) ) {
		$currentDir = $_GET['dir'];
	}

	// Opens directory
	$myDirectory=opendir($currentDir);

	// Gets each entry
	while($entryName=readdir($myDirectory)) {
		$dirArray[]=$entryName;
	}
	
	// Closes directory
	closedir($myDirectory);

	// Counts elements in array
	$indexCount=count($dirArray);
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>Belief Client Downloads</title>
	<link rel="stylesheet" href=".style.css">
    <script src=".sorttable.js"></script>
    <script src=".markdown.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    

</head>

<body>
	<div id="container">
    <div class="heading">
		<h1>CLIENT_NAME_TO_REPLACE</h1>
    </div>
	     <?php
	     	for($index=0; $index < $indexCount; $index++) {
	     		if ( strpos(strtolower($dirArray[$index]),'readme.md') !== FALSE) {
	     			?>
	     			<script>
	     				var readmeFile = '<?php echo $currentDir.'/'.$dirArray[$index]; ?>'
	     				$.get(readmeFile, function(data) {
	     				    var filetxt = data;
	     				    	$('.readme').html(markdown.toHTML(filetxt));
	     				    });
	     			</script>
	     			<div class="readme">
	     			</div>
	     			<?php
	     		} else if ( strpos(strtolower($dirArray[$index]),'readme') !== FALSE) {
	     			$contents = file_get_contents($currentDir.'/'.$dirArray[$index]);
	     			echo '<div class="readme">';
	     			echo nl2br($contents);
	     			echo '</div>';
	     		}
	     	}
	    ?>
   	<div class="dir-location">
		<h3>Directory: <?php  echo str_replace('/',' / ',$currentDir); ?></h3>
    </div>
	<table class="sortable">
		<thead>
			<tr>
				<th>Name</th>
				<th>Size</th>
				<th>Uploaded</th>
			</tr>
		</thead>
		<tbody>
		<?php
				// Finds extensions of files
				function findexts ($filename) {
					$filename=strtolower($filename);
					$exts=split("[/\\.]", $filename);
					$n=count($exts)-1;
					$exts=$exts[$n];
					return $exts;
				}
				
				// Sorts files
				sort($dirArray);
				
				// Loops through the array of files
				for($index=0; $index < $indexCount; $index++) {
				
					// Allows ./?hidden to show hidden files
					if($_SERVER['QUERY_STRING']=="hidden" || ($currentDir != "." && $index < 2) ) {
						$hide="";
						$ahref="./";
						$atext="Hide";
					} else {
						$hide=".";
						$ahref="./?hidden";
						$atext="Show";
					}

					if(substr("$dirArray[$index]", 0, 1) != $hide) {
					
						// Gets File Names
						$name=$dirArray[$index];
						$namehref=rawurlencode($currentDir.'/'.$dirArray[$index]);
						
						// Gets Extensions 
						$extn=findexts($dirArray[$index]); 
						
						// Gets file size 
						$size=number_format(filesize($dirArray[$index]));
						
						// Gets Date Modified Data
						$modtime=date("M j Y g:i A", filemtime($dirArray[$index]));
						$timekey=date("YmdHis", filemtime($dirArray[$index]));
						
						// Prettifies File Types, add more to suit your needs.
						switch ($extn){
							case "png": $extn="PNG"; break;
							case "jpg": $extn="JPEG"; break;
							case "svg": $extn="SVG"; break;
							case "gif": $extn="GIF"; break;
							case "ico": $extn="Windows Icon"; break;
							
							case "txt": $extn="Text File"; break;
							case "log": $extn="Log File"; break;
							case "htm": $extn="HTML"; break;
							case "php": $extn="PHP Script"; break;
							case "js": $extn="Javascript"; break;
							case "css": $extn="Stylesheet"; break;
							case "pdf": $extn="PDF"; break;
							case "pages": $extn="Pages"; break;
							
							case "zip": $extn="ZIP"; break;
							case "bak": $extn="Backup File"; break;
							
							default: $extn=strtoupper($extn)." File"; break;
						}

						
						// Separates directories
						if(is_dir($dirArray[$index]) || is_dir($currentDir.'/'.$dirArray[$index] )) {
							$extn="&lt;Directory&gt;"; 
							$size="&lt;Directory&gt;"; 
							$class="dir";

							if ($name == "." || $name == "..") {
								$path = $currentDir;
								$pathVars = explode("/",$path);
								array_pop($pathVars);
								$newPath = implode("/",$pathVars);

								$namehref='?dir='.rawurlencode($newPath);
							} else {
								if ($currentDir != $dirArray[$index] ) {
									$namehref='?dir='.rawurlencode($currentDir.'/'.$dirArray[$index]);
								} else {
									$namehref='?dir='.rawurlencode($dirArray[$index]);
								}
							}
						} else {
							$class="file";
						}

						if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}
						
						// Cleans up . and .. directories 
						if($name !="."){
							
							// Print 'em
							print("
							<tr class='$class'>
								<td><a href='./$namehref'>$name</a></td>
								<td><a href='./$namehref'>$size</a></td>
								<td sorttable_customkey='$timekey'><a href='./$namehref'>$modtime</a></td>
							</tr>");
						}
					}
				}
			?>
		</tbody>
		</table>
		<div class="copyright">
©2014 Belief Agency. All rights reserved.</div>
</div>
	
</body>

</html>
