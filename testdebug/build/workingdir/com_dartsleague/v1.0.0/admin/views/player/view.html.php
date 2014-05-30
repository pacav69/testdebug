<?php
/**
 * @version		$Id: view.html.php 549 2010-08-30 15:39:45Z SilverPC Consultants. $
 * @package		dartsleague dartsleagueViewplayer class
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class dartsleagueViewplayer extends JView
{

	function display($tpl = null) {

		$mainframe = &JFactory::getApplication();
		$db = & JFactory::getDBO();
		jimport('joomla.filesystem.file');
		jimport('joomla.html.pane');
		JHTML::_('behavior.keepalive');
		JRequest::setVar('hidemainmenu', 1);
		$document = &JFactory::getDocument();
		$document->addScript(JURI::root().'administrator/components/com_dartsleague/lib/Autocompleter.js');
		$document->addScript(JURI::root().'administrator/components/com_dartsleague/lib/observer.js');
		$document->addScript(JURI::root().'administrator/components/com_dartsleague/lib/nicEdit.js');
		$js =
		"function initExtraFieldsEditor(){
			$$('.dartsleagueExtraFieldEditor').each(function(element) {
				var id = element.id;
				if (typeof JContentEditor != 'undefined') {
					if (tinyMCE.get(id)) {
						tinymce.EditorManager.remove(tinyMCE.get(id));
					}
					tinyMCE.execCommand('mceAddControl', false, id);
				} else {
					new nicEditor({fullPanel: true, maxHeight: 180, iconsPath: '".JURI::root()."administrator/components/com_dartsleague/images/system/nicEditorIcons.gif'}).panelInstance(element.getProperty('id'));
				}
			});
		}
		function syncExtraFieldsEditor(){
			$$('.dartsleagueExtraFieldEditor').each(function(element){
				editor = nicEditors.findEditor(element.getProperty('id'));
				if(typeof editor != 'undefined'){
					editor.saveContent();
				}
    		});
		}
		";
		$document->addScriptDeclaration($js);

		$model = & $this->getModel();
		$player = $model->getData();
		//JFilterOutput::objectHTMLSafe( $player, ENT_QUOTES, 'video' );
		$user = & JFactory::getUser();
		if ( JTable::isCheckedOut($user->get ('id'), $player->checked_out )) {
			$msg = JText::sprintf('DESCBEINGEDITTED', JText::_('The player'), $player->title);
			$mainframe->redirect('index.php?option=com_dartsleague', $msg);
		}

        
		if ($player->id){
			$player->checkout($user->get('id'));
		}
		else {
			$createdate =& JFactory::getDate();
			$player->published = 1;
			$player->publish_up = $createdate->toUnix();
			$player->publish_down = JText::_('Never');
			$player->created = $createdate->toUnix();
			$player->modified = $db->getNullDate();
		}
         
        // $listsg = array ();

        $player->birthdate = JHTML::_('date', $player->birthdate, '%Y-%m-%d %H:%M:%S'); 
         
		$player->created = JHTML::_('date', $player->created, '%Y-%m-%d %H:%M:%S');
		$player->publish_up = JHTML::_('date', $player->publish_up, '%Y-%m-%d %H:%M:%S');

		if (JHTML::_('date', $player->publish_down, '%Y') <= 1969 || $player->publish_down == $db->getNullDate()) {
			$player->publish_down = JText::_('Never');
		}
		else {
			$player->publish_down = JHTML::_('date', $player->publish_down, '%Y-%m-%d %H:%M:%S');
		}

		$params = & JComponentHelper::getParams('com_dartsleague');
		$wysiwyg = & JFactory::getEditor();

		if ($params->get("mergeEditors")){

			if (JString::strlen($player->fulltext) > 1) {
				$textValue = $player->introtext."<hr id=\"system-readmore\" />".$player->fulltext;
			}
			else {
				$textValue = $player->introtext;
			}
			$text = $wysiwyg->display('text', $textValue, '100%', '400', '40', '5');
			$this->assignRef('text', $text);
		}

		else {
			$introtext = $wysiwyg->display('introtext', $player->introtext, '100%', '400', '40', '5', array('readmore'));
			$this->assignRef('introtext', $introtext);
			$fulltext = $wysiwyg->display('fulltext', $player->fulltext, '100%', '400', '40', '5', array('readmore'));
			$this->assignRef('fulltext', $fulltext);
		}

		$lists = array ();
        
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $player->published);
		$lists['access'] = JHTML::_('list.accesslevel', $player);
        
        // get the team name from the teams table
       // $query = "SELECT ordering AS value, firstname AS text FROM #__dartsleague_players WHERE id={$player->id}";
       // $lists['orderingteam'] = JHTML::_('list.specificordering', $player, $player->id, $query);

        if(!$player->id)
            $player->id = $mainframe->getUserStateFromRequest('com_dartsleagueteamsfilter_player', 'id',0, 'int');

       // $query = "SELECT p.*, t.name AS teamname FROM #__dartsleague_players as p"; 
       // $query .= " LEFT JOIN #__dartsleague_teams AS t ON t.teamid = p.catid";
       // $query .= " WHERE p.id>0";
//               require_once(JPATH_COMPONENT.DS.'models'.DS.'teamslist.php');
//        $teamsModel= new dartsleagueModelteamslist;
//        $teams_option[]=JHTML::_('select.option', 0, JText::_('- Select Team -'));
//        $teams = $teamsModel->teamsTree(NUll, true, false);
//        $teams_options=@array_merge($teams_option, $teams);
//        $lists['teamid'] = JHTML::_('select.genericlist', $teams_options, 'teamid', '', 'value', 'text', $player->teamid);

    
        require_once (JPATH_COMPONENT.DS.'models'.DS.'playerteamslist.php');
        $playerteamsModel = new dartsleagueModelplayerteamslist;
        $playerteams_option[]=JHTML::_('select.option', 0, JText::_('- Select Team -')); 
        $playerteams = $playerteamsModel->playerteamsTree(NUll, true, false);
        $playerteams_options=@array_merge($playerteams_option, $playerteams); 
        // $lists['teamid'] <--  the third part must match  _JHTML::_ ... _'teamid'_ otherwise the selection will not be saved - this allows the selection list to appear in the default view using $lists->teamid  --> 'class="inputbox" size="1"', 'value', 'text', $team->catid);
        // $lists['teamid'] ........ 'text', $player->teamid <-- the teamid displays what data is associated with player 
        $lists['teamid'] = JHTML::_('select.genericlist', $playerteams_options, 'teamid', 'class="inputbox" size="1"', 'value', 'text', $player->teamid);
      
        // Gender options list
        $genderOptions[] = JHTML::_('select.option', 'm', JText::_('Male'));
        $genderOptions[] = JHTML::_('select.option', 'f', JText::_('Female'));
        $lists['gender'] = JHTML::_('select.radiolist', $genderOptions, 'gender','','value','text',$player->gender);

		/** $query = "SELECT ordering AS value, name AS text FROM #__dartsleague_players WHERE catid={$player->catid}";  */
        // $query = "SELECT ordering AS value, firstname AS text FROM #__dartsleague_players";
        // $query = "SELECT c.*, g.name AS groupname, exfg.name as extra_fields_group FROM #__dartsleague_categories as c LEFT JOIN #__groups AS g ON g.id = c.access LEFT JOIN #__dartsleague_extra_fields_groups AS exfg ON exfg.id = c.extraFieldsGroup WHERE c.id>0";
        $query = "SELECT p.*, t.name AS text, t.id AS value FROM #__dartsleague_players as p ";
        $query .= " LEFT JOIN #__dartsleague_teams AS t ON t.id = p.id";
        
        // $query .= " WHERE p.id>0";
        // $query .= "left outer join #__dartsleague_teams on #__dartsleague_teams.id = #__dartsleague_players.teamid";
        // $query .= " WHERE id={$player->id}";
		$lists['orderingplayer'] = JHTML::_('list.specificordering', $player, $player->id, $query);
          // get list of teams for dropdown filter
        if($player->id)
            $active = $player->player; 
        else
