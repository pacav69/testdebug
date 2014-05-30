/**
 * @version		$Id: dartsleague.mootools.js 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

window.addEvent('domready', function(){

	// Toggler
	if($('dartsleagueToggleSidebar')){	
		$('dartsleagueToggleSidebar').addEvent('click', function(){
			$('adminFormdartsleagueSidebar').setStyle('display', $('adminFormdartsleagueSidebar').getStyle('display') != 'none' ? 'none' : '')
		});
	}
	
	// File browser
	$$('.videoFile').addEvent('click', function(e){
		e = new Event(e).stop();
		parent.$$('input[name=remoteVideo]').setProperty('value', this.getProperty('href'));
		parent.$('sbox-window').close();
	});
	
	$$('.imageFile').addEvent('click', function(e){
		e = new Event(e).stop();
		parent.$$('input[name=existingImage]').setProperty('value', this.getProperty('href'));
		parent.$('sbox-window').close();
	});
	
	
});
