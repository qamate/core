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

$userForm = $classLoader->getClassInstance( 'Form_User' );
$urlStatic = $classLoader->getClassInstance( 'Url_Static' );
?>
<!DOCTYPE HTML>
<html lang="<?php echo str_replace( '_', '-', osc_current_user_locale() ); ?>">
<head>
	<meta charset="UTF-8" />
	<meta name="description" content="<?php echo meta_description(); ?>" />
	<?php if( $view->hasMetaRobots() ): ?>
	<meta name="robots" content="<?php echo $view->getMetaRobots(); ?>" />
	<?php endif; ?>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Expires" content="Fri, Jan 01 1970 00:00:00 GMT" />
	<title><?php echo $view->getTitle(); ?></title>
	<link href="<?php echo $urlStatic->create( 'assets-stylesheets', 'style,tabs' ); ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
	var fileDefaultText = '<?php _e('No file selected', 'modern'); ?>';
	var fileBtnText     = '<?php _e('Choose File', 'modern'); ?>';
	</script>
	<script type="text/javascript" src="/static/scripts/jquery.js"></script>
	<script type="text/javascript" src="<?php echo $urlStatic->create( 'assets-javascripts', 'jquery-ui,jquery.uniform,global,tabber-minimized' ); ?>"></script>
	<?php foreach( $view->getJavaScripts() as $js ): ?>
	<script type="text/javascript" src="<?php echo $js; ?>"></script>
	<?php endforeach; ?>
</head>
<body>
       <div class="container">
<div id="header">
    <a id="logo" href="<?php echo osc_base_url(); ?>/"><strong><?php echo osc_page_title(); ?></strong></a>
    <div id="user_menu">
        <ul>
<?php if (osc_users_enabled()): ?>
                <?php if (osc_is_web_user_logged_in()): ?>
                    <li class="first logged">
                        <?php echo sprintf(__('Hi %s', 'modern'), osc_logged_user_name() . '!'); ?>  &middot;
                        <strong><a href="<?php echo osc_user_dashboard_url(); ?>"><?php _e('My account', 'modern'); ?></a></strong> &middot;
                        <a href="<?php echo osc_user_logout_url(); ?>"><?php _e('Logout', 'modern'); ?></a>
                    </li>
		<?php else: ?>
                    <li class="first">
                        <a id="login_open" href="<?php echo osc_user_login_url(); ?>"><?php _e('Login', 'modern'); ?></a>
			<?php if (osc_user_registration_enabled()): ?>
                            &middot;
                            <a href="<?php echo osc_register_account_url(); ?>"><?php _e('Register for a free account', 'modern'); ?></a>
			<?php endif; ?>
                        <form id="login" action="<?php echo osc_base_url(true); ?>" method="post">
                            <fieldset>
                                <input type="hidden" name="page" value="user" />
                                <input type="hidden" name="action" value="login_post" />
                                <label for="email"><?php _e('E-mail', 'modern'); ?></label>
                                <?php $userForm->email_login_text(); ?>
                                <label for="password"><?php _e('Password', 'modern'); ?></label>
                                <?php $userForm->password_login_text(); ?>
                                <p class="checkbox"><?php $userForm->rememberme_login_checkbox(); ?> <label for="rememberMe"><?php _e('Remember me', 'modern'); ?></label></p>
                                <button type="submit"><?php _e('Log in', 'modern'); ?></button>
                                <div class="forgot">
                                    <a href="<?php echo osc_recover_user_password_url(); ?>"><?php _e("Forgot password?", 'modern'); ?></a>
                                </div>
                            </fieldset>
                        </form>
                    </li>
		<?php endif; ?>
<?php endif; ?>
<?php if( osc_count_web_enabled_locales() > 1 ): ?>
                <?php
	osc_goto_first_locale(); ?>
                <li class="last with_sub">
                    <strong><?php _e("Language", 'modern'); ?></strong>
                    <ul>
                        <?php $i = 0; ?>
			<?php while (osc_has_web_enabled_locales()): ?>
				<li <?php if ($i == 0): ?><?php echo "class='first'"; ?><?php endif; ?>
		<a id="<?php echo osc_locale_code(); ?>" href="<?php echo osc_change_language_url(osc_locale_code()); ?>"><?php echo osc_locale_name(); ?></a></li>
                            <?php $i++; ?>
                        <?php endwhile; ?>
                    </ul>
                </li>
<?php endif; ?>
        </ul>
        <?php if( osc_users_enabled() || (!osc_users_enabled() && !osc_reg_user_post()) ): ?>
            <div id="form_publish">
                <strong class="publish_button"><a href="<?php echo osc_item_post_url_in_category(); ?>"><?php _e("Publish your ad for free", 'modern'); ?></a></strong>
            </div>
	<?php endif; ?>
        <div class="empty"></div>
    </div>
</div>
