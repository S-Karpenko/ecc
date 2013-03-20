<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

// Import Libraries
jimport('joomla.application.helper');
jimport('joomla.filesystem.file');
jimport('joomla.installer.installer');

class com_MijosefInstallerScript {

    private $_current_version = null;
    private $_is_new_installation = true;

    public function preflight($type, $parent) {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT params FROM #__extensions WHERE element = "com_mijosef" AND type = "component"');
        $config = $db->loadResult();

        if (!empty($config)) {
            $this->_is_new_installation = false;

            $mijosef_xml = JPATH_ADMINISTRATOR.'/components/com_mijosef/a_mijosef.xml';

            if (JFile::exists($mijosef_xml)) {
                $xml = JFactory::getXML($mijosef_xml);
                $this->_current_version = (string)$xml->version;
            }
        }
    }
	
	public function postflight($type, $parent) {
        $db =& JFactory::getDBO();
        $src = $parent->getParent()->getPath('source');

        $status = new JObject();
        $status->adapters = array();
        $status->extensions = array();
        $status->modules = array();
        $status->plugins = array();

        /***********************************************************************************************
         * ---------------------------------------------------------------------------------------------
         * ADAPTER INSTALLATION SECTION
         * ---------------------------------------------------------------------------------------------
         ***********************************************************************************************/
        $adp_src = $src.'/admin/adapters/mijosef_ext.php';
        $adp_dst = JPATH_LIBRARIES.'/joomla/installer/adapters/mijosef_ext.php';
        if (is_writable(dirname($adp_dst))) {
            JFile::copy($adp_src, $adp_dst);
            $status->adapters[] = 1;
        }

        /***********************************************************************************************
         * ---------------------------------------------------------------------------------------------
         * EXTENSION INSTALLATION SECTION
         * ---------------------------------------------------------------------------------------------
         ***********************************************************************************************/
		if ($this->_is_new_installation == true) {
			$extensions = array('com_mijosef', 'com_banners', 'com_contact', 'com_content', 'com_mailto', 'com_newsfeeds', 'com_search', 'com_users', 'com_weblinks', 'com_wrapper');
			foreach ($extensions as $extension) {
				$file = $src.'/admin/extensions/'.$extension.'.xml';

				if (!file_exists($file)) {
					continue;
				}

				$manifest = JFactory::getXML($file);

				if (!$manifest) {
					continue;
				}

				$ename = (string)$manifest->name;

				$prms = array();
				$prms['router'] = '3';

				$element = $manifest->install->defaultParams;
				if ($element && count($element->children())) {
					$defaultParams = $element->children();
					if (count($defaultParams) != 0) {
						foreach ($defaultParams as $param) {
							$name = (string)$param->attributes()->name;
							$value = (string)$param->attributes()->value;

							$prms[$name] = $value;
						}
					}
				}

				if (!isset($prms['skip_menu'])) {
					$prms['skip_menu'] = '0';
				}

				if (!isset($prms['prefix'])) {
					$prms['prefix'] = '';
				}

				$reg = new JRegistry($prms);
				$params = $reg->toString();

				$db->setQuery("INSERT IGNORE INTO #__mijosef_extensions (name, extension, params) VALUES ('{$ename}', '{$extension}', '{$params}')");
				$db->query();

				$status->extensions[] = array('name' => $ename);
			}
		}
		
        /***********************************************************************************************
         * ---------------------------------------------------------------------------------------------
         * MODULE INSTALLATION SECTION
         * ---------------------------------------------------------------------------------------------
         ***********************************************************************************************/
        $installer = new JInstaller();
        $installer->install($src.'/modules/mod_mijosef_quickicons');

        $db->setQuery("UPDATE #__modules SET position = 'icon', ordering = '0', published = '1' WHERE module = 'mod_mijosef_quickicons'");
        $db->query();

        $db->setQuery("SELECT `id` FROM `#__modules` WHERE `module` = 'mod_mijosef_quickicons'");
        $mod_id = $db->loadResult();

        $db->setQuery("REPLACE INTO `#__modules_menu` (`moduleid`, `menuid`) VALUES ({$mod_id}, 0)");
        $db->query();

        $status->modules[] = array('name' => 'MijoSEF - Quick Icons', 'client' => 'Administrator');


        /***********************************************************************************************
         * ---------------------------------------------------------------------------------------------
         * PLUGIN INSTALLATION SECTION
         * ---------------------------------------------------------------------------------------------
         ***********************************************************************************************/
        $installer = new JInstaller();
        $installer->install($src.'/plugins/mijosef');
        $db->setQuery("UPDATE #__extensions SET enabled = 1 WHERE type = 'plugin' AND element = 'mijosef' AND folder = 'system'");
        $db->query();

        $status->plugins[] = array('name' => 'MijoSEF', 'group' => 'System');

        $installer = new JInstaller();
        $installer->install($src.'/plugins/mijosefmetacontent');
        $db->setQuery("UPDATE #__extensions SET enabled = 1 WHERE type = 'plugin' AND element = 'mijosefmetacontent' AND folder = 'system'");
        $db->query();

        $status->plugins[] = array('name' => 'MijoSEF Metadata (Content)', 'group' => 'System');

        /***********************************************************************************************
         * ---------------------------------------------------------------------------------------------
         * SITEMAP INSTALLATION SECTION
         * ---------------------------------------------------------------------------------------------
         ***********************************************************************************************/
        $sitemap = JPATH_ROOT.'/sitemap.xml';
        if (!JFile::exists($sitemap)) {
            $content = '';
            JFile::write($sitemap, $content);
        }

        if ($this->_is_new_installation == true) {
            $this->_installConfig();
        }
        else {
            $this->_updateMijosef();
        }

        $this->_installationOutput($status);
	}

