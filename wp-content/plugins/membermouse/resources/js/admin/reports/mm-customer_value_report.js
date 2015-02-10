/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_CustomerValueReportViewJS = MM_ReportJSBase.extend({
	
	//both visual elements need to react simultaneosly to sort events. This may need to change if any visual elements are added later
	changeDirective: function(elementId, newDirectives)
	{
		var _this = this;
		jQuery(".mm-report-visual-element").each(function(){
			var localId = jQuery(this).attr("id");
			var objRef = _this.peekParams(localId);
			for (var key in newDirectives)
			{
				objRef.directives[key] = newDirectives[key];
			}
			jQuery("#" + localId).attr('data-status','loading');
			_this.showLoadingIcon(localId);
		});
		this.poll();
	}
});

var mmjs = new MM_CustomerValueReportViewJS("MM_CustomerValueReport");
