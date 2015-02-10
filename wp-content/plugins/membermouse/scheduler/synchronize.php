<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

require_once("../../../../wp-load.php");
require_once("../includes/mm-constants.php");
require_once("../includes/init.php");

// Send connection close to allow the caller to continue processing
// ----------------------------------------------------------------
MM_ConnectionUtils::closeConnectionAndContinueProcessing();

// Set operating parameters
// ----------------------------------------------------------------
$maxExecutionTime = 600; //in seconds
$maxBatchSize = 100; 
$expiredBatchTime = gmdate("Y-m-d H:i:s",strtotime("-{$maxExecutionTime} seconds",time()));
$batchIdentifier = MM_TransactionKey::createRandomIdentifier(); 

// Begin synchronization
// ----------------------------------------------------------------
set_time_limit($maxExecutionTime);
$queueEmpty = false; 
$queTable = MM_TABLE_QUEUED_SCHEDULED_EVENTS;
$eventTable = MM_TABLE_SCHEDULED_EVENTS;

LogMe::write("Beginning Scheduled Event Queue Synchronization at ".gmdate("Y-m-d H:i:s",time()));

try 
{
	do 
	{
		$currentTime = gmdate("Y-m-d H:i:s",time());
		//make this call atomic
		$lockAcquired = $wpdb->get_var("SELECT COALESCE(GET_LOCK('synchronize_mm_scheduler',10),0)");
		if ($lockAcquired != "1")
		{
			throw new Exception("Scheduler sync: Could not acquire lock");
		}
		$markBatchSQL = "UPDATE {$queTable} SET batch_id='{$batchIdentifier}', batch_started='{$currentTime}' WHERE (batch_id IS NULL) OR ((batch_id != '{$batchIdentifier}') AND (batch_started < '{$expiredBatchTime}')) LIMIT {$maxBatchSize}";
		$res = $wpdb->query($markBatchSQL);
		if ($res !== false)
		{
			//release the lock, in case any other sync processes are waiting
			$wpdb->query("SELECT RELEASE_LOCK('synchronize_mm_scheduler')");
			
			//read in the batch 
			$batchSQL = "SELECT q.*, e.scheduled_date FROM {$queTable} q LEFT JOIN {$eventTable} e ON (q.event_id = e.id) WHERE (q.batch_id='{$batchIdentifier}') AND (q.batch_started = '{$currentTime}') AND (e.id IS NOT NULL)";
			$queuedEvents = $wpdb->get_results($batchSQL);
			$updates = array();
			$deletes = array();
			foreach ($queuedEvents as $queuedEvent)
			{
				if ($queuedEvent->command == MM_ScheduledEvent::$QUEUE_COMMAND_UPDATE)
				{
					$updates[$queuedEvent->event_id] = $queuedEvent->scheduled_date;
				}
				else if ($queuedEvent->command == MM_ScheduledEvent::$QUEUE_COMMAND_DELETE)
				{
					$deletes[] = $queuedEvent->event_id;
				}
			}
			
			if ((count($updates) > 0) || (count($deletes) > 0))
			{
				//construct JSON message and send to server
				$schedulerCommand = "BATCH_SYNC";
				$license = new MM_License("",false);
				MM_MemberMouseService::getLicense($license);
				
				$messageArray = array("api_key"        => $license->getApiKey(),
									  "api_secret"     => $license->getApiSecret(),
									  "command"		   => $schedulerCommand,
									  "updates" 	   => $updates,
									  "deletes"		   => $deletes
				);		
				$message = json_encode($messageArray);
				
				$response = MM_Utils::sendRequest(MM_SCHEDULING_SERVER_URL, $message);
				if ($response === false)
				{
					//can't contact the scheduling server, throw an exception and let the next sync pick up this batch after it expires
					throw new Exception("Scheduler sync: Error communicating with scheduling server");
				}
				
				//decode response and determine if its ok, partial_error, or error status, then delete records from queue accordingly
				$response = json_decode($response);
				if (is_null($response)) 
				{
					throw new Exception("Scheduler sync: Invalid response received from server during sync operation");
				}
				else 
				{
					//There is a valid response from the server, determine the status and respond appropriately
					if ($response->status == "ok")
					{
						//all updates were successful, delete the successes from the que table and exit
						$wpdb->query("DELETE FROM {$queTable} WHERE batch_id = '{$batchIdentifier}'");
					}
					else if (($response->status == "partial_error") && (is_array($response->message)))
					{
						$errorIdArray = array();
						foreach ($response->message as $errorId)
						{
							//this next should perform the same escaping as mysql_real_escape_string, without relying on the open link
							$errorIdArray[] = $wpdb->prepare("%s",$errorId);
						}
						$errorIdString = implode(",",$errorIdArray);
						$wpdb->query("DELETE FROM {$queTable} WHERE (batch_id='{$batchIdentifier}') AND (event_id NOT IN ({$errorIdString}))");	
					}
					else if ($response->status == "error")
					{
						//entire batch errored, do not remove any of them from the que table (ie. do nothing)
					}
				}
			}
		}
		else 
		{
			$error = "Scheduler sync: Local database error encountered while synchronizing with scheduler".!empty($wpdb->last_error)?":{$wpdb->last_error}":"";
			throw new Exception($error);
		}
		
		//check to see if there are any more events left
		$expiredBatchTime = gmdate("Y-m-d H:i:s",strtotime("-{$maxExecutionTime} seconds",time()));
		$remainingEvents = $wpdb->get_var("SELECT EXISTS (SELECT * FROM {$queTable} WHERE (batch_id IS NULL) OR ((batch_id != '{$batchIdentifier}') AND (batch_started < '{$expiredBatchTime}')))");
		$queueEmpty = (is_null($remainingEvents) || ($remainingEvents == "0")); 
	}
	while (!$queueEmpty);
	LogMe::write("Completed Scheduled Event Queue Synchronization at ".gmdate("Y-m-d H:i:s",time()));
}
catch (Exception $e)
{
	LogMe::write($e->getMessage());
	exit;
}	
?>