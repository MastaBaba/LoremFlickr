<?php
require_once("includes/initialize.php");
require_once("includes/phpFlickr/phpFlickr.php");
require_once("includes/functions.php");
require_once("includes/smart_resize_image.function.php");

$_GET = setGet();

$f = "";
if (isset($_GET["f"])) {
	$f = $_GET["f"];
}

$w = 0;
if (isset($_GET["w"]))
	$w = (int)$_GET["w"];
if ($w <= 0)
	$w = "640";
	
$h = 0;
if (isset($_GET["h"]))
	$h = (int)$_GET["h"];
if ($h <= 0)
	$h = "320";

$m = "";
if (isset($_GET["m"]))
	$m = trim(strtolower($_GET["m"]));
if ($m != "any" && $m != "all")
	$m = 'any';

$u = "";
if (isset($_GET["user"])) {
	$u = trim(strtolower($_GET["user"]));

	$user = flickrUser($u);

	if (!isset($user["id"])) {
		$user["id"] = "";
	}
}
else
	$user["id"] = "";

$k = "";
if (isset($_GET["k"])) {
	if ($_GET["k"] != "notag") {
		$k = trim(strtolower($_GET["k"]));
	}
}
if ($k == "" && $user["id"] == "")
	$k = "kitten";

$images = flickrTagmatch ($k, $m, 500, 0, 0, "interestingness-desc", $user["id"]);

if (count($images["photo"]) > 0) {
	$rndID = rand(0, count($images["photo"]) - 1);

	if (isset($_GET["u"])) {
		if ("".$_GET["u"] == "d") {
			$rndID = (-1 + (-1 + date("n", time())) * date("t", time()) + date("j", time())) % (count($images["photo"]) - 1);
		}
	}
	
	$selectedImage = $images["photo"][$rndID];

	$selectedImage["url"] = getImageURL($selectedImage, $w, $h);
	$selectedImage["licensename"] = getImageLicense($selectedImage);
	$selectedImage["fulltitle"] = $selectedImage["title"]." by ".$selectedImage["ownername"];
}

if (!isset($selectedImage)) {
	$selectedImage["url"] = $site["flickr"]["defaultImage"];
	$selectedImage["licensename"] = $site["flickr"]["defaultLicense"];
	$selectedImage["fulltitle"] = $site["flickr"]["defaultFullTitle"];
}

$selectedImage["md5"] = md5($selectedImage["url"]);
$selectedImage["cached"] = $site["imageCache"].$selectedImage["md5"].".jpg";
$selectedImage["cachedresized"] = $site["path"].$site["folder"].$site["imageCache"].$selectedImage["md5"].".".$w.".".$h.".jpg";
$selectedImage["cachedresizedpublic"] = $site["folder"].$site["imageCache"].$selectedImage["md5"].".".$w.".".$h.".jpg";

cacheImage ($selectedImage["url"], $selectedImage["cached"]);
smart_resize_image($selectedImage["cached"], null, $w, $h, false, $selectedImage["cachedresized"], true);

//Convert image to jpg
$img = $selectedImage["cachedresized"];
$img_info = getimagesize($img);
$width = $img_info[0];
$height = $img_info[1];

$convert = false;
switch ($img_info[2]) {
	case IMAGETYPE_GIF  : $src = imagecreatefromgif($img); $convert = true; break;
	case IMAGETYPE_PNG  : $src = imagecreatefrompng($img); $convert = true; break;
}
if ($convert) {
	$tmp = imagecreatetruecolor($width, $height);
	imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
	imagejpeg($tmp, $selectedImage["cachedresized"]);
}

//Greyscale, texts and boxes
$image = imagecreatefromjpeg($selectedImage["cachedresized"]);

if ($f == "g")
	imagefilter($image, IMG_FILTER_GRAYSCALE);
elseif ($f == "p")
	imagefilter($image, IMG_FILTER_PIXELATE, 3);
elseif ($f == "red")
	imagefilter($image, IMG_FILTER_COLORIZE, 255, 0, 0);
elseif ($f == "green")
	imagefilter($image, IMG_FILTER_COLORIZE, 0, 255, 0);
elseif ($f == "blue")
	imagefilter($image, IMG_FILTER_COLORIZE, 0, 0, 255);

$transparent = imagecolorallocatealpha($image, 20, 20, 20, 100);
imagefilledrectangle($image, 0, -1, 1 + strlen($selectedImage["licensename"]) * 5, 8, $transparent);
imagefilledrectangle($image, 0, $h - 8, 1 + strlen($selectedImage["fulltitle"]) * 5, $h, $transparent);

$color = imagecolorallocate($image, 255, 255, 255);
imagestring($image, 1, 1, 0, $selectedImage["licensename"], $color);
imagestring($image, 1, 1, $h - 8, $selectedImage["fulltitle"], $color);

imagejpeg($image, $selectedImage["cachedresized"]);

//Output file
$fp = fopen($selectedImage["cachedresized"], 'rb');
header("Content-Type: image/jpg");
header("Content-Length: " . filesize($selectedImage["cachedresized"]));
fpassthru($fp);
exit;