    protected function _installConfig() {
        $config = new stdClass();
        $config->mode = '1';
        $config->generate_sef = '1';
        $config->version_checker = '1';
        $config->purge_ext_urls = '1';
        $config->jquery_mode = '1';
        $config->pid = '';
        $config->cache_instant = '1';
        $config->cache_versions = '1';
        $config->cache_extensions = '0';
        $config->cache_urls = '0';
        $config->cache_urls_size = '10000';
        $config->cache_metadata = '0';
        $config->cache_sitemap = '0';
        $config->cache_urls_moved = '0';
        $config->cache_tags = '0';
        $config->cache_ilinks = '1';
        $config->seo_nofollow = '0';
        $config->page404 = 'custom';
        $config->url_lowercase = '1';
        $config->global_smart_itemid = '1';
        $config->ignore_multi_itemid = '0';
        $config->numeral_duplicated = '0';
        $config->record_duplicated = '1';
        $config->url_suffix = '';
        $config->replacement_character = '-';
        $config->parent_menus = '0';
        $config->menu_url_part = 'title';
        $config->title_alias = 'title';
        $config->append_itemid = '0';
        $config->remove_trailing_slash = '1';
        $config->tolerant_to_trailing_slash = '1';
        $config->url_strip_chars = '^$%@#()+*!?.~:;|[]{},&¦';
        $config->source_tracker = '0';
        $config->insert_active_itemid = '0';
        $config->remove_sid = '0';
        $config->set_query_string = '1';
        $config->base_href = '3';
        $config->append_non_sef = '1';
        $config->prevent_dup_error = '1';
        $config->show_db_errors = '0';
        $config->check_url_by_id = '1';
        $config->non_sef_vars = 'format=feed, type=rss, type=atom';
        $config->disable_sef_vars = 'tmpl, format=raw, format=json, no_html=1, format=xml, aklazy, nonce';
        $config->skip_menu_vars = '';
        $config->db_404_errors = '1';
        $config->log_404_errors = '0';
        $config->sent_headers_error = '0';
        $config->multilang = '0';
        $config->joomfish_main_lang = '0';
        $config->joomfish_main_lang_del = '0';
        $config->joomfish_lang_code = '1';
        $config->joomfish_trans_url = '1';
        $config->joomfish_cookie = '1';
        $config->joomfish_browser = '1';
        $config->utf8_url = '0';
        $config->char_replacements = 'Á|A, Â|A, Å|A, Ă|A, Ä|A, À|A, Ã|A, Ć|C, Ç|C, Č|C, Ď|D, É|E, È|E, Ë|E, Ě|E, Ê|E, Ì|I, Í|I, Î|I, Ï|I, Ĺ|L, Ń|N, Ň|N, Ñ|N, Ò|O, Ó|O, Ô|O, Õ|O, Ö|O, Ő|O, Ŕ|R, Ř|R, Š|S, Ś|O, Ť|T, Ů|U, Ú|U, Ű|U, Ü|U, Ý|Y, Ž|Z, Ź|Z, á|a, â|a, å|a, ä|a, à|a, ã|a, ć|c, ç|c, č|c, ď|d, đ|d, é|e, ę|e, ë|e, ě|e, è|e, ê|e, ì|i, í|i, î|i, ï|i, ĺ|l, ń|n, ň|n, ñ|n, ò|o, ó|o, ô|o, ő|o, ö|o, õ|o, š|s, ś|s, ř|r, ŕ|r, ť|t, ů|u, ú|u, ű|u, ü|u, ý|y, ž|z, ź|z, ˙|-, ß|ss, Ą|A, µ|u, Ą|A, µ|u, ą|a, Ą|A, ę|e, Ę|E, ś|s, Ś|S, ż|z, Ż|Z, ź|z, Ź|Z, ć|c, Ć|C, ł|l, Ł|L, ó|o, Ó|O, ń|n, Ń|N, Б|B, б|b, В|V, в|v, Г|G, г|g, Д|D, д|d, Ж|Zh, ж|zh, З|Z, з|z, И|I, и|i, Й|Y, й|y, К|K, к|k, Л|L, л|l, м|m, Н|N, н|n, П|P, п|p, т|t, У|U, у|u, Ф|F, ф|f, Х|Ch, х|ch, Ц|Ts, ц|ts, Ч|Ch, ч|ch, Ш|Sh, ш|sh, Щ|Sch, щ|sch, Ы|I, ы|i, Э|E, э|e, Ю|U, ю|iu, Я|Ya, я|ya, Ş|S, İ|I, Ğ|G, ş|s, ğ|g, ı|i, $|S, ¥|Y, £|L, ù|u, °|o, º|o, ª|a';
        $config->redirect_to_www = '0';
        $config->redirect_to_sef = '1';
        $config->redirect_to_sef_gen = '0';
        $config->jsef_to_mijosef = '1';
        $config->force_ssl = '[]';
        $config->url_append_limit = '0';
        $config->delete_other_sef = '1';
        $config->meta_core = '1';
        $config->meta_title = '1';
        $config->meta_title_tag = '1';
        $config->meta_desc = '1';
        $config->meta_key = '1';
        $config->meta_generator = '';
        $config->meta_generator_rem = '1';
        $config->meta_abstract = '';
        $config->meta_revisit = '';
        $config->meta_direction = '';
        $config->meta_googlekey = '';
        $config->meta_livekey = '';
        $config->meta_yahookey = '';
        $config->meta_alexa = '';
        $config->meta_name_1 = '';
        $config->meta_name_2 = '';
        $config->meta_name_3 = '';
        $config->meta_con_1 = '';
        $config->meta_con_2 = '';
        $config->meta_con_3 = '';
        $config->meta_t_seperator = '-';
        $config->meta_t_sitename = '';
        $config->meta_t_usesitename = '1';
        $config->meta_t_prefix = '';
        $config->meta_t_suffix = '';
        $config->meta_key_blacklist = 'a, able, about, above, abroad, according, accordingly, across, actually, adj, after, afterwards, again, against, ago, ahead, ain\'t, all, allow, allows, almost, alone, along, alongside, already, also, although, always, am, amid, amidst, among, amongst, an, and, another, any, anybody, anyhow, anyone, anything, anyway, anyways, anywhere, apart, appear, appreciate, appropriate, are, aren\'t, around, as, a\'s, aside, ask, asking, associated, at, available, away, awfully, b, back, backward, backwards, be, became, because, become, becomes, becoming, been, before, beforehand, begin, behind, being, believe, below, beside, besides, best, better, between, beyond, both, brief, but, by, c, came, can, cannot, cant, can\'t, caption, cause, causes, certain, certainly, changes, clearly, c\'mon, co, co., com, come, comes, concerning, consequently, consider, considering, contain, containing, contains, corresponding, could, couldn\'t, course, c\'s, currently, d, dare, daren\'t, definitely, described, despite, did, didn\'t, different, directly, do, does, doesn\'t, doing, done, don\'t, down, downwards, during, e, each, edu, eg, eight, eighty, either, else, elsewhere, end, ending, enough, entirely, especially, et, etc, even, ever, evermore, every, everybody, everyone, everything, everywhere, ex, exactly, example, except, f, fairly, far, farther, few, fewer, fifth, first, five, followed, following, follows, for, forever, former, formerly, forth, forward, found, four, from, further, furthermore, g, get, gets, getting, given, gives, go, goes, going, gone, got, gotten, greetings, h, had, hadn\'t, half, happens, hardly, has, hasn\'t, have, haven\'t, having, he, he\'d, he\'ll, hello, help, , hence, her, here, hereafter, hereby, herein, here\'s, hereupon, hers, herself, he\'s, hi, him, himself, his, hither, hopefully, how, howbeit, however, hundred, i, i\'d, ie, if, ignored, i\'ll, i\'m, immediate, in, inasmuch, inc, inc., indeed, indicate, indicated, indicates, inner, inside, insofar, instead, into, inward, is, isn\'t, it, it\'d, it\'ll, its, it\'s, itself, i\'ve, j, just, k, keep, keeps, kept, know, known, knows, l, last, lately, later, latter, latterly, least, less, lest, let, let\'s, like, liked, likely, likewise, little, look, looking, looks, low, lower, ltd, m, made, mainly, make, makes, many, may, maybe, mayn\'t, me, mean, meantime, meanwhile, merely, might, mightn\'t, mine, minus, miss, more, moreover, most, mostly, mr, mrs, much, must, mustn\'t, my, myself, n, name, namely, nd, near, nearly, necessary, need, needn\'t, needs, neither, never, neverf, neverless, nevertheless, new, next, nine, ninety, no, nobody, non, none, nonetheless, noone, no-one, nor, normally, not, nothing, notwithstanding, novel, now, nowhere, o, obviously, of, off, often, oh, ok, okay, old, on, once, one, ones, one\'s, only, onto, opposite, or, other, others, otherwise, ought, oughtn\'t, our, ours, ourselves, out, outside, over, overall, own, p, particular, particularly, past, per, perhaps, placed, please, plus, possible, presumably, probably, provided, provides, q, que, quite, qv, r, rather, rd, re, really, reasonably, recent, recently, regarding, regardless, regards, relatively, respectively, right, round, s, said, same, saw, say, saying, says, second, secondly, , see, seeing, seem, seemed, seeming, seems, seen, self, selves, sensible, sent, serious, seriously, seven, several, shall, shan\'t, she, she\'d, she\'ll, she\'s, should, shouldn\'t, since, six, so, some, somebody, someday, somehow, someone, something, sometime, sometimes, somewhat, somewhere, soon, sorry, specified, specify, specifying, still, sub, such, sup, sure, t, take, taken, taking, tell, tends, th, than, thank, thanks, thanx, that, that\'ll, thats, that\'s, that\'ve, the, their, theirs, them, themselves, then, thence, there, thereafter, thereby, there\'d, therefore, therein, there\'ll, there\'re, theres, there\'s, thereupon, there\'ve, these, they, they\'d, they\'ll, they\'re, they\'ve, thing, things, think, third, thirty, this, thorough, thoroughly, those, though, three, through, throughout, thru, thus, till, to, together, too, took, toward, towards, tried, tries, truly, try, trying, t\'s, twice, two, u, un, under, underneath, undoing, unfortunately, unless, unlike, unlikely, until, unto, up, upon, upwards, us, use, used, useful, uses, using, usually, v, value, various, versus, very, via, viz, vs, w, want, wants, was, wasn\'t, way, we, we\'d, welcome, well, we\'ll, went, were, we\'re, weren\'t, we\'ve, what, whatever, what\'ll, what\'s, what\'ve, when, whence, whenever, where, whereafter, whereas, whereby, wherein, where\'s, whereupon, wherever, whether, which, whichever, while, whilst, whither, who, who\'d, whoever, whole, who\'ll, whom, whomever, who\'s, whose, why, will, willing, wish, with, within, without, wonder, won\'t, would, wouldn\'t, x, y, yes, yet, you, you\'d, you\'ll, your, you\'re, yours, yourself, yourselves, you\'ve, z, zero';
        $config->meta_key_whitelist = '';
        $config->sm_file = 'sitemap';
        $config->sm_xml_date = '1';
        $config->sm_xml_freq = '1';
        $config->sm_xml_prior = '1';
        $config->sm_dot_tree = '1';
        $config->sm_ping_type = 'link';
        $config->sm_ping = '1';
        $config->sm_yahoo_appid = '';
        $config->sm_ping_services = 'http://blogsearch.google.com/ping/RPC2, http://rpc.pingomatic.com/';
        $config->sm_freq = 'weekly';
        $config->sm_priority = '0.5';
        $config->sm_auto_mode = '1';
        $config->sm_auto_components = array('com_content');
        $config->sm_auto_enable_cats = '0';
        $config->sm_auto_filter_s = '.pdf';
        $config->sm_auto_filter_r = 'format=pdf, format=feed, type=rss';
        $config->sm_auto_cron_mode = '0';
        $config->sm_auto_cron_freq = '24';
        $config->sm_auto_cron_last = '1286615325';
        $config->sm_auto_xml = '1';
        $config->sm_auto_ping_c = '0';
        $config->sm_auto_ping_s = '0';
        $config->tags_mode = '1';
        $config->tags_area = '1';
        $config->tags_components = array('com_content');
        $config->tags_enable_cats = '0';
        $config->tags_in_cats = '0';
        $config->tags_in_page = '15';
        $config->tags_order = 'ordering';
        $config->tags_position = '2';
        $config->tags_limit = '20';
        $config->tags_show_tag_desc = '0';
        $config->tags_show_prefix = '1';
        $config->tags_show_item_desc = '1';
        $config->tags_exp_item_desc = '0';
        $config->tags_published = '1';
        $config->tags_auto_mode = '0';
        $config->tags_auto_components = array('com_content');
        $config->tags_auto_length = '4';
        $config->tags_auto_filter_s = '.pdf';
        $config->tags_auto_filter_r = 'format=pdf, format=feed, type=rss';
        $config->tags_auto_blacklist = 'a, able, about, above, abroad, according, accordingly, across, actually, adj, after, afterwards, again, against, ago, ahead, ain\'t, all, allow, allows, almost, alone, along, alongside, already, also, although, always, am, amid, amidst, among, amongst, an, and, another, any, anybody, anyhow, anyone, anything, anyway, anyways, anywhere, apart, appear, appreciate, appropriate, are, aren\'t, around, as, a\'s, aside, ask, asking, associated, at, available, away, awfully, b, back, backward, backwards, be, became, because, become, becomes, becoming, been, before, beforehand, begin, behind, being, believe, below, beside, besides, best, better, between, beyond, both, brief, but, by, c, came, can, cannot, cant, can\'t, caption, cause, causes, certain, certainly, changes, clearly, c\'mon, co, co., com, come, comes, concerning, consequently, consider, considering, contain, containing, contains, corresponding, could, couldn\'t, course, c\'s, currently, d, dare, daren\'t, definitely, described, despite, did, didn\'t, different, directly, do, does, doesn\'t, doing, done, don\'t, down, downwards, during, e, each, edu, eg, eight, eighty, either, else, elsewhere, end, ending, enough, entirely, especially, et, etc, even, ever, evermore, every, everybody, everyone, everything, everywhere, ex, exactly, example, except, f, fairly, far, farther, few, fewer, fifth, first, five, followed, following, follows, for, forever, former, formerly, forth, forward, found, four, from, further, furthermore, g, get, gets, getting, given, gives, go, goes, going, gone, got, gotten, greetings, h, had, hadn\'t, half, happens, hardly, has, hasn\'t, have, haven\'t, having, he, he\'d, he\'ll, hello, help, , hence, her, here, hereafter, hereby, herein, here\'s, hereupon, hers, herself, he\'s, hi, him, himself, his, hither, hopefully, how, howbeit, however, hundred, i, i\'d, ie, if, ignored, i\'ll, i\'m, immediate, in, inasmuch, inc, inc., indeed, indicate, indicated, indicates, inner, inside, insofar, instead, into, inward, is, isn\'t, it, it\'d, it\'ll, its, it\'s, itself, i\'ve, j, just, k, keep, keeps, kept, know, known, knows, l, last, lately, later, latter, latterly, least, less, lest, let, let\'s, like, liked, likely, likewise, little, look, looking, looks, low, lower, ltd, m, made, mainly, make, makes, many, may, maybe, mayn\'t, me, mean, meantime, meanwhile, merely, might, mightn\'t, mine, minus, miss, more, moreover, most, mostly, mr, mrs, much, must, mustn\'t, my, myself, n, name, namely, nd, near, nearly, necessary, need, needn\'t, needs, neither, never, neverf, neverless, nevertheless, new, next, nine, ninety, no, nobody, non, none, nonetheless, noone, no-one, nor, normally, not, nothing, notwithstanding, novel, now, nowhere, o, obviously, of, off, often, oh, ok, okay, old, on, once, one, ones, one\'s, only, onto, opposite, or, other, others, otherwise, ought, oughtn\'t, our, ours, ourselves, out, outside, over, overall, own, p, particular, particularly, past, per, perhaps, placed, please, plus, possible, presumably, probably, provided, provides, q, que, quite, qv, r, rather, rd, re, really, reasonably, recent, recently, regarding, regardless, regards, relatively, respectively, right, round, s, said, same, saw, say, saying, says, second, secondly, , see, seeing, seem, seemed, seeming, seems, seen, self, selves, sensible, sent, serious, seriously, seven, several, shall, shan\'t, she, she\'d, she\'ll, she\'s, should, shouldn\'t, since, six, so, some, somebody, someday, somehow, someone, something, sometime, sometimes, somewhat, somewhere, soon, sorry, specified, specify, specifying, still, sub, such, sup, sure, t, take, taken, taking, tell, tends, th, than, thank, thanks, thanx, that, that\'ll, thats, that\'s, that\'ve, the, their, theirs, them, themselves, then, thence, there, thereafter, thereby, there\'d, therefore, therein, there\'ll, there\'re, theres, there\'s, thereupon, there\'ve, these, they, they\'d, they\'ll, they\'re, they\'ve, thing, things, think, third, thirty, this, thorough, thoroughly, those, though, three, through, throughout, thru, thus, till, to, together, too, took, toward, towards, tried, tries, truly, try, trying, t\'s, twice, two, u, un, under, underneath, undoing, unfortunately, unless, unlike, unlikely, until, unto, up, upon, upwards, us, use, used, useful, uses, using, usually, v, value, various, versus, very, via, viz, vs, w, want, wants, was, wasn\'t, way, we, we\'d, welcome, well, we\'ll, went, were, we\'re, weren\'t, we\'ve, what, whatever, what\'ll, what\'s, what\'ve, when, whence, whenever, where, whereafter, whereas, whereby, wherein, where\'s, whereupon, wherever, whether, which, whichever, while, whilst, whither, who, who\'d, whoever, whole, who\'ll, whom, whomever, who\'s, whose, why, will, willing, wish, with, within, without, wonder, won\'t, would, wouldn\'t, x, y, yes, yet, you, you\'d, you\'ll, your, you\'re, yours, yourself, yourselves, you\'ve, z, zero';
        $config->ilinks_mode = '1';
        $config->ilinks_area = '1';
        $config->ilinks_components = array('com_content');
        $config->ilinks_enable_cats = '0';
        $config->ilinks_in_cats = '0';
        $config->ilinks_case = '1';
        $config->ilinks_published = '1';
        $config->ilinks_nofollow = '0';
        $config->ilinks_blank = '0';
        $config->ilinks_limit = '10';
        $config->bookmarks_mode = '1';
        $config->bookmarks_area = '1';
        $config->bookmarks_components = array('com_content');
        $config->bookmarks_enable_cats = '0';
        $config->bookmarks_in_cats = '0';
        $config->bookmarks_twitter = '';
        $config->bookmarks_addthis = '';
        $config->bookmarks_taf = '';
        $config->bookmarks_icons_pos = '2';
        $config->bookmarks_icons_txt = 'Share:';
        $config->bookmarks_icons_line = '35';
        $config->bookmarks_published = '1';
        $config->bookmarks_type = 'icon';
        $config->ui_cpanel = '2';
        $config->ui_sef_language = '0';
        $config->ui_sef_published = '1';
        $config->ui_sef_used = '1';
        $config->ui_sef_locked = '1';
        $config->ui_sef_blocked = '0';
        $config->ui_sef_cached = '1';
        $config->ui_sef_date = '0';
        $config->ui_sef_hits = '1';
        $config->ui_sef_id = '0';
        $config->ui_moved_published = '1';
        $config->ui_moved_hits = '1';
        $config->ui_moved_clicked = '1';
        $config->ui_moved_cached = '1';
        $config->ui_moved_id = '1';
        $config->ui_metadata_keys = '1';
        $config->ui_metadata_published = '1';
        $config->ui_metadata_cached = '1';
        $config->ui_metadata_id = '0';
        $config->ui_sitemap_title = '1';
        $config->ui_sitemap_published = '1';
        $config->ui_sitemap_id = '1';
        $config->ui_sitemap_parent = '1';
        $config->ui_sitemap_order = '1';
        $config->ui_sitemap_date = '1';
        $config->ui_sitemap_frequency = '1';
        $config->ui_sitemap_priority = '1';
        $config->ui_sitemap_cached = '1';
        $config->ui_tags_published = '1';
        $config->ui_tags_ordering = '1';
        $config->ui_tags_cached = '1';
        $config->ui_tags_hits = '1';
        $config->ui_tags_id = '0';
        $config->ui_ilinks_published = '1';
        $config->ui_ilinks_nofollow = '1';
        $config->ui_ilinks_blank = '1';
        $config->ui_ilinks_limit = '1';
        $config->ui_ilinks_cached = '1';
        $config->ui_ilinks_id = '1';
        $config->ui_bookmarks_published = '1';
        $config->ui_bookmarks_id = '1';

        $reg = new JRegistry($config);
        $config = $reg->toString();

        $db = JFactory::getDBO();
        $db->setQuery('UPDATE #__extensions SET params = '.$db->Quote($config).' WHERE element = "com_mijosef" AND type = "component"');
        $db->query();
    }

