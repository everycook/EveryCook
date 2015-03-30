<?php
	include_once '/db/protected/extensions/phpFlickr.php';
	define('API_KEY', '0234d7c05a28b88127d199ad854c6250');
	define('API_SECRET', '1097e1e06045b42d');
    
	$result = null;
	//if (isset($_GET['q'])) {
	$flickr = new phpFlickr(API_KEY, API_SECRET);
	$result = $flickr->photos_search(array(
					//"text" => $_GET['q'],
					"text" => "onion",
					"per_page" => 6,
					"license" => "1,2,3,4,5,6",
					"sort" => 'relevant',
	));
	//}
    
	if ($result) {
		foreach ($result['photo'] as $photo) {
			?>
			<li style="float: left; margin: 0 10px 10px 0;">
			<a target="_blank" href="http://farm<?php echo $photo['farm']; ?>.static.flickr.com/<?php echo $photo['server']; ?>/<?php echo $photo['id']; ?>_<?php echo $photo['secret']; ?>.jpg" title="<?php echo $photo['title']; ?>">
			<img src="http://farm<?php echo $photo['farm']; ?>.static.flickr.com/<?php echo $photo['server']; ?>/<?php echo $photo['id']; ?>_<?php echo $photo['secret']; ?>_s.jpg">
			</a>
			</li>
			<?php
		}
	}
?>
<h1>aaa</h1>
