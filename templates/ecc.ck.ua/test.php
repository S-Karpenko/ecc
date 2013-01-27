<?php
defined('_JEXEC') or die;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions.php';

// Create alias for $this object reference:
$document = $this;

// Shortcut for template base url:
$templateUrl = $document->baseurl . '/templates/' . $document->template;

Artx::load("Artx_Page");

// Initialize $view:
$view = $this->artx = new ArtxPage($this);

// Decorate component with Artisteer style:
$view->componentWrapper();

JHtml::_('behavior.framework', true);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <jdoc:include type="head" />
    <link rel="stylesheet" href="<?php echo $document->baseurl; ?>/templates/system/css/system.css" />
    <link rel="stylesheet" href="<?php echo $document->baseurl; ?>/templates/system/css/general.css" />

    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="<?php echo $templateUrl; ?>/css/template.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="<?php echo $templateUrl; ?>/css/template.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="<?php echo $templateUrl; ?>/css/template.responsive.css" media="all">


    <script>if ('undefined' != typeof jQuery) document._artxJQueryBackup = jQuery;</script>
    <script src="<?php echo $templateUrl; ?>/jquery.js"></script>
    <script>jQuery.noConflict();</script>

    <script src="<?php echo $templateUrl; ?>/script.js"></script>
    <script>if (document._artxJQueryBackup) jQuery = document._artxJQueryBackup;</script>
    <script src="<?php echo $templateUrl; ?>/script.responsive.js"></script>
</head>


<body>

<div class="wrapper">
    <div class="header">
        <a href="../../../ecc/templates/ecc.ck.ua/index.php" title="Experimental Creative Center"><img src="../../../ecc/templates/ecc.ck.ua/images/logo.png" width="225" height="156" alt="Experimental Creative Center" /></a>
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



    <div class="conteiner">
        <div class="content">
            <jdoc:include type="message" />
            <jdoc:include type="component" />
        </div>
    </div>

    <div class="sidebar">
            <div class="widjet calendar">
                <?php if ($this->countModules('position-7')): ?>
                    <jdoc:include type="modules" name="position-7" style="none" />
                <?php endif; ?>
            </div>
            <div class="widjet partners"></div>
            <div class="widjet voting"></div>
        </div>
    </div>







<?php if($this->countModules('atomic-sidebar') || $this->countModules('position-7')
    || $this->countModules('position-4') || $this->countModules('position-5')
    || $this->countModules('position-3') || $this->countModules('position-6') || $this->countModules('position-8'))
    : ?>
<div class="span-7 last">
    <jdoc:include type="modules" name="position-7" style="sidebar" />
    <jdoc:include type="modules" name="position-4" style="sidebar" />
    <jdoc:include type="modules" name="position-5" style="sidebar" />
    <jdoc:include type="modules" name="position-6" style="sidebar" />
    <jdoc:include type="modules" name="position-8" style="sidebar" />
    <jdoc:include type="modules" name="position-3" style="sidebar" />
</div>
    <?php endif; ?>







    <!--footer-->
    <div class="footer">
        <p>Експерементальний творчи центр</p>
    </div>
</div>
<?php echo $view->position('debug'); ?>
</body>
</html>