    protected function _updateMijosef() {
        if (empty($this->_current_version)) {
            return;
        }

        if ($this->_current_version = '1.0.0') {
            $db = JFactory::getDBO();
            jimport('joomla.html.parameter');

            $db->setQuery('SELECT params FROM #__extensions WHERE element = "com_mijosef" AND type = "component"');
            $o_c = $db->loadResult();

            $o_p = new JParameter($o_c);

            $reg = new JRegistry($o_p->toArray());
            $config = $reg->toString();

            $db->setQuery('UPDATE #__extensions SET params = '.$db->Quote($config).' WHERE element = "com_mijosef" AND type = "component"');
            $db->query();

            $db->setQuery('SELECT id, params FROM #__mijosef_extensions');
            $o_exts = $db->loadObjectList();

            if (empty($o_exts)) {
                return;
            }

            foreach ($o_exts as $o_ext) {
                $ext_p = new JParameter($o_ext->params);

                $reg = new JRegistry($ext_p->toArray());
                $params = $reg->toString();

                $db = JFactory::getDBO();
                $db->setQuery('UPDATE #__mijosef_extensions SET params = '.$db->Quote($params).' WHERE id='.$o_ext->id);
                $db->query();
            }

            $db->setQuery('SELECT id, params FROM #__mijosef_urls');
            $o_urls = $db->loadObjectList();

            if (empty($o_urls)) {
                return;
            }

            foreach ($o_urls as $o_url) {
                $url_p = new JParameter($o_url->params);

                $reg = new JRegistry($url_p->toArray());
                $params = $reg->toString();

                $db = JFactory::getDBO();
                $db->setQuery('UPDATE #__mijosef_urls SET params = '.$db->Quote($params).' WHERE id='.$o_url->id);
                $db->query();
            }

            return;
        }
    }

