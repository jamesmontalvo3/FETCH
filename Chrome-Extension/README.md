To do for Chrome Extension
==========================

* Make search-wikis user defined
  * See http://developer.chrome.com/extensions/options.html
* Make search performed using wiki/api.php instead of database access
  * This should be interesting if there are many wikis, since many AJAX calls will be made that all have to sync up somehow
  * Also, in cases where the wikis are on a corporate intranet, all the wikis are belong to us (i.e. they're on the same server)
* Break the Chrome Extension out of the rest of this repository (make two repos)
