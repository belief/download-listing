<!doctype html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Belief Client Downloads</title>
	<link rel="stylesheet" href=".style.css">
    <script src=".sorttable.js"></script>
    <script type="text/javascript" src="http://www.workofself.com/jquery.js"></script>
    

</head>

<body>

	<div id="container">
    <div class="heading">
		<h1>Staging</h1>
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
				// Opens directory
				$myDirectory=opendir(".");
				
				// Gets each entry
				while($entryName=readdir($myDirectory)) {
					$dirArray[]=$entryName;
				}
				
				// Finds extensions of files
				function findexts ($filename) {
					$filename=strtolower($filename);
					$exts=split("[/\\.]", $filename);
					$n=count($exts)-1;
					$exts=$exts[$n];
					return $exts;
				}
				
				// Closes directory
				closedir($myDirectory);
				
				// Counts elements in array
				$indexCount=count($dirArray);
				
				// Sorts files
				sort($dirArray);
				
				// Loops through the array of files
				for($index=0; $index < $indexCount; $index++) {
				
					// Allows ./?hidden to show hidden files
					if($_SERVER['QUERY_STRING']=="hidden")
					{$hide="";
					$ahref="./";
					$atext="Hide";}
					else
					{$hide=".";
					$ahref="./?hidden";
					$atext="Show";}
					if(substr("$dirArray[$index]", 0, 1) != $hide) {
					
					// Gets File Names
					$name=$dirArray[$index];
					$namehref=$dirArray[$index];
					
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
						
						case "zip": $extn="ZIP"; break;
						case "bak": $extn="Backup File"; break;
						
						default: $extn=strtoupper($extn)." File"; break;
					}
					
					// Separates directories
					if(is_dir($dirArray[$index])) {
						$extn="&lt;Directory&gt;"; 
						$size="&lt;Directory&gt;"; 
						$class="dir";
					} else {
						$class="file";
					}
					
					// Cleans up . and .. directories 
					if($name=="."){$name=". (Current Directory)"; $extn="&lt;System Dir&gt;";}
					if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}
					
					// Print 'em
					print("
					<tr class='$class'>
						<td><a href='./$namehref'>$name</a></td>
						<td><a href='./$namehref'>$size</a></td>
						<td sorttable_customkey='$timekey'><a href='./$namehref'>$modtime</a></td>
					</tr>");
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
