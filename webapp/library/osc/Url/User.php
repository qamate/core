<?php

class Url_User extends Url_Abstract
{
	public function loadUrls()
	{
	}

	public function loadRules( Rewrite $rewrite )
	{
		$rewrite->addRule('^/user/login$', 'index.php?page=user&action=login');
		$rewrite->addRule('^/user/dashboard/?$', 'index.php?page=user&action=dashboard');
		$rewrite->addRule('^/user/logout$', 'index.php?page=user&action=logout');
		$rewrite->addRule('^/user/register$', 'index.php?page=user&action=register');
		$rewrite->addRule('^/user/activate/([0-9]+)/(.*?)/?$', 'index.php?page=user&action=validate&id=$1&code=$2');
		$rewrite->addRule('^/user/activate_alert/([a-zA-Z0-9]+)/(.+)$', 'index.php?page=user&action=activate_alert&email=$2&secret=$1');
		$rewrite->addRule('^/user/profile$', 'index.php?page=user&action=profile');
		$rewrite->addRule('^/user/profile/([0-9]+)$', 'index.php?page=user&action=public-profile&id=$1');
		$rewrite->addRule('^/user/items$', 'index.php?page=user&action=items');
		$rewrite->addRule('^/user/alerts$', 'index.php?page=user&action=alerts');
		$rewrite->addRule('^/user/recover/?$', 'index.php?page=user&action=recover');
		$rewrite->addRule('^/user/forgot/([0-9]+)/(.*)$', 'index.php?page=user&action=forgot&userId=$1&code=$2');
		$rewrite->addRule('^/user/change_password$', 'index.php?page=user&action=change_password');
		$rewrite->addRule('^/user/change_email$', 'index.php?page=user&action=change_email');
		$rewrite->addRule('^/user/change_email_confirm/([0-9]+)/(.*?)/?$', 'index.php?page=user&action=change_email_confirm&userId=$1&code=$2');
	}
	/**
	 * Gets current user alert unsubscribe url
	 *
	 * @param string $email
	 * @param string $secret
	 * @return string
	 */
	function osc_user_unsubscribe_alert_url($email = '', $secret = '') 
	{
		if ($secret == '') 
		{
			$secret = osc_alert_secret();
		}
		if ($email == '') 
		{
			$email = osc_user_email();
		}
		return $this->getBaseUrl(true) . '?page=user&action=unsub_alert&email=' . urlencode($email) . '&secret=' . $secret;
	}
	/**
	 * Gets user alert activate url
	 *
	 * @param string $secret
	 * @param string $email
	 * @return string
	 */
	function osc_user_activate_alert_url($secret, $email) 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/user/activate_alert/' . $secret . '/' . urlencode($email);
		}
		else
		{
			return $this->getBaseUrl(true) . '?page=user&action=activate_alert&email=' . urlencode($email) . '&secret=' . $secret;
		}
	}
	/**
	 * Gets current user url
	 *
	 * @return string
	 */
	function osc_user_profile_url() 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/user/profile';
		}
		else
		{
			return $this->getBaseUrl(true) . '?page=user&action=profile';
		}
	}
	/**
	 * Gets current user alert activate url
	 *
	 * @param int $page
	 * @return string
	 */
	function osc_user_list_items_url($page = '') 
	{
		if (osc_rewrite_enabled()) 
		{
			if ($page == '') 
			{
				return $this->getBaseUrl() . '/user/items';
			}
			else
			{
				return $this->getBaseUrl() . '/user/items?iPage=' . $page;
			}
		}
		else
		{
			if ($page == '') 
			{
				return $this->getBaseUrl(true) . '?page=user&action=items';
			}
			else
			{
				return $this->getBaseUrl(true) . '?page=user&action=items&iPage=' . $page;
			}
		}
	}
	/**
	 * Gets url to change email
	 *
	 * @return string
	 */
	function osc_change_user_email_url() 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/user/change_email';
		}
		else
		{
			return $this->getBaseUrl(true) . '?page=user&action=change_email';
		}
	}
	/**
	 * Gets confirmation url of change email
	 *
	 * @param int $userId
	 * @param string $code
	 * @return string
	 */
	function osc_change_user_email_confirm_url($userId, $code) 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/user/change_email_confirm/' . $userId . '/' . $code;
		}
		else
		{
			return $this->getBaseUrl(true) . '?page=user&action=change_email_confirm&userId=' . $userId . '&code=' . $code;
		}
	}
	/**
	 * Gets url for changing password
	 *
	 * @return string
	 */
	function osc_change_user_password_url() 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/user/change_password';
		}
		else
		{
			return $this->getBaseUrl(true) . '?page=user&action=change_password';
		}
	}
	/**
	 * Gets url for recovering password
	 *
	 * @return string
	 */
	function osc_recover_user_password_url() 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/user/recover';
		}
		else
		{
			return $this->getBaseUrl(true) . '?page=user&action=recover';
		}
	}
	/**
	 * Gets url for confirm the forgot password process
	 *
	 * @param int $userId
	 * @param string $code
	 * @return string
	 */
	function osc_forgot_user_password_confirm_url($userId, $code) 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/user/forgot/' . $userId . '/' . $code;
		}
		else
		{
			return $this->getBaseUrl(true) . '?page=user&action=forgot&userId=' . $userId . '&code=' . $code;
		}
	}
	/**
	 * Gets url for changing website language (for users)
	 *
	 * @param string $locale
	 * @return string
	 */
	function osc_change_language_url($locale) 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/language/' . $locale;
		}
		else
		{
			return $this->getBaseUrl(true) . '?page=language&locale=' . $locale;
		}
	}

	/**
	 * Create automatically the url of the users' dashboard
	 *
	 * @return string
	 */
	function osc_user_dashboard_url() 
	{
		if (osc_rewrite_enabled()) 
		{
			$path = $this->getBaseUrl() . '/user/dashboard';
		}
		else
		{
			$path = $this->getBaseUrl(true) . '?page=user&action=dashboard';
		}
		return $path;
	}
	/**
	 * Create automatically the logout url
	 *
	 * @return string
	 */
	function osc_user_logout_url() 
	{
		if (osc_rewrite_enabled()) 
		{
			$path = $this->getBaseUrl() . '/user/logout';
		}
		else
		{
			$path = $this->getBaseUrl(true) . '?page=user&action=logout';
		}
		return $path;
	}
	/**
	 * Create automatically the login url
	 *
	 * @return string
	 */
	function osc_user_login_url() 
	{
		if (osc_rewrite_enabled()) 
		{
			$path = $this->getBaseUrl() . '/user/login';
		}
		else
		{
			$path = $this->getBaseUrl(true) . '?page=user&action=login';
		}
		return $path;
	}
	/**
	 * Create automatically the url to register an account
	 *
	 * @return string
	 */
	function osc_register_account_url() 
	{
		if (osc_rewrite_enabled()) 
		{
			$path = $this->getBaseUrl() . '/user/register';
		}
		else
		{
			$path = $this->getBaseUrl(true) . '?page=user&action=register';
		}
		return $path;
	}
	/**
	 * Create automatically the url to activate an account
	 *
	 * @param int $id
	 * @param string $code
	 * @return string
	 */
	function osc_user_activate_url($id, $code) 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/user/activate/' . $id . '/' . $code;
		}
		else
		{
			return $this->getBaseUrl(true) . '?page=user&action=validate&id=' . $id . '&code=' . $code;
		}
	}

	/**
	 * @return string
	 */
	public function getPublicProfileUrl( array $user )
	{
		if( osc_rewrite_enabled() )
		{
			return $this->getBaseUrl() . '/user/profile/' . $user['pk_i_id'];
		}
		else
		{
			return $this->getBaseUrl( true ) . '?page=user&action=pub_profile&id=' . $user['pk_i_id'];
		}

	}

	/**
	 * Gets current page url
	 *
	 * @param string $locale
	 * @return string
	 */
	public function getUrl( array $page, $locale = null )
	{
		if( empty( $locale ) ) 
		{
			if (osc_rewrite_enabled()) 
			{
				return $this->getBaseUrl() . '/' . osc_field( $page, "s_internal_name") . "-p" . osc_field( $page, "pk_i_id") . "-" . $locale;
			}
			else
			{
				return $this->getBaseUrl(true) . "?page=page&id=" . osc_field( $page, "pk_i_id") . "&lang=" . $locale;
			}
		}
		else
		{
			if (osc_rewrite_enabled()) 
			{
				return $this->getBaseUrl() . '/' . osc_field( $page, "s_internal_name") . "-p" . osc_field( $page, "pk_i_id");
			}
			else
			{
				return $this->getBaseUrl(true) . "?page=page&id=" . osc_field( $page, "pk_i_id");
			}
		}
	}

	public function getDetailsUrl( array $item, $locale = null )
	{
		$url = null;
		if( osc_rewrite_enabled() )
		{
			$sanitized_title = osc_sanitizeString( osc_item_title() );
			$sanitized_category = '/';
			$cat = ClassLoader::getInstance()->getClassInstance( 'Model_Category' )->hierarchy( osc_item_category_id() );
			for( $i = count( $cat ); $i > 0; $i-- )
			{
				$sanitized_category .= $cat[$i - 1]['s_slug'] . '/';
			}
			if( empty( $locale ) )
			{
				$url = $this->getBaseUrl() . sprintf('%s%s_%d', $sanitized_category, $sanitized_title, osc_item_id());
			}
			else
			{
				$url = $this->getBaseUrl() . sprintf('/%s_%s%s_%d', $locale, $sanitized_category, $sanitized_title, osc_item_id());
			}
		}
		else
		{
			$url = osc_item_url_ns( osc_item_id(), $locale );
		}

		if( empty( $url ) )
			throw new Exception( 'URL could not be created' );
		
		return $url;
	}

	/**
	 * Gets current user alerts' url
	 *
	 * @return string
	 */
	public function osc_user_alerts_url() 
	{
		if (osc_rewrite_enabled()) 
		{
			return $this->getBaseUrl() . '/user/alerts';
		}
		else
		{
			return $this->getBaseUrl( true ) . '?page=user&action=alerts';
		}
	}
}

