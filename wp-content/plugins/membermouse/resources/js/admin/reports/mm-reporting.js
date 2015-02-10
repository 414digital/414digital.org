/*
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_ReportingDashboardViewJS = MM_ReportJSBase.extend({
	
	animate: function(elementSelector)
	{
		jQuery(function() {
			jQuery("#" + elementSelector + " .mm-report-animated").each(function() {
				var target = jQuery(this).attr("data-anim-target");
				var sep = jQuery(this).attr("data-anim-sep");
				var dps = jQuery(this).attr("data-anim-dps");
				
				//sanitizing 
				target = (target == undefined)?0:target;
				dps = ((dps == undefined) || (dps == ""))?0:dps;
				sep = (sep == undefined)?"":sep;
				
				var df = (dps === 0)?1:(dps * 10);
				if (dps > 0)
				{
					jQuery(this).animateNumber(
					    {
					      number: target * df,
					      numberStep: function(now, tween) {
					        var floored_number = Math.floor(now) / df,
					            target = jQuery(tween.elem);
		
					        // force decimal places even if they are 0
					        floored_number = floored_number.toFixed(dps);
					     
					        if ((sep != undefined) && (sep != ""))
					        {
					        	
						        var parts = floored_number.toString().split(".");
						        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, sep);
						        floored_number = parts.join(".");
					        }
					        target.text(floored_number);
					      }
					    },
					    2000
					  );
				}
				else
				{
					var comma_separator_number_step = jQuery.animateNumber.numberStepFactories.separator(sep)
					jQuery(this).animateNumber({ number: target, numberStep: comma_separator_number_step},2000);
				}
			});
		});
	}
});

var mmjs = new MM_ReportingDashboardViewJS("MM_ReportingDashboard");