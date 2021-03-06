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

class CWebUser extends Controller_User
{
	function __construct() 
	{
		parent::__construct();
		if (!osc_users_enabled()) 
		{
			$this->getSession()->addFlashMessage( _m('Users not enabled'), 'ERROR' );
			$this->redirectTo(osc_base_url(true));
		}
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		switch ($this->action) 
		{
		case ('dashboard'):
			$max_items = (Params::getParam('max_items') != '') ? Params::getParam('max_items') : 5;
			$itemModel = new \Osc\Model\Item;
			$aItems = $itemModel->findByUserIDEnabled($this->getSession()->_get('userId'), 0, $max_items);

			$view = $this->getView();
			$this->getView()->assign('items', $aItems);
			$this->getView()->assign('max_items', $max_items);
			$view->setTitle( __('Dashboard', 'modern') . ' - ' . osc_page_title() );
			echo $this->getView()->render( 'user/index' );
			break;

		case 'activate_alert':
			$email = Params::getParam('email');
			$secret = Params::getParam('secret');
			$result = 0;
			if ($email != '' && $secret != '') 
			{
				$result = Alerts::newInstance()->activate($email, $secret);
			}
			if ($result == 1) 
			{
				$this->getSession()->addFlashMessage( _m('Alert activated') );
			}
			else
			{
				$this->getSession()->addFlashMessage( _m('Ops! There was a problem trying to activate alert. Please contact the administrator'), 'ERROR' );
			}
			$this->redirectTo(osc_base_url(true));
			break;

		case 'unsub_alert':
			$email = Params::getParam('email');
			$secret = Params::getParam('secret');
			if ($email != '' && $secret != '') 
			{
				Alerts::newInstance()->delete(array('s_email' => $email, 's_secret' => $secret));
				$this->getSession()->addFlashMessage( _m('Unsubscribed correctly') );
			}
			else
			{
				$this->getSession()->addFlashMessage( _m('Ops! There was a problem trying to unsubscribe you. Please contact the administrator'), 'ERROR' );
			}
			$this->redirectTo(osc_user_alerts_url());
			break;

		case 'deleteResource':
			$id = Params::getParam('id');
			$name = Params::getParam('name');
			$fkid = Params::getParam('fkid');
			osc_deleteResource($id);
			$resourceModel = new \Osc\Model\ItemResource;
			$resourceModel->delete(array('pk_i_id' => $id, 'fk_i_item_id' => $fkid, 's_name' => $name));
			$this->redirectTo(osc_base_url(true) . "?page=item&action=item_edit&id=" . $fkid);
			break;
		}
	}
}
