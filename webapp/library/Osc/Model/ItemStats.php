<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2012 OpenSourceClassifieds
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
namespace Osc\Model;
/**
 * Model database for ItemStat table
 *
 * @package OpenSourceClassifieds
 * @subpackage Model
 * @since unknown
 */
class ItemStats extends \DAO
{
	/**
	 * Set data related to t_item_stats table
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->setTableName('t_item_stats');
		$this->setPrimaryKey('fk_i_item_id');
		$this->setFields(array('fk_i_item_id', 'i_num_views', 'i_num_spam', 'i_num_repeated', 'i_num_bad_classified', 'i_num_offensive', 'i_num_expired', 'i_num_premium_views', 'dt_date'));
	}
	/**
	 * Increase the stat column given column name and item id
	 *
	 * @access public
	 * @since unknown
	 * @param string $column
	 * @param int $itemId
	 * @return bool
	 * @todo OJO query('update ....') cambiar a ->update()
	 */
	function increase($column, $itemId) 
	{
		//('INSERT INTO %s (fk_i_item_id, dt_date, %3$s) VALUES (%d, \'%4$s\',1) ON DUPLICATE KEY UPDATE %3$s = %3$s + 1', $this->getTableName(), $id, $column, date('Y-m-d H:i:s')) ;
		$increaseColumns = array('i_num_views', 'i_num_spam', 'i_num_repeated', 'i_num_bad_classified', 'i_num_offensive', 'i_num_expired', 'i_num_expired', 'i_num_premium_views');
		if (!in_array($column, $increaseColumns)) 
		{
			return false;
		}
		$sql = 'INSERT INTO ' . $this->getTableName() . ' (fk_i_item_id, dt_date, ' . $column . ') VALUES (' . $itemId . ', \'' . date('Y-m-d H:i:s') . '\',1) ON DUPLICATE KEY UPDATE  ' . $column . ' = ' . $column . ' + 1 ';
		return $this->dbCommand->query($sql);
	}
	/**
	 * Insert an empty row into table item stats
	 *
	 * @access public
	 * @since unknown
	 * @param int $itemId Item id
	 * @return bool
	 */
	function emptyRow($itemId) 
	{
		return $this->insert(array('fk_i_item_id' => $itemId, 'dt_date' => date('Y-m-d H:i:s')));
	}
	/**
	 * Return number of views of an item
	 *
	 * @access public
	 * @since 2.3.3
	 * @param int $itemId Item id
	 * @return int
	 */
	function getViews($itemId) 
	{
		$this->dbCommand->select('SUM(i_num_views) AS i_num_views');
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('fk_i_item_id', $itemId);
		$result = $this->dbCommand->get();
		if (!$result) 
		{
			return 0;
		}
		else
		{
			$res = $result->result();
			return $res[0]['i_num_views'];
		}
	}
}
