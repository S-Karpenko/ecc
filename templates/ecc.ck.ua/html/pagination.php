<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	Templates.bluestork
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * This is a file to add template specific chrome to pagination rendering.
 *
 * pagination_list_footer
 *	Input variable $list is an array with offsets:
 *		$list[prefix]		: string
 *		$list[limit]		: int
 *		$list[limitstart]	: int
 *		$list[total]		: int
 *		$list[limitfield]	: string
 *		$list[pagescounter]	: string
 *		$list[pageslinks]	: string
 *
 * pagination_list_render
 *	Input variable $list is an array with offsets:
 *		$list[all]
 *			[data]		: string
 *			[active]	: boolean
 *		$list[start]
 *			[data]		: string
 *			[active]	: boolean
 *		$list[previous]
 *			[data]		: string
 *			[active]	: boolean
 *		$list[next]
 *			[data]		: string
 *			[active]	: boolean
 *		$list[end]
 *			[data]		: string
 *			[active]	: boolean
 *		$list[pages]
 *			[{PAGE}][data]		: string
 *			[{PAGE}][active]	: boolean
 *
 * pagination_item_active
 *	Input variable $item is an object with fields:
 *		$item->base	: integer
 *		$item->prefix	: string
 *		$item->link	: string
 *		$item->text	: string
 *
 * pagination_item_inactive
 *	Input variable $item is an object with fields:
 *		$item->base	: integer
 *		$item->prefix	: string
 *		$item->link	: string
 *		$item->text	: string
 *
 * This gives template designers ultimate control over how pagination is rendered.
 *
 * NOTE: If you override pagination_item_active OR pagination_item_inactive you MUST override them both
 */

function pagination_list_footer($list)
{
	// Initialise variables.
	$lang = JFactory::getLanguage();
	$html = "<div class=\"container\"><div class=\"pagination\">\n";

	$html .= "\n<div class=\"limit\">".JText::_('JGLOBAL_DISPLAY_NUM').$list['limitfield']."</div>";
	$html .= $list['pageslinks'];
	$html .= "\n<div class=\"limit\">".$list['pagescounter']."</div>";

	$html .= "\n<input type=\"hidden\" name=\"" . $list['prefix'] . "limitstart\" value=\"".$list['limitstart']."\" />";
	$html .= "\n</div></div>";

	return $html;
}

function pagination_list_render($list)
{
	// Initialise variables.
	$lang = JFactory::getLanguage();
	$html = null;
    $html = '<ul>';

	if ($list['previous']['active']) {
		$html .= "<li>".$list['previous']['data']."</li>";
	} else {
		$html .= "<li>".$list['previous']['data']."</li>";
	}

	$html .= "\n<li>";
	foreach($list['pages'] as $page) {
		$html .= $page['data'];
	}
	$html .= "\n</li>";

	if ($list['next']['active']) {
		$html .= "<li>".$list['next']['data']."</li>";
	} else {
		$html .= "<li>".$list['next']['data']."</li>";
	}
    $html .= '</ul>';

	return $html;
}

function pagination_item_active(&$item)
{
    if ($item->text == 'Next'){
        $item->text = '&raquo;';
    }
    if ($item->text == 'Prev'){
        $item->text = '&laquo;';
    }
    if ($item->text == 'Попередня'){
        $item->text = '<img src="/templates/ecc.ck.ua/images/prev_button.jpg" alt="Назад" />';
    }
    if ($item->text == 'Наступна'){
        $item->text = '<img src="/templates/ecc.ck.ua/images/next_button.jpg" alt="Назад" />';
    }

	if ($item->base>0)
		return "<a href=\"".$item->link."\">".$item->text."</a>";
	else
		return "<a href=\"".$item->link."\">".$item->text."</a>";
}

function pagination_item_inactive(&$item)
{
    if ($item->text == 'Next'){
        $item->text = '&raquo;';
    }
    if ($item->text == 'Prev'){
        $item->text = '&laquo;';
    }
    if ($item->text == 'Попередня'){
        $item->text = '<img src="/templates/ecc.ck.ua/images/prev_button.jpg" alt="Назад" />';
    }
    if ($item->text == 'Наступна'){
        $item->text = '<img src="/templates/ecc.ck.ua/images/next_button.jpg" alt="Назад" />';
    }
	return "<span>".$item->text."</span>";
}
?>