    public function uninstall($parent) {
        $db = &JFactory::getDBO();
        $src = $parent->getParent()->getPath('source');

        $status = new JObject();
        $status->adapters = array();
        $status->extensions = array();
        $status->modules = array();
        $status->plugins = array();

        /***********************************************************************************************
         * ---------------------------------------------------------------------------------------------
         * DATABASE BACKUP SECTION
         * ---------------------------------------------------------------------------------------------
         ***********************************************************************************************/
        $db->setQuery("DROP TABLE IF EXISTS `#__mijosef_bookmarks_backup`");
        $db->query();
        $db->setQuery("RENAME TABLE `#__mijosef_bookmarks` TO `#__mijosef_bookmarks_backup`");
        $db->query();

        $db->setQuery("DROP TABLE IF EXISTS `#__mijosef_ilinks_backup`");
        $db->query();
        $db->setQuery("RENAME TABLE `#__mijosef_ilinks` TO `#__mijosef_ilinks_backup`");
        $db->query();

        $db->setQuery("DROP TABLE IF EXISTS `#__mijosef_metadata_backup`");
        $db->query();
        $db->setQuery("RENAME TABLE `#__mijosef_metadata` TO `#__mijosef_metadata_backup`");
        $db->query();

        $db->setQuery("DROP TABLE IF EXISTS `#__mijosef_sitemap_backup`");
        $db->query();
        $db->setQuery("RENAME TABLE `#__mijosef_sitemap` TO `#__mijosef_sitemap_backup`");
        $db->query();

        $db->setQuery("DROP TABLE IF EXISTS `#__mijosef_tags_backup`");
        $db->query();
        $db->setQuery("RENAME TABLE `#__mijosef_tags` TO `#__mijosef_tags_backup`");
        $db->query();

        $db->setQuery("DROP TABLE IF EXISTS `#__mijosef_tags_map_backup`");
        $db->query();
        $db->setQuery("RENAME TABLE `#__mijosef_tags_map` TO `#__mijosef_tags_map_backup`");
        $db->query();

        $db->setQuery("DROP TABLE IF EXISTS `#__mijosef_urls_backup`");
        $db->query();
        $db->setQuery("RENAME TABLE `#__mijosef_urls` TO `#__mijosef_urls_backup`");
        $db->query();

        $db->setQuery("DROP TABLE IF EXISTS `#__mijosef_urls_moved_backup`");
        $db->query();
        $db->setQuery("RENAME TABLE `#__mijosef_urls_moved` TO `#__mijosef_urls_moved_backup`");
        $db->query();

        $db->setQuery("DROP TABLE IF EXISTS `#__mijosef_extensions_backup`");
        $db->query();
        $db->setQuery("RENAME TABLE `#__mijosef_extensions` TO `#__mijosef_extensions_backup`");
        $db->query();

        /***********************************************************************************************
         * ---------------------------------------------------------------------------------------------
         * ADAPTER REMOVAL SECTION
         * ---------------------------------------------------------------------------------------------
         ***********************************************************************************************/
        $adapter = JPATH_LIBRARIES.'/joomla/installer/adapters/mijosef_ext.php';
        if (JFile::exists($adapter)) {
            JFile::delete($adapter);
            $status->adapters[] = 1;
        }

        /***********************************************************************************************
         * ---------------------------------------------------------------------------------------------
         * EXTENSION REMOVAL SECTION
         * ---------------------------------------------------------------------------------------------
         ***********************************************************************************************/
        $db =& JFactory::getDBO();
        $db->setQuery("SELECT name FROM #__mijosef_extensions_backup WHERE name != ''");
        $extensions = $db->loadColumn();

        if (!empty($extensions)) {
            foreach ($extensions as $extension) {
                $status->extensions[] = array('name' => $extension);
            }
        }

        /***********************************************************************************************
         * ---------------------------------------------------------------------------------------------
         * MODULE REMOVAL SECTION
         * ---------------------------------------------------------------------------------------------
         ***********************************************************************************************/
        $db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'module' AND element = 'mod_mijosef_quickicons' LIMIT 1");
        $id = $db->loadResult();
        if ($id) {
            $installer = new JInstaller();
            $installer->uninstall('module', $id);
            $status->modules[] = array('name' => 'MijoSEF - Quick Icons', 'client' => 'Administrator');
        }

        /***********************************************************************************************
         * ---------------------------------------------------------------------------------------------
         * PLUGIN REMOVAL SECTION
         * ---------------------------------------------------------------------------------------------
         ***********************************************************************************************/
        $db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'mijosef' LIMIT 1");
        $id = $db->loadResult();
        if ($id) {
            $installer = new JInstaller();
            $installer->uninstall('plugin', $id);
            $status->plugins[] = array('name' => 'MijoSEF', 'group' => 'System');
        }

        $db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'mijosefmetacontent' LIMIT 1");
        $id = $db->loadResult();
        if ($id) {
            $installer = new JInstaller();
            $installer->uninstall('plugin', $id);
            $status->plugins[] = array('name' => 'MijoSEF Metadata (Content)', 'group' => 'System');
        }

        $this->_uninstallationOutput($status);
	}

