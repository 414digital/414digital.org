<?php 
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

require_once("../../../../../../../wp-config.php");

$export_type = (isset($_GET['export_type'])) ? $_GET['export_type'] : "standard";
$includeBundleInfo = false;

if ($export_type == 'standard' || $export_type == 'canceled_bundles')
{
	if ((!isset($_GET['membership_id']) || !is_numeric($_GET['membership_id'])) && (!isset($_GET['bundle_id']) || !is_numeric($_GET['bundle_id'])))
	{
		exit; //must have membership ID or bundle ID
	}
	
	if(isset($_GET['membership_id']))
	{
		$includeBundleInfo = true;
		$membership = new MM_MembershipLevel();
		$membership->setId($_GET['membership_id']);
		$membership->getData();
		
		if (!$membership->isValid())
		{
			exit;
		}
		$filename = preg_replace("/([^A-za-z0-9\s])/","",strtolower($membership->getName()));
		$filename = preg_replace("/\s/","_",$filename)."_export.csv";
	}
	else if(isset($_GET['bundle_id']))
	{
		$includeBundleInfo = false;
		$bundle = new MM_Bundle($_GET['bundle_id']);
		
		if (!$bundle->isValid())
		{
			exit;
		}
		$filename = preg_replace("/([^A-za-z0-9\s])/","",strtolower($bundle->getName()));
		
		$prefix = "";
		if ($export_type == 'canceled_bundles')
		{
			$prefix = "canceled_";
		}
		
		$filename = $prefix.preg_replace("/\s/","_",$filename)."_export.csv";
	}
	else
	{
		exit;
	}
}
else if ($export_type == 'cancellation')
{
	$includeBundleInfo = true;
	$filename = "cancelled_members_export.csv";
}
else 
{
	$includeBundleInfo = true;
	$filename = "member_export.csv";
}

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");

$outstream = fopen("php://output",'w');

$user_fields = array("user_email"=>"Email", "first_name"=>"First Name", "last_name"=>"Last Name");
$bundles = array();

if($includeBundleInfo)
{
	$sql = "select id, short_name from ".MM_TABLE_BUNDLES;
	$bundle_results = $wpdb->get_results($sql, ARRAY_A);
	
	foreach ($bundle_results as $rownum=>$access_tag)
	{
		$bundles[$access_tag['short_name']] = $access_tag['short_name'];
	}
}

//output header row
$header_row = array_merge($user_fields, $bundles);
fputcsv($outstream, $header_row, ',', '"');

if(isset($_GET["membership_id"]) || $export_type == "cancellation")
{
	$sql = "SELECT u.id, u.user_email, mmu.status, mmu.first_name, mmu.last_name ";
	$sql .= "FROM ".$wpdb->users." u, ".MM_TABLE_USER_DATA." mmu WHERE (mmu.wp_user_id = u.ID) ";
	
	if ($export_type == 'cancellation')
	{
		$sql .= " AND ((mmu.status = %d) OR (mmu.status = %d))";
		$results = $wpdb->get_results($wpdb->prepare($sql, MM_Status::$PAUSED, MM_Status::$CANCELED), ARRAY_A);
	}
	else 
	{
		$sql .= " AND (mmu.membership_level_id = %d) AND ((mmu.status = %d) OR (mmu.status = %d) OR (mmu.status = %d) OR (mmu.status = %d))";
		$results = $wpdb->get_results($wpdb->prepare($sql, $membership->getId(), MM_Status::$ACTIVE, MM_Status::$PENDING_CANCELLATION, MM_Status::$LOCKED, MM_Status::$OVERDUE), ARRAY_A);
	}
}
else if(isset($_GET["bundle_id"]))
{
	$sql = "SELECT u.id, u.user_email, mmu.status, mmu.first_name, mmu.last_name ".
	"FROM {$wpdb->users} u LEFT JOIN ".MM_TABLE_USER_DATA." mmu ON (mmu.wp_user_id = u.ID) ".
	"LEFT JOIN ".MM_TABLE_APPLIED_BUNDLES." apb ON ((apb.access_type='".MM_AppliedBundle::$ACCESS_TYPE_USER."') AND (apb.access_type_id = u.ID)) ".
	"WHERE (apb.bundle_id = %d) ";
	
	if ($export_type == 'canceled_bundles')
	{
		$sql .= " AND ((apb.status = %d) OR (apb.status = %d))";
		$results = $wpdb->get_results($wpdb->prepare($sql, $_GET["bundle_id"], MM_Status::$PAUSED, MM_Status::$CANCELED), ARRAY_A);
	}
	else
	{
		$sql .= " AND ((mmu.status = %d) OR (mmu.status = %d) OR (mmu.status = %d) OR (mmu.status = %d))";
		$results = $wpdb->get_results($wpdb->prepare($sql, $_GET["bundle_id"], MM_Status::$ACTIVE, MM_Status::$LOCKED, MM_Status::$PENDING_CANCELLATION, MM_Status::$OVERDUE), ARRAY_A);
	}
}
else 
{
	exit;
}

foreach ($results as $rownum=>$member_row) 
{
	$current_row_output = array();
	foreach ($user_fields as $field_key=>$v)
	{
		$current_row_output[] = $member_row[$field_key];
	}
	$member = MM_user::findByEmail($member_row['user_email']);
	
	if (!$member->isValid())
	{
		// shouldn't ever happen
		continue;
	}
	
	if($includeBundleInfo)
	{
		$appliedBundles = $member->getAppliedBundles();
		$current_member_bundles = array();
		foreach ($appliedBundles as $appliedBundle)
		{
			$bundle = $appliedBundle->getBundle();
			
			if($bundle->isValid())
			{
				$shortName = strtoupper($bundle->getShortName());
				$current_member_bundles[$shortName] = true;
			}
		}
		
		foreach ($bundles as $short_name)
		{
			$current_row_output[]  = (isset($current_member_bundles[$short_name]) && ($current_member_bundles[$short_name] === true))?"TRUE":"FALSE";
		}
	}
	
	fputcsv($outstream, $current_row_output, ',', '"');
}

fclose($outstream);
?>