<?PHP
# Diverse weitere Browserkennungs- Beispiele unter:
# http://www.joergkrusesweb.de/browser/kennung
# http://developer.apple.com/internet/safari/faq.html#anchor2
# http://developer.apple.com/internet/safari/uamatrix.html

class stat_func {
	### operating system detection ###

	public static function os_detection ( $var , $var2){
	//	$temp='';
		if (preg_match("/windows nt 6.0/i",$var)) $temp = 'Windows Vista';
		elseif (preg_match("/windows nt 5.1/i",$var)) $temp = 'Windows XP';
		elseif (preg_match("/windows xp/i",$var)) $temp = 'Windows XP';
		
		elseif (preg_match("/sunos/i",$var)) $temp = 'SunOS';
		elseif (preg_match("/(Debian\/[0-9.]*)/i",$var,$treffer)) $temp = 'Linux: ' . $treffer[1];
		elseif (preg_match("/(Ubuntu\/[0-9.]*)/i",$var,$treffer)) $temp = 'Linux: ' . $treffer[1];
		elseif (preg_match("/(Gentoo\/[0-9.]*)/i",$var,$treffer)) $temp = 'Linux: ' . $treffer[1];
		elseif (preg_match("/(Mandriva\/[0-9.]*)/i",$var,$treffer)) $temp = 'Linux: ' . $treffer[1];
		elseif (preg_match("/(SUSE\/[0-9.]*)/i",$var,$treffer)) $temp = 'Linux: ' . $treffer[1];
		elseif (preg_match("/(Fedora\/[0-9.]*)/i",$var,$treffer)) $temp = 'Linux: ' . $treffer[1];
		elseif (preg_match("/(PCLinuxOS\/[0-9.]*)/i",$var,$treffer)) $temp = 'Linux: ' . $treffer[1];
		elseif (preg_match("/(SUSE\/[0-9.]*)/i",$var,$treffer)) $temp = 'Linux: ' . $treffer[1];
		elseif (preg_match("/Debian/i",$var)) $temp = 'Linux: Debian';
		elseif (preg_match("/Kubuntu/i",$var)) $temp = 'Linux: Kubuntu';
		elseif (preg_match("/Ubuntu/i",$var)) $temp = 'Linux: Ubuntu';
		elseif (preg_match("/Gentoo/i",$var)) $temp = 'Linux: Gentoo';
		
		elseif (preg_match("/Linux ([^;()]*\([^)]*\)[^;)]*)/i",$var,$treffer)) $temp = 'Linux: ' . $treffer[1];
		elseif (preg_match("/Linux ([^;)]*)/i",$var,$treffer)) $temp = 'Linux: ' . $treffer[1];
		elseif (preg_match("/Linux/i",$var)) $temp = 'Linux';
		elseif (preg_match("/CPU like Mac OS X/i",$var)) $temp = 'CPU like Mac OS X -&gt; IPhone?';
		elseif (preg_match("/(Mac OS X [0-9_.]*)/i",$var,$treffer)) $temp = $treffer[1];
		elseif (preg_match("/Mac OS X/i",$var)) $temp = 'Mac OS X';
		elseif (preg_match("/Mac_PowerPC/i",$var)) $temp = 'Mac OS';
		elseif (preg_match("/macintosh/i",$var)) $temp = 'Macintosh';
		elseif (preg_match("/Mac/i",$var)) $temp = 'Mac';
		
		elseif (preg_match("/win 9x 4.90/i",$var)) $temp = 'Windows Me';
		elseif (preg_match("/windows me/i",$var)) $temp = 'Windows Me';
		elseif (preg_match("/windows nt 5.0/i",$var)) $temp = 'Windows 2000';
		elseif (preg_match("/windows 2000/i",$var)) $temp = 'Windows 2000';
		elseif (preg_match("/windows nt 3.1/i",$var)) $temp = 'Windows 3.1';
		elseif (preg_match("/windows nt 3.5.0/i",$var)) $temp = 'Windows NT 3.5';
		elseif (preg_match("/windows nt 3.5.1/i",$var)) $temp = 'Windows NT 3.5.1';
		elseif (preg_match("/windows nt 4.0/i",$var)) $temp = 'Windows NT 4.0';
		elseif (preg_match("/windows 98/i",$var)) $temp = 'Windows 98';
		elseif (preg_match("/win98/i",$var)) $temp = 'Windows 98';
		elseif (preg_match("/windows 95/i",$var)) $temp = 'Windows 95';
		elseif (preg_match("/windows nt/i",$var)) { if (preg_match("/(windows nt [0-9.]*)/i",$var,$treffer)) $temp = $treffer[1]; }
		elseif (preg_match("/windows ([^;)]*)/i",$var,$treffer)) $temp = 'Windows ' . $treffer[1];
		elseif (preg_match("/windows/i",$var)) $temp = 'Windows';
		
		elseif (preg_match("/WinHttp.WinHttpRequest/i",$var)) $temp = 'WinHttp.WinHttpRequest';
		
		elseif (preg_match("/(Nintendo [^ ;]+)/i",$var,$treffer)) $temp = $treffer[1];
		elseif (preg_match("/(psp [^;]+)/i",$var,$treffer)) $temp = $treffer[1];
		elseif (preg_match("/(playstation [^ ;]+)/i",$var,$treffer)) $temp = $treffer[1];
		
		elseif (preg_match("/Nokia([^ ]+) [^ ]* ?(SymbianOS\/[0-9.]*)/i",$var,$treffer)) $temp = 'Nokia: ' . $treffer[1] . ' ' . $treffer[2];
		elseif (preg_match("/Nokia([^ ;]+)/i",$var,$treffer)) $temp = 'Nokia: ' . $treffer[1];
		elseif (preg_match("/SonyEricsson([^ ;]+)/i",$var,$treffer)) $temp = 'SonyEricsson: ' . $treffer[1];
		elseif (preg_match("/(MOT[^ ;]+)/i",$var,$treffer)) $temp = 'Motorola: ' . $treffer[1];
		elseif (preg_match("/SymbianOS(\/[0-9.]*);[^;]*;?(Series[^;)]*)/i",$var,$treffer)) $temp = 'SymbianOS: ' . $treffer[1] . ' ' . $treffer[2];
		elseif (preg_match("/BlackBerry;[^;]*;? ?BlackBerry ([0-9.]*)/i",$var,$treffer)) $temp = 'BlackBerry: ' . $treffer[1];
		
		elseif (preg_match("/(Googlebot\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(Yahoo! Slurp\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(Yahoo! Slurp)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(msnbot-media\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(msnbot\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(Yeti\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/ia_archiver/i",$var)) $temp = 'Webcrawler: ia_archiver -&gt; WayBackMachine';
		elseif (preg_match("/(Gigabot\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(DotBot\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/^([^ ]*Bot\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		else $temp = $var2;
		return $temp;

	//	return ($temp != 'Linux') ?'found':$var;
	//    return ($temp != '') ?'found':$var;
	}

	###############################


	### browser detection ###

	public static function browser_detection ( $var , $var2){
		if (preg_match("/(Opera)\/([0-9.]+)/i",$var,$treffer)) $temp = $treffer[1] . '|' . $treffer[2];
		elseif (preg_match("/(Opera) ([0-9.]+)/i",$var,$treffer)) $temp = $treffer[1] . '|' . $treffer[2];
		elseif (preg_match("/Opera/i",$var)) $temp = 'Opera';
		elseif (preg_match("/Gecko\/[0-9]+ ([^\/;() ]+)\/([0-9.]+[ab0-9]+)$/i",$var,$treffer)) $temp = $treffer[1] . '|' . $treffer[2];	//Irgend ein Gecko Browser
		elseif (preg_match("/Gecko\/[0-9]+ ([^\/;()]+)\/([0-9.]+[ab0-9]+) \(like [^)]+\)/i",$var,$treffer)) $temp = $treffer[1] . '|' . $treffer[2];	//Ein Firefox ähnlicher Browser
		elseif (preg_match("/Gecko\/[0-9]+ ([^\/;()]+)\/([0-9.]+[ab0-9]+) Firefox ?\/ ?[0-9.]*[ab0-9]+ compatible/i",$var,$treffer)) $temp =$treffer[1] . '|' . $treffer[2];	//Ein Firefox kompatibler Browser
		elseif (preg_match("/Gecko\/[0-9]+ (Firefox) ?\/ ?([0-9.]+[ab0-9]+)/i",$var,$treffer)) $temp = $treffer[1] . '|' . $treffer[2];	//Irgend ein Gecko Browser
		elseif (preg_match("/Gecko\/[0-9]+ .+ (Firefox) ?\/ ?([0-9.]+[ab0-9]+)/i",$var,$treffer)) $temp = $treffer[1] . '|' . $treffer[2];	//Ein Firefox Browser
		elseif (preg_match("/Firefox/i",$var)) $temp = 'Firefox';	//Ein Firefox Browser
		elseif (preg_match("/Gecko\/[0-9]+ ([^\/;() ]*)\/([0-9.]*[ab0-9]+) \([^)]+\)$/i",$var,$treffer)) $temp = $treffer[1] . '|' . $treffer[2];	//Irgend ein Gecko Browser
		elseif (preg_match("/(Gecko\/[0-9]+)(.*)$/i",$var,$treffer)) $temp = $treffer[1] . ' ' . $treffer[2];	//Irgend ein Gecko Browser
		
		//elseif (preg_match("/\(KHTML, like Gecko\) Version\/([0-9.]*) ([^\/]*)\/([0-9.]*)/i",$var,$treffer)) $temp = $treffer[2] . '|' . $treffer[1].", Build: " . $treffer[3];    //KHTML Browser
		elseif (preg_match("/\(KHTML, like Gecko\) Version\/([0-9.]*) (Mobile\/?[^ ]*) ([^\/]*)\/([0-9.]*)/i",$var,$treffer)) $temp = $treffer[3] . ' ' . 'Mobile'  . '|' . $treffer[1];    //KHTML Browser
		elseif (preg_match("/\(KHTML, like Gecko\) Version\/([0-9.]*) ([^\/]*)\/([0-9.]*)/i",$var,$treffer)) $temp = $treffer[2] . '|' . $treffer[3];    //KHTML Browser
		elseif (preg_match("/\(KHTML, like Gecko\) ([^\/]*)\/v?([0-9.]*)/i",$var,$treffer)) $temp = $treffer[1] . "|" . $treffer[2];    //KHTML Browser
		
		elseif (preg_match("/(Chrome)\/?([0-9.]*)/i",$var,$treffer)) $temp = $treffer[1] . "|" . $treffer[2];    //Chrome
		elseif (preg_match("/(Chromium)\/?([0-9.]*)/i",$var,$treffer)) $temp = $treffer[1] . "|" . $treffer[2];    //Chromium
		elseif (preg_match("/(Safari)\/?([0-9.]*)/i",$var,$treffer)) $temp = $treffer[1] . "|" . $treffer[2];    //safari
		
		/*
		elseif (preg_match("/Netscape\/7.0/i",$var)) $temp = 'Netscape|7';
		elseif (preg_match("/Netscape\/7.1/i",$var)) $temp = 'Netscape|7.1';
		elseif (preg_match("/Netscape\/7.2/i",$var)) $temp = 'Netscape|7.2';
		*/
		elseif (preg_match("/(Netscape)\/([0-9.]*)/i",$var)) $temp = $treffer[1] . "|" . $treffer[2];    //Irgend ein Netscape
		/*
		elseif (preg_match("/rv:1.4/i",$var)) $temp = 'Mozilla|1.4';
		elseif (preg_match("/rv:1.5a/i",$var)) $temp = 'Mozilla|1.5a';
		elseif (preg_match("/rv:1.5/i",$var)) $temp = 'Mozilla|1.5';
		elseif (preg_match("/rv:1.7.3/i",$var)) $temp = 'Firefox|1.0RC1';
		elseif (preg_match("/rv:1.7.5/i",$var)) $temp = 'Firefox|1.0';
		elseif (preg_match("/rv:1.7.10/i",$var)) $temp = 'Firefox|1.0.6';
		elseif (preg_match("/rv:1.7.12/i",$var)) $temp = 'Firefox|1.0.7';
		elseif (preg_match("/rv:1.7/i",$var)) $temp = 'Firefox|0.9.2';
		elseif (preg_match("/rv:1.8a5/i",$var)) $temp = 'MultiZilla|1.7';
		*/
		
		/*
		elseif (preg_match("/MSIE 8.0/i",$var)) $temp = 'InternetExplorer|8.0';
		elseif (preg_match("/MSIE 7/i",$var)) $temp = 'InternetExplorer|7';
		elseif (preg_match("/MSIE 6/i",$var)) $temp = 'InternetExplorer|6';
		elseif (preg_match("/MSIE 5.5/i",$var)) $temp = 'InternetExplorer|5.5';
		elseif (preg_match("/MSIE 5.0/i",$var)) $temp = 'InternetExplorer|5';
		elseif (preg_match("/MSIE 4/i",$var)) $temp = 'InternetExplorer|4';
		*/
		elseif (preg_match("/MSIE ([0-9.]+)/i",$var,$treffer)) $temp = 'InternetExplorer|' . $treffer[1];	//Irgend ein IE
		elseif (preg_match("/MSIE/i",$var)) $temp = 'InternetExplorer';
		
		elseif (preg_match("/(Galeon)\/([0-9.]*)/i",$var,$treffer)) $temp = $treffer[1] . '|' . $treffer[2];
		elseif (preg_match("/Galeon/i",$var)) $temp = 'Galeon';
		elseif (preg_match("/(Konqueror)\/([0-9.]*)/i",$var,$treffer)) $temp = $treffer[1] . '|' . $treffer[2];
		elseif (preg_match("/Konqueror/i",$var)) $temp = 'Konqueror';
		
		elseif (preg_match("/AOL 9.0/i",$var)) $temp = 'AOL|9.0';
		elseif (preg_match("/(AppleWebKit)\/?([0-9.]*)/i",$var,$treffer)) $temp = $treffer[1] . "|" . $treffer[2];    //AppleWebKit
		
		elseif (preg_match("/(Googlebot\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(Yahoo! Slurp\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(Yahoo! Slurp)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(msnbot-media\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(msnbot\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(Yeti\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/ia_archiver/i",$var)) $temp = 'Webcrawler: ia_archiver -&gt; WayBackMachine';
		elseif (preg_match("/(Gigabot\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/(DotBot\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		elseif (preg_match("/^([^ ]*Bot\/[0-9.]*)/i",$var,$treffer)) $temp = 'Webcrawler: ' . $treffer[1];
		
		elseif (preg_match("/Mozilla\/4.0 \(compatible/i",$var)) $temp = 'Mozilla 4.0 Kompatibel';
		elseif (preg_match("/Mozilla\/5.0 \(compatible/i",$var)) $temp = 'Mozilla 5.0 Kompatibel';
		
		elseif (strlen($var)>0 and strlen($var)<15) $temp = $var;
		elseif (strlen($var)>0) {$pos=strpos($var,"("); if ($pos>0){$temp = substr($var,0,$pos-1);} else {$temp = $var2;}}
		else $temp = $var2;
		
	//    if ($temp == 'Mozilla/4.0' or $temp == 'Mozilla/4.0') $temp = $var;
	//    if ($temp != $var) $temp = $var2;
		
		return $temp;
	}
	###############################


	### Country Array ######################

	public static $country_array = array (
	 "ac"=>"Ascension Island", "ad"=>"Andorra", "ae"=>"United Arab Emirates", "af"=>"Afghanistan", "ag"=>"Antigua and Barbuda",
	 "ai"=>"Anguilla", "al"=>"Albania", "am"=>"Armenia", "an"=>"Netherlands Antilles", "ao"=>"Angola", "aq"=>"Antarctica",
	 "ar"=>"Argentina", "as"=>"American Samoa", "at"=>"Austria", "au"=>"Australia", "aw"=>"Aruba", "az"=>"Azerbaijan",
	 "ba"=>"Bosnia and Herzegovina", "bb"=>"Barbados", "bd"=>"Bangladesh", "be"=>"Belgium", "bf"=>"Burkina Faso",
	 "bg"=>"Bulgaria", "bh"=>"Bahrain", "bi"=>"Burundi", "bj"=>"Benin", "bm"=>"Bermuda", "bn"=>"Brunei Darussalam",
	 "bo"=>"Bolivia", "br"=>"Brazil", "bs"=>"Bahamas", "bt"=>"Bhutan", "bv"=>"Bouvet Island", "bw"=>"Botswana",
	 "by"=>"Belarus", "bz"=>"Belize", "ca"=>"Canada", "cc"=>"Cocos (Keeling) Islands","cd"=>"Congo, Democratic Republic of the",
	 "cf"=>"Central African Republic", "cg"=>"Congo, Republic of", "ch"=>"Switzerland", "ci"=>"Cote d'Ivoire",
	 "ck"=>"Cook Islands", "cl"=>"Chile", "cm"=>"Cameroon", "cn"=>"China", "co"=>"Colombia", "cr"=>"Costa Rica",
	 "cu"=>"Cuba", "cv"=>"Cap Verde", "cx"=>"Christmas Island", "cy"=>"Cyprus", "cz"=>"Czech Republic","de"=>"Germany",
	 "dj"=>"Djibouti", "dk"=>"Denmark", "dm"=>"Dominica", "do"=>"Dominican Republic", "dz"=>"Algeria", "ec"=>"Ecuador",
	 "ee"=>"Estonia", "eg"=>"Egypt", "eh"=>"Western Sahara", "er"=>"Eritrea", "es"=>"Spain", "et"=>"Ethiopia", "fi"=>"Finland",
	 "fj"=>"Fiji", "fk"=>"Falkland Islands (Malvina)", "fm"=>"Micronesia, Federal State of", "fo"=>"Faroe Islands",
	 "fr"=>"France", "ga"=>"Gabon", "gd"=>"Grenada", "ge"=>"Georgia", "gf"=>"French Guiana", "gg"=>"Guernsey",
	 "gh"=>"Ghana", "gi"=>"Gibraltar", "gl"=>"Greenland", "gm"=>"Gambia", "gn"=>"Guinea", "gp"=>"Guadeloupe",
	 "gq"=>"Equatorial Guinea", "gr"=>"Greece", "gs"=>"South Georgia and the South Sandwich Islands", "gt"=>"Guatemala",
	 "gu"=>"Guam", "gw"=>"Guinea Bissau", "gy"=>"Guyana", "hk"=>"Hong Kong", "hm"=>"Heard and McDonald Islands",
	 "hn"=>"Honduras", "hr"=>"Croatia/Hrvatska", "ht"=>"Haiti", "hu"=>"Hungary", "id"=>"Indonesia", "ie"=>"Ireland",
	 "il"=>"Israel", "im"=>"Isle of Man", "in"=>"India", "io"=>"British Indian Ocean Territory", "iq"=>"Iraq",
	 "ir"=>"Iran (Islamic Republic of)", "is"=>"Iceland", "it"=>"Italy", "je"=>"Jersey", "jm"=>"Jamaica", "jo"=>"Jordan",
	 "jp"=>"Japan", "ke"=>"Kenya", "kg"=>"Kyrgyzstan", "kh"=>"Cambodia", "ki"=>"Kiribati", "km"=>"Comoros",
	"kn"=>"Saint Kitts and Nevis", "kp"=>"Korea, Democratic People's Republic", "kr"=>"Korea, Republic of",
	"kw"=>"Kuwait",   "ky"=>"Cayman Islands", "kz"=>"Kazakhstan", "la"=>"Lao People's Democratic Republic", "lb"=>"Lebanon", "lc"=>"Saint Lucia",
	"li"=>"Liechtenstein", "lk"=>"Sri Lanka", "lr"=>"Liberia", "ls"=>"Lesotho", "lt"=>"Lithuania", "lu"=>"Luxembourg",
	"lv"=>"Latvia", "ly"=>"Libyan Arab Jamahiriya", "ma"=>"Morocco", "mc"=>"Monaco", "md"=>"Moldova, Republic of", "mg"=>"Madagascar",
	"mh"=>"Marshall Islands", "mk"=>"Macedonia, Former Yugoslav Republic",  "ml"=>"Mali",
	"mm"=>"Myanmar", "mn"=>"Mongolia", "mo"=>"Macau", "mp"=>"Northern Mariana Islands", "mq"=>"Martinique", "mr"=>"Mauritania",
	"ms"=>"Montserrat", "mt"=>"Malta", "mu"=>"Mauritius", "mv"=>"Maldives", "mw"=>"Malawi", "mx"=>"Mexico",
	"my"=>"Malaysia", "mz"=>"Mozambique", "na"=>"Namibia", "nc"=>"New Caledonia", "ne"=>"Niger",  "nf"=>"Norfolk Island",
	"ng"=>"Nigeria", "ni"=>"Nicaragua", "nl"=>"Netherlands", "no"=>"Norway", "np"=>"Nepal", "nr"=>"Nauru",
	"nu"=>"Niue", "nz"=>"New Zealand", "om"=>"Oman", "pa"=>"Panama", "pe"=>"Peru",  "pf"=>"French Polynesia",
	"pg"=>"Papua New Guinea", "ph"=>"Philippines", "pk"=>"Pakistan", "pl"=>"Poland",  "pm"=>"St Pierre and Miquelon", "pn"=>"Pitcairn Island",
	"pr"=>"Puerto Rico", "ps"=>"Palestinian Territories", "pt"=>"Portugal", "pw"=>"Palau", "py"=>"Paraguay", "qa"=>"Qatar",
	"re"=>"Reunion Island", "ro"=>"Romania",  "ru"=>"Russian Federation", "rw"=>"Rwanda", "sa"=>"Saudi Arabia",  "sb"=>"Solomon Islands",
	"sc"=>"Seychelles", "sd"=>"Sudan", "se"=>"Sweden", "sg"=>"Singapore", "sh"=>"St Helena", "si"=>"Slovenia",
	"sj"=>"Svalbard and Jan Mayen Islands", "sk"=>"Slovak Republic",  "sl"=>"Sierra Leone", "sm"=>"San Marino", "sn"=>"Senegal",    "so"=>"Somalia",
	"sr"=>"Suriname", "st"=>"Sao Tome and Principe", "sv"=>"El Salvador", "sy"=>"Syrian Arab Republic", "sz"=>"Swaziland", "tc"=>"Turks and Caicos Islands",
	"td"=>"Chad", "tf"=>"French Southern Territories", "tg"=>"Togo", "th"=>"Thailand", "tj"=>"Tajikistan", "tk"=>"Tokelau",
	"tm"=>"Turkmenistan", "tn"=>"Tunisia", "to"=>"Tonga", "tp"=>"East Timor", "tr"=>"Turkey", "tt"=>"Trinidad and Tobago",
	"tv"=>"Tuvalu", "tw"=>"Taiwan", "tz"=>"Tanzania", "ua"=>"Ukraine", "ug"=>"Uganda", "uk"=>"United Kingdom",
	"um"=>"US Minor Outlying Islands", "us"=>"United States", "uy"=>"Uruguay", "uz"=>"Uzbekistan", "va"=>"Holy See (City Vatican State)", "vc"=>"Saint Vincent and the Grenadines",
	"ve"=>"Venezuela", "vg"=>"Virgin Islands (British)", "vi"=>"Virgin Islands (USA)", "vn"=>"Vietnam", "vu"=>"Vanuatu",  "wf"=>"Wallis and Futuna Islands",
	"ws"=>"Western Samoa", "ye"=>"Yemen", "yt"=>"Mayotte", "yu"=>"Yugoslavia", "za"=>"South Africa", "zm"=>"Zambia",
	"zw"=>"Zimbabwe", "aero"=>"Aviation", "biz"=>"Business", "coop"=>"Co-Operative Orga.", "info"=>"Info",
	"int"=>"Intern.Organisation", "name"=>"Homepage", "us"=>"Museum", "us"=>"USA",
	"org"=>"Organisation", "com"=>"Commercial", "net"=>"Network", "org"=>"Organisation",
	"edu"=>"University USA", "gov"=>"Government USA", "mil"=>"US Army"
	);
}
?>