/*        select * from
jos_dartsleague_players 
left outer join jos_dartsleague_teams on jos_dartsleague_teams.id = jos_dartsleague_players.teamid;*/
/*
            $query = 'SELECT id AS value, name AS text' .
                    ' FROM #__dartsleague_teams' .
                    ' ORDER BY name'; */
 /*
        //$lists['teams'] = JHTML::_('select.genericlist', $teams_options, 'filter_team', 'onchange="this.form.submit();"', 'value', 'text', $filter_team);
/*             
        require_once(JPATH_COMPONENT.DS.'models'.DS.'teams.php');
        $teamsModel= new dartsleagueModelteams();
        $teams_option[]=JHTML::_('select.option', 0, JText::_('- Select Team -'));
        $teams = $venuesModel->venuesTree(NUll, true, false);
        $teams_options=@array_merge($teams_option, $teams);
        // dump($venues_options, '$venues_options');
        $lists['teamtypes']    = JHTML::_('select.genericlist', $teamtypes, 'teamtypes', 'class="inputbox" size="1" ', 'value', 'text', $team->id);
 */
        // $lists['teamtypes'] = JHTML::_('select.genericlist', $venues_options, 'venueid', 'class="inputbox" size="1"', 'value', 'text', $team->catid);
//

//            $query = "";        
//           $query = "SELECT id AS value, name AS text FROM #__dartsleague_teams"; 
//            $query .= "  WHERE p.id={$player->id}"; 
//            $query .= "  ORDER BY name"; 
//            $db->setQuery( $query );
//            $teamtypes[] = JHTML::_('select.option',1, '- '. JText::_( 'Select team' ) .' -' );         
            // $teamtypes = array_merge( $teamtypes, $db->loadObjectList() );
