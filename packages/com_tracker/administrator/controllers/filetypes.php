<?php
/**
 * @version			2.5.14-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

class TrackerControllerFiletypes extends JControllerAdmin {

	protected $text_prefix = 'COM_TRACKER_FILETYPES';

	public function getModel($name = 'Filetype', $prefix = 'TrackerModel', $config = array()) {
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
}