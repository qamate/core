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
class CAdminCategory extends AdminSecBaseModel
{
	private $categoryManager;
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		$this->categoryManager = Category::newInstance();
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		osc_current_admin_theme_path( 'categories/settings.php' );
		Session::newInstance()->_clearVariables();
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$selectableParent = Params::getParam('selectable_parent_categories');
		$updated = Preference::newInstance()->update(array('s_value' => $selectableParent), array('s_name' => 'selectable_parent_categories'));
		if ($updated > 0) 
		{
			osc_add_flash_ok_message(_m('Categories\' settings have been updated'), 'admin');
		}
		$this->redirectTo(osc_admin_base_url(true) . '?page=category&action=settings');
	}
}
