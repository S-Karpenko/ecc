<?php
defined('_JEXEC') or die('Restricted access');

$cfg	 = & JEVConfig::getInstance();

$this->data = $data = $this->datamodel->getDayData( $this->year, $this->month, $this->day );
$articles = $this->datamodel->getDayArticles($this->year, $this->month, $this->day );


$cfg = & JEVConfig::getInstance();
$Itemid = JEVHelper::getItemid();
$cfg = & JEVConfig::getInstance();
$hasevents = false;

echo '<fieldset><legend class="ev_fieldset">' . JText::_('JEV_EVENTSFORTHE') .'</legend><br />' . "\n";
echo '<table align="center" width="100%" cellspacing="0" cellpadding="5" class="ev_table">' . "\n";
?>
    <tr valign="top">
        <td colspan="2"  align="center" class="cal_td_daysnames">
           <!-- <div class="cal_daysnames"> -->
            <?php echo JEventsHTML::getDateFormat( $this->year, $this->month, $this->day, 0) ;?>
            <!-- </div> -->
        </td>
    </tr>
<?php
// Timeless Events First
if (count($data['hours']['timeless']['events'])>0){
	$start_time = JText::_( 'TIMELESS' );
        $hasevents = true;

	echo '<tr><td class="ev_td_left">' . $start_time . '</td>' . "\n";
	echo '<td class="ev_td_right"><ul class="ev_ul">' . "\n";
	foreach ($data['hours']['timeless']['events'] as $row) {
		$listyle = 'style="border-color:'.$row->bgcolor().';"';
		echo "<li class='ev_td_li' $listyle>\n";

		if (!$this->loadedFromTemplate('icalevent.list_row', $row, 0)){
			$this->viewEventRowNew ( $row);
			echo '&nbsp;::&nbsp;';
			$this->viewEventCatRowNew($row);
		}
		echo "</li>\n";
	}
	echo "</ul></td></tr>\n";
}

for ($h=0;$h<24;$h++){
	if (count($data['hours'][$h]['events'])>0){
                $hasevents = true;
		$start_time = JEVHelper::getTime($data['hours'][$h]['hour_start']);

		echo '<tr>' . "\n";
		echo '<td class="ev_td_right"><ul class="ev_ul">' . "\n";
		foreach ($data['hours'][$h]['events'] as $row) {
			$listyle = 'style="border-color:'.$row->bgcolor().';"';
			echo "<li class='ev_td_li' $listyle>\n";

			if (!$this->loadedFromTemplate('icalevent.list_row', $row, 0)){
				$this->viewEventRowNew ( $row);
				echo '&nbsp;::&nbsp;';
				$this->viewEventCatRowNew($row);
			}
			echo "</li>\n";
		}
		echo "</ul></td></tr>\n";
	}
}
if (!$hasevents) {
		echo '<tr><td class="ev_td_right" colspan="3"><ul class="ev_ul" style="list-style: none;">' . "\n";
		echo "<li class='ev_td_li' style='border:0px;'>\n";
		echo JText::_('JEV_NO_EVENTS') ;
		echo "</li>\n";
		echo "</ul></td></tr>\n";
}
echo '</table><br />' . "\n";
echo '</fieldset><br /><br />' . "\n";
//  $this->showNavTableText(10, 10, $num_events, $offset, '');
?>

<?php if (count($articles) > 0) { ?>
<h4 class="articles-title">Статті</h4>
<table class="ev_table" width="100%" cellspacing="0" cellpadding="5" align="center">
    <?php $counter = 0; ?>
    <?php foreach($articles as $item) { ?>
        <tr <?php if ($counter % 2 === 0) { ?> class="odd"<?php } ?>>
            <td class="ev_td_right">
                <?php echo date("d.m.Y", strtotime($item->publish_up));?>
            </td>
            <td class="ev_td_right">
                <?php
                    $item->slug = $item->id.':'.$item->alias;
                    $item->catslug = $item->catid ? $item->catid .':'.$item->category_alias : $item->catid;
                ?>
                <a class="article-link" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));?>" title="<?php echo $item->title; ?>">
                    <?php echo $item->title; ?>
                </a>
            </td>
        </tr>
    <?php
        $counter++;
    } ?>
</table>
<?php } ?>