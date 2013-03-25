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
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blueprint/print.css" type="text/css" media="print" />
		<!--[if lt IE 8]><link rel="stylesheet" href="blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

		<!-- The following line loads the template CSS file located in the template folder. -->
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />

		<!-- The following four lines load the Blueprint CSS Framework and the template CSS file for right-to-left languages. If you don't want to use these, delete these lines. -->
		<?php if($this->direction == 'rtl') : ?>
			<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blueprint/plugins/rtl/screen.css" type="text/css" />
			<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template_rtl.css" type="text/css" />
		<?php endif; ?>

		<!-- The following line loads the template JavaScript file located in the template folder. It's blank by default. -->

    </head>

    <body>
    <div class="bg_blue"></div>
    <div class="bg_cloud"></div>
    <div class="wrapper">
        <div class="header">
            <canvas id="logoCanvas" width="197" height="98"></canvas>
            <h1>

            </h1>
        </div>
        <div class="buttons">
            <a id="facebook-button" class="fly-button" target="_blank" href="http://www.facebook.com/groups/500989506625626/"></a>
            <a id="twitter-button" class="fly-button" target="_blank" href="https://twitter.com/tatius181"></a>
            <a id="vkontakte-button" class="fly-button" target="_blank" href="http://vk.com/club51174283"></a>
            <a id="odnoklassniki-button" class="fly-button" target="_blank" href="http://www.odnoklassniki.ru/group/54565615829017/"></a>
            <a id="youtube-button" class="fly-button" target="_blank" href="http://www.youtube.com/user/ECC2013?feature=mhee"></a>

        </div>

    <!--navigation-->
        <div class="navbar">
            <?php if ($this->countModules('position-1')): ?>
                <jdoc:include type="modules" name="position-1" style="none" />
            <?php endif; ?>
        </div>






            <div class="content">
                    <jdoc:include type="message" />
			        <jdoc:include type="component" />
                    <div class="map">
                        <?php if ($this->countModules('position-2')): ?>
                        <jdoc:include type="modules" name="position-2" style="none" />
                        <?php endif; ?>
                    </div>
                    <div class="form">
                       <?php if ($this->countModules('position-3')): ?>
                       <jdoc:include type="modules" name="position-3" style="none" />
                       <?php endif; ?>
                    </div>

                <div class="comments">
                    <?php if ($this->countModules('position-4')): ?>
                    <jdoc:include type="modules" name="position-4" style="none" />
                    <?php endif; ?>
                </div>
            </div>

            <div class="sidebar">
                <div class="calendar">
                    <?php if ($this->countModules('position-7')): ?>
                    <jdoc:include type="modules" name="position-7" style="xhtml" />
                    <?php endif; ?>
                </div>
                <div class="voting">
                    <?php if ($this->countModules('position-8')): ?>
                    <jdoc:include type="modules" name="position-8" style="none" />
                    <?php endif; ?>
                </div>
                <div class="partners">
                    <?php if ($this->countModules('position-9')): ?>
                    <jdoc:include type="modules" name="position-9" style="xhtml" />
                    <?php endif; ?>
                </div>

            </div>


		<div class="footer">
            <div>
                <?php if ($this->countModules('position-10')): ?>
                <jdoc:include type="modules" name="position-10" style="none" />
                <?php endif; ?>
            </div>
			<p>&copy;<?php echo date('Y'); ?> <?php echo htmlspecialchars($app->getCfg('sitename')); ?></p>
		</div>

		<jdoc:include type="modules" name="debug" />
	<div/>
    </body>
</html>
