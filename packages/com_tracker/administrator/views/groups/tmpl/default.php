<?php
/**
 * @version			3.3.2-dev
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright	Copyright (C) 2007 - 2015 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted Access');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$component = JComponentHelper::getComponent('com_tracker');
$params = $component->params;

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_tracker');
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_tracker&task=groups.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'groupList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=groups'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>

	<div id="j-main-container" class="span10">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>
		<div class="clearfix"> </div>

		<table class="table table-striped" id="groupList">
			<thead>
				<tr>
                <th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>

				<th class='nowrap left'>
					<?php echo JHtml::_('grid.sort',  'JGLOBAL_FIELD_ID_LABEL', 'a.id', $listDirn, $listOrder); ?>
				</th>
				
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>

				<th class="center">
					<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_VIEW_TORRENTS', 'a.view_torrents', $listDirn, $listOrder); ?>
				</th>
				
				<th class="center">
					<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_EDIT_TORRENTS', 'a.edit_torrents', $listDirn, $listOrder); ?>
				</th>

				<th class="center">
					<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_DELETE_TORRENTS', 'a.delete_torrents', $listDirn, $listOrder); ?>
				</th>

				<th class="center">
					<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_UPLOAD_TORRENTS', 'a.upload_torrents', $listDirn, $listOrder); ?>
				</th>

				<th class="center">
					<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_DOWNLOAD_TORRENTS', 'a.download_torrents', $listDirn, $listOrder); ?>
				</th>

				<th class="center">
					<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_CAN_LEECH', 'a.can_leech', $listDirn, $listOrder); ?>
				</th>

				<?php if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') {?>
					<th class="center">
						<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_VIEW_COMMENTS', 'a.view_comments', $listDirn, $listOrder); ?>
					</th>

					<th class="center">
						<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_WRITE_COMMENTS', 'a.write_comments', $listDirn, $listOrder); ?>
					</th>

					<th class="center">
						<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_EDIT_COMMENTS', 'a.edit_comments', $listDirn, $listOrder); ?>
					</th>

					<th class="center">
						<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_DELETE_COMMENTS', 'a.delete_comments', $listDirn, $listOrder); ?>
					</th>

					<th class="center">
						<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_AUTOPUBLISH_COMMENTS', 'a.autopublish_comments', $listDirn, $listOrder); ?>
					</th>
				<?php } ?>

				<?php if ($params->get('torrent_multiplier')) {?>
					<th class="center">
						<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_DOWNLOAD_MULTIPLIER', 'a.download_multiplier', $listDirn, $listOrder); ?>
					</th>

					<th class="center">
						<?php echo JHtml::_('grid.sort',  'COM_TRACKER_GROUP_UPLOAD_MULTIPLIER', 'a.upload_multiplier', $listDirn, $listOrder); ?>
					</th>
				<?php } ?>

				<?php if (isset($this->items[0]->state)): ?>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>

				</tr>
			</thead>
			<tfoot>
                <?php 
                if(isset($this->items[0])){
                    $colspan = count(get_object_vars($this->items[0]));
                }
                else{
                    $colspan = 9;
                }
            ?>
			<tr>
				<td colspan="<?php echo $colspan ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering   = ($listOrder == 'a.ordering');
                $canCreate	= $user->authorise('core.create',		'com_tracker');
                $canEdit	= $user->authorise('core.edit',			'com_tracker');
                $canCheckin	= $user->authorise('core.manage',		'com_tracker');
                $canChange	= $user->authorise('core.edit.state',	'com_tracker');
			?>
				<tr class="row<?php echo $i % 2; ?>">

                <td class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
                    
				<td><?php echo $item->id; ?></td>

				<td>
				<?php
					if ($canEdit) echo "<a href=".JRoute::_('index.php?option=com_tracker&task=group.edit&id='.(int) $item->id).">".$this->escape($item->name)."</a>";
					else echo $item->name;
				?>
				</td>

				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->view_torrents, 'groups.view_torrents', 'groups.no_view_torrents'); ?></td>

				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->edit_torrents, 'groups.edit_torrents', 'groups.no_edit_torrents'); ?></td>

				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->delete_torrents, 'groups.delete_torrents', 'groups.no_delete_torrents'); ?></td>

				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->upload_torrents, 'groups.upload_torrents', 'groups.no_upload_torrents'); ?></td>

				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->download_torrents, 'groups.download_torrents', 'groups.no_download_torrents'); ?></td>

				<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->can_leech, 'groups.can_leech', 'groups.no_can_leech'); ?></td>

				<?php if ($params->get('enable_comments') && $params->get('comment_system') == 'internal') {?>
					<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->view_comments, 'groups.view_comments', 'groups.no_view_comments'); ?></td>

					<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->write_comments, 'groups.write_comments', 'groups.no_write_comments'); ?></td>

					<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->edit_comments, 'groups.edit_comments', 'groups.no_edit_comments'); ?></td>

					<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->delete_comments, 'groups.delete_comments', 'groups.no_delete_comments'); ?></td>

					<td class="center"><?php echo JHtml::_('grid.boolean', $i, $item->autopublish_comments, 'groups.autopublish_comments', 'groups.no_autopublish_comments'); ?></td>
				<?php } ?>

				<?php if ($params->get('torrent_multiplier')) {?>
					<td class="center"><?php echo $item->download_multiplier; ?></td>

					<td class="center"><?php echo $item->upload_multiplier; ?></td>
				<?php } ?>
				
                <?php if (isset($this->items[0]->state)): ?>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'groups.', $canChange, 'cb'); ?>
					</td>
                <?php endif; ?>

				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
 
		<?php /*endif; */?>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
