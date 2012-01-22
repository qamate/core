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
class CAdminField extends AdministrationController
{
	private $fieldManager;
	function __construct() 
	{
		parent::__construct();
		$this->fieldManager = $this->getClassLoader()->getClassInstance( 'Model_Field' );
	}
	function doModel() 
	{
		parent::doModel();

		$categories = ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->toTreeAll();
		$selected = array();
		foreach ($categories as $c) 
		{
			$selected[] = $c['pk_i_id'];
			foreach ($c['categories'] as $cc) 
			{
				$selected[] = $cc['pk_i_id'];
			}
		}
		$this->getView()->_exportVariableToView("categories", $categories);
		$this->getView()->_exportVariableToView("default_selected", $selected);
		$this->getView()->_exportVariableToView("fields", $this->fieldManager->listAll());
		$this->doView("fields/index.php");
	}
}