//            $teamtypes = array_merge( $teamtypes, $query ); 
//           $lists['teamtypes']    = JHTML::_('select.genericlist', $teamtypes, 'teamtypes', 'class="inputbox" size="1" ', 'value', 'text', $team->id);

		 if(!$player->id)
			$player->catid = $mainframe->getUserStateFromRequest('com_dartsleagueplayerfilter_team', 'catid',0, 'int');
/**
		require_once (JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel = new dartsleagueModelCategories;
		$categories = $categoriesModel->categoriesTree();
		$lists['catid'] = JHTML::_('select.genericlist', $categories, 'catid', 'class="inputbox"', 'value', 'text', $player->catid);

		$lists['checkSIG']=$model->checkSIG();
		$lists['checkAllVideos']=$model->checkAllVideos();

		$remoteVideo = false;
		$providerVideo = false;
		$embedVideo = false;
*/ 
/**     
	 if (stristr($player->video,'remote}') !== false) {
			$remoteVideo = true;
			$options['startOffset']= 1;
		}

		$providers = $model->getVideoProviders();

		if (count($providers)){

			foreach ($providers as $provider){
				$providersOptions[] = JHTML::_('select.option', $provider, $provider);
				if (stristr($player->video,"{{$provider}}") !== false) {
					$providerVideo = true;
					$options['startOffset']= 2;
				}
			}

		}

		if (JString::substr($player->video, 0, 1) !== '{') {
				$embedVideo = true;
				$options['startOffset']= 3;
		}

		$lists['uploadedVideo'] = (!$remoteVideo && !$providerVideo && !$embedVideo) ? true : false;

		if ($lists['uploadedVideo'] || $player->video==''){
			$options['startOffset']= 0;
		}

		$lists['remoteVideo'] = ($remoteVideo)?preg_replace('%\{[a-z0-9-_]*\}(.*)\{/[a-z0-9-_]*\}%i', '\1', $player->video):'';
		$lists['remoteVideoType'] = ($remoteVideo)?preg_replace('%\{([a-z0-9-_]*)\}.*\{/[a-z0-9-_]*\}%i', '\1', $player->video):'';
		$lists['providerVideo'] = ($providerVideo)?preg_replace('%\{[a-z0-9-_]*\}(.*)\{/[a-z0-9-_]*\}%i', '\1', $player->video):'';
		$lists['providerVideoType'] = ($providerVideo)?preg_replace('%\{([a-z0-9-_]*)\}.*\{/[a-z0-9-_]*\}%i', '\1', $player->video):'';
		$lists['embedVideo'] = ($embedVideo)?$player->video:'';

		if (isset($providersOptions)){
			$lists['providers'] = JHTML::_('select.genericlist', $providersOptions, 'videoProvider', '', 'value', 'text', $lists['providerVideoType']);
		}

		JPluginHelper::importPlugin ('content', 'jw_sigpro');
		JPluginHelper::importPlugin ('content', 'jw_allvideos');

		$dispatcher = &JDispatcher::getInstance ();

		$params->set('galleries_rootfolder', 'media/dartsleague/galleries');
		$params->set('thb_width', '150');
		$params->set('thb_height', '120');
		$params->set('popup_engine', 'mootools_slimbox');
		$params->set('enabledownload', '0');
		$player->text=$player->gallery;
		$dispatcher->trigger ( 'onPrepareContent', array (&$player, &$params, null ) );
		$player->gallery=$player->text;

		if(!$embedVideo){
			$params->set('vfolder', 'media/dartsleague/videos');
			if(JString::strpos($player->video, 'remote}')){
				preg_match("#}(.*?){/#s",$player->video, $matches);
				if(JString::substr($matches[1], 0, 7)!='http://')
					$player->video = JString::str_ireplace($matches[1], JURI::root().$matches[1], $player->video);
			}
			$player->text=$player->video;
			$dispatcher->trigger ( 'onPrepareContent', array (&$player, &$params, null ) );
			$player->video=$player->text;
		} else {
			// no nothing
		}  */

		if (isset($player->created_by)) {
			$author= & JUser::getInstance($player->created_by);
			$player->author=$author->name;
		}
		if (isset($player->modified_by)) {
			$moderator = & JUser::getInstance($player->modified_by);
			$player->moderator=$moderator->name;
		}

		if($player->id)
			$active = $player->created_by;
		else
			$active = $user->id;

		$lists['authors'] = JHTML::_('list.users', 'created_by', $active, false);
 // Create the teams lists for selection for the player
