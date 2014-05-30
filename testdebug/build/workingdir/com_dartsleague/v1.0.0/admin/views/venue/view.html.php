<?php
/**
 * @version		$Id: view.html.php 549 2010-08-30 15:39:45Z SilverPC Consultants. $
 * @package		dartsleague       dartsleagueViewvenue
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class dartsleagueViewvenue extends JView
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
		$venue = $model->getData();
	//	JFilterOutput::objectHTMLSafe( $venue, ENT_QUOTES, 'video' );
		$user = & JFactory::getUser();
		if ( JTable::isCheckedOut($user->get ('id'), $venue->checked_out )) {
			$msg = JText::sprintf('DESCBEINGEDITTED', JText::_('The venue'), $venue->title);
			$mainframe->redirect('index.php?option=com_dartsleague', $msg);
		}

		if ($venue->id){
			$venue->checkout($user->get('id'));
		}
		else {
			$createdate =& JFactory::getDate();
			$venue->published = 1;
			$venue->publish_up = $createdate->toUnix();
			$venue->publish_down = JText::_('Never');
			$venue->created = $createdate->toUnix();
			$venue->modified = $db->getNullDate();
		}

		$venue->created = JHTML::_('date', $venue->created, '%Y-%m-%d %H:%M:%S');
		$venue->publish_up = JHTML::_('date', $venue->publish_up, '%Y-%m-%d %H:%M:%S');

		if (JHTML::_('date', $venue->publish_down, '%Y') <= 1969 || $venue->publish_down == $db->getNullDate()) {
			$venue->publish_down = JText::_('Never');
		}
		else {
			$venue->publish_down = JHTML::_('date', $venue->publish_down, '%Y-%m-%d %H:%M:%S');
		}

		$params = & JComponentHelper::getParams('com_dartsleague');
		$wysiwyg = & JFactory::getEditor();

		if ($params->get("mergeEditors")){

			if (JString::strlen($venue->fulltext) > 1) {
				$textValue = $venue->introtext."<hr id=\"system-readmore\" />".$venue->fulltext;
			}
			else {
				$textValue = $venue->introtext;
			}
			$text = $wysiwyg->display('text', $textValue, '100%', '400', '40', '5');
			$this->assignRef('text', $text);
		}

		else {
			$introtext = $wysiwyg->display('introtext', $venue->introtext, '100%', '400', '40', '5', array('readmore'));
			$this->assignRef('introtext', $introtext);
			$fulltext = $wysiwyg->display('fulltext', $venue->fulltext, '100%', '400', '40', '5', array('readmore'));
			$this->assignRef('fulltext', $fulltext);
		}

		$lists = array ();
        
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $venue->published);
		$lists['vip'] = JHTML::_('select.booleanlist', 'vip', 'class="inputbox"', $venue->vip);
        $lists['access'] = JHTML::_('list.accesslevel', $venue);

		$query = "SELECT ordering AS value, name AS text FROM #__dartsleague_venues WHERE id={$venue->id}"; 
        /*$query = "SELECT ordering AS value, id as value, name AS text FROM #__dartsleague_venues";*/
		$lists['ordering'] = JHTML::_('list.specificordering', $venue, $venue->id, $query);

		 if(!$venue->id)
			$venue->catid = $mainframe->getUserStateFromRequest('com_dartsleaguevenuefilter_category', 'catid',0, 'int');
       
		require_once (JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel = new dartsleagueModelCategories;
		$categories = $categoriesModel->categoriesTree();
		$lists['catid'] = JHTML::_('select.genericlist', $categories, 'catid', 'class="inputbox"', 'value', 'text', $venue->catid);
        /**
		$lists['checkSIG']=$model->checkSIG();
		$lists['checkAllVideos']=$model->checkAllVideos();

		$remoteVideo = false;
		$providerVideo = false;
		$embedVideo = false;
        */  
/*		if (stristr($venue->video,'remote}') !== false) {
			$remoteVideo = true;
			$options['startOffset']= 1;
		} */

/*		$providers = $model->getVideoProviders();

		if (count($providers)){

			foreach ($providers as $provider){
				$providersOptions[] = JHTML::_('select.option', $provider, $provider);
				if (stristr($venue->video,"{{$provider}}") !== false) {
					$providerVideo = true;
					$options['startOffset']= 2;
				}
			}

		}
            */
		if (JString::substr($venue->video, 0, 1) !== '{') {
				$embedVideo = true;
				$options['startOffset']= 3;
		}

		$lists['uploadedVideo'] = (!$remoteVideo && !$providerVideo && !$embedVideo) ? true : false;

		if ($lists['uploadedVideo'] || $venue->video==''){
			$options['startOffset']= 0;
		}

		$lists['remoteVideo'] = ($remoteVideo)?preg_replace('%\{[a-z0-9-_]*\}(.*)\{/[a-z0-9-_]*\}%i', '\1', $venue->video):'';
		$lists['remoteVideoType'] = ($remoteVideo)?preg_replace('%\{([a-z0-9-_]*)\}.*\{/[a-z0-9-_]*\}%i', '\1', $venue->video):'';
		$lists['providerVideo'] = ($providerVideo)?preg_replace('%\{[a-z0-9-_]*\}(.*)\{/[a-z0-9-_]*\}%i', '\1', $venue->video):'';
		$lists['providerVideoType'] = ($providerVideo)?preg_replace('%\{([a-z0-9-_]*)\}.*\{/[a-z0-9-_]*\}%i', '\1', $venue->video):'';
		$lists['embedVideo'] = ($embedVideo)?$venue->video:'';

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
		$venue->text=$venue->gallery;
		$dispatcher->trigger ( 'onPrepareContent', array (&$venue, &$params, null ) );
		$venue->gallery=$venue->text;

		if(!$embedVideo){
			$params->set('vfolder', 'media/dartsleague/videos');
			if(JString::strpos($venue->video, 'remote}')){
				preg_match("#}(.*?){/#s",$venue->video, $matches);
				if(JString::substr($matches[1], 0, 7)!='http://')
					$venue->video = JString::str_ireplace($matches[1], JURI::root().$matches[1], $venue->video);
			}
			$venue->text=$venue->video;
			$dispatcher->trigger ( 'onPrepareContent', array (&$venue, &$params, null ) );
			$venue->video=$venue->text;
		} else {
			// no nothing
		}

		if (isset($venue->created_by)) {
			$author= & JUser::getInstance($venue->created_by);
			$venue->author=$author->name;
		}
		if (isset($venue->modified_by)) {
			$moderator = & JUser::getInstance($venue->modified_by);
			$venue->moderator=$moderator->name;
		}

		if($venue->id)
			$active = $venue->created_by;
		else
			$active = $user->id;

		//$lists['authors'] = JHTML::_('list.users', 'created_by', $active, false);

/*
		require_once(JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel= new dartsleagueModelCategories;
		$categories_option[]=JHTML::_('select.option', 0, JText::_('- Select category -'));
		$categories = $categoriesModel->categoriesTree(NUll, true, false);
		$categories_options=@array_merge($categories_option, $categories);
		$lists['categories'] = JHTML::_('select.genericlist', $categories_options, 'catid', '', 'value', 'text', $venue->catid);
*/

/*		JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
		$category = & JTable::getInstance('dartsleagueCategory', 'Table');
		$category->load($venue->catid);    */

/*		require_once(JPATH_COMPONENT.DS.'models'.DS.'extrafield.php');
		$extraFieldModel= new dartsleagueModelExtraField;
		if($venue->id)
			$extraFields = $extraFieldModel->getExtraFieldsByGroup($category->extraFieldsGroup);
		else $extraFields = NULL; */


		for($i=0; $i<sizeof($extraFields); $i++){
			$extraFields[$i]->element=$extraFieldModel->renderExtraField($extraFields[$i],$venue->id);
		}

		if($venue->id){
			$venue->attachments=$model->getAttachments($venue->id);
			$rating = $model->getRating();
			if(is_null($rating)){
				$venue->ratingSum = 0;
				$venue->ratingCount = 0;
			}
			else{
				$venue->ratingSum = (int)$rating->rating_sum;
				$venue->ratingCount = (int)$rating->rating_count;
			}
		}
		else {
			$venue->attachments = NULL;
			$venue->ratingSum = 0;
			$venue->ratingCount = 0;
		}


        if($user->gid<24 && $params->get('lockTags'))
			$params->set('taggingSystem',0);

		$tags=$model->getAvailableTags($venue->id);
		$lists['tags'] = JHTML::_ ( 'select.genericlist', $tags, 'tags', 'multiple="multiple" size="10" ', 'id', 'name' );

		if (isset($venue->id)){
			$venue->tags=$model->getCurrentTags($venue->id);
			$lists['selectedTags'] = JHTML::_ ( 'select.genericlist', $venue->tags, 'selectedTags[]', 'multiple="multiple" size="10" ', 'id', 'name' );
		}
		else {
			$lists['selectedTags']='<select size="10" multiple="multiple" id="selectedTags" name="selectedTags[]"></select>';
		}

		$lists['metadata']=new JParameter($venue->metadata);

		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'dartsleague'.DS.'venues'.DS.'cache'.DS.md5("Image".$venue->id).'_L.jpg'))
			$venue->image = JURI::root().'media/dartsleague/venues/cache/'.md5("Image".$venue->id).'_L.jpg';

		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'dartsleague'.DS.'venues'.DS.'cache'.DS.md5("Image".$venue->id).'_S.jpg'))
			$venue->thumb = JURI::root().'media/dartsleague/venues/cache/'.md5("Image".$venue->id).'_S.jpg';


		JPluginHelper::importPlugin ( 'dartsleague' );
		$dispatcher = &JDispatcher::getInstance ();

		$dartsleaguePluginsvenueContent=$dispatcher->trigger('onRenderAdminForm', array (&$venue, 'venue', 'content' ) );
		$this->assignRef('dartsleaguePluginsvenueContent', $dartsleaguePluginsvenueContent);

		$dartsleaguePluginsvenueImage=$dispatcher->trigger('onRenderAdminForm', array (&$venue, 'venue', 'image' ) );
		$this->assignRef('dartsleaguePluginsvenueImage', $dartsleaguePluginsvenueImage);

		$dartsleaguePluginsvenueGallery=$dispatcher->trigger('onRenderAdminForm', array (&$venue, 'venue', 'gallery' ) );
		$this->assignRef('dartsleaguePluginsvenueGallery', $dartsleaguePluginsvenueGallery);

		$dartsleaguePluginsvenueVideo=$dispatcher->trigger('onRenderAdminForm', array (&$venue, 'venue', 'video' ) );
		$this->assignRef('dartsleaguePluginsvenueVideo', $dartsleaguePluginsvenueVideo);

		$dartsleaguePluginsvenueExtraFields=$dispatcher->trigger('onRenderAdminForm', array (&$venue, 'venue', 'extra-fields' ) );
		$this->assignRef('dartsleaguePluginsvenueExtraFields', $dartsleaguePluginsvenueExtraFields);

		$dartsleaguePluginsvenueAttachments=$dispatcher->trigger('onRenderAdminForm', array (&$venue, 'venue', 'attachments' ) );
		$this->assignRef('dartsleaguePluginsvenueAttachments', $dartsleaguePluginsvenueAttachments);

		$dartsleaguePluginsvenueOther=$dispatcher->trigger('onRenderAdminForm', array (&$venue, 'venue', 'other' ) );
		$this->assignRef('dartsleaguePluginsvenueOther', $dartsleaguePluginsvenueOther);

		$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'venue.xml');
		$form->loadINI($venue->params);
		$this->assignRef('form', $form);

		// $this->assignRef('extraFields', $extraFields);
		$this->assignRef('options', $options);
		$this->assignRef('row', $venue);
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		$this->assignRef('user', $user);
		(JRequest::getInt('cid'))? $title = JText::_('Edit Venue') : $title = JText::_('Add Venue');
		JToolBarHelper::title($title, 'dartsleague.png');
		JToolBarHelper::save();
		JToolBarHelper::custom('saveAndNew','save.png','save_f2.png','Save &amp; New', false);
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
                                                // new toolbar
        JToolBarHelper::help( 'screen.venue',true ); 

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
