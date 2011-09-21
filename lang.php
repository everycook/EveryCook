<?php
if(isset($_REQUEST['language'])){
	$language=$_GET['language'];
}
else {
	$language="English";	
}
$handle=fopen('languages/'.$language.'.txt',"r");
while(($buffer = fgets($handle, 4096)) !== false) {
	if (strlen($buffer)>1) {
		$text_part=explode(";",$buffer);
		$text_part[1]=substr($text_part[1],0,-1);
		echo 'textparts["'.$text_part[0].'"]="'.$text_part[1].'";<br>';
	}
}
fclose($handle);
?>