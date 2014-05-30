<?php
/**
 * @version		$Id: view.html.php 549 2010-08-30 15:39:45Z SilverPC Consultants. $
 * @package		dl dlViewseason class
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class dlViewseason extends JView
{

	function display($tpl = null) {

		$mainframe = &JFactory::getApplication();
		$db = & JFactory::getDBO();
		jimport('joomla.filesystem.file');
		jimport('joomla.html.pane');
		JHTML::_('behavior.keepalive');
		JRequest::setVar('hidemainmenu', 1);
		$document = &JFactory::getDocument();
		$document->addScript(JURI::root().'administrator/components/com_dl/lib/Autocompleter.js');
		$document->addScript(JURI::root().'administrator/components/com_dl/lib/observer.js');
		$document->addScript(JURI::root().'administrator/components/com_dl/lib/nicEdit.js');
		$js =
		"function initExtraFieldsEditor(){
			$$('.dlExtraFieldEditor').each(function(element) {
				var id = element.id;
				if (typeof JContentEditor != 'undefined') {
					if (tinyMCE.get(id)) {
						tinymce.EditorManager.remove(tinyMCE.get(id));
					}
					tinyMCE.execCommand('mceAddControl', false, id);
				} else {
					new nicEditor({fullPanel: true, maxHeight: 180, iconsPath: '".JURI::root()."administrator/components/com_dl/images/system/nicEditorIcons.gif'}).panelInstance(element.getProperty('id'));
				}
			});
		}
		function syncExtraFieldsEditor(){
			$$('.dlExtraFieldEditor').each(function(element){
				editor = nicEditors.findEditor(element.getProperty('id'));
				if(typeof editor != 'undefined'){
					editor.saveContent();
				}
    		});
		}
		";
		$document->addScriptDeclaration($js);

		$model = & $this->getModel();
		$season = $model->getData();
		JFilterOutput::objectHTMLSafe( $season, ENT_QUOTES, 'video' );
		$user = & JFactory::getUser();
		if ( JTable::isCheckedOut($user->get ('id'), $season->checked_out )) {
			$msg = JText::sprintf('DESCBEINGEDITTED', JText::_('The Season'), $season->title);
			$mainframe->redirect('index.php?option=com_dl', $msg);
		}

        
		if ($season->id){
			$season->checkout($user->get('id'));
		}
		else {
			$createdate =& JFactory::getDate();
			$season->published = 1;
			$season->publish_up = $createdate->toUnix();
			$season->publish_down = JText::_('Never');
			$season->created = $createdate->toUnix();
			$season->modified = $db->getNullDate();
		}
         
        // $listsg = array ();

        
		$season->created = JHTML::_('date', $season->created, '%Y-%m-%d %H:%M:%S');
		$season->publish_up = JHTML::_('date', $season->publish_up, '%Y-%m-%d %H:%M:%S');

		if (JHTML::_('date', $season->publish_down, '%Y') <= 1969 || $season->publish_down == $db->getNullDate()) {
			$season->publish_down = JText::_('Never');
		}
		else {
			$season->publish_down = JHTML::_('date', $season->publish_down, '%Y-%m-%d %H:%M:%S');
		}

		$params = & JComponentHelper::getParams('com_dl');
		$wysiwyg = & JFactory::getEditor();

		/*if ($params->get("mergeEditors")){

			if (JString::strlen($season->fulltext) > 1) {
				$textValue = $season->introtext."<hr id=\"system-readmore\" />".$season->fulltext;
			}
			else {
				$textValue = $season->introtext;
			}
			$text = $wysiwyg->display('text', $textValue, '100%', '400', '40', '5');
			$this->assignRef('text', $text);
		}

		else {
			$introtext = $wysiwyg->display('introtext', $season->introtext, '100%', '400', '40', '5', array('readmore'));
			$this->assignRef('introtext', $introtext);
			$fulltext = $wysiwyg->display('fulltext', $season->fulltext, '100%', '400', '40', '5', array('readmore'));
			$this->assignRef('fulltext', $fulltext);
		}       */

		$lists = array ();
        
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $season->published);
		$lists['access'] = JHTML::_('list.accesslevel', $season);


		/** $query = "SELECT ordering AS value, name AS text FROM #__dl_seasons WHERE catid={$season->catid}";  */
        // $query = "SELECT ordering AS value, firstname AS text FROM #__dl_seasons";
        $query = "SELECT name AS text FROM #__dl_seasons";
		$lists['ordering'] = JHTML::_('list.specificordering', $season, $season->id, $query);

		/** if(!$season->id)
			$season->catid = $mainframe->getUserStateFromRequest('com_dlseasonfilter_category', 'catid',0, 'int');
       
		require_once (JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel = new dlModelCategories;
		$categories = $categoriesModel->categoriesTree();
		$lists['catid'] = JHTML::_('select.genericlist', $categories, 'catid', 'class="inputbox"', 'value', 'text', $season->catid);

		$lists['checkSIG']=$model->checkSIG();
		$lists['checkAllVideos']=$model->checkAllVideos();

		$remoteVideo = false;
		$providerVideo = false;
		$embedVideo = false;
        */  
		if (stristr($season->video,'remote}') !== false) {
			$remoteVideo = true;
			$options['startOffset']= 1;
		}

		$providers = $model->getVideoProviders();

		if (count($providers)){

			foreach ($providers as $provider){
				$providersOptions[] = JHTML::_('select.option', $provider, $provider);
				if (stristr($season->video,"{{$provider}}") !== false) {
					$providerVideo = true;
					$options['startOffset']= 2;
				}
			}

		}

		if (JString::substr($season->video, 0, 1) !== '{') {
				$embedVideo = true;
				$options['startOffset']= 3;
		}

		$lists['uploadedVideo'] = (!$remoteVideo && !$providerVideo && !$embedVideo) ? true : false;

		if ($lists['uploadedVideo'] || $season->video==''){
			$options['startOffset']= 0;
		}

		$lists['remoteVideo'] = ($remoteVideo)?preg_replace('%\{[a-z0-9-_]*\}(.*)\{/[a-z0-9-_]*\}%i', '\1', $season->video):'';
		$lists['remoteVideoType'] = ($remoteVideo)?preg_replace('%\{([a-z0-9-_]*)\}.*\{/[a-z0-9-_]*\}%i', '\1', $season->video):'';
		$lists['providerVideo'] = ($providerVideo)?preg_replace('%\{[a-z0-9-_]*\}(.*)\{/[a-z0-9-_]*\}%i', '\1', $season->video):'';
		$lists['providerVideoType'] = ($providerVideo)?preg_replace('%\{([a-z0-9-_]*)\}.*\{/[a-z0-9-_]*\}%i', '\1', $season->video):'';
		$lists['embedVideo'] = ($embedVideo)?$season->video:'';

		if (isset($providersOptions)){
			$lists['providers'] = JHTML::_('select.genericlist', $providersOptions, 'videoProvider', '', 'value', 'text', $lists['providerVideoType']);
		}

		JPluginHelper::importPlugin ('content', 'jw_sigpro');
		JPluginHelper::importPlugin ('content', 'jw_allvideos');

		$dispatcher = &JDispatcher::getInstance ();

		$params->set('galleries_rootfolder', 'media/dl/galleries');
		$params->set('thb_width', '150');
		$params->set('thb_height', '120');
		$params->set('popup_engine', 'mootools_slimbox');
		$params->set('enabledownload', '0');
		$season->text=$season->gallery;
		$dispatcher->trigger ( 'onPrepareContent', array (&$season, &$params, null ) );
		$season->gallery=$season->text;

		if(!$embedVideo){
			$params->set('vfolder', 'media/dl/videos');
			if(JString::strpos($season->video, 'remote}')){
				preg_match("#}(.*?){/#s",$season->video, $matches);
				if(JString::substr($matches[1], 0, 7)!='http://')
					$season->video = JString::str_ireplace($matches[1], JURI::root().$matches[1], $season->video);
			}
			$season->text=$season->video;
			$dispatcher->trigger ( 'onPrepareContent', array (&$season, &$params, null ) );
			$season->video=$season->text;
		} else {
			// no nothing
		}

		if (isset($season->created_by)) {
			$author= & JUser::getInstance($season->created_by);
			$season->author=$author->name;
		}
		if (isset($season->modified_by)) {
			$moderator = & JUser::getInstance($season->modified_by);
			$season->moderator=$moderator->name;
		}

		if($season->id)
			$active = $season->created_by;
		else
			$active = $user->id;

		$lists['authors'] = JHTML::_('list.users', 'created_by', $active, false);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel= new dlModelCategories;
		$categories_option[]=JHTML::_('select.option', 0, JText::_('- Select category -'));
		$categories = $categoriesModel->categoriesTree(NUll, true, false);
		$categories_options=@array_merge($categories_option, $categories);
		$lists['categories'] = JHTML::_('select.genericlist', $categories_options, 'catid', '', 'value', 'text', $season->catid);

		JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
		$category = & JTable::getInstance('dlCategory', 'Table');
		$category->load($season->catid);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'extrafield.php');
		$extraFieldModel= new dlModelExtraField;
		if($season->id)
			$extraFields = $extraFieldModel->getExtraFieldsByGroup($category->extraFieldsGroup);
		else $extraFields = NULL;


		for($i=0; $i<sizeof($extraFields); $i++){
			$extraFields[$i]->element=$extraFieldModel->renderExtraField($extraFields[$i],$season->id);
		}

		if($season->id){
			$season->attachments=$model->getAttachments($season->id);
			$rating = $model->getRating();
			if(is_null($rating)){
				$season->ratingSum = 0;
				$season->ratingCount = 0;
			}
			else{
				$season->ratingSum = (int)$rating->rating_sum;
				$season->ratingCount = (int)$rating->rating_count;
			}
		}
		else {
			$season->attachments = NULL;
			$season->ratingSum = 0;
			$season->ratingCount = 0;
		}


        if($user->gid<24 && $params->get('lockTags'))
			$params->set('taggingSystem',0);

		$tags=$model->getAvailableTags($season->id);
		$lists['tags'] = JHTML::_ ( 'select.genericlist', $tags, 'tags', 'multiple="multiple" size="10" ', 'id', 'name' );

		if (isset($season->id)){
			$season->tags=$model->getCurrentTags($season->id);
			$lists['selectedTags'] = JHTML::_ ( 'select.genericlist', $season->tags, 'selectedTags[]', 'multiple="multiple" size="10" ', 'id', 'name' );
		}
		else {
			$lists['selectedTags']='<select size="10" multiple="multiple" id="selectedTags" name="selectedTags[]"></select>';
		}

		$lists['metadata']=new JParameter($season->metadata);

		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'dl'.DS.'seasons'.DS.'cache'.DS.md5("Image".$season->id).'_L.jpg'))
			$season->image = JURI::root().'media/dl/seasons/cache/'.md5("Image".$season->id).'_L.jpg';

		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'dl'.DS.'seasons'.DS.'cache'.DS.md5("Image".$season->id).'_S.jpg'))
			$season->thumb = JURI::root().'media/dl/seasons/cache/'.md5("Image".$season->id).'_S.jpg';


		JPluginHelper::importPlugin ( 'dl' );
		$dispatcher = &JDispatcher::getInstance ();

		$dlPluginsseasonContent=$dispatcher->trigger('onRenderAdminForm', array (&$season, 'season', 'content' ) );
		$this->assignRef('dlPluginsseasonContent', $dlPluginsseasonContent);

		$dlPluginsseasonImage=$dispatcher->trigger('onRenderAdminForm', array (&$season, 'season', 'image' ) );
		$this->assignRef('dlPluginsseasonImage', $dlPluginsseasonImage);

		$dlPluginsseasonGallery=$dispatcher->trigger('onRenderAdminForm', array (&$season, 'season', 'gallery' ) );
		$this->assignRef('dlPluginsseasonGallery', $dlPluginsseasonGallery);

		$dlPluginsseasonVideo=$dispatcher->trigger('onRenderAdminForm', array (&$season, 'season', 'video' ) );
		$this->assignRef('dlPluginsseasonVideo', $dlPluginsseasonVideo);

		$dlPluginsseasonExtraFields=$dispatcher->trigger('onRenderAdminForm', array (&$season, 'season', 'extra-fields' ) );
		$this->assignRef('dlPluginsseasonExtraFields', $dlPluginsseasonExtraFields);

		$dlPluginsseasonAttachments=$dispatcher->trigger('onRenderAdminForm', array (&$season, 'season', 'attachments' ) );
		$this->assignRef('dlPluginsseasonAttachments', $dlPluginsseasonAttachments);

		$dlPluginsseasonOther=$dispatcher->trigger('onRenderAdminForm', array (&$season, 'season', 'other' ) );
		$this->assignRef('dlPluginsseasonOther', $dlPluginsseasonOther);

		$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'season.xml');
		$form->loadINI($season->params);
		$this->assignRef('form', $form);

		$this->assignRef('extraFields', $extraFields);
		$this->assignRef('options', $options);
		$this->assignRef('row', $season);
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		$this->assignRef('user', $user);
		(JRequest::getInt('cid'))? $title = JText::_('Edit season') : $title = JText::_('Add season');
		JToolBarHelper::title($title, 'dl.png');
		JToolBarHelper::save();
		JToolBarHelper::custom('saveAndNew','save.png','save_f2.png','Save &amp; New', false);
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
                                // new toolbar
        JToolBarHelper::help( 'screen.season',true ); 

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