    private function _installationOutput($status) {
$rows = 0;
?>
<img src="components/com_mijosef/assets/images/logo.png" alt="Joomla! SEO Suite" style="width:80px; height:80px; float: left; padding-right:15px;" />

<h2>MijoSEF Installation</h2>
<h2><a href="index.php?option=com_mijosef">Go to MijoSEF</a></h2>
<table class="adminlist">
    <thead>
    <tr>
        <th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
        <th width="30%"><?php echo JText::_('Status'); ?></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="3"></td>
    </tr>
    </tfoot>
    <tbody>
    <tr>
        <th colspan="3"><?php echo JText::_('Core'); ?></th>
    </tr>
    <tr class="row0">
        <td class="key" colspan="2"><?php echo 'MijoSEF '.JText::_('Component'); ?></td>
        <td><strong><?php echo JText::_('Installed'); ?></strong></td>
    </tr>
        <?php
        if (count($status->adapters)) : ?>
        <tr class="row1">
            <td class="key" colspan="2"><?php echo 'MijoSEF Adapter'; ?></td>
            <td><strong><?php echo JText::_('Installed'); ?></strong></td>
        </tr>
            <?php
        endif;
        if (count($status->extensions)) : ?>
        <tr>
            <th colspan="3"><?php echo JText::_('MijoSEF Extension'); ?></th>
        </tr>
            <?php foreach ($status->extensions as $extension) : ?>
            <tr class="row<?php echo (++ $rows % 2); ?>">
                <td class="key" colspan="2"><?php echo $extension['name']; ?></td>
                <td><strong><?php echo JText::_('Installed'); ?></strong></td>
            </tr>
                <?php endforeach;
        endif;
        if (count($status->modules)) : ?>
        <tr>
            <th><?php echo JText::_('Module'); ?></th>
            <th colspan="2"><?php echo JText::_('Client'); ?></th>
        </tr>
            <?php foreach ($status->modules as $module) : ?>
            <tr class="row<?php echo (++ $rows % 2); ?>">
                <td class="key"><?php echo $module['name']; ?></td>
                <td class="key"><?php echo ucfirst($module['client']); ?></td>
                <td><strong><?php echo JText::_('Installed'); ?></strong></td>
            </tr>
                <?php endforeach;
        endif;
        if (count($status->plugins)) : ?>
        <tr>
            <th><?php echo JText::_('Plugin'); ?></th>
            <th colspan="2"><?php echo JText::_('Group'); ?></th>
        </tr>
            <?php foreach ($status->plugins as $plugin) : ?>
            <tr class="row<?php echo (++ $rows % 2); ?>">
                <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
                <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
                <td><strong><?php echo JText::_('Installed'); ?></strong></td>
            </tr>
                <?php endforeach;
        endif;
        ?>

    </tbody>
</table>
        <?php
    }

