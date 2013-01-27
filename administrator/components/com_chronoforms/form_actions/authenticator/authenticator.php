<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionAuthenticator{
	var $formname;
	var $formid;
	var $events = array('allowed' => 0, 'denied' => 0);
	var $group = array('id' => 'form_security', 'title' => 'Security');
	var $details = array('title' => 'Authenticator', 'tooltip' => "Control users access to your form's different events, select which users groups should be allowed access and which ones should be denied.");
	//var $users_groups = array();
	
	function run($form, $actiondata){
		$params = new JParameter($actiondata->params);
		$user = JFactory::getUser();
		//check guests
		$guest = $params->get('guests', 1);
		if($user->guest && $guest){
			$this->events['allowed'] = 1;
			return true;
		}
		//check other groups
		$jversion = new JVersion();
		if((bool)$params->get('inheritable', 0) === true){
			//get users groups objects from the db
			$db_users_groups = $this->get_groups_list();
		}
		if($jversion->RELEASE > 1.5){
			if(trim($params->get('groups', '')) || trim($params->get('denied_groups', ''))){
				if(trim($params->get('groups', ''))){
					$_groups = explode(',', trim($params->get('groups', '')));
					array_walk($_groups, 'trim');
				}else{
					$_groups = array();
				}
				//if guest, then add the public group
				if((bool)$user->guest === true){
					$user->groups[] = 1;
				}
				$user->groups = array_unique($user->groups);
				
				//check denied groups if there are any
				if(trim($params->get('denied_groups', ''))){
					$d_groups = explode(',', trim($params->get('denied_groups', '')));
					array_walk($d_groups, 'trim');
					if(!empty($d_groups)){
						$denied_groups = array();
						foreach($d_groups as $d_group){
							if((bool)$params->get('inheritable', 0) === true){
								$group = $form->search_array($db_users_groups, array('key' => 'id', 'value' => $d_group));
								if(!empty($group)){
									$children = $this->get_child_path($group[0], $db_users_groups);
									$denied_groups = array_merge($denied_groups, $children);
								}
							}else{
								$denied_groups[] = (int)$d_group;
							}
						}
						$denied_groups = array_unique($denied_groups);
						if($this->group_exists($user, $denied_groups) === true){
							$this->events['denied'] = 1;
							return false;
						}
					}
				}
				
				
				$allowed_groups = array();
				foreach($_groups as $_group){
					if((bool)$params->get('inheritable', 0) === true){
						$group = $form->search_array($db_users_groups, array('key' => 'id', 'value' => $_group));
						if(!empty($group)){
							$children = $this->get_child_path($group[0], $db_users_groups);
							$allowed_groups = array_merge($allowed_groups, $children);
						}
					}else{
						$allowed_groups[] = (int)$_group;
					}
				}
				$allowed_groups = array_unique($allowed_groups);
					
				
				if($this->group_exists($user, $allowed_groups) === true){
					$this->events['allowed'] = 1;
					return true;
				}
			}
		}else{
			if(trim($params->get('groups', '')) || trim($params->get('denied_groups', ''))){
				if(trim($params->get('groups', ''))){
					$_groups = explode(',', trim($params->get('groups', '')));
					array_walk($_groups, 'trim');
				}else{
					$_groups = array();
				}
				//if guest, then add the public group
				if((bool)$user->guest === true){
					$user->gid = 29;
				}
				
				//check denied groups if there are any
				if(trim($params->get('denied_groups', ''))){
					$d_groups = explode(',', trim($params->get('denied_groups', '')));
					array_walk($d_groups, 'trim');
					if(!empty($d_groups)){
						$denied_groups = array();
						foreach($d_groups as $d_group){
							if((bool)$params->get('inheritable', 0) === true){
								$group = $form->search_array($db_users_groups, array('key' => 'id', 'value' => $d_group));
								if(!empty($group)){
									$children = $this->get_child_path($group[0], $db_users_groups);
									$denied_groups = array_merge($denied_groups, $children);
								}
							}else{
								$denied_groups[] = (int)$d_group;
							}
						}
						$denied_groups = array_unique($denied_groups);
						if($this->group_exists($user, $denied_groups) === true){
							$this->events['denied'] = 1;
							return false;
						}
					}
				}
				
				
				$allowed_groups = array();
				foreach($_groups as $_group){
					if((bool)$params->get('inheritable', 0) === true){
						$group = $form->search_array($db_users_groups, array('key' => 'id', 'value' => $_group));
						if(!empty($group)){
							$children = $this->get_child_path($group[0], $db_users_groups);
							$allowed_groups = array_merge($allowed_groups, $children);
						}
					}else{
						$allowed_groups[] = (int)$_group;
					}
				}
				$allowed_groups = array_unique($allowed_groups);
					
				
				if($this->group_exists($user, $allowed_groups) === true){
					$this->events['allowed'] = 1;
					return true;
				}
			}
		}
		//user group not found, set denied = 1
		$this->events['denied'] = 1;
		return null;
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'guests' => 1,
				'content1' => '',
				'groups' => '',
				'denied_groups' => '',
				'inheritable' => 0,
				'groups_list' => $this->get_goptions_list($this->get_groups_list())
			);
		}
		return array('action_params' => $action_params);
	}
	
	function group_exists($user, $groups){
		$jversion = new JVersion();		
		if($jversion->RELEASE > 1.5){
			if(!empty($user->groups)){
				foreach($user->groups as $kg => $group){
					if(in_array((int)$group, $groups, true)){
						//$this->events['allowed'] = 1;
						return true;
					}
				}
			}
		}else{
			if(in_array((int)$user->gid, $groups, true)){
				//$this->events['allowed'] = 1;
				return true;
			}
		}
		return false;
	}
	
	function get_groups_list(){
		/*if(isset($this->users_groups) && !empty($this->users_groups)){
			return $this->users_groups;
		}*/
		static $users_groups;
		if(!isset($users_groups)){
			$users_groups = array();
		}else{
			return $users_groups;
		}
		$jversion = new JVersion();
		$database = JFactory::getDBO();		
		if($jversion->RELEASE > 1.5){
			$query = "SELECT * FROM `#__usergroups` ORDER BY `lft`";
			$database->setQuery($query);
			$users_groups = $groups = $database->loadAssocList();			
		}else{
			$query = "SELECT * FROM `#__core_acl_aro_groups`";
			$database->setQuery($query);			
			$users_groups = $groups = $database->loadAssocList();
		}
		return $groups;
	}
	
	function get_goptions_list($groups){
		$jversion = new JVersion();
		$options = array();
		foreach($groups as $group){
			//$options[$group->id] = $group->title;
			$child_path = $this->get_parent_path($group, $groups);
			$depth = count($child_path);
			array_shift($child_path);
			foreach($child_path as $k => $p){
				$child_path[$k] = '|&mdash;';
			}
			if($jversion->RELEASE > 1.5){
				$options[$group['id']] = implode('', $child_path).$group['title'];
			}else{
				$options[$group['id']] = implode('', $child_path).$group['name'];
			}
		}
		return $options;
	}
	
	function get_parent_path($m_group, $groups, &$return = array()){
		if(empty($return)){
			if($m_group['id'] == 1){
				return array(1);
			}else{
				$return[] = (int)$m_group['id'];
			}
		}
		foreach($groups as $group){
			if((int)$group['id'] == (int)$m_group['parent_id']){
				$return[] = (int)$group['id'];
				if((int)$group['id'] == 1){
					break;
				}else{
					$return = $this->get_parent_path($group, $groups, $return);
				}
			}
		}
		return $return;
	}
	
	function get_child_path($m_group, $groups, &$return = array()){
		if(empty($return)){
			$return[] = (int)$m_group['id'];
		}
		$children = array();
		foreach($groups as $group){
			if(((int)$group['parent_id'] == (int)$m_group['id']) && !in_array((int)$group['id'], $return)){
				$return[] = (int)$group['id'];
				$children[] = $group;
				//$return = $this->get_child_path($group, $groups, $return);
			}
		}
		foreach($children as $child){
			$return = $this->get_child_path($child, $groups, $return);
		}
		return $return;
	}
}
?>