<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2011 OpenSourceClassifieds
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
class CAdminEmail extends AdministrationController
{
	private $emailManager;
	function __construct() 
	{
		parent::__construct();
		$this->emailManager = ClassLoader::getInstance()->getClassInstance( 'Model_Page' );
	}
	public function doModel() 
	{
		parent::doModel();
		switch ($this->action) 
		{
		case 'edit':
			if (Params::getParam("id") == '') 
			{
				$this->redirectTo(osc_admin_base_url(true) . "?page=emails");
			}
			$this->getView()->_exportVariableToView("email", $this->emailManager->findByPrimaryKey(Params::getParam("id")));
			$this->doView("emails/frm.php");
			break;

		case 'edit_post':
			$id = Params::getParam("id");
			$s_internal_name = Params::getParam("s_internal_name");
			$aFieldsDescription = array();
			$postParams = Params::getParamsAsArray();
			$not_empty = false;
			foreach ($postParams as $k => $v) 
			{
				if (preg_match('|(.+?)#(.+)|', $k, $m)) 
				{
					if ($m[2] == 's_title' && $v != '') 
					{
						$not_empty = true;
					};
					$aFieldsDescription[$m[1]][$m[2]] = $v;
				}
			}
			if ($not_empty) 
			{
				foreach ($aFieldsDescription as $k => $_data) 
				{
					$this->emailManager->updateDescription($id, $k, $_data['s_title'], $_data['s_text']);
				}
				if (!$this->emailManager->internalNameExists($id, $s_internal_name)) 
				{
					if (!$this->emailManager->isIndelible($id)) 
					{
						$this->emailManager->updateInternalName($id, $s_internal_name);
					}
					osc_add_flash_ok_message(_m('The email/alert has been updated'), 'admin');
					$this->redirectTo(osc_admin_base_url(true) . "?page=emails");
				}
				osc_add_flash_error_message(_m('You can\'t repeat internal name'), 'admin');
			}
			else
			{
				osc_add_flash_error_message(_m('The email couldn\'t be updated, at least one title should not be empty'), 'admin');
			}
			$this->redirectTo(osc_admin_base_url(true) . "?page=emails?action=edit&id=" . $id);
			break;
		}
	}
}

