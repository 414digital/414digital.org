<?php
function mm_content_data($attributes)
{
	return executeSmartTag("MM_Content_Data", $attributes);
}

function mm_content_link($attributes)
{
	return executeSmartTag("MM_Content_Link", $attributes);
}

function mm_corepage_link($attributes)
{
	return executeSmartTag("MM_CorePage_Link", $attributes);
}

function mm_customfield_data($attributes)
{
	return executeSmartTag("MM_CustomField_Data", $attributes);
}

function mm_employee_data($attributes)
{
	return executeSmartTag("MM_Employee_Data", $attributes);
}

function mm_member_data($attributes)
{
	return executeSmartTag("MM_Member_Data", $attributes);
}

function mm_member_link($attributes)
{
	return executeSmartTag("MM_Member_Link", $attributes);
}

function mm_order_data($attributes)
{
	return executeSmartTag("MM_Order_Data", $attributes);
}

function mm_purchase_link($attributes)
{
	return executeSmartTag("MM_Purchase_Link", $attributes);
}

function mm_access_decision($attributes)
{
	return executeDecisionSmartTag("MM_Access_Decision", $attributes);
}

function mm_affiliate_decision($attributes)
{
	return executeDecisionSmartTag("MM_Affiliate_Decision", $attributes);
}

function mm_custom_decision($attributes)
{
	return executeDecisionSmartTag("MM_Custom_Decision", $attributes);
}

function mm_member_decision($attributes)
{
	return executeDecisionSmartTag("MM_Member_Decision", $attributes);
}

function mm_order_decision($attributes)
{
	return executeDecisionSmartTag("MM_Order_Decision", $attributes);
}

/**
 * This function executes a [MM_..._Data] or [MM_..._Link] SmartTag
 * @param String $smartTagName the new of the SmartTag to execute
 * @param Array $attributes an associative array of name/value pairs to pass to the SmartTag
 * @return String returns the result of executing the SmartTag with the attributes passed
 */
function executeSmartTag($smartTagName, $attributes)
{
	if(is_array($attributes))
	{
		$smartTag = "[{$smartTagName}";
		foreach($attributes as $name=>$value)
		{
			$smartTag .= " {$name}='{$value}'";
		}
		$smartTag .= "]";
	
		$context = new MM_Context();
		return MM_SmartTagUtil::processContent($smartTag, $context);
	}
	else
	{
		return "";
	}
}

/**
 * This function executes a [MM_..._Decision] SmartTag
 * @param String $smartTagName the new of the SmartTag to execute
 * @param Array $attributes an associative array of name/value pairs to pass to the SmartTag
 * @return Boolean returns the result of executing the SmartTag with the attributes passed
 */
function executeDecisionSmartTag($smartTagName, $attributes)
{
	if(is_array($attributes))
	{
		$smartTag = "[{$smartTagName}";
		foreach($attributes as $name=>$value)
		{
			$smartTag .= " {$name}='{$value}'";
		}
		$smartTag .= "]success[/{$smartTagName}]";

		$context = new MM_Context();
		$result = MM_SmartTagUtil::processContent($smartTag, $context);
		return ($result == "success") ? true : false;
	}
	else
	{
		return false;
	}
}
?>