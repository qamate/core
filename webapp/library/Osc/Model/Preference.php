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
 *
 */
class Preference extends \DAO
{
	private $pref;
	function __construct() 
	{
		parent::__construct();
		$this->setTableName('t_preference');
		/* $this->set_primary_key($key) ; // no primary key in preference table */
		$this->setFields(array('s_section', 's_name', 's_value', 'e_type'));
		$this->toArray();
	}
	/**
	 * Find a value by its name
	 *
	 * @access public
	 * @since unknown
	 * @param type $name
	 * @return type
	 */
	function findValueByName($name) 
	{
		$this->dbCommand->select('s_value');
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('s_name', $name);
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return false;
		}
		if ($result->numRows() == 0) 
		{
			return false;
		}
		$row = $result->row();
		return $row['s_value'];
	}
	/**
	 * Find array preference for a given section
	 *
	 * @access public
	 * @since unknown
	 * @param string $name
	 * @return array
	 */
	public function findBySection($name) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('s_section', $name);
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return array();
		}
		if ($result->numRows() == 0) 
		{
			return false;
		}
		return $result->result();
	}
	/**
	 * Modify the structure of table.
	 *
	 * @access public
	 * @since unknown
	 */
	public function toArray() 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return false;
		}
		if ($result->numRows() == 0) 
		{
			return false;
		}
		$aTmpPref = $result->result();
		foreach ($aTmpPref as $tmpPref) 
		{
			$this->pref[$tmpPref['s_section']][$tmpPref['s_name']] = $tmpPref['s_value'];
		}
		return true;
	}
	/**
	 * Get value, given a preference name and a section name.
	 *
	 * @access public
	 * @since unknown
	 * @param string $key
	 * @param string $section
	 * @return string
	 */
	public function get( $key, $section = 'osc' ) 
	{
		if( !isset( $this->pref[ $section ][ $key ] ) ) 
		{
			return null;
		}
		return $this->pref[ $section ][ $key ];
	}
	/**
	 * Set preference value, given a preference name and a section name.
	 *
	 * @access public
	 * @since unknown
	 * @param string $key
	 * @param string$value
	 * @param string $section
	 */
	public function set($key, $value, $section = "osc") 
	{
		$this->pref[$section][$key] = $value;
	}
	/**
	 * Replace preference value, given preference name, preference section and type value.
	 *
	 * @access public
	 * @since unknown
	 * @param string $key
	 * @param string $value
	 * @param string $section
	 * @param string $type
	 * @return boolean
	 */
	public function replace($key, $value, $section = 'osc', $type = 'STRING') 
	{
		$array_replace = array('s_name' => $key, 's_value' => $value, 's_section' => $section, 'e_type' => $type);
		return $this->dbCommand->replace($this->getTableName(), $array_replace);
	}

	public function insertOrUpdate( $key, $value, $section = 'osc', $type = 'STRING' )
	{
		$sql = <<<SQL
INSERT INTO
	/*TABLE_PREFIX*/t_preference
	( s_name, s_value, s_section, e_type )
VALUES
	( ?, ?, ?, ? )
ON DUPLICATE KEY
UPDATE
	s_value = ?
SQL;

		$stmt = $this->prepareStatement( $sql );
		$stmt->bind_param( 'sssss', $key, $value, $section, $type, $value );
		$result = $stmt->execute();
		$stmt->close();

		return $result;
	}
}

