<?php
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

function remote_filesize($uri){
  $array=parse_url($uri);
  $path=$array['path'];
  if (strlen($array['query'])>0){
    $path=$path . "?" . $array['query'];
  }
  $port=$array['port'];
  if (strlen($port)<1){
    $port=80;
  }
  $fp = @fsockopen ($array['host'],$port , $errno, $errstr, 30);
  if (!$fp) {
    return "ERROR: $errstr ($errno)<br />\n";
  } else {
    $regex = '/Content-Length:\s([0-9].+?)\s/';
    //Content-Length: 407675 Connection: close Content-Type: application/zip
    //einlesen bis zum ersten \r\n\r\n also bis header fertig ist und dandaraus das extrahieren was ich gerde will^^
    fputs ($fp, "GET ".$path." HTTP/1.0\r\n\r\n");
    while (!feof($fp)) {
      $wert.= fgets($fp,128);
      $count = preg_match($regex, $wert, $matches);
      if (isset($matches[1])){
        return $matches[1];
      }
    }
    fclose($fp);
    return $wert; //"unknown";
  }
}

function remote_fileheader($uri){
	$array=parse_url($uri);
	$path=$array['path'];
	if (isset($array['query']) && strlen($array['query'])>0){
		$path=$path . "?" . $array['query'];
	}

	if (isset($array['port']) && strlen($array['port'])>0){
		$port=$array['port'];
	} else {
		$port=80;
	}

	if (isset($array['host']) && strlen($array['host'])>0){
		$host=$array['host'];
	} else {
		$host="localhost";
	}
	
  $fp = @fsockopen ($host, $port, $errno, $errstr, 30);
  if (!$fp) {
    return "ERROR: $errstr ($errno)<br />\n";
  } else {
    fputs ($fp, "HEAD ".$path." HTTP/1.1\r\nHost: $host\r\n\r\n");
    $weiter=true;
    $wert="";
    while (!feof($fp) and $weiter) {
      $wert.= fgets($fp,128);
      $werte=explode("\r\n\r\n",$wert,2);
//      $werte=explode("\n\n",$wert,2);
      if (isset($werte[1])){
        $weiter=false;
      }
    }
    if ($weiter){
    	$werte[0]=$werte;
    }
    fclose($fp);
	return parseHeader($werte[0]);
  }
}

function parseHeader($headerString){
    $header=explode("\r\n",$headerString);
    $headerarray=array();
    for ($i=0;$i<sizeof($header);$i++){
      $item=explode(": ",$header[$i]);
      if (isset($item[1])){
        $headerarray=array_merge($headerarray,array($item[0] => $item[1]));
      } else {
        $headerarray=array_merge($headerarray,array($item[0]));
      }
    }
    return $headerarray;
}

function remote_filesize2($uri){
  $header = remote_fileheader($uri);
  return $header["Content-Length"];
}

function remote_file($uri){
	$array=parse_url($uri);
	$path=$array['path'];
	if (isset($array['query']) && strlen($array['query'])>0){
		$path=$path . "?" . $array['query'];
	}

	if (isset($array['port']) && strlen($array['port'])>0){
		$port=$array['port'];
	} else {
		$port=80;
	}

	if (isset($array['host']) && strlen($array['host'])>0){
		$host=$array['host'];
	} else {
		$host="localhost";
	}
	$fp = @fsockopen ($host, $port, $errno, $errstr, 30);
	if (!$fp) {
		return "ERROR: $errstr ($errno)<br />\n";
	} else {
		fputs ($fp, "GET ".$path." HTTP/1.1\r\nHost: $host\r\n\r\n");
		$wert="";
		$contentLength=-1;
		while (!feof($fp)) {
//			$wert.= fgets($fp,10240);
			//$wert.= fgets($fp,1280);
			$newval= fgets($fp,1280);
			$wert.=$newval;
			
			$pos=strpos($wert,"\r\n\r\n");
			if ($contentLength == -1){
				if ($pos>-1){
					$inhalt=explode("\r\n\r\n",$wert,2);
					if (isset($inhalt[1])){
						$headers = parseHeader($inhalt[0]);
						if (isset($headers["Content-Length"])){
							$contentLength = $headers["Content-Length"];
						} else {
							$contentLength = -2;
						}
					}
				}
			}
			if ($contentLength > -1){
				if ((strlen($wert) - $pos - 4) >= $contentLength){
					break;
				}
			}
		}
		fclose($fp);
		$inhalt=explode("\r\n\r\n",$wert,2);
		return $inhalt[1];
	}
}
?>
