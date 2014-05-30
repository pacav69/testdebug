<?php
/**
 * @version		$Id: view.html.php 549 2010-08-30 15:39:45Z SilverPC Consultants. $
 * @package		dl       dlViewScoresheet
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class dlViewScoresheet extends JView
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
		$scoresheet = $model->getData();
		JFilterOutput::objectHTMLSafe( $scoresheet, ENT_QUOTES, 'video' );
		$user = & JFactory::getUser();
		if ( JTable::isCheckedOut($user->get ('id'), $scoresheet->checked_out )) {
			$msg = JText::sprintf('DESCBEINGEDITTED', JText::_('The Scoresheet'), $scoresheet->title);
			$mainframe->redirect('index.php?option=com_dl', $msg);
		}

		if ($scoresheet->id){
			$scoresheet->checkout($user->get('id'));
		}
		else {
			$createdate =& JFactory::getDate();
			$scoresheet->published = 1;
			$scoresheet->publish_up = $createdate->toUnix();
			$scoresheet->publish_down = JText::_('Never');
			$scoresheet->created = $createdate->toUnix();
			$scoresheet->modified = $db->getNullDate();
		}

		$scoresheet->created = JHTML::_('date', $scoresheet->created, '%Y-%m-%d %H:%M:%S');
		$scoresheet->publish_up = JHTML::_('date', $scoresheet->publish_up, '%Y-%m-%d %H:%M:%S');

		if (JHTML::_('date', $scoresheet->publish_down, '%Y') <= 1969 || $scoresheet->publish_down == $db->getNullDate()) {
			$scoresheet->publish_down = JText::_('Never');
		}
		else {
			$scoresheet->publish_down = JHTML::_('date', $scoresheet->publish_down, '%Y-%m-%d %H:%M:%S');
		}

		$params = & JComponentHelper::getParams('com_dl');
		$wysiwyg = & JFactory::getEditor();

		if ($params->get("mergeEditors")){

			if (JString::strlen($scoresheet->fulltext) > 1) {
				$textValue = $scoresheet->introtext."<hr id=\"system-readmore\" />".$scoresheet->fulltext;
			}
			else {
				$textValue = $scoresheet->introtext;
			}
			$text = $wysiwyg->display('text', $textValue, '100%', '400', '40', '5');
			$this->assignRef('text', $text);
		}

		else {
			$introtext = $wysiwyg->display('introtext', $scoresheet->introtext, '100%', '400', '40', '5', array('readmore'));
			$this->assignRef('introtext', $introtext);
			$fulltext = $wysiwyg->display('fulltext', $scoresheet->fulltext, '100%', '400', '40', '5', array('readmore'));
			$this->assignRef('fulltext', $fulltext);
		}

		$lists = array ();
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $scoresheet->published);
		$lists['access'] = JHTML::_('list.accesslevel', $scoresheet);

		$query = "SELECT ordering AS value, title AS text FROM #__dl_scoresheet WHERE catid={$scoresheet->catid}";
		$lists['ordering'] = JHTML::_('list.specificordering', $scoresheet, $scoresheet->id, $query);

		if(!$scoresheet->id)
			$scoresheet->catid = $mainframe->getUserStateFromRequest('com_dlscoresheetfilter_category', 'catid',0, 'int');

		require_once (JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel = new dlModelCategories;
		$categories = $categoriesModel->categoriesTree();
		$lists['catid'] = JHTML::_('select.genericlist', $categories, 'catid', 'class="inputbox"', 'value', 'text', $scoresheet->catid);

		$lists['checkSIG']=$model->checkSIG();
		$lists['checkAllVideos']=$model->checkAllVideos();

		$remoteVideo = false;
		$providerVideo = false;
		$embedVideo = false;

		if (stristr($scoresheet->video,'remote}') !== false) {
			$remoteVideo = true;
			$options['startOffset']= 1;
		}

		$providers = $model->getVideoProviders();

		if (count($providers)){

			foreach ($providers as $provider){
				$providersOptions[] = JHTML::_('select.option', $provider, $provider);
				if (stristr($scoresheet->video,"{{$provider}}") !== false) {
					$providerVideo = true;
					$options['startOffset']= 2;
				}
			}

		}

		if (JString::substr($scoresheet->video, 0, 1) !== '{') {
				$embedVideo = true;
				$options['startOffset']= 3;
		}

		$lists['uploadedVideo'] = (!$remoteVideo && !$providerVideo && !$embedVideo) ? true : false;

		if ($lists['uploadedVideo'] || $scoresheet->video==''){
			$options['startOffset']= 0;
		}

		$lists['remoteVideo'] = ($remoteVideo)?preg_replace('%\{[a-z0-9-_]*\}(.*)\{/[a-z0-9-_]*\}%i', '\1', $scoresheet->video):'';
		$lists['remoteVideoType'] = ($remoteVideo)?preg_replace('%\{([a-z0-9-_]*)\}.*\{/[a-z0-9-_]*\}%i', '\1', $scoresheet->video):'';
		$lists['providerVideo'] = ($providerVideo)?preg_replace('%\{[a-z0-9-_]*\}(.*)\{/[a-z0-9-_]*\}%i', '\1', $scoresheet->video):'';
		$lists['providerVideoType'] = ($providerVideo)?preg_replace('%\{([a-z0-9-_]*)\}.*\{/[a-z0-9-_]*\}%i', '\1', $scoresheet->video):'';
		$lists['embedVideo'] = ($embedVideo)?$scoresheet->video:'';

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
		$scoresheet->text=$scoresheet->gallery;
		$dispatcher->trigger ( 'onPrepareContent', array (&$scoresheet, &$params, null ) );
		$scoresheet->gallery=$scoresheet->text;

		if(!$embedVideo){
			$params->set('vfolder', 'media/dl/videos');
			if(JString::strpos($scoresheet->video, 'remote}')){
				preg_match("#}(.*?){/#s",$scoresheet->video, $matches);
				if(JString::substr($matches[1], 0, 7)!='http://')
					$scoresheet->video = JString::str_ireplace($matches[1], JURI::root().$matches[1], $scoresheet->video);
			}
			$scoresheet->text=$scoresheet->video;
			$dispatcher->trigger ( 'onPrepareContent', array (&$scoresheet, &$params, null ) );
			$scoresheet->video=$scoresheet->text;
		} else {
			// no nothing
		}

		if (isset($scoresheet->created_by)) {
			$author= & JUser::getInstance($scoresheet->created_by);
			$scoresheet->author=$author->name;
		}
		if (isset($scoresheet->modified_by)) {
			$moderator = & JUser::getInstance($scoresheet->modified_by);
			$scoresheet->moderator=$moderator->name;
		}

		if($scoresheet->id)
			$active = $scoresheet->created_by;
		else
			$active = $user->id;

		$lists['authors'] = JHTML::_('list.users', 'created_by', $active, false);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel= new dlModelCategories;
		$categories_option[]=JHTML::_('select.option', 0, JText::_('- Select category -'));
		$categories = $categoriesModel->categoriesTree(NUll, true, false);
		$categories_options=@array_merge($categories_option, $categories);
		$lists['categories'] = JHTML::_('select.genericlist', $categories_options, 'catid', '', 'value', 'text', $scoresheet->catid);

		JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
		$category = & JTable::getInstance('dlCategory', 'Table');
		$category->load($scoresheet->catid);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'extrafield.php');
		$extraFieldModel= new dlModelExtraField;
		if($scoresheet->id)
			$extraFields = $extraFieldModel->getExtraFieldsByGroup($category->extraFieldsGroup);
		else $extraFields = NULL;


		for($i=0; $i<sizeof($extraFields); $i++){
			$extraFields[$i]->element=$extraFieldModel->renderExtraField($extraFields[$i],$scoresheet->id);
		}

		if($scoresheet->id){
			$scoresheet->attachments=$model->getAttachments($scoresheet->id);
			$rating = $model->getRating();
			if(is_null($rating)){
				$scoresheet->ratingSum = 0;
				$scoresheet->ratingCount = 0;
			}
			else{
				$scoresheet->ratingSum = (int)$rating->rating_sum;
				$scoresheet->ratingCount = (int)$rating->rating_count;
			}
		}
		else {
			$scoresheet->attachments = NULL;
			$scoresheet->ratingSum = 0;
			$scoresheet->ratingCount = 0;
		}


        if($user->gid<24 && $params->get('lockTags'))
			$params->set('taggingSystem',0);

		$tags=$model->getAvailableTags($scoresheet->id);
		$lists['tags'] = JHTML::_ ( 'select.genericlist', $tags, 'tags', 'multiple="multiple" size="10" ', 'id', 'name' );

		if (isset($scoresheet->id)){
			$scoresheet->tags=$model->getCurrentTags($scoresheet->id);
			$lists['selectedTags'] = JHTML::_ ( 'select.genericlist', $scoresheet->tags, 'selectedTags[]', 'multiple="multiple" size="10" ', 'id', 'name' );
		}
		else {
			$lists['selectedTags']='<select size="10" multiple="multiple" id="selectedTags" name="selectedTags[]"></select>';
		}

		$lists['metadata']=new JParameter($scoresheet->metadata);

		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'dl'.DS.'scoresheets'.DS.'cache'.DS.md5("Image".$scoresheet->id).'_L.jpg'))
			$scoresheet->image = JURI::root().'media/dl/scoresheets/cache/'.md5("Image".$scoresheet->id).'_L.jpg';

		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'dl'.DS.'scoresheets'.DS.'cache'.DS.md5("Image".$scoresheet->id).'_S.jpg'))
			$scoresheet->thumb = JURI::root().'media/dl/scoresheets/cache/'.md5("Image".$scoresheet->id).'_S.jpg';


		JPluginHelper::importPlugin ( 'dl' );
		$dispatcher = &JDispatcher::getInstance ();

		$dlPluginsscoresheetContent=$dispatcher->trigger('onRenderAdminForm', array (&$scoresheet, 'scoresheet', 'content' ) );
		$this->assignRef('dlPluginsscoresheetContent', $dlPluginsscoresheetContent);

		$dlPluginsscoresheetImage=$dispatcher->trigger('onRenderAdminForm', array (&$scoresheet, 'scoresheet', 'image' ) );
		$this->assignRef('dlPluginsscoresheetImage', $dlPluginsscoresheetImage);

		$dlPluginsscoresheetGallery=$dispatcher->trigger('onRenderAdminForm', array (&$scoresheet, 'scoresheet', 'gallery' ) );
		$this->assignRef('dlPluginsscoresheetGallery', $dlPluginsscoresheetGallery);

		$dlPluginsscoresheetVideo=$dispatcher->trigger('onRenderAdminForm', array (&$scoresheet, 'scoresheet', 'video' ) );
		$this->assignRef('dlPluginsscoresheetVideo', $dlPluginsscoresheetVideo);

		$dlPluginsscoresheetExtraFields=$dispatcher->trigger('onRenderAdminForm', array (&$scoresheet, 'scoresheet', 'extra-fields' ) );
		$this->assignRef('dlPluginsscoresheetExtraFields', $dlPluginsscoresheetExtraFields);

		$dlPluginsscoresheetAttachments=$dispatcher->trigger('onRenderAdminForm', array (&$scoresheet, 'scoresheet', 'attachments' ) );
		$this->assignRef('dlPluginsscoresheetAttachments', $dlPluginsscoresheetAttachments);

		$dlPluginsscoresheetOther=$dispatcher->trigger('onRenderAdminForm', array (&$scoresheet, 'scoresheet', 'other' ) );
		$this->assignRef('dlPluginsscoresheetOther', $dlPluginsscoresheetOther);

		$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'scoresheet.xml');
		$form->loadINI($scoresheet->params);
		$this->assignRef('form', $form);

		$this->assignRef('extraFields', $extraFields);
		$this->assignRef('options', $options);
		$this->assignRef('row', $scoresheet);
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		$this->assignRef('user', $user);
		(JRequest::getInt('cid'))? $title = JText::_('Edit scoresheet') : $title = JText::_('Add scoresheet');
		JToolBarHelper::title($title, 'dl.png');
		JToolBarHelper::save();
		JToolBarHelper::custom('saveAndNew','save.png','save_f2.png','Save &amp; New', false);
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
                                // new toolbar
        JToolBarHelper::help( 'screen.scoresheet',true ); 

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
