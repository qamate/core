<?php
/**
 * Helper Users
 * @package OpenSourceClassifieds
 * @subpackage Helpers
 * @author OpenSourceClassifieds
 */
/**
 * Gets a specific field from current user
 *
 * @param string $field
 * @param string $locale
 * @return mixed
 */
function osc_user_field( array $user, $field, $locale = null )
{
	return osc_field( $user, $field, $locale);
}
/**
 * Gets user array from view
 *
 * @return array
 */
function sc_user() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	if ($view->_exists('users')) 
	{
		$user = $view->_current('users');
	}
	else
	{
		$user = $view->_get('user');
	}
	return ($user);
}
/**
 * Gets true if user is logged in web
 *
 * @return boolean
 */
function osc_is_web_user_logged_in() 
{
	$classLoader = ClassLoader::getInstance();
	$session = $classLoader->getClassInstance( 'Session' );
	$userModel = new \Osc\Model\User;
	if ( $session->_get("userId") != '') 
	{
		$user = $userModel->findByPrimaryKey( $session->_get("userId"));
		if (isset($user['b_enabled']) && $user['b_enabled'] == 1) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//can already be a logged user or not, we'll take a look into the cookie

	$cookie = new \Cuore\Input\Cookie;
	if ($cookie->getValue('oc_userId') != '' && $cookie->getValue('oc_userSecret') != '') 
	{
		$user = $userModel->findByIdSecret($cookie->getValue('oc_userId'), $cookie->getValue('oc_userSecret'));
		if (isset($user['b_enabled']) && $user['b_enabled'] == 1) 
		{
			$session->_set('userId', $user['pk_i_id']);
			$session->_set('userName', $user['s_name']);
			$session->_set('userEmail', $user['s_email']);
			$phone = ($user['s_phone_mobile']) ? $user['s_phone_mobile'] : $user['s_phone_land'];
			$session->_set('userPhone', $phone);
			return true;
		}
		else
		{
			return false;
		}
	}
	return false;
}
/**
 * Gets logged user id
 *
 * @return int
 */
function osc_logged_user_id() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return $session->_get("userId");
}
/**
 * Gets logged user mail
 *
 * @return string
 */
function osc_logged_user_email() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('userEmail');
}
/**
 * Gets logged user email
 *
 * @return string
 */
function osc_logged_user_name() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('userName');
}
/**
 * Gets logged user phone
 *
 * @return string
 */
function osc_logged_user_phone() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('userPhone');
}
/**
 * Gets true if admin user is logged in
 *
 * @return boolean
 */
function osc_is_admin_user_logged_in() 
{
	$classLoader = ClassLoader::getInstance();
	$userModel = new \Osc\Model\User;
	$session = $classLoader->getClassInstance( 'Session' );
	if ($session->_get("adminId") != '') 
	{
		$admin = $userModel->findByPrimaryKey($session->_get("adminId"));
		return (isset($admin['pk_i_id'])); 
	}
	//can already be a logged user or not, we'll take a look into the cookie
	$cookie = new \Cuore\Input\Cookie;
	if ($cookie->getValue('oc_adminId') != '' && $cookie->getValue('oc_adminSecret') != '') 
	{
		$admin = $userModel->findByIdSecret($cookie->getValue('oc_adminId'), $cookie->getValue('oc_adminSecret'));
		if (isset($admin['pk_i_id'])) 
		{
			$session->_set('adminId', $admin['pk_i_id']);
			$session->_set('adminUserName', $admin['s_username']);
			$session->_set('adminName', $admin['s_name']);
			$session->_set('adminEmail', $admin['s_email']);
			$session->_set('adminLocale', $cookie->getValue('oc_adminLocale'));
			return true;
		}
	}
	return false;
}
/**
 * Gets logged admin id
 *
 * @return int
 */
function osc_logged_admin_id() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (int)$session->_get("adminId");
}
/**
 * Gets logged admin username
 *
 * @return string
 */
function osc_logged_admin_username() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('adminUserName');
}
/**
 * Gets logged admin name
 * @return string
 */
function osc_logged_admin_name() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('adminName');
}
/**
 * Gets logged admin email
 *
 * @return string
 */
function osc_logged_admin_email() 
{
	$session = ClassLoader::getInstance()->getClassInstance( 'Session' );
	return (string)$session->_get('adminEmail');
}
/**
 * Gets name of current user
 *
 * @return string
 */
function osc_user_name( array $user ) 
{
	return (string)osc_user_field( $user, "s_name");
}
/**
 * Gets email of current user
 *
 * @return string
 */
