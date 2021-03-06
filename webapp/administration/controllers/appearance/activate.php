<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2012 OpenSourceClassifieds
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
class CAdminAppearance extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$preferenceModel = $this->getClassLoader()
			->getClassInstance( 'Model_Preference' );
		$preferenceModel->update( array( 's_value' => Params::getParam('theme')), array('s_section' => 'osc', 's_name' => 'theme'));
		$this->getSession()->addFlashMessage( _m('Theme activated correctly'), 'admin' );
		osc_run_hook("theme_activate", Params::getParam('theme'));
		$this->redirectTo( osc_admin_base_url( true ) . "?page=appearance" );
	}
}

