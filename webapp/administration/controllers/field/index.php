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
class CAdminField extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$this->fieldManager = $this->getClassLoader()->getClassInstance( 'Model_Field' );

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
		$this->getView()->assign("categories", $categories);
		$this->getView()->assign("selected", $selected);
		$this->getView()->assign("fields", $this->fieldManager->listAll());
		$this->doView("fields/index.php");
	}
}

