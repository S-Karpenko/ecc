<?php
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	AcePolls
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*
* Based on Apoll Component
* @copyright (C) 2009 - 2011 Hristo Genev All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.afactory.org
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.view');

class AcepollsViewPoll extends JView {

	function display($tpl = null) {
		$this->mainframe = JFactory::getApplication();

		$db 	  	=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$date 		=& JFactory::getDate();
		$document 	=& JFactory::getDocument();
		$pathway  	=& $this->mainframe->getPathway();

		$poll_id = JRequest::getInt('id', 0);

		$poll =& JTable::getInstance('Poll', 'Table');
		$poll->load($poll_id);

		// if id value is passed and poll not published then exit
		if ($poll->id > 0 && $poll->published != 1) {
			JError::raiseError(403, JText::_('Access Forbidden'));
			return;
		}

		// Adds parameter handling
		$temp = new JRegistry();
		$temp->loadJSON($poll->params);
		$params = clone($this->mainframe->getParams());
		$params->merge($temp);

		//Set page title information
		$menu = JSite::getMenu()->getActive();

		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object($menu)) {
			$menu_params = new JRegistry();
			$menu_params->loadJSON($menu->params);
			if (!$menu_params->get('page_title')) {
				$params->set('page_title', $poll->title);
			} else {
				$params->set('page_title', $menu_params->get('page_title'));
			}
		}
		else {
			$params->set('page_title', $poll->title);
		}
		$document->setTitle($params->get('page_title'));
		
		//Set pathway information
		$pathway->addItem($poll->title, '');

		$params->def('show_page_title', 1);
		$params->def('page_title', $poll->title);


		// Check if there is a poll corresponding to id and if poll is published
		if ($poll->id > 0) {
			if (empty($poll->title)) {
				$poll->id = 0;
				$poll->title = JText::_('COM_ACEPOLLS_SELECT_POLL');
			}
            //get the array of options
    		$options =& $this->get('Options');
		}
		else {
			$options = array();
		}

		// list of polls for dropdown selection
		$pList = $this->get('Polls');

		foreach ($pList as $k=>$p) {
			$pList[$k]->url = JRoute::_('index.php?option=com_acepolls&view=poll&id='.$p->slug);
		}

		array_unshift($pList, JHTML::_('select.option', '', JText::_('COM_ACEPOLLS_SELECT_POLL'), 'url', 'title'));

		// dropdown output
		$lists = array();

		$lists['polls'] = JHTML::_('select.genericlist',   $pList, 'id',
			'class="inputbox" size="1" style="width:400px" onchange="if (this.options[selectedIndex].value != \'\') {document.location.href=this.options[selectedIndex].value}"',
 			'url', 'title',
 			JRoute::_('index.php?option=com_acepolls&view=poll&id='.$poll->id.':'.$poll->alias)
 			);

		//get the number of voters
		$voters		= isset($options[0]) ? $options[0]->voters : 0;
		$num_of_options = count( $options );
		
		for ($i = 0; $i < $num_of_options; $i++) {
			$vote =& $options[$i];
			
			//assign pie color to colors array
			$colors[$i]=$vote->color;
			//$links[$i]=$vote->link;

			if ($voters > 0) {
				$vote->percent = round( 100 * $vote->hits / $voters, 1 );
			}
			else  {
				//if nobody has voted yet
				if ($params->get('show_what') == 1) {
					$vote->percent = round(100 / $num_of_options, 1);
				} else {
					$vote->percent	= 0;
				}
			}

		}
		

		// show_what==1 because 1=show pie, 0=joomla default, 3 may be something else later
		//Pie chart 
		// pass in two arrays, one of data, the other data labels
		$title_lenght = $params->get('title_lenght');

        foreach ($options as $vote_array) {
            //if show hits is true
            if ($params->get('show_hits')) {
				$hits = " (".$vote_array->hits.")";
			} else {
				$hits = '';
			}
			
            //if show options with zero votes is true
            if ($params->get('show_zero_votes')) {
                $text = JString::substr(html_entity_decode( $vote_array->text, ENT_QUOTES, "utf-8" ), 0, $title_lenght).$hits;
                //$values[] = array("value" => $vote_array->percent, "label" => $text, "text" => $text);
				$values[] = '
				"value":'.$vote_array->percent.', 
				"label":"'.addslashes($text).'", 
				"text":"'.addslashes($text).'"
				';
 				
            } else {
                //show only options that have hits
                if ($vote_array->percent) {
                    $text = JString::substr(html_entity_decode( $vote_array->text, ENT_QUOTES, "utf-8" ), 0, $title_lenght).$hits;
                    //$values[] = array("value" => $vote_array->percent, "label" => $text, "text" => $text);
					$values[] = '
					"value":'.$vote_array->percent.', 
					"label":"'.addslashes($text).'", 
					"text":"'.addslashes($text).'"
					';	
				}
            }
        }

