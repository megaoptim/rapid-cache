=== Rapid Cache ===

Stable tag: 1.1.0
Requires at least: 4.2
Tested up to: 5.8
Text Domain: rapid-cache
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Author: MegaOptim
Author URI: https://megaoptim.com/rapid-cache
Contributors: megaoptim,darkog
Tags: cache, speed, performance, fast, caching, advanced cache, static, client-side cache, rss cache, feed cache, gzip compression, page cache

Rapid Cache is advanced WordPress caching plugin inspired by simplicity that will make your site blazing fast!

== Description ==

**Rapid Cache** is a fork of *Comet Cache* that focuses on new features, stability and simplicity.

If you care about the speed of your site, Rapid Cache is one of those plugins that you absolutely MUST have installed! 🤓

Rapid Cache takes a real-time snapshot (building a cache) of every Page, Post, Category, Link, etc. These snapshots are then stored (cached) intuitively, so they can be referenced later, in order to save all of that processing time that has been dragging your site down and costing you money.

The plugin uses configuration options that you select from the options panel. See: **Rapid Cache -› Options** in your Dashboard. Once a file has been cached, Rapid Cache uses advanced techniques that allow it to recognize when it should and should not serve a cached version of the file.

By default, Rapid Cache does not serve cached pages to users who are logged in, or to users who have left comments recently. Rapid Cache also excludes administrative pages, login pages, POST/PUT/DELETE/GET(w/ query string) requests and/or CLI processes.

