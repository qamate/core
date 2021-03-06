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
?>

        <script type="text/javascript">
            var base_url    = '<?php echo osc_base_url(); ?>';
            var s_close     = '<?php _e('Close'); ?>';
            var s_view_more = '<?php _e('View more'); ?>';
        </script>
				<div id="content_header" class="content_header">
					<div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/settings-icon.png'); ?>" alt="" title=""/>
                    </div>
					<div id="content_header_arrow">&raquo; <?php _e('Permalinks settings'); ?></div>
					<div style="clear: both;"></div>
				</div>
				<div id="content_separator"></div>
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
					<div style="padding: 20px;">

                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="settings" />
                            <input type="hidden" name="action" value="permalinks" />
						
                            <div style="float: left; width: 100%;">
                                <fieldset>
                                    <legend><?php _e('Friendly urls'); ?></legend>
                                    <div><?php _e('By default OpenSourceClassifieds uses web URLs which have question marks and lots of numbers in them. However, OpenSourceClassifieds offers you friendly urls. This can improve the aesthetics, usability, and forward-compatibility of your links'); ?>.</div>
                                    <br />
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_rewrite_enabled() ? 'checked="true"' : ''); ?> name="rewrite_enabled" id="rewrite_enabled" value="1" />
                                    <label for="rewrite_enabled"><?php
_e('Enable friendly urls'); ?></label>
                                </fieldset>
                            </div>

                            <?php
if (osc_rewrite_enabled()) 
{ 
?>
                            <div style="float: left; width: 100%;">
                                <fieldset>
                                    <legend><?php
	_e('.htaccess file'); ?></legend>
                                    <?php
	switch ($htaccess) 
	{
	case 1:
		_e('Module <em>mod_rewrite</em> was found on the server.');
		break;

	case 2:
		_e('Warning! Rewrite module wasn\'t found on the server. This means you don\'t have it enabled or you\'re running PHP as CGI (or fastCGI). In the case you don\'t have mod_rewrite you could still use friendly urls if AcceptPathInfo option is on in your Apache configuration (we can\'t know if it\'s enabled or not, but it usually is). "Index.php" will appear as a part of your URL (ie. http://www.example.com/index.php/nice/url).');
		break;
	}
?>
                                         <br/>
                                    <?php
	switch ($file) 
	{
	case 3:
		_e('Error. We couldn\'t write the .htaccess file on your server. Please create a file called .htaccess in the root of your OpenSourceClassifieds installation with the following content.');
		break;

	case 1:
		_e('File .htaccess already exists. Please check that the .htaccess file has the following content.');
		break;

	case 2:
		_e('We\'ve created a .htaccess file on the root of your OpenSourceClassifieds installation.');
		break;
	}
?>
                                    <div style="margin-top: 10px; clear: both;"></div>
                                    <div style="float: left; width: 50%;">
                                        <?php
	_e('Content of .htaccess file should look like this:'); ?>
                                        <textarea rows="8" style="width: 90%;" readonly="readonly" onclick="this.select();">
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.php [L]
</IfModule>
                                        </textarea>
                                    </div>
                                    <div style="float: right; width: 50%;">
                                        <?php
	if (file_exists(ABS_PATH . '.htaccess')) 
	{
		$htaccess_content = file_get_contents(osc_base_path() . '.htaccess');
		if ($htaccess_content) 
		{
			_e('Current content of your .htaccess file:');
?>
                                        <br />
                                        <textarea rows="8" style="width: 90%;" readonly="readonly"><?php
			echo $htaccess_content; ?></textarea>
                                        <?php
		}
	} ?>
                                    </div>
                                </fieldset>
                            </div>
                            <?php
} ?>
                            <div style="clear: both;"></div>
                            <input id="button_save" type="submit" value="<?php
_e('Update'); ?>" />
                        </form>
					</div>
				</div>