		// prepare the javascript for the pie
		//colors and values array to json 
		//TODO use json_encode some day
		$json_colors = '["'.implode('", "',$colors).'"]';
		$json_values = '[{'.implode('}, {', $values).'}]';

$js = '
        function ofc_ready() {}

        function open_flash_chart_data()
        {
        	return JSON.stringify(data);
        }

        var data = {
        "title":{
        	"text" : "",
        	"style" : "font-size: 14px; font-family: Verdana; text-align: center;"
        	},
        "bg_colour" : "#'.$params->get("bg_color").'",
        "elements" :
            [{
            "type" : "pie",
            "tip" : "#label# \n#val#%",
            "colours" : '.$json_colors.',
            "alpha" : '.$params->get("opacity").',
            "start-angle" : '.$params->get("start_angle").',
            "radius" : '.$params->get("radius").',
            "no-labels" : '.$params->get("no_labels").',
            "values" : '. $json_values . ',
            "gradient-fill" : '.$params->get("gradient"). ',
            "font-size" : '.$params->get("font_size").',
            "label-colour" : "#'.$params->get("font_color").'",
            "animate":[{
                "distance" : '.$params->get("bounce_dinstance").',
                "type" : "'.$params->get("animation_type").'"
        		}]
        	}]
        };
';

		//Check if the user has voted
		$cookieName	= JUtility::getHash($this->mainframe->getName().'poll'.$poll_id );
		$cookieVoted= JRequest::getVar($cookieName, '0', 'COOKIE', 'INT');
		
		$query = "SELECT date FROM ".$db->nameQuote('#__acepolls_votes')." WHERE poll_id=".(int)$poll_id." AND user_id=".(int)$user->id; 
		$db->setQuery($query);
		$userVoted	= ($db->loadResult()) ? 1 : 0;
		
		$ip = ip2long($_SERVER['REMOTE_ADDR']);
		$query = "SELECT ip FROM ".$db->nameQuote('#__acepolls_votes')." WHERE poll_id=".(int)$poll_id." AND ip = '".$ip."'";
		$db->setQuery($query); 
		$ipVoted = ($db->loadResult()) ? 1 : 0;
		
		$date->setOffset($this->mainframe->getCfg('offset'));
		$now = $date->toFormat();
	
		//Algorithm for shoiwing or not the poll in the component
		if ($params->get('allow_voting')) {
			if (($now > $poll->publish_up) && ($now < $poll->publish_down)) { 
				if ($params->get('only_registered')) {
					if (!$user->guest) {
						if($params->get('one_vote_per_user')) {
							if ($userVoted) {
								$allowToVote = 0;
								$msg = JText::_('COM_ACEPOLLS_ALREADY_VOTED');
							} else {
								$allowToVote = 1;
							}
						} else {
							if ($cookieVoted) {
								$allowToVote = 0;
								$msg = JText::_('COM_ACEPOLLS_ALREADY_VOTED');
							} else { 
								$allowToVote = 1;
							}							
						}
					} else {
						$allowToVote = 0;
						$msg = JText::_('COM_ACEPOLLS_REGISTER_TO_VOTE');
					}
				}
				else {
					if ($cookieVoted) {
						$allowToVote = 0;
						$msg = JText::_('COM_ACEPOLLS_ALREADY_VOTED');
					} else {
						if ($params->get('ip_check')) {
							if($ipVoted) {
								$allowToVote = 0;
								$msg = JText::_('COM_ACEPOLLS_ALREADY_VOTED');
							} else {
								$allowToVote = 1;
							}
						} else {
							$allowToVote = 1;
						}
					}
				}
			} else {
				$allowToVote = 0;
			}
			
			if ($now<$poll->publish_up) { 
				$msg = JText::_('COM_ACEPOLLS_VOTE_NOT_STARTED');
			}
			
			if ($now>$poll->publish_down) { 
				$msg = JText::_('COM_ACEPOLLS_VOTE_ENDED');
			}
		}
		else {
			$allowToVote = 0;
		}

		$this->assignRef('lists',				$lists);
		$this->assignRef('params',				$params);
		$this->assignRef('poll',				$poll);
		$this->assignRef('options',				$options); 
		$this->assignRef('allowToVote',			$allowToVote);
		$this->assignRef('msg',					$msg);
		$this->assignRef('js',				    $js);

		parent::display($tpl);
	}
}