    private function _uninstallationOutput($status) {
$rows = 0;
?>

<h2>MijoSEF Removal</h2>
<table class="adminlist">
<thead>
<tr>
    <th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
    <th width="30%"><?php echo JText::_('Status'); ?></th>
</tr>
</thead>
<tfoot>
<tr>
    <td colspan="3"></td>
</tr>
</tfoot>
<tbody>
<tr>
    <th colspan="3"><?php echo JText::_('Core'); ?></th>
</tr>
<tr class="row0">
    <td class="key" colspan="2"><?php echo 'MijoSEF '.JText::_('Component'); ?></td>
    <td><strong><?php echo JText::_('Removed'); ?></strong></td>
</tr>
    <?php
    if (count($status->adapters)) : ?>
    <tr class="row1">
        <td class="key" colspan="2"><?php echo 'MijoSEF Adapter'; ?></td>
        <td><strong><?php echo JText::_('Removed'); ?></strong></td>
    </tr>
        <?php
    endif;
    if (count($status->extensions)) : ?>
    <tr>
        <th colspan="3"><?php echo JText::_('MijoSEF Extension'); ?></th>
    </tr>
        <?php foreach ($status->extensions as $extension) : ?>
        <tr class="row<?php echo (++ $rows % 2); ?>">
            <td class="key" colspan="2"><?php echo $extension['name']; ?></td>
            <td><strong><?php echo JText::_('Removed'); ?></strong></td>
        </tr>
            <?php endforeach;
    endif;
    if (count($status->modules)) : ?>
    <tr>
        <th><?php echo JText::_('Module'); ?></th>
        <th colspan="2"><?php echo JText::_('Client'); ?></th>
    </tr>
        <?php foreach ($status->modules as $module) : ?>
        <tr class="row<?php echo (++ $rows % 2); ?>">
            <td class="key"><?php echo $module['name']; ?></td>
            <td class="key"><?php echo ucfirst($module['client']); ?></td>
            <td><strong><?php echo JText::_('Removed'); ?></strong></td>
        </tr>
            <?php endforeach;
    endif;
    if (count($status->plugins)) : ?>
    <tr>
        <th><?php echo JText::_('Plugin'); ?></th>
        <th colspan="2"><?php echo JText::_('Group'); ?></th>
    </tr>
        <?php foreach ($status->plugins as $plugin) : ?>
        <tr class="row<?php echo (++ $rows % 2); ?>">
            <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
            <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
            <td><strong><?php echo JText::_('Removed'); ?></strong></td>
        </tr>
            <?php endforeach;
    endif;
    ?>
</tbody>
</table>
        <?php
    }
}