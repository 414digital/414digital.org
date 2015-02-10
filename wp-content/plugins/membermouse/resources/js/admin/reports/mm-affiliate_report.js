/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_AffiliateReportViewJS = MM_ReportJSBase.extend({
	validate: function()
	{
		var fields = { "mm-from-date":"Start Date", "mm-to-date":"End Date"};
		for (var id in fields)
		{
			if (jQuery("#"+id).val() == "")
			{
				alert("Please enter a value for " + fields[id]);
				return false;
			}
		}
		return true;
	},
});

var mmjs = new MM_AffiliateReportViewJS("MM_AffiliateReport");
