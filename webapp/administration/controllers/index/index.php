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

class CAdminIndex extends Controller_Administration 
{
	public function doGet( HttpRequest $req, HttpResponse $res ) 
	{
		$classLoader = $this->getClassLoader();
		$classLoader->loadFile( 'helpers/feeds' );

		$userModel = new \Osc\Model\User;
		$itemModel = new \Osc\Model\Item;
		$commentModel = new \Osc\Model\ItemComment;

		$this->getView()->assign( "numUsers", $userModel->count() );
		$this->getView()->assign( "numItems", $itemModel->count() );
		$this->getView()->assign( "numItemsPerCategory", osc_get_non_empty_categories() );
		$this->getView()->assign( "newsList", osc_listNews() );
		$this->getView()->assign( "comments", $commentModel->getLastComments(5) );

		$this->doView('main/index.php');
	}
}

