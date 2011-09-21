<!DOCTYPE html>
<html>
	<head>
		<title>EveryCook "The worlds best recipe database"</title>
		<link rel="stylesheet" type="text/css" href="css/styles.css"/>
		<meta charset="utf-8">
		<script type="text/javascript" src="js/index.js"></script>
		<script type="text/javascript">
		
		var textparts = new Array();
		function getlang(langage) {
			$.getScript('langtest.php?language='+language, function() {
				document.getElementById("login").innerHTML=textparts['login'];
				
			});
			alert(textparts["login"]);
		}
		
		$(document).ready(function() {
			$("#index_lang").hover(function() {
				$("#index_lang_pick").addClass("index_lang_v");
			},function(){
				$("#index_lang_pick").removeClass("index_lang_v");
			});
		});
		
		</script>
	</head>
	<body>
		<img src="pics/bg.png" alt="Background" id="index_bg"/>
		<div id="index_content">
			<a href="log.php">
				<div id="index_log">
					<div class="index_text_middle">
						<div>
							<span id="login"></span>
						</div>
					</div>
				</div>
			</a>
			<a href="set.php">
				<div id="index_set">
					<div class="index_text_middle">
						<div>
							Settings
						</div>
					</div>
				</div>
			</a>
			<div id="index_mf">
			
			</div>
			<div id="index_text_mf">
				<div id="index_text_mf_t">
					Text
				</div>
				<a href="pymn.php">
					<div id="index_pymn">
						<div class="index_text_middle">
							<div>
								Plan Your Meal Now
							</div>
						</div>
					</div>
				</a>
			</div>
			<a href="sr.php">
				<div id="index_sr">
					<div class="index_text_middle">
						<div>
							Search Recipe
						</div>
					</div>
				</div>
			</a>
			<a href="sf.php">
				<div id="index_sf">
					<div class="index_text_middle">
						<div>
							Search Food
						</div>
					</div>
				</div>
			</a>
			<a href="tcm.php">
				<div id="index_tcm">
					<div class="index_text_middle">
						<div>
							The Cooking Machine
						</div>
					</div>
				</div>
			</a>
			<div id="index_lang">
				<div class="index_text_middle">
					<div>
						Language
					</div>
				</div>
				<div id="index_lang_pick" class="index_lang_h">
					<?php
						$dir = "languages";	//quell ordner
						$lang_array = Array();	//ziel Array
						if(is_dir($dir)) {	//existiert ordner?
							$handle = opendir($dir);	//öffne ordner
							if(is_resource($handle)) {	//ordner offen?
								while($file=readdir($handle)) {	//daten auslesen
									if($file != "." && $file != "..")
										array_push($lang_array, $file);
								}
							}else{
								$lang_array[0] = "fehler opendir";
							}
						}else{
							$lang_array[0] = "fehler existdir";
						}
						foreach($lang_array AS $language)
						{
							if (strrpos($language, '~')===false){
								echo '<a href="#" onClick="getlang('.$language.');">';
								echo substr($language,0,-4);
								echo '</a>';
								echo "<br>";
							}
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>  