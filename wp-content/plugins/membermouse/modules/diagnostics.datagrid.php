<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
 
// get data based on filters and datagrid settings
$view = new MM_DiagnosticsView();
$dataGrid = new MM_DataGrid($_REQUEST, "event_date", "desc",100);
$data = $view->filter($_REQUEST, $dataGrid);

$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "event";


// define datagrid headers
$headers = array
(
		'type'	      => array('content' => '<a onclick="mmjs.sort(\'type\');" href="#">Type</a>'),
		'ip_address'  => array('content' => '<a onclick="mmjs.sort(\'ip_address\');" href="#">IP</a>'),
		'session'	  => array('content' => '<a onclick="mmjs.sort(\'session\');" href="#">Session</a>'),
		'location'	  => array('content' => '<a onclick="mmjs.sort(\'location\');" href="#">Location</a>'),
		'line'	      => array('content' => '<a onclick="mmjs.sort(\'line\');" href="#">Line</a>'),
		'event'		  => array('content' => '<a onclick="mmjs.sort(\'event\');" href="#">Event Data</a>'),
		'event_date'  => array('content' => '<a onclick="mmjs.sort(\'event_date\');" href="#">Timestamp</a>'),
);

$rows = array();
// process data
foreach($data as $item)
{
	switch ($item->type)
	{
		case 'mm-error':
			$eventType = "<i class='fa fa-times-circle' style='color:#ff0000' title='error response'></i>\n";
			break;
		case 'php-error':
			$eventType = "<i class='fa fa-code' style='color:#ff0000' title='php error'></i>\n";
			break;
		case 'php-warning':
			$eventType = "<i class='fa fa-exclamation-triangle' style='color:#eeee00' title='php warning'></i>\n";
			break;
		case 'mm-success':
		default:
			$eventType = "<i class='fa fa-thumbs-o-up' style='color:#00ee00' title='success response'></i>\n";
				break;
	}	

	$eventTimestamp = date("m-d-Y H:i:s",strtotime($item->event_date));
	$eventDataFull = htmlentities($item->event,ENT_QUOTES);
	$eventDataBlurb = substr($eventDataFull,0,255);
    
    $rows[] = array
    (
    	array( 'content' => $eventType),
    	array( 'content' => $item->ip_address),
    	array( 'content' => $item->session),
    	array( 'content' => $item->location),
    	array( 'content' => $item->line),
    	array( 'content' => "<span title='{$eventDataFull}'>{$eventDataBlurb}</span>\n"),
    	array( 'content' => $eventTimestamp),
    );
}


$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if(empty($dgHtml)) 
{
	$dgHtml = "<p><i>No events found.</i></p>";
}

echo $dgHtml;
?>