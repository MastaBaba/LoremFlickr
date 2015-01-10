<?php
function flickrTagmatch ($keywords = "", $mode, $count, $page, $date = 0, $sort = "interestingness-desc", $user = "", $test = false) {
	global $site;

	if ($keywords != "") {
		$keywords = str_replace(".", "", $keywords);
		$parameters["tags"] = $keywords;	
		$parameters["tag_mode"] = $mode;
	}	
	
	$f = new phpFlickr ( $site["flickr"]["key"] );
	$f->enableCache("fs", $site["path"].$site["folder"].$site["flickr"]["cache"], $site["flickr"]["cacheduration"]);

	$parameters["sort"] = $sort;
	if ($user != "") {
		//No license restrictions when specifying a user.
		$parameters["user_id"] = $user;
	}
	else {
		$parameters["license"] = "1,2,3,4,5,6,7";
	}
	$parameters["per_page"] = "".$count;
	$parameters["page"] = "".$page;
	if ($date != 0) {
		$timeStart = strtotime(date ( "Y-m-d 0:0:0", $date ));
		$timeEnd = strtotime(date ( "Y-m-d 23:59:59", $date ));

		$parameters["min_taken_date"] = $timeStart;
		$parameters["max_taken_date"] = $timeEnd;
	}
	$parameters["content_type"] = "1";
	$parameters["extras"] = "license,owner_name,url_o,url_l,url_c,url_z,url_n";

	$tagmatch = $f->photos_search ( $parameters );

	if ($test) {
		print "<pre>";
		print_r ($parameters);

		print_r ($tagmatch);
		print "</pre>";
	}

	return $tagmatch;
}

function flickrUser ($userName) {
	global $site;

	$f = new phpFlickr ( $site["flickr"]["key"] );
	$f->enableCache("fs", $site["path"].$site["folder"].$site["flickr"]["cache"], $site["flickr"]["cacheduration"]);

	$user = $f->people_findByUsername($userName);
	
	return $user;
}

function getImageURL($image, $w, $h) {
	global $site;

	$t = false;
	
	foreach ($site["sizes"] as $key=>$value) {
		if (isset($image["url_".$value])) {
			if ($w <= $image["width_".$value] && $h <= $image["height_".$value]) {
				$t = $image["url_".$value];
			}
		}
	}
	
	if ($t == false) {
		if (isset($image["url_o"])) {
			$t = $image["url_o"];
		}
		elseif (isset($image["url_l"])) {
			$t = $image["url_l"];
		} 
	}

	return $t;
}

function getImageLicense($image) {
	$t = false;
	
	if ($image["license"] == 0)
		$t = "All rights reserved"; //copyrighted
	elseif ($image["license"] == 1)
		$t = "cc-nc-sa";
	elseif ($image["license"] == 2)
		$t = "cc-nc";
	elseif ($image["license"] == 3)
		$t = "cc-nc-nd";
	elseif ($image["license"] == 4)
		$t = "cc";
	elseif ($image["license"] == 5)
		$t = "cc-sa";
	elseif ($image["license"] == 6)
		$t = "cc-nd";
	elseif ($image["license"] == 7)
		$t = "no copyright restrictions";
	elseif ($image["license"] == 8)
		$t = "USGov";
	
	return $t;
}

function setGet () {
	global $site;
	
	if (isset($_GET)) {
	//	if ("".$_GET["v1"] == "g") {
		if (strstr($site["filterSwitches"], "".$_GET["v1"])) {
			$_GET["f"] = "".$_GET["v1"];
			$_GET["w"] = (int)$_GET["v2"];
			$_GET["h"] = (int)$_GET["v3"];
			
			if (isset($_GET["v4"]))
				$_GET["k"] = "".$_GET["v4"];
	
			if (isset($_GET["v5"]))
				$_GET["m"] = "".$_GET["v5"];
				
			if (isset($_GET["v6"]))
				$_GET["user"] = "".$_GET["v6"];
		}
		else {
			$_GET["w"] = (int)$_GET["v1"];
			$_GET["h"] = (int)$_GET["v2"];
			
			if (isset($_GET["v3"]))
				$_GET["k"] = "".$_GET["v3"];
	
			if (isset($_GET["v4"]))
				$_GET["m"] = "".$_GET["v4"];
		}
	}

	return $_GET;
}

function cacheImage ($url, $path) {

  $newfilename = $path;
  $file = fopen ($url, "rb");
  if ($file) {
    $newfile = fopen ($newfilename, "wb");

    if ($newfile)
    while(!feof($file)) {
      fwrite($newfile, fread($file, 1024 * 8 ), 1024 * 8 );
    }
  }

  if ($file) {
    fclose($file);
  }
  if ($newfile) {
    fclose($newfile);
  }
 }
