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

class SecureController extends Controller
{
	public function __construct() 
	{
		parent::__construct();
		if (!$this->isLogged()) 
		{
			//If we are not logged or we do not have permissions -> go to the login page
			$this->logout();
			$this->showAuthFailPage();
		}
	}
	public function setGranting($grant) 
	{
		$this->grant = $grant;
	}
	public function logout() 
	{
		$this->getSession()->destroy();
	}
	public function doModel() 
	{
	}
	public function doView($file) 
	{
	}
}