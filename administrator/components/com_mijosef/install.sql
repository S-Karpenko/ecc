# -----------------------
# MijoSEF SQL Installation
# -----------------------
DROP TABLE IF EXISTS `#__mijosef_urls`;
CREATE TABLE IF NOT EXISTS `#__mijosef_urls` (
  `id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `url_sef` VARCHAR(255) NOT NULL DEFAULT '',
  `url_real` VARCHAR(255) NOT NULL DEFAULT '',
  `cdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `used` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `hits` INT(12) UNSIGNED NOT NULL DEFAULT '0',
  `source` TEXT NOT NULL,
  `params` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_real` (`url_real`),
  KEY `url_sef` (`url_sef`),
  KEY `used` (`used`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__mijosef_urls_moved`;
CREATE TABLE IF NOT EXISTS `#__mijosef_urls_moved` (
  `id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `url_new` VARCHAR(255) NOT NULL DEFAULT '',
  `url_old` VARCHAR(255) NOT NULL DEFAULT '',
  `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `hits` INT(12) UNSIGNED NOT NULL DEFAULT '0',
  `last_hit` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_old` (`url_old`),
  KEY `url_new` (`url_new`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__mijosef_metadata`;
CREATE TABLE IF NOT EXISTS `#__mijosef_metadata` (
  `id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `url_sef` VARCHAR(255) NOT NULL DEFAULT '',
  `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `description` VARCHAR(255) NOT NULL DEFAULT '',
  `keywords` VARCHAR(255) NOT NULL DEFAULT '',
  `lang` VARCHAR(30) NOT NULL DEFAULT '',
  `robots` VARCHAR(30) NOT NULL DEFAULT '',
  `googlebot` VARCHAR(30) NOT NULL DEFAULT '',
  `canonical` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_sef` (`url_sef`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__mijosef_sitemap`;
CREATE TABLE IF NOT EXISTS `#__mijosef_sitemap` (
  `id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `url_sef` VARCHAR(255) NOT NULL DEFAULT '',
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `sdate` DATE NOT NULL DEFAULT '0000-00-00',
  `frequency` VARCHAR(30) NOT NULL DEFAULT '',
  `priority` VARCHAR(10) NOT NULL DEFAULT '',
  `sparent` INT(12) UNSIGNED NOT NULL DEFAULT '0',
  `sorder` INT(5) UNSIGNED NOT NULL DEFAULT '1000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_sef` (`url_sef`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__mijosef_tags`;
CREATE TABLE IF NOT EXISTS `#__mijosef_tags` (
  `id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL DEFAULT '',
  `alias` VARCHAR(150) NOT NULL DEFAULT '',
  `description` VARCHAR(150) NOT NULL DEFAULT '',
  `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `ordering` INT(12) NOT NULL DEFAULT '0',
  `hits` INT(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__mijosef_tags_map`;
CREATE TABLE IF NOT EXISTS `#__mijosef_tags_map` (
  `url_sef` VARCHAR(255) NOT NULL DEFAULT '',
  `tag` VARCHAR(150) NOT NULL DEFAULT ''
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__mijosef_ilinks`;
CREATE TABLE IF NOT EXISTS `#__mijosef_ilinks` (
  `id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `word` VARCHAR(255) NOT NULL DEFAULT '',
  `link` VARCHAR(255) NOT NULL DEFAULT '',
  `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `nofollow` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `iblank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `ilimit` VARCHAR(30) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`),
  UNIQUE KEY `word` (`word`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__mijosef_extensions`;
CREATE TABLE IF NOT EXISTS `#__mijosef_extensions` (
  `id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL DEFAULT '',
  `extension` VARCHAR(45) NOT NULL DEFAULT '',
  `params` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `extension` (`extension`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__mijosef_bookmarks`;
CREATE TABLE IF NOT EXISTS `#__mijosef_bookmarks` (
  `id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL DEFAULT '',
  `html` TEXT NOT NULL DEFAULT '',
  `btype` VARCHAR(20) NOT NULL DEFAULT '',
  `placeholder` VARCHAR(150) NOT NULL DEFAULT '',
  `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__mijosef_bookmarks` (`id`, `name`, `html`, `btype`, `placeholder`) VALUES
(1,'Digg.com','<a rel="nofollow" href="http://digg.com/" title="Digg!" target="_blank" onclick="window.open(''http://digg.com/submit?url=*mijosef*url*&title=*mijosef*title*&bodytext=*mijosef*description*''); return false;"><img height="18px" width="18px" src="*mijosef*imageDirectory*/digg.png" alt="Digg!" title="Digg!" /></a>','icon','{mijosef icon}'),
(2,'Digg.com - Normal','<script type="text/javascript">digg_url=''*mijosef*url*''; digg_title=''*mijosef*title*''; digg_bodytext=''*mijosef*description*''; digg_bgcolor=''*mijosef*bgcolor*''; digg_window=''new'';</script><script src="http://digg.com/tools/diggthis.js" type="text/javascript"></script>','badge','{mijosef Digg1}'),
(3,'Digg.com - Compact','<script type="text/javascript">digg_url=''*mijosef*url*''; digg_title=''*mijosef*title*''; digg_bodytext=''*mijosef*description*''; digg_bgcolor=''*mijosef*bgcolor*''; digg_skin=''compact''; digg_window = ''new'';</script><script src="http://digg.com/tools/diggthis.js" type="text/javascript"></script>','badge','{mijosef Digg2}'),
(4,'Reddit.com','<a rel="nofollow" onclick="window.open(''http://reddit.com/submit?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://reddit.com" title="Reddit!" target="_blank"><img height="18px" width="18px" src="*mijosef*imageDirectory*/reddit.png" alt="Reddit!" title="Reddit!" /></a>','icon','{mijosef icon}'),
(5,'Reddit.com - Style 1','<script>reddit_url=''*mijosef*url*''</script><script>reddit_title=''*mijosef*title*''</script><script type="text/javascript" src="http://reddit.com/button.js?t=1"></script>','badge','{mijosef Reddit1}'),
(6,'Reddit.com - Style 2','<script>reddit_url=''*mijosef*url*''</script><script>reddit_title=''*mijosef*title*''</script><script type="text/javascript" src="http://reddit.com/button.js?t=2"></script>','badge','{mijosef Reddit2}'),
(7,'Reddit.com - Style 3','<script>reddit_url=''*mijosef*url*''</script><script>reddit_title=''*mijosef*title*''</script><script type="text/javascript" src="http://reddit.com/button.js?t=3"></script>','badge','{mijosef Reddit3}'),
(8,'Del.icio.us','<a rel="nofollow" href="http://del.icio.us/" title="Del.icio.us!" target="_blank" onclick="window.open(''http://del.icio.us/post?v=4&noui&jump=close&url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;"><img height="18px" width="18px" src="*mijosef*imageDirectory*/delicious.png" alt="Del.icio.us!" title="Del.icio.us!" /></a>','icon','{mijosef icon}'),
(9,'Del.icio.us - Tall','<script src="http://images.del.icio.us/static/js/blogbadge.js"></script>','badge','{mijosef Delicious2}'),
(10,'Del.icio.us - One Line','<script type="text/javascript">if (typeof window.Delicious == "undefined") window.Delicious = {}; Delicious.BLOGBADGE_DEFAULT_CLASS = ''delicious-blogbadge-line'';</script><script src="http://images.del.icio.us/static/js/blogbadge.js"></script>','badge','{mijosef Delicious2}'),
(11,'Mixx','<a rel="nofollow" onclick="window.open(''http://www.mixx.com/submit?page_url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://www.mixx.com/" title="Mixx!" target="_blank"><img height="18px" width="18px" src="*mijosef*imageDirectory*/mixx.png" alt="Mixx!" title="Mixx!" /></a>','icon','{mijosef icon}'),
(12,'EntirelyOpenSource.com','<a onclick="window.open(''http://www.entirelyopensource.com/submit.php?url=*mijosef*url_encoded*''); return false;" href="http://www.entirelyopensource.com/" title="Free and Open Source Software News" target="_blank"><img height="18px" width="18px" src="*mijosef*imageDirectory*/entirelyopensource.png" alt="Free and Open Source Software News" title="Free and Open Source Software News" /></a>','icon','{mijosef icon}'),
(13,'Google Bookmarks','<a rel="nofollow" onclick="window.open(''http://www.google.com/bookmarks/mark?op=edit&bkmk=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://www.google.com/bookmarks/" title="Google!" target="_blank"><img height="18px" width="18px" src="*mijosef*imageDirectory*/google.png" alt="Google!" title="Google!" /></a>','icon','{mijosef icon}'),
(14,'Live.com','<a rel="nofollow" onclick="window.open(''https://favorites.live.com/quickadd.aspx?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="https://favorites.live.com/" title="Live!" target="_blank"><img height="18px" width="18px" src="*mijosef*imageDirectory*/live.png" alt="Live!" title="Live!" /></a>','icon','{mijosef icon}'),
(15,'Facebook.com','<a rel="nofollow" onclick="window.open(''http://www.facebook.com/sharer.php?u=*mijosef*url_encoded*&t=*mijosef*title_encoded*''); return false;" href="https://www.facebook.com/" title="Facebook!" target="_blank"><img height="18px" width="18px" src="*mijosef*imageDirectory*/facebook.png" alt="Facebook!" title="Facebook!" /></a>','icon','{mijosef icon}'),
(16,'Slashdot.org','<a rel="nofollow" onclick="window.open('' http://slashdot.org/bookmark.pl?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://slashdot.org/" title="Slashdot!" target="_blank"><img height="18px" width="18px" src="*mijosef*imageDirectory*/slashdot.png" alt="Slashdot!" title="Slashdot!" /></a>','icon','{mijosef icon}'),
(17,'Netscape.com','<a rel="nofollow" onclick="window.open(http://www.netscape.com/submit/?U=*mijosef*url_encoded*&T=*mijosef*title_encoded*''); return false;" href="http://www.netscape.com/" title="Netscape!" target="_blank"><img height="18px" width="18px" src="*mijosef*imageDirectory*/netscape.png" alt="Netscape!" title="Netscape!" /></a>','icon','{mijosef icon}'),
(18,'Technorati.com','<a rel="nofollow" onclick="window.open(''http://www.technorati.com/faves?add=*mijosef*url_encoded*''); return false;" href="http://www.technorati.com/" title="Technorati!" target="_blank"><img src="*mijosef*imageDirectory*/technorati.png" alt="Technorati!" title="Technorati!" /></a>','icon','{mijosef icon}'),
(19,'StumbleUpon.com','<a rel="nofollow" onclick="window.open(''http://www.stumbleupon.com/submit?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://www.stumbleupon.com/" title="StumbleUpon!" target="_blank"><img src="*mijosef*imageDirectory*/stumbleupon.png" alt="StumbleUpon!" title="StumbleUpon!" /></a>','icon','{mijosef icon}'),
(20,'MySpace.com','<a rel="nofollow" href="http://www.myspace.com/" title="MySpace!" target="_blank" onclick="window.open(''http://www.myspace.com/Modules/PostTo/Pages/?t=*mijosef*title*&u=*mijosef*url*''); return false;"><img height="18px" width="18px" src="*mijosef*imageDirectory*/myspace.png" alt="MySpace!" title="MySpace!" /></a>','icon','{mijosef icon}'),
(21,'Spurl.net','<a rel="nofollow" onclick="window.open(''http://www.spurl.net/spurl.php?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://www.spurl.net/" title="Spurl!" target="_blank"><img src="*mijosef*imageDirectory*/spurl.png" alt="Spurl!" title="Spurl!" /></a>','icon','{mijosef icon}'),
(22,'Wists.com','<a rel="nofollow" onclick="window.open(''http://wists.com/r.php?c=&r=*mijosef*url_encoded*&tot;e=*mijosef*title_encoded*''); return false;" href="http://wists.com/" title="Wists!" target="_blank"><img src="*mijosef*imageDirectory*/wists.png" alt="Wists!" title="Wists!" /></a>','icon','{mijosef icon}'),
(23,'Simpy.com','<a rel="nofollow" onclick="window.open(''http://www.simpy.com/simpy/LinkAdd.do?href=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://www.simpy.com/" title="Simpy!" target="_blank"><img src="*mijosef*imageDirectory*/simpy.png" alt="Simpy!" title="Simpy!" /></a>','icon','{mijosef icon}'),
(24,'Newsvine.com','<a rel="nofollow" onclick="window.open('' http://www.newsvine.com/_wine/save?u=*mijosef*url_encoded*&h=*mijosef*title_encoded*''); return false;" href="http://www.newsvine.com/" title="Newsvine!" target="_blank"><img src="*mijosef*imageDirectory*/newsvine.png" alt="Newsvine!" title="Newsvine!" /></a>','icon','{mijosef icon}'),
(25,'BlinkList.com','<a rel="nofollow" onclick="window.open('' http://blinklist.com/index.php?Action=Blink/addblink.php&Url=*mijosef*url_encoded*&Title=*mijosef*title_encoded*''); return false;" href="http://www.blinklist.com/" title="Blinklist!" target="_blank"><img src="*mijosef*imageDirectory*/blinklist.png" alt="Blinklist!" title="Blinklist!" /></a>','icon','{mijosef icon}'),
(26,'Furl.net','<a rel="nofollow" onclick="window.open(''http://furl.net/storeIt.jsp?u=*mijosef*url_encoded*&t=*mijosef*title_encoded*''); return false;" href="http://www.furl.net/" title="Furl!" target="_blank"><img src="*mijosef*imageDirectory*/furl.png" alt="Furl!" title="Furl!" /></a>','icon','{mijosef icon}'),
(27,'Fark.com','<a rel="nofollow" onclick="window.open(''http://cgi.fark.com/cgi/fark/submit.pl?new_url=*mijosef*url_encoded*&new_comment=*mijosef*title_encoded*&linktype='');return false;" href="http://fark.com" title="Fark!" target="_blank"><img src="*mijosef*imageDirectory*/fark.png" alt="Fark!" title="Fark!" /></a>','icon','{mijosef icon}'),
(28,'BlogMarks.net','<a rel="nofollow" onclick="window.open(''http://blogmarks.net/my/new.php?mini=1&simple=1&url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://blogmarks.net/" title="Blogmarks!" target="_blank"><img src="*mijosef*imageDirectory*/blogmarks.png" alt="Blogmarks!" title="Blogmarks!" /></a>','icon','{mijosef icon}'),
(29,'Yahoo! Buzz','<a rel="nofollow" onclick="window.open(''http://myweb2.search.yahoo.com/myresults/bookmarklet?u=*mijosef*url_encoded*&t=*mijosef*title_encoded*''); return false;" href="http://myweb2.search.yahoo.com/" title="Yahoo!" target="_blank"><img src="*mijosef*imageDirectory*/yahoo.png" alt="Yahoo!" title="Yahoo!" /></a>','icon','{mijosef icon}'),
(30,'Smarking.com','<a rel="nofollow" onclick="window.open(''http://smarking.com/editbookmark/?url=*mijosef*url_encoded*''); return false;" href="http://smarking.com/" title="Smarking!" target="_blank"><img src="*mijosef*imageDirectory*/smarking.png" alt="Smarking!" title="Smarking!" /></a>','icon','{mijosef icon}'),
(31,'Netvouz.com','<a rel="nofollow" onclick="window.open(''http://www.netvouz.com/action/submitBookmark?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*&popup=no''); return false;" href="http://www.netvouz.com/" title="Smarking!" target="_blank"><img src="*mijosef*imageDirectory*/netvouz.png" alt="Netvouz!" title="Netvouz!" /></a>','icon','{mijosef icon}'),
(32,'Mister-Wong.com','<a rel="nofollow" onclick="window.open(''http://www.mister-wong.com/index.php?action=addurl&bm_url=*mijosef*url_encoded*&bm_description=*mijosef*title_encoded*''); return false;" href="http://www.mister-wong.com/" title="Mister-Wong!" target="_blank"><img src="*mijosef*imageDirectory*/mister-wong.png" alt="Mister-Wong!" title="Mister-Wong!" /></a>','icon','{mijosef icon}'),
(33,'RawSugar.com','<a rel="nofollow" onclick="window.open(''http://www.rawsugar.com/tagger/?turl=*mijosef*url_encoded*&tttl=*mijosef*title_encoded*&editorInitialized=1''); return false;" href="http://www.rawsugar.com/" title="RawSugar!" target="_blank"><img src="*mijosef*imageDirectory*/rawsugar.png" alt="RawSugar!" title="RawSugar!" /></a>','icon','{mijosef icon}'),
(34,'Ma.gnolia.com','<a rel="nofollow" onclick="window.open(''http://ma.gnolia.com/bookmarklet/add?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://ma.gnolia.com/" title="Ma.gnolia!" target="_blank"><img src="*mijosef*imageDirectory*/magnolia.png" alt="Ma.gnolia!" title="Ma.gnolia!" /></a>','icon','{mijosef icon}'),
(35,'Squidoo.com','<a rel="nofollow" onclick="window.open(''http://www.squidoo.com/lensmaster/bookmark?*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://www.squidoo.com/" title="Squidoo!" target="_blank"><img src="*mijosef*imageDirectory*/squidoo.png" alt="Squidoo!" title="Squidoo!" /></a>','icon','{mijosef icon}'),
(36,'FeedMeLinks.com','<a rel="nofollow" onclick="window.open(''http://feedmelinks.com/categorize?from=toolbar&op=submit&url=*mijosef*url_encoded*&name=*mijosef*title_encoded*''); return false;" href="http://feedmelinks.com/" title="FeedMeLinks!" target="_blank"><img src="*mijosef*imageDirectory*/feedmelinks.png" alt="FeedMeLinks!" title="FeedMeLinks!" /></a>','icon','{mijosef icon}'),
(37,'BlinkBits.com','<a rel="nofollow" onclick="window.open(''http://www.blinkbits.com/bookmarklets/save.php?v=1&source_url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://www.blinkbits.com/" title="BlinkBits!" target="_blank"><img src="*mijosef*imageDirectory*/blinkbits.png" alt="BlinkBits!" title="BlinkBits!" /></a>','icon','{mijosef icon}'),
(38,'TailRank.com','<a rel="nofollow" onclick="window.open(''http://tailrank.com/share/?link_href=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://tailrank.com/" title="Tailrank!" target="_blank"><img src="*mijosef*imageDirectory*/tailrank.png" alt="Tailrank!" title="Tailrank!" /></a>','icon','{mijosef icon}'),
(39,'linkaGoGo.com','<a rel="nofollow" onclick="window.open(''http://www.linkagogo.com/go/AddNoPopup?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://www.linkagogo.com/" title="linkaGoGo!" target="_blank"><img src="*mijosef*imageDirectory*/linkagogo.png" alt="linkaGoGo!" title="linkaGoGo!" /></a>','icon','{mijosef icon}'),
(40,'Cannotea.org','<a rel="nofollow" onclick="window.open(''http://www.connotea.org/addpopup?continue=confirm&uri=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://www.cannotea.org/" title="Cannotea!" target="_blank"><img src="*mijosef*imageDirectory*/cannotea.png" alt="Cannotea!" title="Cannotea!" /></a>','icon','{mijosef icon}'),
(41,'Diigo.com','<a rel="nofollow" onclick="window.open(''http://www.diigo.com/post?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://www.diigo.com/" title="Diigo!" target="_blank"><img src="*mijosef*imageDirectory*/diigo.png" alt="Diigo!" title="Diigo!" /></a>','icon','{mijosef icon}'),
(42,'Faves.com','<a rel="nofollow" onclick="window.open(''http://faves.com/Authoring.aspx?u=*mijosef*url_encoded*&t=*mijosef*title_encoded*''); return false;" href="http://faves.com/" title="Faves!" target="_blank"><img src="*mijosef*imageDirectory*/faves.png" alt="Faves!" title="Faves!" /></a>','icon','{mijosef icon}'),
(43,'Ask.com','<a rel="nofollow" onclick="window.open(''http://myjeeves.ask.com/mysearch/BookmarkIt?v=1.2&t=webpages&url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://faves.com/" title="Ask!" target="_blank"><img src="*mijosef*imageDirectory*/ask.png" alt="Ask!" title="Ask!" /></a>','icon','{mijosef icon}'),
(44,'DZone.com','<a rel="nofollow" onclick="window.open(''http://www.dzone.com/links/add.html?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://www.dzone.com/" title="DZone!" target="_blank"><img src="*mijosef*imageDirectory*/dzone.png" alt="DZone!" title="DZone!" /></a>','icon','{mijosef icon}'),
(45,'DZone.com - Tall','<script type="text/javascript">var dzone_url = ''*mijosef*url*'';</script><script type="text/javascript">var dzone_title = ''*mijosef*title*'';</script><script type="text/javascript">var dzone_blurb=''*mijosef*description*'';</script><script type="text/javascript">var dzone_style = ''1'';</script><script language="javascript" src="http://widgets.dzone.com/widgets/zoneit.js"></script>','badge','{mijosef DZone1}'),
(46,'DZone.com - Wide','<script type="text/javascript">var dzone_url = ''*mijosef*url*'';</script><script type="text/javascript">var dzone_title = ''*mijosef*title*'';</script><script type="text/javascript">var dzone_blurb=''*mijosef*description*'';</script><script type="text/javascript">var dzone_style = ''2'';</script><script language="javascript" src="http://widgets.dzone.com/widgets/zoneit.js"></script>','badge','{mijosef DZone2}'),
(47,'Swik.net','<a rel="nofollow" onclick="window.open(''http://stories.swik.net/?submitUrl&url=*mijosef*url_encoded*''); return false;" href="http://stories.swik.net/" title="Swik!" target="_blank"><img src="*mijosef*imageDirectory*/swik.png" alt="Swik!" title="Swik!" /></a>','icon','{mijosef icon}'),
(48,'Shoutwire.com','<a rel="nofollow" onclick="window.open(''http://www.shoutwire.com/?p=submit&link=*mijosef*url_encoded*''); return false;" href="http://wwww.shoutwire.net/" title="ShoutWire!" target="_blank"><img src="*mijosef*imageDirectory*/shoutwire.png" alt="ShoutWire!" title="ShoutWire!" /></a>','icon','{mijosef icon}'),
(49,'MyLinkVault.com','<a rel="nofollow" onclick="window.open(''http://www.mylinkvault.com/link-quick.php?u=*mijosef*url_decoded*&n=*mijosef*title_encoded*''); return false;" href="http://wwww.mylinkvault.net/" title="MyLinkVault!" target="_blank"><img src="*mijosef*imageDirectory*/mylinkvault.png" alt="MyLinkVault!" title="MyLinkVault!" /></a>','icon','{mijosef icon}'),
(50,'Maple.nu','<a rel="nofollow" onclick="window.open(''http://www.maple.nu/submit.php?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*''); return false;" href="http://maple.nu/" title="Maple!" target="_blank"><img src="*mijosef*imageDirectory*/maple.png" alt="Maple!" title="Maple!" /></a>','icon','{mijosef icon}'),
(51,'BlogRolling.com','<a rel="nofollow" onclick="window.open(''http://www.blogrolling.com/add_links_pop.phtml?u=*mijosef*url_encoded*&t=*mijosef*title_encoded*''); return false;" href="http://www.blogrolling.com/" title="BlogRolling!" target="_blank"><img src="*mijosef*imageDirectory*/blogrolling.png" alt="BlogRolling!" title="BlogRolling!" /></a>','icon','{mijosef icon}'),
(52,'AddThis.com - Drop Down','<!-- AddThis Bookmark Button BEGIN --><script type="text/javascript">addthis_url=''*mijosef*url*''; addthis_title=''*mijosef*title*''; addthis_pub=''*mijosef*addThisPubId*'';</script><script type="text/javascript" src="http://s7.addthis.com/js/addthis_widget.php?v=12" ></script><!-- AddThis Bookmark Button END -->','iconset','{mijosef AddThis1}'),
(53,'AddThis.com - Style 1','<!-- AddThis Bookmark Button BEGIN --><a href="http://www.addthis.com/bookmark.php" onclick="addthis_url=''*mijosef*url*''; addthis_title=''*mijosef*title*''; return addthis_click(this);" target="_blank"><img src="http://s9.addthis.com/button0-bm.gif" width="83" height="16" border="0" alt="AddThis Social Bookmark Button" /></a> <script type="text/javascript">var addthis_pub=''*mijosef*addThisPubId*'';</script><script type="text/javascript" src="http://s9.addthis.com/js/widget.php?v=10"></script>  <!-- AddThis Bookmark Button END -->','iconset','{mijosef AddThis2}'),
(54,'AddThis.com - Style 2','<!-- AddThis Bookmark Button BEGIN --><a href="http://www.addthis.com/bookmark.php" onclick="addthis_url=''*mijosef*url*''; addthis_title=''*mijosef*title*''; return addthis_click(this);" target="_blank"><img src="http://s9.addthis.com/button1-bm.gif" width="125" height="16" border="0" alt="AddThis Social Bookmark Button" /></a> <script type="text/javascript">var addthis_pub=''*mijosef*addThisPubId*'';</script><script type="text/javascript" src="http://s9.addthis.com/js/widget.php?v=10"></script>  <!-- AddThis Bookmark Button END -->','iconset','{mijosef AddThis3}'),
(55,'AddThis.com - Style 3','<!-- AddThis Bookmark Button BEGIN --><a href="http://www.addthis.com/bookmark.php" onclick="addthis_url=''*mijosef*url*''; addthis_title=''*mijosef*title*''; return addthis_click(this);" target="_blank"><img src="http://s9.addthis.com/button1-share.gif" width="125" height="16" border="0" alt="AddThis Social Bookmark Button" /></a> <script type="text/javascript">var addthis_pub=''*mijosef*addThisPubId*'';</script><script type="text/javascript" src="http://s9.addthis.com/js/widget.php?v=10"></script>  <!-- AddThis Bookmark Button END -->','iconset','{mijosef AddThis4}'),
(56,'AddThis.com - Style 4','<!-- AddThis Bookmark Button BEGIN --><a href="http://www.addthis.com/bookmark.php" onclick="addthis_url=''*mijosef*url*''; addthis_title=''*mijosef*title*''; return addthis_click(this);" target="_blank"><img src="http://s9.addthis.com/button1-addthis.gif" width="125" height="16" border="0" alt="AddThis Social Bookmark Button" /></a> <script type="text/javascript">var addthis_pub=''*mijosef*addThisPubId*'';</script><script type="text/javascript" src="http://s9.addthis.com/js/widget.php?v=10"></script><!-- AddThis Bookmark Button END -->','iconset','{mijosef AddThis5}'),
(57,'AddThis.com - Style 5','<!-- AddThis Bookmark Button BEGIN --><a href="http://www.addthis.com/bookmark.php" onclick="addthis_url=''*mijosef*url*''; addthis_title=''*mijosef*title*''; return addthis_click(this);" target="_blank"><img src="http://s9.addthis.com/button2-bm.png" width="160" height="24" border="0" alt="AddThis Social Bookmark Button" /></a> <script type="text/javascript">var addthis_pub=''*mijosef*addThisPubId*'';</script><script type="text/javascript" src="http://s9.addthis.com/js/widget.php?v=10"></script><!-- AddThis Bookmark Button END -->','iconset','{mijosef AddThis6}'),
(58,'GodSurfer.com','<a rel="nofollow" href="http://www.godsurfer.com/" title="GodSurfer!" target="_blank"\r\nonclick="window.open(''http://www.godsurfer.com/addStory.php?url=*mijosef*url*''); return false;">\r\n<img height="18px" width="18px" src="*mijosef*imageDirectory*/godsurfer.png" alt="GodSurfer!" title="GodSurfer!" /></a>','icon','{mijosef icon}'),
(59,'GodSurfer.com - Large','<script type="text/javascript">GODSurfer_url = "*mijosef*url*";</script><script src="http://www.godsurfer.com/tools/GODSurfer.js" type="text/javascript"></script>','badge','{mijosef GodSurfer1}'),
(60,'GodSurfer.com - Compact','<script type="text/javascript">GODSurfer_url = "*mijosef*url*"; GODSurfer_skin = "compact";</script><script src="http://www.godsurfer.com/tools/GODSurfer.js" type="text/javascript"></script>','badge','{mijosef GodSurfer2}'),
(61,'Tell-a-Friend','<script src="http://cdn.socialtwist.com/*mijosef*TellAFriendId*/script.js"></script><img style="border:0;padding:0;margin:0;" src="http://images.socialtwist.com/*mijosef*TellAFriendId*/button.png" onmouseout="hideHoverMap(this)" onmouseover="showHoverMap(this, ''*mijosef*TellAFriendId*'', window.location, document.title)" onclick="cw(this, {id: ''*mijosef*TellAFriendId*'',link: window.location, title: document.title })"/>','iconset','{mijosef TellAFriend}'),
(62,'Google Buzz','<a rel="nofollow" onclick="window.open(''http://www.google.com/reader/link?url=*mijosef*url_encoded*&title=*mijosef*title_encoded*&srcUrl=*mijosef*domain*&srcTitle=*mijosef*sitename*&snippet=*mijosef*description*''); return false;" href="http://www.google.com/" title="Buzz" target="_blank"><img src="*mijosef*imageDirectory*/googlebuzz.png" alt="Buzz" title="Buzz" /></a>','icon','{mijosef icon}'),
(63,'Twitter','<script type="text/javascript">tweetmeme_url=''*mijosef*url_encoded*'';tweetme_window=''new'';tweetme_bgcolor=''*mijosef*bgcolor*'';tweetmeme_source=''*mijosef*twitterAccount*'';tweetmeme_service=''bit.ly'';tweetme_title=''*mijosef*title*'';tweetmeme_hashtags='''';</script><script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>','badge','{mijosef Twitter}'),
(64,'Google Buzz','<a title="Post on Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="normal-count" ></a><script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>','badge','{mijosef GoogleBuzz}'),
(65,'Yahoo! Buzz','<script type="text/javascript">yahooBuzzArticleId = window.location.href;</script><script type="text/javascript" src="http://d.yimg.com/ds/badge2.js" badgetype="square"></script>','badge','{mijosef YahooBuzz}'),
(66,'MySpace','<a href="javascript:void(window.open(\'http://www.myspace.com/Modules/PostTo/Pages/?u=\'+encodeURIComponent(document.location.toString()),\'ptm\',\'height=450,width=440\').focus())"><img src="http://cms.myspacecdn.com/cms/ShareOnMySpace/LargeSquare.png" border="0" alt="Share on MySpace" /></a>','badge','{mijosef MySpace}'),
(67,'Stumbleupon','<script src="http://www.stumbleupon.com/hostedbadge.php?s=5"></script>','badge','{mijosef Stumbleupon}'),
(68,'Google Buzz (with counter)','<a title="Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="small-count"></a><script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>','badge','{mijosef GoogleBuzz2}'),
(69,'Twitter (with counter)','<a href="http://twitter.com/share?url=*mijosef*url_encoded*" class="twitter-share-button" data-text="*mijosef*title*:" data-count="horizontal" data-via="*mijosef*twitterAccount*">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>','badge','{mijosef Twitter2}'),
(70,'Facebook (with counter)','<iframe src="http://www.facebook.com/plugins/like.php?href=*mijosef*url_encoded*&amp;layout=button_count&amp;width=90&amp;height=20&amp;show_faces=false&amp;action=like&amp;colorscheme=light" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:20px;" allowTransparency="true"></iframe>','badge','{mijosef Facebook2}'),
(71,'Facebook Share','<script>var fbShare = {url: "*mijosef*url_encoded*"}</script><script src="http://widgets.fbshare.me/files/fbshare.js"></script>','badge','{mijosef Facebook}');