<?php
if (Params::getParam("action_specific") != '') 
{
	switch (Params::getParam("action_specific")) 
	{
	case ('upload_logo'):
		$package = Params::getFiles("logo");
		if ($package['error'] == UPLOAD_ERR_OK) 
		{
			if (move_uploaded_file($package['tmp_name'], ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' )->getCurrentThemePath() . "images/logo.jpg")) 
			{
				$this->getSession()->addFlashMessage( _m('The logo image has been uploaded correctly'), 'admin' );
			}
			else
			{
				$this->getSession()->addFlashMessage( _m("An error has occurred, please try again"), 'admin', 'ERROR' );
			}
		}
		else
		{
			$this->getSession()->addFlashMessage( _m("An error has occurred, please try again"), 'admin', 'ERROR' );
		}
		break;

	case ('remove'):
		if (file_exists(ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' )->getCurrentThemePath() . "images/logo.jpg")) 
		{
			unlink(ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' )->getCurrentThemePath() . "images/logo.jpg");
			$this->getSession()->addFlashMessage( _m('The logo image has been removed'), 'admin' );
		}
		else
		{
			$this->getSession()->addFlashMessage( _m("Image not found"), 'admin', 'ERROR' );
		}
		break;
	}
}
?>
    <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
        <div style="padding: 20px;">

            <?php if (is_writable(ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' )->getCurrentThemePath() . "images/"))  { ?>

            <p style="border-bottom: 1px black solid;padding-bottom: 10px;">
                <img style="padding-right: 10px;"src="<?php
	echo osc_current_admin_theme_url('images/info-icon.png'); ?>"/>
                <?php
	_e('The preferred size of the logo is 600x100', 'modern'); ?>.
                <?php
	if (file_exists(ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' )->getCurrentThemePath() . "images/logo.jpg")) 
	{ ?>
                <strong><?php
		_e('Note: Uploading another logo will overwrite current logo', 'modern'); ?>.</strong>
                <?php
	} ?>
            </p>

            <form action="<?php echo osc_admin_render_theme_url('components/themes/modern/admin/admin_settings.php'); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action_specific" value="upload_logo" />
                <p>
                    <label for="package"><?php _e('Logo image', 'modern'); ?> (png,gif,jpg)</label>
                    <input type="file" name="logo" id="package" />
                </p>
                <input id="button_save" type="submit" value="<?php _e('Upload', 'modern'); ?>" />
            </form>
            <div>
                <?php
	if (file_exists(ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' )->getCurrentThemePath() . "images/logo.jpg")) 
	{ ?>
                <p>
                    Preview:<br>
                    <img border="0" alt="<?php echo osc_page_title(); ?>" src="<?php echo osc_current_web_theme_url('images/logo.jpg'); ?>"/>
                    <form action="<?php echo osc_admin_render_theme_url('components/themes/modern/admin/admin_settings.php'); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="action_specific" value="remove" />
                        <input id="button_remove" type="submit" value="<?php _e('Remove logo', 'modern'); ?>" />
                    </form>
                </p>
                <?php
	}
	else
	{ ?>
                    <p><?php _e('Has not uploaded any logo image', 'modern'); ?></p>
                <?php
	} ?>
            </div>
            <div style="clear: both;"></div>

            <?php
}
else
{ ?>

            <div id="flash_message">
                <p>
                    <?php
	$msg = sprintf(__('The images folder %s is not writable on your server', 'modern'), ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' )->getCurrentThemePath() . "images/") . ", ";
	$msg.= __('OpenSourceClassifieds can\'t upload logo image from the administration panel', 'modern') . '. ';
	$msg.= __('Please make the mentioned images folder writable', 'modern') . '.';
	echo $msg;
?>
                </p>
                <p>
                    <?php _e('To make a directory writable under UNIX execute this command from the shell', 'modern'); ?>:
                </p>
                <p style="background-color: white; border: 1px solid black; padding: 8px;">
                    chmod a+w <?php echo ClassLoader::getInstance()->getClassInstance( 'Ui_MainTheme' )->getCurrentThemePath() . "images/"; ?>
                </p>
            </div>
            
            <?php
} ?>
        </div>
    </div>
