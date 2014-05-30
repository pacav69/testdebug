/**
 * @version		$Id: dartsleague.js 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

var dartsleagueJS = {

	toggleSidebar: function(){
		var toggler = document.getElementById('dartsleagueToggleSidebar');
		
		toggler.onclick = function(){
			dartsleagueJS.toggle('adminFormdartsleagueSidebar');
			return false;
		}
		
	},

	toggle: function(obj) {
		var el = document.getElementById(obj);
		el.style.display = (el.style.display != 'none' ? 'none' : '' );
	},

	// Loader
	addLoadEvent: function(func) {
		var oldonload = window.onload;
		if (typeof window.onload != 'function') {
			window.onload = func;
		} else {
			window.onload = function() {
				if (oldonload) {
					oldonload();
				}
				func();
			}
		}
	}
	
	// END
};

// Load dartsleague
dartsleagueJS.addLoadEvent(dartsleagueJS.toggleSidebar);
