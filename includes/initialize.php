<?php
session_start();

error_reporting(E_ALL); 
ini_set( 'display_errors','1');

date_default_timezone_set("America/Sao_Paulo"); //Or whatever you fancy

//Site variables
$site["URL"] = ""; //Website URL
$site["name"] = ""; //Website name
$site["folder"] = "/"; //Folder from website root.
$site["path"] = "/path/to/root"; //Path from server root. No trailing slash.
$site["imageCache"] = "cache/images/"; //Cache location from $site["folder"]
$site["filterSwitches"] = "|g|p|red|green|blue|o|"; //Available switches

$site["sizes"] = array("o", "l", "c", "z", "n"); //Available Flickr sizes

//phpFlickr settings
$site["flickr"]["key"] = ""; //Flickr key
$site["flickr"]["secret"] = ""; //Flickr secret
$site["flickr"]["cache"] = "cache/flickrsearch"; //Flickr cache
$site["flickr"]["cacheduration"] = "".(24 * 60 * 60);

$site["flickr"]["defaultImage"] = "https://farm3.staticflickr.com/2917/14666161715_62204a459d_h.jpg"; //Full URL to default image
$site["flickr"]["defaultLicense"] = "cc (no matching photo found)"; //License info for detault image
$site["flickr"]["defaultFullTitle"] = "Machu Picchu by MastaBaba (Babak Fakhamzadeh)"; //title info for default image
