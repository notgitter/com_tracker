<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class TrackerViewTorrents extends JViewLegacy {

	protected $state;
	protected $items;
	protected $pagination;

	public function display($tpl = null) {
		$app	= JFactory::getApplication();

		// Initialise variables
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->params		= $app->getParams();
		$this->user			= JFactory::getUser();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		if ($this->user->get('guest') && ($this->params->get('allow_guest') == 0)) {
			$app->redirect(JUri::base() . 'index.php', JText::_('COM_TRACKER_NOT_LOGGED_IN'), 'error');
		}

		if ($this->user->get('guest') && $this->params->get('allow_guest') == 1) {
			$this->user->id = $this->params->get('guest_user');
		}

		return parent::display($tpl);
	}
}
