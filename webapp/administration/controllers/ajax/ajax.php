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

class CAdminAjax extends Controller_Administration
{
	function __construct() 
	{
		parent::__construct();
		$this->ajax = true;
	}

	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		switch ($this->action) 
		{
		case 'bulk_actions':
			break;

		case 'location': // This is the autocomplete AJAX
			$cities = City::newInstance()->ajax(Params::getParam("term"));
			echo json_encode($cities);
			break;

		case 'alerts': // Allow to register to an alert given (not sure it's used on admin)
			$alert = Params::getParam("alert");
			$email = Params::getParam("email");
			$userid = Params::getParam("userid");
			if ($alert != '' && $email != '') 
			{
				Alerts::newInstance()->insert(array('fk_i_user_id' => $userid, 's_email' => $email, 's_search' => $alert, 'e_type' => 'DAILY'));
				echo "1";
				return true;
			}
			echo '0';
			break;

		case 'runhook': //Run hooks
			$hook = Params::getParam("hook");
			switch ($hook) 
			{
			case 'item_form':
				$catId = Params::getParam("catId");
				if ($catId != '') 
				{
					osc_run_hook("item_form", $catId);
				}
				else
				{
					osc_run_hook("item_form");
				}
				break;

			case 'item_edit':
				$catId = Params::getParam("catId");
				$itemId = Params::getParam("itemId");
				osc_run_hook("item_edit", $catId, $itemId);
				break;

			default:
				if ($hook == '') 
				{
					return false;
				}
				else
				{
					osc_run_hook($hook);
				}
				break;
			}
			break;

		case 'items': // Return items (use external file administration/ajax/item_processing.php)
			require_once osc_admin_base_path() . 'ajax/items_processing.php';
			$items_processing = new ItemsProcessingAjax(Params::getParamsAsArray("get"));
			break;

		case 'categories_order': // Save the order of the categories
			$aIds = Params::getParam('list');
			$orderParent = 0;
			$orderSub = 0;
			$catParent = 0;
			$catManager = ClassLoader::getInstance()->getClassInstance( 'Model_Category' );
			foreach ($aIds as $id => $parent) 
			{
				if ($parent == 'root') 
				{
					if (!$catManager->updateOrder($id, $orderParent)) 
					{
						$error = 1;
					}
					// set parent category
					$conditions = array('pk_i_id' => $id);
					$array['fk_i_parent_id'] = NULL;
					if (!$catManager->update($array, $conditions) > 0) 
					{
						$error = 1;
					}
					$orderParent++;
				}
				else
				{
					if ($parent != $catParent) 
					{
						$catParent = $parent;
						$orderSub = 0;
					}
					if (!$catManager->updateOrder($id, $orderSub)) 
					{
						$error = 1;
					}
					// set parent category
					$conditions = array('pk_i_id' => $id);
					$array['fk_i_parent_id'] = $catParent;
					if (!$catManager->update($array, $conditions) > 0) 
					{
						$error = 1;
					}
					$orderSub++;
				}
			}
			$result = "{";
			$error = 0;
			if ($error) 
			{
				$result.= '"error" : "' . __("Some error ocurred") . '"';
			}
			else
			{
				$result.= '"ok" : "' . __("Order saved") . '"';
			}
			$result.= "}";
			echo $result;
			break;

		case 'field_categories_iframe':
			$selected = Field::newInstance()->categories(Params::getParam("id"));
			if ($selected == null) 
			{
				$selected = array();
			};
			$this->getView()->assign("selected", $selected);
			$this->getView()->assign("field", Field::newInstance()->findByPrimaryKey(Params::getParam("id")));
			$this->getView()->assign("categories", ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->toTreeAll());
			echo $this->getView()->render( 'fields/iframe.php' );
			break;

		case 'field_categories_post':
			$error = 0;
			if (!$error) 
			{
				try
				{
					$field = Field::newInstance()->findByName(Params::getParam("s_name"));
					if (!isset($field['pk_i_id']) || (isset($field['pk_i_id']) && $field['pk_i_id'] == Params::getParam("id"))) 
					{
						Field::newInstance()->cleanCategoriesFromField(Params::getParam("id"));
						$slug = Params::getParam("field_slug") != '' ? Params::getParam("field_slug") : Params::getParam("id");
						$slug = preg_replace('|([-]+)|', '-', preg_replace('|[^a-z0-9_-]|', '-', strtolower($slug)));
						Field::newInstance()->update(array('s_name' => Params::getParam("s_name"), 'e_type' => Params::getParam("field_type"), 's_slug' => $slug, 'b_required' => Params::getParam("field_required") == "1" ? 1 : 0, 's_options' => Params::getParam('s_options')), array('pk_i_id' => Params::getParam("id")));
						Field::newInstance()->insertCategories(Params::getParam("id"), Params::getParam("categories"));
					}
					else
					{
						$error = 1;
						$message = __("Sorry, you already have one field with that name");
					}
				}
				catch(Exception $e) 
				{
					$error = 1;
					$message = __("Error while updating.");
				}
			}
			$result = "{";
			if ($error) 
			{
				$result.= '"error" : "';
				$result.= $message;
				$result.= '"';
			}
			else
			{
				$result.= '"ok" : "' . __("Saved") . '", "text" : "' . Params::getParam("s_name") . '"';
			}
			$result.= "}";
			echo $result;
			break;

		case 'custom': // Execute via AJAX custom file
			$ajaxfile = Params::getParam("ajaxfile");
			if ($ajaxfile != '') 
			{
				require_once osc_admin_base_path() . $ajaxfile;
			}
			else
			{
				echo json_encode(array('error' => __('no action defined')));
			}
			break;

		case 'test_mail':
			$title = __('Test email') . ", " . osc_page_title();
			$body = __("Test email") . "<br><br>" . osc_page_title();
			$emailParams = array('subject' => $title, 'to' => osc_contact_email(), 'to_name' => 'admin', 'body' => $body, 'alt_body' => $body);
			$array = array();
			if (osc_sendMail($emailParams)) 
			{
				$array = array('status' => '1', 'html' => __('Email sent successfully'));
			}
			else
			{
				$array = array('status' => '0', 'html' => __('An error has occurred while sending email'));
			}
			echo json_encode($array);
			break;

		case 'order_pages':
			$order = Params::getParam("order");
			$id = Params::getParam("id");
			$count = osc_count_static_pages();
			if ($order != '' && $id != '') 
			{
				$mPages = ClassLoader::getInstance()->getClassInstance( 'Model_Page' );
				$actual_page = $mPages->findByPrimaryKey($id);
				$actual_order = $actual_page['i_order'];
				$array = array();
				$condition = array();
				$new_order = $actual_order;
				if ($order == 'up') 
				{
					if ($actual_order > 0) 
					{
						$new_order = $actual_order - 1;
					}
				}
				else if ($order == 'down') 
				{
					if ($actual_order != ($count - 1)) 
					{
						$new_order = $actual_order + 1;
					}
				}
				if ($new_order != $actual_order) 
				{
					$auxpage = $mPages->findByOrder($new_order);
					$array = array('i_order' => $actual_order);
					$conditions = array('pk_i_id' => $auxpage['pk_i_id']);
					$mPages->update($array, $conditions);
					$array = array('i_order' => $new_order);
					$conditions = array('pk_i_id' => $id);
					$mPages->update($array, $conditions);
				}
				else
				{
				}
				// json for datatables
				$prefLocale = osc_current_admin_locale();
				$aPages = $mPages->listAll(0);
				$json = "[";
				foreach ($aPages as $key => $page) 
				{
					$body = array();
					if (isset($page['locale'][$prefLocale]) && !empty($page['locale'][$prefLocale]['s_title'])) 
					{
						$body = $page['locale'][$prefLocale];
					}
					else
					{
						$body = current($page['locale']);
					}
					$p_body = str_replace("'", "\'", trim(strip_tags($body['s_title']), "\x22\x27"));
					$json.= "[\"<input type='checkbox' name='id[]' value='" . $page['pk_i_id'] . "' />\",";
					$json.= "\"" . $page['s_internal_name'] . "<div id='datatables_quick_edit'>";
					$json.= "<a href='" . osc_static_page_url() . "'>" . __('View page') . "</a> | ";
					$json.= "<a href='" . osc_admin_base_url(true) . "?page=pages&action=edit&id=" . $page['pk_i_id'] . "'>";
					$json.= __('Edit') . "</a>";
					if (!$page['b_indelible']) 
					{
						$json.= " | ";
						$json.= "<a onclick=\\\"javascript:return confirm('";
						$json.= __('This action can\\\\\'t be undone. Are you sure you want to continue?') . "')\\\" ";
						$json.= " href='" . osc_admin_base_url(true) . "?page=pages&action=delete&id=" . $page['pk_i_id'] . "'>";
						$json.= __('Delete') . "</a>";
					}
					$json.= "</div>\",";
					$json.= "\"" . $p_body . "\",";
					$json.= "\"<img id='up' onclick='order_up(" . $page['pk_i_id'] . ");' style='cursor:pointer;width:15;height:15px;' src='" . osc_current_admin_theme_url('images/arrow_up.png') . "'/> <br/> <img id='down' onclick='order_down(" . $page['pk_i_id'] . ");' style='cursor:pointer;width:15;height:15px;' src='" . osc_current_admin_theme_url('images/arrow_down.png') . "'/>\"]";
					if ($key != count($aPages) - 1) 
					{
						$json.= ',';
					}
					else
					{
						$json.= '';
					}
				}
				$json.= "]";
				echo $json;
			}
			break;
		}
		$this->getSession()->_dropKeepForm();
		$this->getSession()->_clearVariables();
	}
}
