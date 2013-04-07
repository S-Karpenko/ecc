<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: datamodel.php 3553 2012-04-20 10:18:59Z geraintedwards $
 * @package     JEvents
 * @copyright   Copyright (C) 2008-2009 GWE Systems Ltd, 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://www.jevents.net
 */

// functions common to component and modules
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JEventsDataModel {
	var $myItemid = null;
	var $catidsOut = "";
	var $catids = null;
	var $catidList = null;
	// menu/module catid constraints - distinct from URL
	var $mmcatids = null;
	var $mmcatidList = null;

	var $aid = null;

	// flag to track if we should who all categories
	var $moduleAllCats = false;

	var $queryModel;

	function  JEventsDataModel($dbmodel=null){
		$cfg = & JEVConfig::getInstance();
		
		$user = JFactory::getUser();
		$this->aid = JEVHelper::getAid($user);

		if (is_null($dbmodel)){
			$this->queryModel =new JEventsDBModel($this);
		}
		else {
			include_once(JPATH_ADMINISTRATOR."/components/".JEV_COM_COMPONENT."/libraries/adminqueries.php");
			$this->queryModel = new $dbmodel($this);
		}

	}

	function setupModuleCatids($modparams){
		$this->myItemid = findAppropriateMenuID ($this->catidsOut, $this->catids, $this->catidList, $modparams->toObject(), $this->moduleAllCats);
		
		// set menu/module constraint values for later use
		$this->mmcatids = array();
		// New system
		$newcats = $modparams->get( "catidnew", false);
		if ($newcats && is_array($newcats )){
			foreach ($newcats as $newcat){
				if ( !in_array( $newcat, $this->mmcatids )){
					$this->mmcatids[]	= $newcat;
				}
			}				
		}
		else {
			for ($c=0; $c < 999; $c++) {
				$nextCID = "catid$c";
				//  stop looking for more catids when you reach the last one!
				if (!$nextCatId = $modparams->get( $nextCID, null)) {
					break;
				}
				if ( !in_array( $nextCatId, $this->mmcatids )){
					$this->mmcatids[]	= $nextCatId;
				}
			}
		}
		$this->mmcatidList = implode(",",$this->mmcatids);
		
		return $this->myItemid;
	}

	function setupComponentCatids(){
		// if no catids from GET or POST default to the menu values
		// Note that module links must pass a non default value
		
		$Itemid = JEVHelper::getItemid();
		$this->myItemid = $Itemid;

		$menu	=& JSite::getMenu();
		$active = $menu->getActive();
		if (!is_null($active) && $active->component==JEV_COM_COMPONENT){
			$params	=&  JComponentHelper::getParams(JEV_COM_COMPONENT);
		}
		else {
			// If accessing this function from outside the component then I must load suitable parameters
			$params = $menu->getParams($this->myItemid);
		}

		$separator = $params->get("catseparator","|");

		$catidsIn		= JRequest::getVar(	'catids', 		'NONE' ) ;
		if ($catidsIn == "NONE"   || $catidsIn == 0 ) {
			$catidsIn		= JRequest::getVar(	'category_fv', 		'NONE' ) ;
		}
		
		// set menu/module constraint values for later use
		$this->mmcatids = array();
		// New system
		$newcats = $params->get( "catidnew", false);
		if ($newcats && is_array($newcats )){
			foreach ($newcats as $newcat){
				if ( !in_array( $newcat, $this->mmcatids )){
					$this->mmcatids[]	= $newcat;
				}
			}				
		}
		else {
			for ($c=0; $c < 999; $c++) {
				$nextCID = "catid$c";
				//  stop looking for more catids when you reach the last one!
				if (!$nextCatId = $params->get( $nextCID, null)) {
					break;
				}
				if ( !in_array( $nextCatId, $this->mmcatids )){
					$this->mmcatids[]	= $nextCatId;
				}
			}
		}
		$this->mmcatidList = implode(",",$this->mmcatids);
		
		// if resettting then always reset to module/menu value
		if (intval(JRequest::getVar('filter_reset',0))){
			$this->catids = $this->mmcatids;
			$this->catidList  = $this->mmcatidList ;
		}
		else {
			$this->catids = array();
			if ($catidsIn == "NONE"  || $catidsIn == 0 ) {
				$this->catidList	= "";
				// New system
				$newcats = $params->get( "catidnew", false);
				if ($newcats && is_array($newcats )){
					foreach ($newcats as $newcat){
						if ( !in_array( $newcat, $this->catids )){
							$this->catids[]	= $newcat;
						}
					}				
				}
				else {
					for ($c=0; $c < 999; $c++) {
						$nextCID = "catid$c";
						//  stop looking for more catids when you reach the last one!
						if (!$nextCatId = $params->get( $nextCID, null)) {
							break;
						}
						if ( !in_array( $nextCatId, $this->catids )){
							$this->catids[]	= $nextCatId;
						}
					}
				}
				$this->catidList = implode(",",$this->catids);
				// no need to set catidsOut for menu item since the menu item knows this information already!
				//$this->catidsOut = str_replace( ',', $separator, $this->catidList );
			}
			else {
				$this->catids = explode( $separator, $catidsIn );
				// hardening!
				$this->catidList = JEVHelper::forceIntegerArray($this->catids,true);
				$this->catidsOut = str_replace(',', $separator, $this->catidList);
			}
		}
		// some functions e.g. JEventCal::viewDetailLink don't have access to a datamodel so set a global value
		// as a backup
		global $catidsOut;
		$catidsOut = $this->catidsOut;
	}

	/**
	 * Gets appropriate Itemid part of URL 
	 *
	 * @return string
	 */
	function getItemidLink($withAmp=true){
		if (!is_null($this->myItemid)){
			return ($withAmp?"&":"")."Itemid=".$this->myItemid;
		}
		else return "";
	}

	/**
	 * Gets appropriate category restriction part of URL 
	 * 
	 * @return string
	 */
	function getCatidsOutLink($withAmp=true){
		$ret = "";
		if ($this->catidsOut!=""){
			$ret .= ($withAmp?"&":"")."catids=".$this->catidsOut;
		}
		return $ret;
	}

	/**
	 * Gets calendar data for use in main calendar and module
	 *
	 * @param int $year
	 * @param int $month
	 * @param int $day
	 * @param boolean $short - use true for module which only requires knowledge of if dat has an event
	 * @param boolean $veryshort - use true for module which only requires dates and nothing about events
	 * @return array - calendar data array
	 */
	function getCalendarData( $year, $month, $day , $short=false, $veryshort = false){
		

		$data = array();
		$data['year']=$year;
		$data['month']=$month;

		$db	=& JFactory::getDBO();

		if (!isset($this->myItemid) || is_null($this->myItemid)) {
			$Itemid = JEVHelper::getItemid();
			$this->myItemid = $Itemid;
		}

		include_once(JPATH_ADMINISTRATOR."/components/".JEV_COM_COMPONENT."/libraries/colorMap.php");

		$cfg = & JEVConfig::getInstance();

		if (!$veryshort){
			//$rows = $this->queryModel->listEventsByMonthNEW( $year, $month, 'reccurtype ASC,publish_up');
			$icalrows = $this->queryModel->listIcalEventsByMonth( $year, $month);

			// handy for developement in case I comment out part of the above
			if (!isset($rows)) $rows = array();
			if (!isset($icalrows)) $icalrows = array();
			$rows = array_merge($icalrows,$rows);
		}
		else {
			$rows = array();
		}
        $articles = $this->queryModel->articlesList($year, $month);
		$rowcount = count( $rows );

		if (strlen($this->catidsOut)>0) {
			$cat = "&catids=$this->catidsOut";
		} else {
			$cat="";
		}

		$month = intval($month);
		if( $month <= '9' ) {
			$month = '0' . $month;
		}

		$fieldsetText = "";
		$yearNow = date("Y");
		$monthNow = date("m");
		$dayNow = intval(date("d"));
		if (!$short){
			if ($year==$yearNow && $month==$monthNow && $day==$dayNow){
				$fieldsetText = JEventsHTML::getDateFormat( $year, $month, $day, 1 );
			}
			else $fieldsetText = JEventsHTML::getDateFormat( $year, $month, "", 3 );
			$data["fieldsetText"]=$fieldsetText;
		}
		$startday = $cfg->get('com_starday');
		if(( !$startday ) ){
			$startday = 0;
		}
		$data['startday']=$startday;
		if (!$short){
			$data["daynames"]=array();
			for( $i = 0; $i < 7; $i++ ) {
				$data["daynames"][$i]=JEventsHTML::getDayName(($i+$startday)%7, true );
			}
		}

		$data["dates"]=array();
		//Start days
		$start = (( date( 'w', JevDate::mktime( 0, 0, 0, $month, 1, $year )) - $startday + 7 ) % 7 );
		$base = date( 't', JevDate::mktime( 0, 0, 0, $month, 0, $year ));
		$dayCount=0;
		$priorMonth = $month-1;
		$priorYear = $year;
		if ($priorMonth<=0) {
			$priorMonth+=12;
			$priorYear-=1;
		}
		for( $a = $start; $a > 0; $a-- ){
			$d =  intval($base - $a + 1);

			$data["dates"][$dayCount]=array();
			$data["dates"][$dayCount]["monthType"]="prior";
			$data["dates"][$dayCount]["month"]=$priorMonth;
			$data["dates"][$dayCount]["year"]=$priorYear;
			$data["dates"][$dayCount]['countDisplay']=0;
			if( $d <= '9' ) {
				$do = '0' . $d;
			} else {
				$do = $d;
			}
			$data["dates"][$dayCount]['d']=$d;
			$data["dates"][$dayCount]['d0']=$do;

			if ($short){
				$data["dates"][$dayCount]["events"]=false;
			}
			else {
				$data["dates"][$dayCount]["events"]=array();
			}

			$cellDate		= JevDate::mktime (0, 0, 0, $priorMonth, $d, $priorYear);
			$data["dates"][$dayCount]['cellDate']=$cellDate;

			$data["dates"][$dayCount]["today"]=false;

			$link = JRoute::_( 'index.php?option=' . JEV_COM_COMPONENT . '&task=day.listevents&year='
			. $priorYear . '&month=' . $priorMonth . '&day=' . $do .$cat. '&Itemid=' . $this->myItemid );

			$data["dates"][$dayCount]["link"]=$link;
			$dayCount++;
		}
		sort($data["dates"]);

		//Current month
		$end = date( 't', JevDate::mktime( 0, 0, 0,( $month + 1 ), 0, $year ));
		for( $d = 1; $d <= $end; $d++ ){
			$data["dates"][$dayCount]=array();
			// utility field used to keep track of events displayed in a day!
			$data["dates"][$dayCount]['countDisplay']=0;
			$data["dates"][$dayCount]["monthType"]="current";
			$data["dates"][$dayCount]["month"]=$month;
			$data["dates"][$dayCount]["year"]=$year;

			if ($short){
				$data["dates"][$dayCount]["events"]=false;
			}
			else {
				$data["dates"][$dayCount]["events"]=array();
			}
			$t_datenow = JEVHelper::getNow();
			$now_adjusted = $t_datenow->toUnix(true);
			if( $month == JevDate::strftime( '%m', $now_adjusted)
			&& $year == JevDate::strftime( '%Y', $now_adjusted)
			&& $d == JevDate::strftime( '%d', $now_adjusted)) {
				$data["dates"][$dayCount]["today"]=true;
			}else{
				$data["dates"][$dayCount]["today"]=false;
			}

            foreach($articles as $item){
                if ($item->publish_day == $d) {
                    $data["dates"][$dayCount]["articles"] = true;
                }
            }
            
			if( $d <= '9') {
				$do = '0' . $d;
			} else {
				$do = $d;
			}

			$data["dates"][$dayCount]['d']=$d;
			$data["dates"][$dayCount]['d0']=$do;

			$link = JRoute::_( 'index.php?option=' . JEV_COM_COMPONENT . '&task=day.listevents&year='
			. $year . '&month=' . $month . '&day=' . $do .$cat. '&Itemid=' . $this->myItemid );
			$data["dates"][$dayCount]["link"]=$link;

			$cellDate		= JevDate::mktime (0, 0, 0, $month, $d, $year);
			$data["dates"][$dayCount]['cellDate']=$cellDate;
			//$data["dates"][$dayCount]['events'] = array();

			if( $rowcount > 0 ){
				foreach ($rows as $row) {

					if ($row->checkRepeatMonth($cellDate,$year,$month))  {


						if ($short){
							$data["dates"][$dayCount]['events']=true;
							// I can skip testing all the events since checkRepeatMonth tests for multiday events to make xure they only appear
							// on secondary days if the multiday flag is set to 1
							break;
						}
						else {
							$i=count($data["dates"][$dayCount]['events']);
							$data["dates"][$dayCount]['events'][$i] = $row;
						}
					}
				}
			}
			// sort events of this day by time
			if (is_array($data["dates"][$dayCount]['events'])) {
				usort($data["dates"][$dayCount]['events'],array("JEventsDataModel", "_sortEventsByTime"));
			}
			$dayCount++;
		}

		$days 	= ( 7 - date( 'w', JevDate::mktime( 0, 0, 0, $month + 1, 1, $year )) + $startday ) %7;
		$d		= 1;

		$followMonth = $month+1;
		$followYear = $year;
		if ($followMonth>12) {
			$followMonth-=12;
			$followYear+=1;
		}
		$data["followingMonth"]=array();
		for( $d = 1; $d <= $days; $d++ ) {

			$data["dates"][$dayCount]=array();
			$data["dates"][$dayCount]["monthType"]="following";
			$data["dates"][$dayCount]["month"]=$followMonth;
			$data["dates"][$dayCount]["year"]=$followYear;
			$data["dates"][$dayCount]['countDisplay']=0;
			if( $d <= '9') {
				$do = '0' . $d;
			} else {
				$do = $d;
			}

			$data["dates"][$dayCount]['d']=$d;
			$data["dates"][$dayCount]['d0']=$do;

			if ($short){
				$data["dates"][$dayCount]["events"]=false;
			}
			else {
				$data["dates"][$dayCount]["events"]=array();
			}

			$cellDate		= JevDate::mktime (0, 0, 0, $followMonth, $d, $followYear);
			$data["dates"][$dayCount]['cellDate']=$cellDate;

			$data["dates"][$dayCount]["today"]=false;

			$link = JRoute::_( 'index.php?option=' . JEV_COM_COMPONENT . '&task=day.listevents&year='
			. $followYear . '&month=' . $followMonth . '&day=' . $do .$cat. '&Itemid=' . $this->myItemid );

			$data["dates"][$dayCount]["link"]=$link;
			$dayCount++;
		}

		// Week data and links
		$data["weeks"]=array();
		for ($w=0;$w<6 && $w*7<count($data["dates"]);$w++){
			$date = $data["dates"][$w*7]['cellDate'];
			$day = $data["dates"][$w*7]["d"];
			$month = $data["dates"][$w*7]["month"];
			$year = $data["dates"][$w*7]["year"];
			// get week number from second weekday to avoid confusion with week start sunday + 1 day + 3 hours to avoid DST change problems
			$week = intval(JEV_CommonFunctions::jev_strftime("%V",$date+97200));
			$link = JRoute::_( 'index.php?option=' . JEV_COM_COMPONENT . '&task=week.listevents&year='
			. $year . '&month=' . $month . '&day=' . $day .$cat. '&Itemid=' . $this->myItemid );
			$data["weeks"][$week]=$link;
		}

		return $data;
	}

	function getYearData($year, $limit, $limitstart )
	{
		

		$data = array();
		$data ["year"]=$year;

		$db	=& JFactory::getDBO();

		$cfg = & JEVConfig::getInstance();

		include_once(JPATH_ADMINISTRATOR."/components/".JEV_COM_COMPONENT."/libraries/colorMap.php");

		$data ["limit"] = $limit;

		if ($data ["limit"]>0){

			$counter = $this->queryModel->countIcalEventsByYear( $year,  $cfg->get('com_showrepeats'));

			$data["total"] =  $counter ;

			if( $data["total"] <= $data ["limit"] ) {
				$limitstart = 0;
			}
			$data ["limitstart"]=$limitstart;
		}
		else {
			$data["total"]=0;
			$data ["limitstart"]=0;
		}

		$data["months"]=array();
		$rows = $this->queryModel->listIcalEventsByYear( $year, $data ["limitstart"], $data ["limit"],  $cfg->get('com_showrepeats'));
		$num_events = count( $rows );

		for($month = 1; $month <= 12; $month++) {
			$data["months"][$month] = array();
			$data["months"][$month]["rows"] = array();
			for( $r = 0; $r < $num_events; $r++ ) {
				$row =& $rows[$r];
				if (($month == $row->mup() && $row->yup()==$year) || ($month==1 && $row->yup()<$year)){
					$count = count($data["months"][$month]["rows"]);
					$data["months"][$month]["rows"][$count] = $row;
				}
			}
		}

		//
		//include_once(JPATH_BASE."/components/".JEV_COM_COMPONENT."/libraries/iCalImport.php");
		//iCalHelper::getHolidayDataForYear($data, "USHolidays.ics");

		return $data;
	}

	function getRangeData($start,$end, $limit, $limitstart ,  $order="rpt.startrepeat asc, rpt.endrepeat ASC, det.summary ASC")
	{
		
		$data = array();

		$db	=& JFactory::getDBO();

		$cfg = & JEVConfig::getInstance();

		include_once(JPATH_ADMINISTRATOR."/components/".JEV_COM_COMPONENT."/libraries/colorMap.php");

		$data ["limit"] = $limit;

		if ($data ["limit"]>0){

			$counter = $this->queryModel->countIcalEventsByRange( $start,$end,  $cfg->get('com_showrepeats'));

			$data["total"] =  $counter ;

			if( $data["total"] <= $data ["limit"] ) {
				$limitstart = 0;
			}
			$data ["limitstart"]=$limitstart;
		}
		else {
			$data["total"]=0;
			$data ["limitstart"]=0;
		}

		$data["rows"] = $this->queryModel->listIcalEventsByRange( $start,$end, $data ["limitstart"], $data ["limit"],  $cfg->get('com_showrepeats'), $order);

		return $data;
	}

	/**
	 * gets structured week data
	 *
	 * @param int $year
	 * @param int $month
	 * @param int $day
	 * @param boolean $detailedDay when true gives hour by hour data for the day $day
	 * @return unknown
	 */
	function getWeekData($year, $month, $day, $detailedDay=false) {

		
		$Itemid = JEVHelper::getItemid();
		$db	=& JFactory::getDBO();

		$cat = "";
		if ($this->catidsOut != 0){
			$cat = '&catids='.$this->catidsOut;
		}

		$cfg = & JEVConfig::getInstance();

		include_once(JPATH_ADMINISTRATOR."/components/".JEV_COM_COMPONENT."/libraries/colorMap.php");

		$data = array();
		$indate = JevDate::mktime( 0, 0, 0, $month, $day, $year) ;
		$startday 	= $cfg->get('com_starday', 0);
		$numday		= (( date( 'w', $indate) - $startday + 7) %7 );

		$week_start = JevDate::mktime( 0, 0, 0, $month, ( $day - $numday ), $year );
		$week_end = JevDate::mktime( 0, 0, 0, $month, ( $day - $numday )+6, $year ); // + 6 for inclusinve week

		$rows = $this->queryModel->listEventsByWeekNEW(JevDate::strftime("%Y-%m-%d",$week_start),JevDate::strftime("%Y-%m-%d",$week_end));

		$icalrows = $this->queryModel->listIcalEventsByWeek( $week_start, $week_end);

		$rows = array_merge($icalrows,$rows);
		$rowcount = count( $rows );

		$data['startdate']	= JEventsHTML::getDateFormat( JevDate::strftime("%Y",$week_start), JevDate::strftime("%m",$week_start), JevDate::strftime("%d",$week_start), 1 );
		$data['enddate']	= JEventsHTML::getDateFormat( JevDate::strftime("%Y",$week_end), JevDate::strftime("%m",$week_end), JevDate::strftime("%d",$week_end), 1 );

		$data['days'] = array();

		for( $d = 0; $d < 7; $d++ ){
			$data['days'][$d] = array();
			$data['days'][$d]['rows'] = array();

			$this_currentdate = JevDate::mktime( 0, 0, 0, $month, ( $day - $numday + $d ), $year );

			$data['days'][$d]['week_day'] = JevDate::strftime("%d",$this_currentdate);
			$data['days'][$d]['week_month'] = JevDate::strftime("%m",$this_currentdate);
			$data['days'][$d]['week_year'] = JevDate::strftime("%Y",$this_currentdate);

			// This is really view specific - remove it later
			$data['days'][$d]['link']= JRoute::_( 'index.php?option='.JEV_COM_COMPONENT.'&task=day.listevents&year='.$data['days'][$d]['week_year'].'&month='.$data['days'][$d]['week_month'].'&day='.$data['days'][$d]['week_day'].'&Itemid='.$Itemid . $cat);

			$t_datenow = JEVHelper::getNow();
			$now_adjusted = $t_datenow->toUnix(true);
			if( JevDate::strftime('%Y-%m-%d',$this_currentdate) == JevDate::strftime('%Y-%m-%d', $now_adjusted ))
			{
				$data['days'][$d]['today']=true;
				$data['days']['today']=$d;
			}
			else
			{
				$data['days'][$d]['today']=false;
			}

			if ($detailedDay && ($this_currentdate==$indate)){
				$this->_populateHourData($data, $rows, $indate);
			}

			$num_events		= count( $rows );
			$countprint		= 0;

			for( $r = 0; $r < $num_events; $r++ ){
				$row = $rows[$r];
				if ($row->checkRepeatWeek($this_currentdate,$week_start,$week_end))  {

					$count = count($data['days'][$d]['rows']);
					$data['days'][$d]['rows'][$count] = $row;
				}
			}
			// sort events of this day by time
			usort($data['days'][$d]['rows'],array("JEventsDataModel", "_sortEventsByTime"));
		}

		return $data;
	}

	function _populateHourData(&$data, $rows, $target_date){
		$num_events			= count( $rows );

		$data['hours']=array();
		$data['hours']['timeless']=array();
		$data['hours']['timeless']['events']=array();

		// Timeless events
		for( $r = 0; $r < $num_events; $r++ ){
			$row =& $rows[$r];
			if ($row->checkRepeatDay($target_date))  {

				if ($row->alldayevent() || (!$row->noendtime() && ($row->hup()==$row->hdn() && $row->minup()==$row->mindn() && $row->sup()==$row->sdn()))){
					$count = count($data['hours']['timeless']['events']);
					$data['hours']['timeless']['events'][$count]=$row;
				}
			}
		}

		for ($h=0;$h<24;$h++){
			$data['hours'][$h]=array();
			$data['hours'][$h]['hour_start'] = $target_date+($h*3600);
			$data['hours'][$h]['hour_end'] = $target_date+59+(59*60)+($h*3600);
			$data['hours'][$h]['events'] = array();

			for( $r = 0; $r < $num_events; $r++ ){
				$row =& $rows[$r];
				if (!isset($row->alreadyHourSlotted) && $row->checkRepeatDay($target_date))  {
					if ($row->alldayevent() || (!$row->noendtime() && ($row->hup()==$row->hdn() && $row->minup()==$row->mindn() && $row->sup()==$row->sdn()))){
						// Ignore timeless events
					}
					// if first hour of the day get the previous days events here!!
					else if ($h==0 && $row->getUnixStartDate()<$target_date){
						$count = count($data['hours'][$h]['events']);
						$data['hours'][$h]['events'][$count]=$row;
						$row->alreadyHourSlotted = 1;
					}
					else if ($row->hup()==$h && $row->minup()<=59 && $row->sup()<=59){

						$count = count($data['hours'][$h]['events']);
						$data['hours'][$h]['events'][$count]=$row;
						$row->alreadyHourSlotted = 1;
					}

				}
			}
			// sort events of this day by time
			usort($data['hours'][$h]['events'],array("JEventsDataModel", "_sortEventsByTime"));
		}

	}

	function getDayData($year, $month, $day) {
		

		include_once(JPATH_ADMINISTRATOR."/components/".JEV_COM_COMPONENT."/libraries/colorMap.php");

		$data = array();

		$target_date = JevDate::mktime(0,0,0,$month,$day,$year);
		$rows	= $this->queryModel->listEventsByDateNEW( JevDate::strftime("%Y-%m-%d",$target_date ));
		$icalrows = $this->queryModel->listIcalEventsByDay($target_date);

		$rows = array_merge($icalrows,$rows);

		$this->_populateHourData($data, $rows, $target_date);

		return $data;
	}

    function  getDayArticles($year, $month, $day) {
        $cats = array( "2", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18");
        $db = & JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("*")
            ->from("#__content")
            ->where("catid in (".implode(",", $cats).")")
            ->where("YEAR(publish_up) = ".$year)
            ->where("MONTH(publish_up) = ".$month)
            ->where("DAY(publish_up) = ".$day)
            ->where("state = 1");

        $db->setQuery((string)$query);
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
        }
        $arts = $db->loadObjectList();

        return $arts;
    }

	function getEventData( $rpid, $jevtype, $year, $month, $day, $uid="" ) {
		$data = array();

		
		$pop = intval(JRequest::getVar( 'pop', 0 ));
		$Itemid = JEVHelper::getItemid();
		$db	=& JFactory::getDBO();

		$cfg = & JEVConfig::getInstance();

		$row = $this->queryModel->listEventsById ($rpid, 1, $jevtype);  // include unpublished events for publishers and above

		// if the event is not published then make sure the user can edit or publish it or created it before allowing it to be seen!
		if ($row && !$row->published()) {
			$user= JFactory::getUser();
			if ($user->id!=$row->created_by() && !JEVHelper::canEditEvent($row)  && !JEVHelper::canPublishEvent($row)  && !JEVHelper::isAdminUser($user) ) {
				$row=null;
			}
		}
		
		$num_row = count($row);

		// No matching rows - use uid as alternative
		if ($num_row==0 && strlen($uid)>0){
			$rpid = $this->queryModel->findMatchingRepeat($uid, $year, $month, $day);
			if (isset($rpid) && $rpid>0){
				$row = $this->queryModel->listEventsById ($rpid, 1, $jevtype);  // include unpublished events for publishers and above
				$num_row = count($row);
			}
		}
		
		if( $num_row ){

			// process the new plugins
			$dispatcher	=& JDispatcher::getInstance();
			$dispatcher->trigger('onGetEventData', array (& $row));

			$params =new JParameter(null);
			$row->contactlink = JEventsHTML::getUserMailtoLink( $row->id(), $row->created_by() );

			$event_up = new JEventDate( $row->publish_up() );
			$row->start_date = JEventsHTML::getDateFormat( $event_up->year, $event_up->month, $event_up->day, 0 );
			$row->start_time = JEVHelper::getTime($row->getUnixStartTime() );

			$event_down = new JEventDate( $row->publish_down() );
			$row->stop_date = JEventsHTML::getDateFormat( $event_down->year, $event_down->month, $event_down->day, 0 );
			$row->stop_time = JEVHelper::getTime($row->getUnixEndTime() );
			$row->stop_time_midnightFix = $row->stop_time ;
			$row->stop_date_midnightFix = $row->stop_date ;
			if ($event_down->second == 59){
				$row->stop_time_midnightFix = JEVHelper::getTime($row->getUnixEndTime() +1 );
				$row->stop_date_midnightFix = JEventsHTML::getDateFormat( $event_down->year, $event_down->month, $event_down->day+1 , 0);
			}

			// *******************
			// ** This cloaking should be done by mambot/Joomla function
			// *******************

			// Parse http and  wrap in <a> tag
			// trigger content plugin

			$pattern = '[a-zA-Z0-9&?_.,=%\-\/]';

			// Addresse
			if (!is_numeric($row->location())){
				// don't convert address that already has a link tag
				if (strpos($row->location(),'<a href=')===false){
					$row->location(preg_replace('#(http://)('.$pattern.'*)#i', '<a href="\\1\\2">\\1\\2</a>', $row->location()));
				}
				$tmprow = new stdClass();
				$tmprow->text = $row->location();

				$dispatcher	=& JDispatcher::getInstance();
				JPluginHelper::importPlugin('content');

				if (JVersion::isCompatible("1.6.0")) {
					$dispatcher->trigger( 'onContentPrepare', array('com_jevents', &$tmprow, &$params, 0 ));
				}
				else {
					$dispatcher->trigger( 'onPrepareContent', array( &$tmprow, &$params, 0 ));
				}
				$row->location($tmprow->text);
			}
			
			//Contact
			if (strpos($row->contact_info(),'<a href=')===false){
				$row->contact_info(preg_replace('#(http://)('.$pattern.'*)#i', '<a href="\\1\\2">\\1\\2</a>', $row->contact_info()));
			}
			$tmprow = new stdClass();
			$tmprow->text = $row->contact_info();

			if (JVersion::isCompatible("1.6.0")) {
				$dispatcher->trigger( 'onContentPrepare', array('com_jevents', &$tmprow, &$params, 0 ));
			}
			else {
				$dispatcher->trigger( 'onPrepareContent', array( &$tmprow, &$params, 0 ));
			}
			$row->contact_info($tmprow->text);

			//Extra
			if (strpos($row->extra_info(),'<a href=')===false){
				$row->extra_info(preg_replace('#(http://)('.$pattern.'*)#i', '<a href="\\1\\2">\\1\\2</a>', $row->extra_info()));
			}
			//$row->extra_info(eregi_replace('[^(href=|href="|href=\')](((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)','\\1', $row->extra_info()));
			$tmprow = new stdClass();
			$tmprow->text = $row->extra_info();

			if (JVersion::isCompatible("1.6.0")) {
				$dispatcher->trigger( 'onContentPrepare', array('com_jevents', &$tmprow, &$params, 0 ));
			}
			else {
				$dispatcher->trigger( 'onPrepareContent', array( &$tmprow, &$params, 0 ));
			}
			$row->extra_info($tmprow->text);

			$mask = JFactory::getApplication()->getCfg( 'hideAuthor' ) ? MASK_HIDEAUTHOR : 0;
			$mask |= JFactory::getApplication()->getCfg( 'hideCreateDate' ) ? MASK_HIDECREATEDATE : 0;
			$mask |= JFactory::getApplication()->getCfg( 'hideModifyDate' ) ? MASK_HIDEMODIFYDATE : 0;

			$mask |= JFactory::getApplication()->getCfg( 'hidePdf' ) ? MASK_HIDEPDF : 0;
			$mask |= JFactory::getApplication()->getCfg( 'hidePrint' ) ? MASK_HIDEPRINT : 0;
			$mask |= JFactory::getApplication()->getCfg( 'hideEmail' ) ? MASK_HIDEEMAIL : 0;

			//$mask |= JFactory::getApplication()->getCfg( 'vote' ) ? MASK_VOTES : 0;
			$mask |= JFactory::getApplication()->getCfg( 'vote' ) ? (MASK_VOTES|MASK_VOTEFORM) : 0;
			$mask |= $pop ? MASK_POPUP | MASK_IMAGES | MASK_BACKTOLIST : 0;

			// Do main mambot processing here
			// process bots
			//$row->text      = $row->content;
			$params->set("image",1);
			$row->text = $row->content();

			if (JVersion::isCompatible("1.6.0")) {
				$dispatcher->trigger( 'onContentPrepare', array('com_jevents', &$row, &$params, 0 ));
			}
			else {
				$dispatcher->trigger( 'onPrepareContent', array( &$row, &$params, 0 ));
			}
			$row->content( $row->text );

			$data['row']=$row;
			$data['mask']=$mask;

			$row->updateHits();

			return $data;

		}
		else {
			// Do we have to be logged in to see this event?
			// If we set the access user for ical export (as an example) then use this user id for access checks!
			$user = JFactory::getUser(isset($this->accessuser)?$this->accessuser:null);
			if ($user->id==0)
			{
				$db=JFactory::getDBO();
				$query = "SELECT ev.*"
				. "\n FROM #__jevents_vevent as ev "
				. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
				. "\n WHERE rpt.rp_id = '$rpid'";
				$db->setQuery($query);
				$row2 = $db->loadObject();
				// need to be logged in to see this event?
				if ($row2 && (version_compare(JVERSION, '1.6.0', '>=') ? !in_array($row2->access, JEVHelper::getAid($user, 'array')) : JEVHelper::getAid($user) < $row2->access)){
					$uri = JURI::getInstance();
					$link = $uri->toString();
					$comuser= version_compare(JVERSION, '1.6.0', '>=') ? "com_users":"com_user";
					$link = 'index.php?option='.$comuser.'&view=login&return='.base64_encode($link);
					$link = JRoute::_($link);
					
					JFactory::getApplication()->redirect($link,JText::_('JEV_LOGIN_TO_VIEWEVENT'));
					return null;
				}
			}

			// See if a plugin can find our missing event - maybe on another menu item
			JPluginHelper::importPlugin('jevents');
			$dispatcher	=& JDispatcher::getInstance();
			$dispatcher->trigger('onMissingEvent', array (& $row,$rpid, $jevtype, $year, $month, $day, $uid));

			return null;
		}
	}

	function accessibleCategoryList($aid=null, $catids=null, $catidList=null){
		return $this->queryModel->accessibleCategoryList($aid, $catids, $catidList);
	}

	function getCatData( $catids, $showRepeats=true, $limit=0, $limitstart=0, $order="rpt.startrepeat asc, rpt.endrepeat ASC, det.summary ASC"){
		$data = array();

		$Itemid = JEVHelper::getItemid();
		$db	=& JFactory::getDBO();
		include_once(JPATH_ADMINISTRATOR."/components/".JEV_COM_COMPONENT."/libraries/colorMap.php");

		$cfg = & JEVConfig::getInstance();

		$counter = $this->queryModel->countEventsByCat( $catids);
		$total = $this->queryModel->countIcalEventsByCat( $catids,$showRepeats);
		$counter += $total;

		$data ['total'] =  $counter ;
		//$limit = $limit ? $limit : $cfg->get('com_calEventListRowsPpg');

		if ( $data ['total']  <= $limit  || $limitstart > $data ['total']) {
			$limitstart = 0;
		}
		$data['limit']=$limit;
		$data['limitstart']=$limitstart;

		$rows 	  = $this->queryModel->listEventsByCat( $catids, $limitstart, $limit );
		$icalrows = $this->queryModel->listIcalEventsByCat( $catids,$showRepeats,$total, $limitstart, $limit , $order);

		$rows = array_merge($icalrows,$rows);
		$num_events = count( $rows );

		if (count($catids)==0 || (count($catids)==1 && $catids[0]=="")){
			// We are using the filter instead
			$tempcat = JRequest::getVar("category_fv",0);
			$catids = array();
			$catids[] = $tempcat;
		}
		else {
			// Override multiple categories using the filter instead
			
			$tempcat =JFactory::getApplication()->getUserStateFromRequest( 'category_fv_ses', 'category_fv', 0);
			if ($tempcat>0){
				$catids = array();
				$catids[] = $tempcat;
			}
		}
		$catdesc = "";
		$catname = "";
		if (count($catids)>1){
			$catname = JText::_('JEV_EVENT_CHOOSE_CATEG');
		}
                // should not use the category data since it coujld be a sub category
		if( false && $num_events > 0){
			if ((count($catids)==1 && $catids[0]!=0)  || (count($this->catids)==1 && $this->catids[0]!=0)  ){
				$catname = $rows[0]->getCategoryName();
				$catdesc = $rows[0]->getCategoryDescription();
				foreach ($rows as $row) {
					if ($row->getCategoryName() != $catname){
						$catname = JText::_('JEV_EVENT_CHOOSE_CATEG');
						break;
					}
				}
			}
			else {
				$catname = "";
				$catdesc = "";
			}
		}
		else if( count($catids) == 0 ) {
			$catname = JText::_('JEV_EVENT_CHOOSE_CATEG');
		}
		else if ((count($catids)==1 && $catids[0]!=0)  || (count($this->catids)==1 && $this->catids[0]!=0)  ){
			// get the cat name from the database
			$db	= JFactory::getDBO();
			$user = JFactory::getUser();
			$catid = (count($catids)==1 && $catids[0]!=0)  ? intval($catids[0]) : $this->catids[0];
			$catsql = 'SELECT c.title, c.description FROM #__categories AS c' .
			' WHERE c.access  ' . (version_compare(JVERSION, '1.6.0', '>=') ?  ' IN (' . JEVHelper::getAid($user) . ')'  :  ' <=  ' . JEVHelper::getAid($user)) .
			' AND c.'.(JVersion::isCompatible("1.6.0")?'extension':'section').' = '.$db->Quote(JEV_COM_COMPONENT).
			' AND c.id = '.$db->Quote($catid);
			$db->setQuery($catsql);
			$catdata = $db->loadObject();
			if ($catdata){
				$catname = $catdata->title;
				$catdesc = $catdata->description;
			}
			else {
				$catname = JText::_('JEV_EVENT_ALLCAT');
			}
		}
		$data['catname']=$catname;
		$data['catdesc']=$catdesc;
		$data['catids']=$catids;


		if( $num_events > 0 ){
			for( $r = 0; $r < $num_events; $r++ ){
				$row =& $rows[$r];
				if ($row->catname()==""){
					$row->catname($catname); // for completeness of dataset
				}
			}
		}
		$data['rows']=$rows;

		return $data;
	}

	function getKeywordData(&$keyword, $limit, $limitstart, $useRegX=true)
	{
		$data = array();

		$lang = JFactory::getLanguage();

		$user = JFactory::getUser();
		$Itemid = JEVHelper::getItemid();
		$db	= JFactory::getDBO();

		$cfg = & JEVConfig::getInstance();

		include_once(JPATH_ADMINISTRATOR."/components/".JEV_COM_COMPONENT."/libraries/colorMap.php");

		$keyword		= preg_replace( "/[[:space:]]+/", ' +', $keyword );
		$keyword		= trim( $keyword );
		$keyword		= preg_replace( "/\++/", '+', $keyword );
		$keywordcheck	= preg_replace( "/ |\+/", '', $keyword );
		$searchisValid	= false;
		$total			= 0;

		if( empty( $keyword ) || JString::strlen( $keywordcheck ) < 3 || $keyword == '%%' || $keywordcheck == '' ) {
			$keyword 	= JText::_('JEV_KEYWORD_NOT_VALID');
			$num_events = 0;
			$rows = array();
			$data['total'] = 0;
			$data['limit']=0;
			$data['limitstart']=0;
			$data['num_events']=0;
		} else {
			$searchisValid = true;

			$rows 		= $this->queryModel->listEventsByKeyword( $keyword, 'publish_up,catid', $limit, $limitstart, $total, $useRegX );
			$data['total'] = $total;
			$data['limit']=$limit;
			$data['limitstart']=$limitstart;

			$num_events = count( $rows );
			$data['num_events']=$num_events;
		}

		$chdate	= '';
		$chcat	= '';

		if( $num_events > 0 ){
			for( $r = 0; $r < $num_events; $r++ ){
				$row =& $rows[$r];

				$row->catname		= $row->getCategoryName( );
				$row->contactlink	= JEventsHTML::getUserMailtoLink( $row->id(), $row->created_by() );
				$row->bgcolor		= JEV_CommonFunctions::setColor($row);
				$row->fgcolor		= JevMapColor($row->bgcolor);

				$t_datenow = JEVHelper::getNow();
				$now_adjusted = $t_datenow->toUnix(true);
				if( $row->mup() == JevDate::strftime( '%m', $now_adjusted)	&&
				$row->yup() == JevDate::strftime( '%Y', $now_adjusted )&&
				$row->dup() == JevDate::strftime( '%d', $now_adjusted))
				{
					$row->today=true;
				}
				else
				{
					$row->today=false;
				}
			}
		}

		$data['rows']=$rows;

		$keyword = htmlspecialchars($keyword);
		
		return $data;
	}

	function getDataForAdmin( $creator_id, $limit, $limitstart, $showrepeats = false, $orderby="" ){

		$data= array();

		$is_event_editor = JEVHelper::isEventCreator();
		$user = JFactory::getUser();
		$Itemid = JEVHelper::getItemid();

		$db	= JFactory::getDBO();		

		$cfg = & JEVConfig::getInstance();

		include_once(JPATH_ADMINISTRATOR."/components/".JEV_COM_COMPONENT."/libraries/colorMap.php");

		// Note that these are the vevents not the repeats
		if (!$showrepeats) $total = $this->queryModel->countIcalEventsByCreator ($creator_id);
		else  $total = $this->queryModel->countIcalEventRepeatsByCreator ($creator_id);

		$data['total']=$total;

		$data['limit']=$limit;
		if( $data["total"] <= $data ["limit"] ) {
			$limitstart = 0;
		}
		$data ["limitstart"]=$limitstart;

		// Note that these are the vevents not the repeats
		if (!$showrepeats) $rows = $this->queryModel->listIcalEventsByCreator ($creator_id, $limitstart, $limit, $orderby);
		else $rows = $this->queryModel->listIcalEventRepeatsByCreator ($creator_id, $limitstart, $limit, $orderby);

		$adminView = true;
		$num_events = count( $rows );

		if( $num_events > 0 ){
			for( $r = 0; $r < $num_events; $r++ ) {
				$row =& $rows[$r];

				$row->catname($row->getCategoryName());
				$row->contactlink( JEventsHTML::getUserMailtoLink( $row->id(), $row->created_by(), true));
				$row->bgcolor		= JEV_CommonFunctions::setColor($row);
				$row->fgcolor		= JevMapColor($row->bgcolor);

			}
		}

		$data['rows']=$rows;
		return $data;
	}


	function getAdjacentMonth($data, $direction=1)
	{
		$cfg = & JEVConfig::getInstance();
		$monthResult = array();
		$d1 = JevDate::mktime(0,0,0,intval($data['month'])+$direction,1,$data['year']);
		$monthResult['day1'] = $d1;
		$monthResult['lastday'] = date("t",$d1);
		$year = JevDate::strftime("%Y",$d1);
		
		$cfg = & JEVConfig::getInstance();
		$earliestyear =  $cfg->get('com_earliestyear');
		$latestyear = $cfg->get('com_latestyear');
		if ($year>$latestyear || $year<$earliestyear){
			return false;
		}
		
		$monthResult['year'] = $year;
		$month = JevDate::strftime("%m",$d1);
		$monthResult['month'] = $month;
		$monthResult['name'] = JEVHelper::getMonthName($month);
		$task = JRequest::getString('jevtask');
		$Itemid = JEVHelper::getItemid();
		if (isset($Itemid)) $item= "&Itemid=$Itemid";
		else $item="";
		// URL suffix to preserver catids!
		$cat = $this->getCatidsOutLink();
		$monthResult['link'] = JRoute::_("index.php?option=".JEV_COM_COMPONENT."&task=$task$item&year=$year&month=$month".$cat);
		return $monthResult;
	}

	function getPrecedingMonth($data)
	{
		return 	$this->getAdjacentMonth($data,-1);
	}
	function getFollowingMonth($data)
	{
		return 	$this->getAdjacentMonth($data,+1);
	}

	function getAdjacentWeek($year,$month,$day, $direction=1)
	{
		$d1 = JevDate::mktime(0,0,0,$month,$day+$direction*7,$year);
		$day = JevDate::strftime("%d",$d1);
		$year = JevDate::strftime("%Y",$d1);
		
		$cfg = & JEVConfig::getInstance();
		$earliestyear =  $cfg->get('com_earliestyear');
		$latestyear = $cfg->get('com_latestyear');
		if ($year>$latestyear || $year<$earliestyear){
			return false;
		}
		
		$month = JevDate::strftime("%m",$d1);
		$task = JRequest::getString('jevtask');
		$Itemid = JEVHelper::getItemid();
		if (isset($Itemid)) $item= "&Itemid=$Itemid";
		else $item="";
		// URL suffix to preserver catids!
		$cat = $this->getCatidsOutLink();
		return JRoute::_("index.php?option=".JEV_COM_COMPONENT."&task=$task$item&year=$year&month=$month&day=$day".$cat);
	}
	function getPrecedingWeek($year,$month,$day)
	{
		return 	$this->getAdjacentWeek($year,$month,$day,-1);
	}
	function getFollowingWeek($year,$month,$day)
	{
		return 	$this->getAdjacentWeek($year,$month,$day,+1);
	}
	function getAdjacentDay($year,$month,$day, $direction=1)
	{
		$d1 = JevDate::mktime(0,0,0,$month,$day+$direction,$year);
		$day = JevDate::strftime("%d",$d1);
		$year = JevDate::strftime("%Y",$d1);
		
		$cfg = & JEVConfig::getInstance();
		$earliestyear =  $cfg->get('com_earliestyear');
		$latestyear = $cfg->get('com_latestyear');
		if ($year>$latestyear || $year<$earliestyear){
			return false;
		}
		
		$month = JevDate::strftime("%m",$d1);
		$task = JRequest::getString('jevtask');
		$Itemid = JEVHelper::getItemid();
		if (isset($Itemid)) $item= "&Itemid=$Itemid";
		else $item="";
		// URL suffix to preserver catids!
		$cat = $this->getCatidsOutLink();
		return JRoute::_("index.php?option=".JEV_COM_COMPONENT."&task=$task$item&year=$year&month=$month&day=$day".$cat);
	}
	function getPrecedingDay($year,$month,$day)
	{
		return 	$this->getAdjacentDay($year,$month,$day,-1);
	}
	function getFollowingDay($year,$month,$day)
	{
		return 	$this->getAdjacentDay($year,$month,$day,+1);
	}

	/**
	 * Sort Events by time, use only for events of the same day
	 *
	 * @static
	 * @param object $a
	 * @param object $b
	 * @return boolean
	 */
	function _sortEventsByTime (&$a, &$b) {
		// this custom sort compare function compares the start times of events that are referenced by the a & b vars
		//if ($a->publish_up() == $b->publish_up()) return 0;

		list( $adate, $atime ) = explode( ' ', $a->publish_up() );
		list( $bdate, $btime ) = explode( ' ', $b->publish_up() );
		
		// allday events first, equal time sort by title
		$atime = $a->alldayevent() ? '00:00'.$a->title() : $atime.$a->title();
		$btime = $b->alldayevent() ? '00:00'.$b->title() : $btime.$b->title();
		return strcmp( $atime, $btime );

	}
}
