<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* The following line loads the MooTools JavaScript Library */
JHtml::_('behavior.framework', true);

/* The following line gets the application object for things like displaying the site name */
$app = JFactory::getApplication();
?>
<?php echo '<?'; ?>xml version="1.0" encoding="<?php echo $this->_charset ?>"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
	<head>
		<!-- The following JDOC Head tag loads all the header and meta information from your site config and content. -->
		<jdoc:include type="head" />

		<!-- The following five lines load the Blueprint CSS Framework (http://blueprintcss.org). If you don't want to use this framework, delete these lines. -->
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blueprint/screen.css" type="text/css" media="screen, projection" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blueprint/print.css" type="text/css" media="print" />
		<!--[if lt IE 8]><link rel="stylesheet" href="blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blueprint/plugins/joomla-nav/screen.css" type="text/css" media="screen" />

		<!-- The following line loads the template CSS file located in the template folder. -->
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />

		<!-- The following four lines load the Blueprint CSS Framework and the template CSS file for right-to-left languages. If you don't want to use these, delete these lines. -->
		<?php if($this->direction == 'rtl') : ?>
			<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blueprint/plugins/rtl/screen.css" type="text/css" />
			<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template_rtl.css" type="text/css" />
		<?php endif; ?>

		<!-- The following line loads the template JavaScript file located in the template folder. It's blank by default. -->
		<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/template.js"></script>
	</head>

    <body>
        <div class="header">
            <a href="index.php" title="Experimental Creative Center"><img src="images/logo.png" width="225" height="156" alt="Experimental Creative Center" /></a>
            <ul class="social">
                <li><a class="fb" href="#" title="Facebook">Facebook</a></li>
                <li><a class="vk" href="#" title="Vkontakte">Vkontakte</a></li>
                <li><a class="tw" href="#" title="Twitter">Twitter</a></li>
                <li><a class="yt" href="#" title="YouTube">Youtube</a></li>
                <li><a class="vm" href="#" title="Vimeo">Vimeo</a></li>
            </ul>
        </div>

    <!--navigation-->
        <div class="navbar">
            <?php if ($this->countModules('position-1')): ?>
                <jdoc:include type="modules" name="position-1" style="none" />
            <?php endif; ?>
        </div>
    <!--conteiner-->


        <div class="container">
            <div class="content">
			    <jdoc:include type="message" />
			    <jdoc:include type="component" />
            </div>

            <div class="sidebar">
                <div class="widjet calendar">
                    <?php if ($this->countModules('position-7')): ?>
                    <jdoc:include type="modules" name="position-7" style="none" />
                    <?php endif; ?>
                </div>
                <div class="widjet partners">
                    <?php if ($this->countModules('position-')): ?>
                    <jdoc:include type="modules" name="position-" style="none" />
                    <?php endif; ?>
                </div>
                <div class="widjet voting">
                    <?php if ($this->countModules('position-')): ?>
                    <jdoc:include type="modules" name="position-" style="none" />
                    <?php endif; ?>
                </div>
            </div>

		</div>

		<div class="joomla-footer span-16 append-1">
		    <hr />
			&copy;<?php echo date('Y'); ?> <?php echo htmlspecialchars($app->getCfg('sitename')); ?>
		</div>

		<jdoc:include type="modules" name="debug" />
	</body>
</html>
