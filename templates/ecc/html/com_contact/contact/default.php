<?php
 /**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$cparams = JComponentHelper::getParams ('com_media');
?>
<div class="contact<?php echo $this->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>
	<?php if ($this->contact->name && $this->params->get('show_name')) : ?>
		<h2>
			<span class="contact-name"><?php echo $this->contact->name; ?></span>
		</h2>
	<?php endif;  ?>
	<?php if ($this->params->get('show_contact_category') == 'show_no_link') : ?>
		<h3>
			<span class="contact-category"><?php echo $this->contact->category_title; ?></span>
		</h3>
	<?php endif; ?>
	<?php if ($this->params->get('show_contact_category') == 'show_with_link') : ?>
		<?php $contactLink = ContactHelperRoute::getCategoryRoute($this->contact->catid);?>
		<h3>
			<span class="contact-category"><a href="<?php echo $contactLink; ?>">
				<?php echo $this->escape($this->contact->category_title); ?></a>
			</span>
		</h3>
	<?php endif; ?>
	<?php if ($this->params->get('show_contact_list') && count($this->contacts) > 1) : ?>
		<form action="#" method="get" name="selectForm" id="selectForm">
			<?php echo JText::_('COM_CONTACT_SELECT_CONTACT'); ?>
			<?php echo JHtml::_('select.genericlist',  $this->contacts, 'id', 'class="inputbox" onchange="document.location.href = this.value"', 'link', 'name', $this->contact->link);?>
		</form>
	<?php endif; ?>
    <?php if ($this->contact->misc && $this->params->get('show_misc')) : ?>
        <?php if ($this->params->get('presentation_style')!='plain'){?>
            <?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_OTHER_INFORMATION'), 'display-misc');} ?>
        <?php if ($this->params->get('presentation_style')=='plain'):?>

        <?php endif; ?>
        <div class="contact-miscinfo">
            <div class="map">
                <iframe width="450" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=uk&amp;geocode=&amp;q=%D0%A7%D0%B5%D1%80%D0%BA%D0%B0%D1%81%D1%81%D1%8B+%D1%81%D0%BC%D1%96%D0%BB%D1%8F%D0%BD%D1%81%D1%8C%D0%BA%D1%83+113%2F2&amp;aq=&amp;sll=37.0625,-95.677068&amp;sspn=39.916234,81.123047&amp;ie=UTF8&amp;hq=&amp;hnear=%D0%A1%D0%BC%D1%96%D0%BB%D1%8F%D0%BD%D1%81%D1%8C%D0%BA%D0%B0+%D0%B2%D1%83%D0%BB.,+113,+%D0%A7%D0%B5%D1%80%D0%BA%D0%B0%D1%81%D0%B8,+%D0%A7%D0%B5%D1%80%D0%BA%D0%B0%D1%81%D1%8C%D0%BA%D0%B0+%D0%BE%D0%B1%D0%BB%D0%B0%D1%81%D1%82%D1%8C,+%D0%A3%D0%BA%D1%80%D0%B0%D1%97%D0%BD%D0%B0&amp;t=m&amp;z=14&amp;iwloc=A&amp;ll=49.425504,32.04442&amp;output=embed"></iframe>
            </div>
            <div class="<?php echo $this->params->get('marker_class'); ?>">
                <?php echo $this->params->get('marker_misc'); ?>
            </div>
            <div class="contact-misc">
                <?php echo $this->contact->misc; ?>
            </div>
        </div>
    <?php endif; ?>
	<?php  if ($this->params->get('presentation_style')!='plain'){?>
		<?php  echo  JHtml::_($this->params->get('presentation_style').'.start', 'contact-slider'); ?>
	<?php  echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_DETAILS'), 'basic-details'); } ?>
	<?php /* ?>
    <?php if ($this->params->get('presentation_style')=='plain'):?>
		<?php  echo '<h3>'. JText::_('COM_CONTACT_DETAILS').'</h3>';  ?>
	<?php endif; ?>
    */ ?>
	<?php if ($this->contact->image && $this->params->get('show_image')) : ?>
		<div class="contact-image">
			<?php echo JHtml::_('image', $this->contact->image, JText::_('COM_CONTACT_IMAGE_DETAILS'), array('align' => 'middle')); ?>
		</div>
	<?php endif; ?>

	<?php if ($this->contact->con_position && $this->params->get('show_position')) : ?>
		<p class="contact-position"><?php echo $this->contact->con_position; ?></p>
	<?php endif; ?>

	<?php echo $this->loadTemplate('address'); ?>

	<?php if ($this->params->get('allow_vcard')) :	?>
		<?php echo JText::_('COM_CONTACT_DOWNLOAD_INFORMATION_AS');?>
			<a href="<?php echo JRoute::_('index.php?option=com_contact&amp;view=contact&amp;id='.$this->contact->id . '&amp;format=vcf'); ?>">
			<?php echo JText::_('COM_CONTACT_VCARD');?></a>
	<?php endif; ?>
	<p></p>
	<?php if ($this->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id)) : ?>

		<?php if ($this->params->get('presentation_style')!='plain'):?>
			<?php  echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_EMAIL_FORM'), 'display-form');  ?>
		<?php endif; ?>
		<?php if ($this->params->get('presentation_style')=='plain'):?>
			<?php // echo '<h3>'. JText::_('COM_CONTACT_EMAIL_FORM').'</h3>';  ?>
		<?php endif; ?>
		<?php  echo $this->loadTemplate('form');  ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_links')) : ?>
		<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_articles') && $this->contact->user_id && $this->contact->articles) : ?>
		<?php if ($this->params->get('presentation_style')!='plain'):?>
			<?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('JGLOBAL_ARTICLES'), 'display-articles'); ?>
			<?php endif; ?>
			<?php if  ($this->params->get('presentation_style')=='plain'):?>
			<?php echo '<h3>'. JText::_('JGLOBAL_ARTICLES').'</h3>'; ?>
			<?php endif; ?>
			<?php echo $this->loadTemplate('articles'); ?>
	<?php endif; ?>

	<?php if ($this->params->get('show_profile') && $this->contact->user_id && JPluginHelper::isEnabled('user', 'profile')) : ?>
		<?php if ($this->params->get('presentation_style')!='plain'):?>
			<?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_PROFILE'), 'display-profile'); ?>
		<?php endif; ?>
		<?php if ($this->params->get('presentation_style')=='plain'):?>
			<?php echo '<h3>'. JText::_('COM_CONTACT_PROFILE').'</h3>'; ?>
		<?php endif; ?>
		<?php echo $this->loadTemplate('profile'); ?>
	<?php endif; ?>

	<?php if ($this->params->get('presentation_style')!='plain'){?>
			<?php echo JHtml::_($this->params->get('presentation_style').'.end');} ?>
</div>