If you need more details, check our [Wiki](https://github.com/megaoptim/rapid-cache/wiki/)

= Features =

- SIMPLE and well-documented (just enable and you're all set).
- Options to control the automatic cache clearing behavior for Home and Posts Page, Author Page, Category, Tag, and Custom Term Archives, Custom Post Type Archives, RSS/RDF/ATOM Feeds, and XML Sitemaps.
- URI exclusion patterns (now supporting wildcards too).
- User-Agent exclusion patterns (now supporting wildcards too).
- HTTP referrer exclusion patterns (now supporting wildcards too).
- The ability to set an automatic expiration time for cache files.
- Client-Side Caching (to allow double-caching in the client browser).
- Caching for 404 requests to reduce the impact of those on the server.
- Feed Caching (RSS, RDF, and Atom Feed caching).
- Cache or ignore URLs that contain query strings (GET Requests).
- Apache Optimizations to enable GZIP Compression.
- WP-CLI Compatibility.
- [Actions/filters](https://github.com/megaoptim/rapid-cache/wiki/Developer-Hooks) and [PHP API](https://github.com/megaoptim/rapid-cache/wiki/Clearing-the-Cache-Dynamically) for developers

= Requirements =

In addition to the [WordPress Requirements](http://wordpress.org/about/requirements/), Rapid Cache requires the following minimum versions:

- At least PHP 5.4 (we recommend PHP 7.2+)
- Web server (Apache, NGINX, Litespeed, other)

= License =

Copyright © 2020 [MegaOptim](https://megaoptim.com/)
Copyright © 2016 WebSharks, Inc (coded in the USA)

Released under the terms of the [GNU General Public License](http://www.gnu.org/licenses/gpl-3.0.html).

== Screenshots ==

1. Step 1: Enable Rapid Cache
2. Step 2: Save All Changes; that's it!
3. One-click Clear Cache button
4. Plugin Deletion Safeguards
5. Intelligent and automatic cache clearing
6. Cache Directory
7. Cache Expiration Time
8. Client-Side Cache
9. GET Requests
10. 404 Requests
11. Feed Caching
12. URI Exclusion Patterns
13. HTTP Referrer Exclusion Patterns
14. User-Agent Exclusion Patterns
15. Theme/Plugin Developers

== Installation ==

**Quick Tip:** WordPress® can only deal with one cache plugin being activated at a time. Please uninstall any existing cache plugins that you've tried in the past. In other words, if you've installed W3 Total Cache, WP Super Cache, DB Cache Reloaded, or any other caching plugin, uninstall them all before installing Rapid Cache. One way to check, is to make sure this file: `wp-content/advanced-cache.php` and/or `wp-content/object-cache.php` are NOT present; and if they are, delete these files BEFORE installing Rapid Cache. Those files will only be present if you have a caching plugin already installed. If you don't see them, you're ready to install Rapid Cache :-).

= Rapid Cache is Very Easy to Install =

1. Upload the `/rapid-cache` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the Plugins menu in WordPress®.
3. Navigate to the **Rapid Cache** panel & enable it.

= How will I know Rapid Cache is Working? =

First of all, make sure that you've enabled Rapid Cache. After you activate the plugin in WordPress, go to the Rapid Cache options panel and enable caching (you can't miss the big yellow checkbox). Then scroll to the bottom and click Save All Changes. All of the other options on that page are already pre-configured for typical usage. Skip them all for now. You can go back through all of these later and fine-tune things the way you like them.

Once Rapid Cache has been enabled, **you'll need to log out** (and/or clear browser cookies). Cache files are NOT served to visitors who are logged in, and that includes you too :-) Cache files are NOT served to recent commenters either. If you've commented (or replied to a comment lately); please clear your browser cookies before testing.

**To verify that Rapid Cache is working**, navigate your site like a normal visitor would. Right-click on any page (choose View Source), then scroll to the very bottom of the document. At the bottom, you'll find comments that show Rapid Cache stats and information. You should also notice that page-to-page navigation is lightning fast compared to what you experienced prior to installing Rapid Cache.

= Running Rapid Cache On A WordPress® Multisite Installation =

WordPress® Multisite Networking is a special consideration in WordPress®. If Rapid Cache is installed under a Multisite Network installation, it will be enabled for ALL blogs the same way. The centralized config options for Rapid Cache, can only be modified by a Super Administrator operating on the main site. Rapid Cache has internal processing routines that prevent configuration changes, including menu displays; for anyone other than a Super Administrator operating on the main site.

= EMERGENCY: If All Else Fails (How-To Remove Rapid Cache) =

Ordinarily you can just deactivate Rapid Cache from the plugins menu in WordPress. However, if you're having a more serious issue, please follow the instructions here.

1. Log into your site via FTP; perhaps using [FileZilla](https://www.youtube.com/watch?v=adxmlHDim6c).
2. Delete this file: `/wp-content/advanced-cache.php`
3. Delete this directory: `/wp-content/plugins/rapid-cache/`
4. Remove this line from your `/wp-config.php` file: `define('WP_CACHE', TRUE);`

Rapid Cache is now completely uninstalled and you can start fresh :-)

== Frequently Asked Questions ==

= How can i migrate from Comet Cache =

Rapid Cache comes with migration tool to allow you to migrate your settings from Comet Cache, but you can also do that manually. To utilize the migration tool, follow the steps:

1. Go to Comet Cache settings and make sure "Plugin Deletion Safeguards" is set to "Safeguard my options and Cache" and then deactivate it!
2. Activate Rapid Cache and navigate to Rapid Cache Settings > Import/Export/Migrate and click on the "Migrate" button. That's all!

= How do I know that Rapid Cache is functional? =

First of all, make sure that you've enabled Rapid Cache. After you activate the plugin, go to the Rapid Cache options panel and enable it, then scroll to the bottom and click Save All Changes. All of the other options on that page are already pre-configured for typical usage. Skip them all for now. You can go back through all of them later and fine-tune things the way you like them.

Once Rapid Cache has been enabled, **you'll need to log out** (and/or clear browser cookies). Cache files are NOT served to visitors who are logged in, and that includes you too :-) Cache files are NOT served to recent commenters either. If you've commented (or replied to a comment lately); please clear your browser cookies before testing.

**To verify that Rapid Cache is working**, navigate your site like a normal visitor would. Right-click on any page (choose View Source), then scroll to the very bottom of the document. At the bottom, you'll find comments that show Rapid Cache stats and information. You should also notice that page-to-page navigation is lightning fast compared to what you experienced prior to installing Rapid Cache.

= What is the downside to running Rapid Cache? =

There is NOT one! Rapid Cache is a MUST HAVE for every WordPress® powered site. In fact, we really can't think of any site running WordPress® that would want to be without it. To put it another way, the WordPress® software itself comes with a built in action reference for an `advanced-cache.php` file, because WordPress® developers realize the importance of such as plugin. The `/wp-content/advanced-cache.php` file is named as such, because the WordPress® developers expect it to be there when caching is enabled by a plugin. If you don't have the `/wp-content/advanced-cache.php` file yet, it is because you have not enabled Rapid Cache from the options panel yet.

= So why does WordPress® need to be cached? =

To understand how Rapid Cache works, first you have to understand what a cached file is, and why it is absolutely necessary for your site and every visitor that comes to it. WordPress® (by its very definition) is a database-driven publishing platform. That means you have all these great tools on the back-end of your site to work with, but it also means that every time a Post/Page/Category is accessed on your site, dozens of connections to the database have to be made, and literally thousands of PHP routines run in harmony behind-the-scenes to make everything jive. The problem is, for every request that a browser sends to your site, all of these routines and connections have to be made (yes, every single time). Geesh, what a waste of processing power, memory, and other system resources. After all, most of the content on your site remains the same for at least a few minutes at a time. If you've been using WordPress® for very long, you've probably noticed that (on average) your site does not load up as fast as other sites on the web. Now you know why!

In computer science, a cache (pronounced /kash/) is a collection of data duplicating original values stored elsewhere or computed earlier, where the original data is expensive to fetch (owing to longer access time) or to compute, compared to the cost of reading the cache. In other words, a cache is a temporary storage area where frequently accessed data can be stored for rapid access. Once the data is stored in the cache, it can be used in the future by accessing the cached copy rather than re-fetching or recomputing the original data.

= Where & why are the cache files stored on my server? =

The cache files are stored in a special directory: `/wp-content/cache/rapid-cache`. This directory needs to remain writable, just like the `/wp-content/uploads` directory on many WordPress® installations. The `/rapid-cache/cache` directory is where cache files reside. These files are stored using an intutive directory structure that named based on the request URL (`HTTPS/HTTP_HOST/REQUEST_URI`). See also: **Dashboard -› Rapid Cache -› Cache Directory/Expiration Time** for further details.

Whenever a request comes in from someone on the web, Rapid Cache checks to see if it can serve a cached file; e.g. it looks at the `HTTPS/HTTP_HOST/REQUEST_URI` environent variables, then it checks the `/rapid-cache/cache` directory. If a cache file has been built already, and it matches an existing `HTTPS.HTTP_HOST.REQUEST_URI` combination; and it is not too old (see: **Dashboard -› Rapid Cache -› Cache Directory/Expiration Time**), then it will serve that file instead of asking WordPress® to regenerate it. This adds tremendous speed to your site and reduces server load.

= Is this plugin compatible with Autoptimize?

Sure, this is actually a great combination.

= Is this plugin comaptible with WP Rocket?

No, WP Rocket has its own caching mechanisms.

= Does this plugin optimizes images?

No, for image optimization please check <a href="https://wordpress.org/plugins/megaoptim-image-optimizer/">MegaOptim Image Optimizer</a>, it works great in combination with Rapid Cache.

= Is this plugin compatible with WooCommerce

Yes, sure!

= How can i clear the cache programmatically? =

Sure, we added the following functions:

    rapidcache_clear_cache(), // Clear current site cache
    rapidcache_clear_post_cache($post_id),  // Clear single post cache
    rapidcache_clear_url_cache($url), // Clear url cache
    rapidcache_wipe_cache(),  // Clear entire cache (all sites if multisite)
    rapidcache_purge_expired_cache() // Clear only the expired cache files, leaving the valid intact.
    rapidcache_get_version(), // Returns the plugin version
    rapidcache_get_options(); // Returns the saved plugin options

= Where can i find more details or guides about the plugin? =

We have a [Wiki](https://github.com/megaoptim/rapid-cache/wiki/) page with gudes and some more FAQs

= Does this plugin provides developer hooks ? =

Sure. Read our [developer hooks](https://github.com/megaoptim/rapid-cache/wiki/Developer-Hooks) guide!

= What happens if a user logs in? Are cache files used then? =

By default, Rapid Cache does NOT serve cached pages to users who are logged in, or to users who have left comments recently. Rapid Cache also excludes administrative pages, login pages, POST/PUT/DELETE/GET(w/ query string) requests and/or CLI processes.

= Will comments and other dynamic parts of my blog update immediately? =

It depends on your configuration of Rapid Cache. There is an automatic expiration system (the garbage collector), which runs through WordPress® behind-the-scene, according to your Expiration setting (see: **Dashboard -› Rapid Cache -› Cache Directory/Expiration Time**). There is also a built-in expiration time on existing files that is checked before any cache file is served up, which also uses your Expiration setting. In addition; whenever you update a Post or a Page, Rapid Cache can automatically prune that particular file from the cache so it instantly becomes fresh again. Otherwise, your visitors would need to wait for the previous cached version to expire.

By default, Rapid Cache does NOT serve cached pages to users who are logged in, or to users who have left comments recently. Rapid Cache also excludes administrative pages, login pages, POST/PUT/DELETE/GET(w/ query string) requests and/or CLI processes.

= How do I enable GZIP compression? Is GZIP supported? =

There is no need to use an `.htaccess` file with this plugin; caching is handled by WordPress®/PHP alone. That being said, if you also want to take advantage of GZIP compression (and we do recommend this), then you WILL need an `.htaccess` file to accomplish that part. This plugin fully supports GZIP compression on its output. However, it does not handle GZIP compression directly. We purposely left GZIP compression out of this plugin, because GZIP compression is something that should really be enabled at the Apache level or inside your `php.ini` file. GZIP compression can be used for things like JavaScript and CSS files as well, so why bother turning it on for only WordPress-generated pages when you can enable GZIP at the server level and cover all the bases!

If you want to enable GZIP and your site is running on the Apache web server, visit **Dashboard -> Rapid Cache -> Apache Optimizations -> Enable GZIP Compression?**; or to enable GZIP compression manually create an `.htaccess` file in your WordPress® installation directory (or edit the one that's already there) and put the following few lines in it. That is all there is to it. GZIP is now enabled!

	<IfModule deflate_module>
		<IfModule filter_module>
			AddOutputFilterByType DEFLATE text/plain text/html
			AddOutputFilterByType DEFLATE text/xml application/xml application/xhtml+xml application/xml-dtd
			AddOutputFilterByType DEFLATE application/rdf+xml application/rss+xml application/atom+xml image/svg+xml
			AddOutputFilterByType DEFLATE text/css text/javascript application/javascript application/x-javascript
			AddOutputFilterByType DEFLATE font/otf font/opentype application/font-otf application/x-font-otf
			AddOutputFilterByType DEFLATE font/ttf font/truetype application/font-ttf application/x-font-ttf
		</IfModule>
	</IfModule>

If your installation of Apache does not have `mod_deflate` installed. You can also enable gzip compression using PHP configuration alone. In your `php.ini` file, you can simply add the following line anywhere: `zlib.output_compression = on`

= I'm a developer. How can I prevent certain files from being cached? =

    // define('RAPID_CACHE_ALLOWED', FALSE); // The easiest way.
    // or $_SERVER['RAPID_CACHE_ALLOWED'] = FALSE; // Also very easy.
    // or define('DONOTCACHEPAGE', TRUE); // For compatibility with other cache plugins.

When your script finishes execution, Rapid Cache will know that it should NOT cache that particular page. It does not matter where or when you define this Constant; e.g. `define('RAPID_CACHE_ALLOWED', FALSE);` because Rapid Cache is the last thing to run during execution. So as long as you define this Constant at some point in your routines, everything will be fine.

Rapid Cache also provides support for `define('DONOTCACHEPAGE', TRUE)`, which is used by the WP Super Cache plugin as well. Another option is: `$_SERVER['RAPID_CACHE_ALLOWED'] = FALSE`. The `$_SERVER` array method is useful if you need to disable caching at the Apache level using `mod_rewrite`. The `$_SERVER` array is filled with all environment variables, so if you use `mod_rewrite` to set the `RAPID_CACHE_ALLOWED` environment variable, that will end up in `$_SERVER['RAPID_CACHE_ALLOWED']`. All of these methods have the same end result, so it's up to you which one you'd like to use.

= What should my expiration setting be? =

If you don't update your site much, you could set this to `6 months`; optimizing everything even further. The longer the cache expiration time is, the greater your performance gain. Alternatively, the shorter the expiration time, the fresher everything will remain on your site. A default value of `7 days` (recommended expiration time), is a good conservative middle-ground.

Keep in mind that your expiration setting is only one part of the big picture. Rapid Cache will also purge the cache automatically as changes are made to the site (i.e. you edit a post, someone comments on a post, you change your theme, you add a new navigation menu item, etc., etc.). Thus, your expiration time is really just a fallback; e.g. the maximum amount of time that a cache file could ever possibly live.

That being said, you could set this to just `60 seconds` and you would still see huge differences in speed and performance. If you're just starting out with Rapid Cache (perhaps a bit nervous about old cache files being served to your visitors); you could set this to something like `30 minutes` and experiment with it while you build confidence in Rapid Cache. It's not necessary, but many site owners have reported this makes them feel like they're more-in-control when the cache has a short expiration time. All-in-all, it's a matter of preference :-)

= EMERGENCY: If all else fails, how can I remove Rapid Cache? =

Ordinarily you can just deactivate Rapid Cache from the plugins menu in WordPress. However, if you're having a more serious issue, please follow the instructions here.

1. Log into your site via FTP; perhaps using [FileZilla](https://www.youtube.com/watch?v=adxmlHDim6c).
2. Delete this file: `/wp-content/advanced-cache.php`
3. Delete this directory: `/wp-content/plugins/rapid-cache/`
4. Remove this line from your `/wp-config.php` file: `define('WP_CACHE', TRUE);`

Rapid Cache is now completely uninstalled and you can start fresh :-)

== Changelog ==

= 1.1.0 =
Release date: March 21st, 2020
- New: Added more options for clearing cache in the Admin Bar
- New: Fix wrong query parameter in 'GET Requests' settings. @props aj-adl
- Fix: Improved instructions in Import/Export/Migration tab

= 1.0.1 =

Release date: 13th September 2020
- New: Added import/export options
- New: Added option to preserve settings of the previous Comet install in Rapid Cache > "Import/Export"

= 1.0.0  =

Release date: 11th September 2020
- New: Added composer PSR4 autoloading
- New: Added mbstring polyfill for better compatibility
- New: Removed old notification about deprecated APC support
- New: Rewrote the requirement check system
- New: Removed the PRO version front-end references
- New: Moved the assets to own assets/ directory
- New: Updated conflicting plugins
- New: Removed old database migrations
- New: Updated documentation https://github.com/megaoptim/rapid-cache/wiki
- New: Added developer functions: rapidcache_get_version(), rapidcache_clear_cache(), rapidcache_clear_post_cache($post_id), rapidcache_clear_url_cache($url), rapidcache_wipe_cache(), rapidcache_purge_expired_cache(), rapidcache_get_options()
- New: Added `rapid_cache_ob_callback_filter` that allow to filter the page output before saving into cache
- Fix: 'Non static method should not be called statically
- Fix: 'Headers already sent' warnings
- Fix: 'Notice: id was called incorrectly. Product properties should not be accessed directly.' warning with WooCommerce
- Fix: Warnings when disk_*_space functions are disabled
- New: Add Referrer-Policy to cacheable headers list
- New: Add actions: rapid_cache_wipe_cache, rapid_cache_clear_cache, rapid_cache_purge_cache, rapid_cache_wurge_cache
