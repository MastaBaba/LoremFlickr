# LoremFlickr
LoremFlickr provides placeholder images for every case, web or print, on almost any subject, in any size. Visit http://loremflickr.com to see this in action.

Find completely updated code at https://github.com/MastaBaba/LoremFlickr-2.

## How to install
Put the files in the location of your choice. Enter your Flickr API and server details in initialize.php. Perhaps adjust the cache locations and make sure those folders are server-writable. 

Inside the includes folder, add dan-coulter's phpFlickr: Download his GitHub repository, unzip the files and put the contents of phpflickr-master inside the phpFlickr folder.

You might want to add a cronjob to clean the cache of old files.

Depending on where you've put your files, you might need to update the .htaccess file to make sure redirects point to image.php in the right folder.
## How to use
Point your browser to, depending on where you put the files, http://your-website.com/g/320/240/paris,girl/all

For more details, visit http://loremflickr.com
## Credits
+ LoremFlickr is maintained by Babak Fakhamzadeh, http://babakfakhamzadeh.com. On Flickr,  https://www.flickr.com/photos/mastababa/
+ The image resize function is courtesy of Nimrod007: https://github.com/Nimrod007/PHP_image_resize	
+ phpFlickr is maintained by dan-coulter: https://github.com/dan-coulter/phpflickr
