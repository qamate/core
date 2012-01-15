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
class AdminSecBaseModel extends SecBaseModel
{
	public function isLogged() 
	{
		return osc_is_admin_user_logged_in();
	}
	public function logout() 
	{
		//destroying session
		Session::newInstance()->session_destroy();
		Session::newInstance()->_drop('adminId');
		Session::newInstance()->_drop('adminUserName');
		Session::newInstance()->_drop('adminName');
		Session::newInstance()->_drop('adminEmail');
		Session::newInstance()->_drop('adminLocale');
		Cookie::newInstance()->pop('oc_adminId');
		Cookie::newInstance()->pop('oc_adminSecret');
		Cookie::newInstance()->pop('oc_adminLocale');
		Cookie::newInstance()->set();
	}
	public function showAuthFailPage() 
	{
		require osc_admin_base_path() . '/gui/login.php';
		exit;
	}
}