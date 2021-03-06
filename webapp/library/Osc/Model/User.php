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
 * User DAO
 */
class User extends \DAO
{
	function __construct() 
	{
		parent::__construct();
		$this->setTableName('user');
		$this->setPrimaryKey('pk_i_id');
		$array_fields = array('pk_i_id', 'reg_date', 'mod_date', 's_name', 's_password', 's_secret', 's_email', 's_website', 's_phone_land', 's_phone_mobile', 'b_enabled', 'b_active', 's_pass_code', 's_pass_date', 's_pass_question', 's_pass_answer', 's_pass_ip', 'fk_c_country_code', 's_country', 's_address', 's_zip', 'fk_i_region_id', 's_region', 'fk_i_city_id', 's_city', 'fk_i_city_area_id', 's_city_area', 'd_coord_lat', 'd_coord_long', 'i_permissions', 'b_company', 'i_items', 'i_comments', 's_username', 'role_id' );
		$this->setFields($array_fields);
	}
	/**
	 * Find an user by its primary key
	 *
	 * @access public
	 * @since unknown
	 * @param int $id
	 * @param string $locale
	 * @return array
	 */
	public function findByPrimaryKey($id, $locale = null) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where($this->getPrimaryKey(), $id);
		$result = $this->dbCommand->get();
		$row = $result->row();
		if ($result->numRows() != 1) 
		{
			return array();
		}
		$this->dbCommand->select();
		$this->dbCommand->from(DB_TABLE_PREFIX . 't_user_description');
		$this->dbCommand->where('fk_i_user_id', $id);
		if (!is_null($locale)) 
		{
			$this->dbCommand->where('fk_c_locale_code', $locale);
		}
		$result = $this->dbCommand->get();
		$descriptions = $result->result();
		$row['locale'] = array();
		foreach ($descriptions as $sub_row) 
		{
			$row['locale'][$sub_row['fk_c_locale_code']] = $sub_row;
		}
		return $row;
	}

	public function findByEmailPassword( $email, $password )
	{
		$sql = <<<SQL
SELECT
	pk_i_id, reg_date, mod_date, s_name, s_password, s_secret, s_email, s_website, s_phone_land, s_phone_mobile, b_enabled, b_active, s_pass_code, s_pass_date, s_pass_question, s_pass_answer, s_pass_ip, fk_c_country_code, s_country, s_address, s_zip, fk_i_region_id, s_region, fk_i_city_id, s_city, fk_i_city_area_id, s_city_area, d_coord_lat, d_coord_long, i_permissions, b_company, i_items, i_comments
FROM
	/*TABLE_PREFIX*/user
WHERE
	s_email = ?
AND
	s_password = SHA1( ? )
LIMIT
	1
SQL;

		$stmt = $this->prepareStatement( $sql );
		$stmt->bind_param( 'ss', $email, $password );
		$user = $this->fetch( $stmt );
		$stmt->close();

		return $user;
	}

	/**
	 * Find an user by its email
	 *
	 * @access public
	 * @since unknown
	 * @param string $email
	 * @return array
	 */
	public function findByEmail($email) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$this->dbCommand->where('s_email', $email);
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return false;
		}
		else if ($result->numRows() == 1) 
		{
			return $result->row();
		}
		else
		{
			return array();
		}
	}
	/**
	 * Find an user by its id and secret
	 *
	 * @access public
	 * @since unknown
	 * @param string $id
	 * @param string $secret
	 */
	public function findByIdSecret($id, $secret) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$conditions = array('pk_i_id' => $id, 's_secret' => $secret);
		$this->dbCommand->where($conditions);
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return false;
		}
		else if ($result->numRows() == 1) 
		{
			return $result->row();
		}
		else
		{
			return array();
		}
	}
	/**
	 *
	 *
	 * @access public
	 * @since unknown
	 * @param string $id
	 * @param string $secret
	 * @return array
	 */
	public function findByIdPasswordSecret($id, $secret) 
	{
		if ($secret == '') 
		{
			return null;
		}
		$date = date("Y-m-d H:i:s", (time() - (24 * 3600)));
		$this->dbCommand->select();
		$this->dbCommand->from($this->getTableName());
		$conditions = array('pk_i_id' => $id, 's_pass_code' => $secret);
		$this->dbCommand->where($conditions);
		$this->dbCommand->where("s_pass_date >= '$date'");
		$result = $this->dbCommand->get();
		if ($result == false) 
		{
			return false;
		}
		else if ($result->numRows() == 1) 
		{
			return $result->row();
		}
		else
		{
			return array();
		}
	}
	/**
	 * Delete an user given its id
	 *
	 * @access public
	 * @since unknown
	 * @param int $id
	 * @return bool
	 */
	public function deleteUser( $id ) 
	{
		osc_run_hook('delete_user', $id);
		$this->dbCommand->select('pk_i_id, fk_i_category_id');
		$this->dbCommand->from(DB_TABLE_PREFIX . "item");
		$this->dbCommand->where('fk_i_user_id', $id);
		$result = $this->dbCommand->get();
		$items = $result->result();
		$itemManager = ClassLoader::getInstance()->getClassInstance( 'Model_Item' );
		foreach ($items as $item) 
		{
			$itemManager->deleteByPrimaryKey($item['pk_i_id']);
		}
		ClassLoader::getInstance()->getClassInstance( 'Model_ItemComment' )->delete(array('fk_i_user_id' => $id));
		$this->dbCommand->delete(DB_TABLE_PREFIX . 't_user_email_tmp', array('fk_i_user_id' => $id));
		$this->dbCommand->delete(DB_TABLE_PREFIX . 't_user_description', array('fk_i_user_id' => $id));
		$this->dbCommand->delete(DB_TABLE_PREFIX . 't_alerts', array('fk_i_user_id' => $id));
		return $this->dbCommand->delete($this->getTableName(), array('pk_i_id' => $id));
	}
	/**
	 * Insert users' description
	 *
	 * @access private
	 * @since unknown
	 * @param int $id
	 * @param string $locale
	 * @param string $info
	 * @return array
	 */
	private function insertDescription($id, $locale, $info) 
	{
		$array_set = array('fk_i_user_id' => $id, 'fk_c_locale_code' => $locale, 's_info' => $info);
		$res = $this->dbCommand->insert(DB_TABLE_PREFIX . 't_user_description', $array_set);
		if ($res) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	/**
	 * Update users' description
	 *
	 * @access public
	 * @since unknown
	 * @param int $id
	 * @param string $locale
	 * @param string $info
	 * @return bool
	 */
	public function updateDescription($id, $locale, $info) 
	{
		$conditions = array('fk_c_locale_code' => $locale, 'fk_i_user_id' => $id);
		$exist = $this->existDescription($conditions);
		if (!$exist) 
		{
			$result = $this->insertDescription($id, $locale, $info);
			return $result;
		}
		$array_where = array('fk_c_locale_code' => $locale, 'fk_i_user_id' => $id);
		$result = $this->dbCommand->update(DB_TABLE_PREFIX . 't_user_description', array('s_info' => $info), $array_where);
		return $result;
	}
	/**
	 * Check if a description exists
	 *
	 * @access public
	 * @since unknown
	 * @param array $conditions
	 * @return bool
	 */
	private function existDescription($conditions) 
	{
		$this->dbCommand->select();
		$this->dbCommand->from(DB_TABLE_PREFIX . 't_user_description');
		$this->dbCommand->where($conditions);
		$result = $this->dbCommand->get();
		if ($result == false || $result->numRows() == 0) 
		{
			return false;
		}
		else
		{
			return true;
		}
		return (bool)$result;
	}

	public function findByUsernamePassword( $username, $password )
	{
		$user = null;

		$sql = <<<SQL
SELECT
	*
FROM
	/*TABLE_PREFIX*/user
WHERE
	s_username = ?
AND
	s_password = SHA1( ? )
LIMIT 1
SQL;

		$stmt = $this->prepareStatement( $sql );
		$stmt->bind_param( 'ss', $username, $password );
		if( $stmt->execute() )
		{
			$user = $this->fetch( $stmt );
		}
		$stmt->close();

		return $user;
	}
}