function osc_user_email( array $user ) 
{
	return (string)osc_user_field( $user, "s_email");
}
/**
 * Gets registration date of current user
 *
 * @return string
 */
function osc_user_regdate( array $user ) 
{
	return (string)osc_user_field( $user, "reg_date");
}
/**
 * Gets id of current user
 *
 * @return int
 */
function osc_user_id( array $user ) 
{
	return (int)osc_user_field( $user, "pk_i_id");
}
/**
 * Gets website of current user
 *
 * @return string
 */
function osc_user_website( array $user ) 
{
	return (string)osc_user_field( $user, "s_website");
}
/**
 * Gets description/information of current user
 *
 * @return string
 */
function osc_user_info( array $user ) 
{
	return (string)osc_user_field( $user, "s_info");
}
/**
 * Gets phone of current user
 *
 * @return string
 */
function osc_user_phone_land( array $user ) 
{
	return (string)osc_user_field( $user, "s_phone_land");
}
/**
 * Gets cell phone of current user
 *
 * @return string
 */
function osc_user_phone_mobile( array $user ) 
{
	return (string)osc_user_field( $user, "s_phone_mobile");
}
/**
 * Gets phone_land if exist, else if exist return phone_mobile,
 * else return string blank
 * @return string
 */
function osc_user_phone( array $user ) 
{
	if (osc_user_field( $user, "s_phone_land") != "") 
	{
		return osc_user_field( $user, "s_phone_land");
	}
	else if (osc_user_field( $user, "s_phone_mobile") != "") 
	{
		return osc_user_field( $user, "s_phone_mobile");
	}
	return null;
}
/**
 * Gets country of current user
 *
 * @return string
 */
function osc_user_country( array $user ) 
{
	return (string)osc_user_field( $user, "s_country");
}
/**
 * Gets region of current user
 *
 * @return string
 */
function osc_user_region( array $user ) 
{
	return (string)osc_user_field( $user, "s_region");
}
/**
 * Gets city of current user
 *
 * @return string
 */
function osc_user_city( array $user ) 
{
	return (string)osc_user_field( $user, "s_city");
}
/**
 * Gets city area of current user
 *
 * @return string
 */
function osc_user_city_area( array $user ) 
{
	return (string)osc_user_field( $user, "s_city_area");
}
/**
 * Gets address of current user
 *
 * @return address
 */
function osc_user_address( array $user ) 
{
	return (string)osc_user_field( $user, "s_address");
}
/**
 * Gets postal zip of current user
 *
 * @return string
 */
function osc_user_zip( array $user ) 
{
	return (string)osc_user_field( $user, "s_zip");
}
/**
 * Gets latitude of current user
 *
 * @return float
 */
function osc_user_latitude( array $user ) 
{
	return (float)osc_user_field( $user, "d_coord_lat");
}
/**
 * Gets longitude of current user
 *
 * @return float
 */
function osc_user_longitude() 
{
	return (float)osc_user_field("d_coord_long");
}
/**
 * Gets number of items validated of current user
 *
 * @return int
 */
function osc_user_items_validated() 
{
	return (int)osc_user_field("i_items");
}
/**
 * Gets number of comments validated of current user
 *
 * @return int
 */
function osc_user_comments_validated() 
{
	return osc_user_field("i_comments");
}

/**
 * Gets a specific field from current alert
 *
 * @param array $field
 * @return mixed
 */
function osc_alert_field($field) 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	return osc_field($view->_current('alerts'), $field, '');
}
/**
 * Gets next alert if there is, else return null
 *
 * @return array
 */
function osc_has_alerts() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	$result = $view->_next('alerts');
	$alert = osc_alert();
	$view->assign("items", isset($alert['items']) ? $alert['items'] : array());
	return $result;
}
/**
 * Gets number of alerts in array alerts
 * @return int
 */
function osc_count_alerts() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	return (int)$view->countVar('alerts');
}
/**
 * Gets current alert fomr view
 *
 * @return array
 */
function osc_alert() 
{
	$classLoader = ClassLoader::getInstance();
	$view = $classLoader->getClassInstance( 'View_Html' );
	return $view->_current('alerts');
}
/**
 * Gets search field of current alert
 *
 * @return string
 */
function osc_alert_search() 
{
	return (string)osc_alert_field('s_search');
}
/**
 * Gets secret of current alert
 * @return string
 */
function osc_alert_secret() 
{
	return (string)osc_alert_field('s_secret');
}
/**
 * Gets the search object of a specific alert
 *
 * @return Search
 */
function osc_alert_search_object() 
{
	return osc_unserialize(base64_decode(osc_alert_field('s_search')));
}