//		require_once(JPATH_COMPONENT.DS.'models'.DS.'teamslist.php');
//		$teamsModel= new dartsleagueModelteamslist;
//		$teams_option[]=JHTML::_('select.option', 0, JText::_('- Select Team -'));
//		$teams = $teamsModel->teamsTree(NUll, true, false);
//		$teams_options=@array_merge($teams_option, $teams);
//		$lists['teamid'] = JHTML::_('select.genericlist', $teams_options, 'teamid', '', 'value', 'text', $player->teamid);

//		JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
//		$player = & JTable::getInstance('dartsleagueplayer', 'Table');
//		$player->load($player->id);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'extrafield.php');
		$extraFieldModel= new dartsleagueModelExtraField;
		if($player->id)
			$extraFields = $extraFieldModel->getExtraFieldsByGroup($team->extraFieldsGroup);
		else $extraFields = NULL;


		for($i=0; $i<sizeof($extraFields); $i++){
			$extraFields[$i]->element=$extraFieldModel->renderExtraField($extraFields[$i],$player->id);
		}

		if($player->id){
			$player->attachments=$model->getAttachments($player->id);
			$rating = $model->getRating();
			if(is_null($rating)){
				$player->ratingSum = 0;
				$player->ratingCount = 0;
			}
			else{
				$player->ratingSum = (int)$rating->rating_sum;
				$player->ratingCount = (int)$rating->rating_count;
			}
		}
		else {
			$player->attachments = NULL;
			$player->ratingSum = 0;
			$player->ratingCount = 0;
		}


        if($user->gid<24 && $params->get('lockTags'))
			$params->set('taggingSystem',0);

		$tags=$model->getAvailableTags($player->id);
		$lists['tags'] = JHTML::_ ( 'select.genericlist', $tags, 'tags', 'multiple="multiple" size="10" ', 'id', 'name' );

		if (isset($player->id)){
			$player->tags=$model->getCurrentTags($player->id);
			$lists['selectedTags'] = JHTML::_ ( 'select.genericlist', $player->tags, 'selectedTags[]', 'multiple="multiple" size="10" ', 'id', 'name' );
		}
		else {
			$lists['selectedTags']='<select size="10" multiple="multiple" id="selectedTags" name="selectedTags[]"></select>';
		}

		$lists['metadata']=new JParameter($player->metadata);

		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$player->id).'_L.jpg'))
			$player->image = JURI::root().'media/dartsleague/players/cache/'.md5("Image".$player->id).'_L.jpg';

		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$player->id).'_S.jpg'))
			$player->thumb = JURI::root().'media/dartsleague/players/cache/'.md5("Image".$player->id).'_S.jpg';


		JPluginHelper::importPlugin ( 'dartsleague' );
		$dispatcher = &JDispatcher::getInstance ();

		$dartsleaguePluginsplayerContent=$dispatcher->trigger('onRenderAdminForm', array (&$player, 'player', 'content' ) );
		$this->assignRef('dartsleaguePluginsplayerContent', $dartsleaguePluginsplayerContent);

		$dartsleaguePluginsplayerImage=$dispatcher->trigger('onRenderAdminForm', array (&$player, 'player', 'image' ) );
		$this->assignRef('dartsleaguePluginsplayerImage', $dartsleaguePluginsplayerImage);

		$dartsleaguePluginsplayerGallery=$dispatcher->trigger('onRenderAdminForm', array (&$player, 'player', 'gallery' ) );
		$this->assignRef('dartsleaguePluginsplayerGallery', $dartsleaguePluginsplayerGallery);

		$dartsleaguePluginsplayerVideo=$dispatcher->trigger('onRenderAdminForm', array (&$player, 'player', 'video' ) );
		$this->assignRef('dartsleaguePluginsplayerVideo', $dartsleaguePluginsplayerVideo);

		$dartsleaguePluginsplayerExtraFields=$dispatcher->trigger('onRenderAdminForm', array (&$player, 'player', 'extra-fields' ) );
		$this->assignRef('dartsleaguePluginsplayerExtraFields', $dartsleaguePluginsplayerExtraFields);

		$dartsleaguePluginsplayerAttachments=$dispatcher->trigger('onRenderAdminForm', array (&$player, 'player', 'attachments' ) );
		$this->assignRef('dartsleaguePluginsplayerAttachments', $dartsleaguePluginsplayerAttachments);

		$dartsleaguePluginsplayerOther=$dispatcher->trigger('onRenderAdminForm', array (&$player, 'player', 'other' ) );
		$this->assignRef('dartsleaguePluginsplayerOther', $dartsleaguePluginsplayerOther);

		$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'player.xml');
		$form->loadINI($player->params);
		$this->assignRef('form', $form);

		$this->assignRef('extraFields', $extraFields);
		$this->assignRef('options', $options);
		$this->assignRef('row', $player);
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		$this->assignRef('user', $user);
		(JRequest::getInt('cid'))? $title = JText::_('Edit Player') : $title = JText::_('Add Player');
		JToolBarHelper::title($title, 'dartsleague.png');
		JToolBarHelper::save();
		JToolBarHelper::custom('saveAndNew','save.png','save_f2.png','Save &amp; New', false);
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
                                // new toolbar
        JToolBarHelper::help( 'screen.player',true ); 

		parent::display($tpl);
	}


	function filebrowser($tpl = null){

        $params = &JComponentHelper::getParams('com_media');
        $root = $params->get('file_path', 'media');
		$folder = JRequest::getVar( 'folder', $root, 'default', 'path');
		$type = JRequest::getCmd('type', 'image');
		if(JString::trim($folder)=="")
			$folder = $root;
		$path=JPATH_SITE.DS.JPath::clean($folder);
		JPath::check($path);
		if($type=='video'){
			$title = JText::_('Browse videos');
			$filter = '.wmv|avi|mp4|mpg|mpeg|flv|3gp|mov';
		}
		else {
			$title = JText::_('Browse images');
			$filter = '.jpg|png|gif|xcf|odg|bmp|jpeg';
		}

		if (JFolder::exists($path)){
			$folderList=JFolder::folders($path);
			$filesList=JFolder::files($path, $filter);
		}

		if (!empty($folder) && $folder!=$root){
			$parent=substr($folder, 0,strrpos($folder,'/'));
		}
		else {
			$parent = $root;
		}

		$this->assignRef('folders',$folderList);
		$this->assignRef('files',$filesList);
		$this->assignRef('parent',$parent);
		$this->assignRef('path',$folder);
		$this->assignRef('type',$type);
		$this->assignRef('title',$title);

		$document = &JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'components/com_media/assets/popup-imagelist.css');
		parent::display($tpl);

	}



}
