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
class CAdminPlugin extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$pluginManager = ClassLoader::getInstance()->getClassInstance( 'PluginManager' );
		$plugin = Params::getParam("plugin");
		$fxName = $plugin . '_admin';
		call_user_func( $fxName );
	}

	public function doPost( HttpRequest $req, HttpResponse $res )
	{
		$pluginManager = ClassLoader::getInstance()->getClassInstance( 'PluginManager' );
		$pluginManager->runHook('admin_post');
	}
}

