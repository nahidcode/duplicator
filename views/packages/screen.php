<?php
defined("ABSPATH") or die("");

require_once DUPLICATOR_PLUGIN_PATH . '/classes/ui/class.ui.screen.base.php';

/*
Because the default way is overwriting the option names in the hidden input wp_screen_options[option]
I added all inputs via one option name and saved them with the update_user_meta function.
Also, the set-screen-option is not being triggered inside the class, that's why it's here. -TG
*/
add_filter('set-screen-option', 'dup_packages_set_option', 10, 3);
function dup_packages_set_option($status, $option, $value) {
    if('package_screen_options' == $option){
        $user_id = get_current_user_id();
    }
    return false;
}

class DUP_Package_Screen extends DUP_UI_Screen
{

	public function __construct($page)
    {
       add_action('load-'.$page, array($this, 'Init'));
    }

	public function Init()
	{
		$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'list';
		$active_tab = isset($_GET['action']) && $_GET['action'] == 'detail' ? 'detail' : $active_tab;
		$this->screen = get_current_screen();

		switch (strtoupper($active_tab)) {
			case 'LIST':	$content = $this->get_list_help();		break;
			case 'NEW1':	$content = $this->get_step1_help();		break;
			case 'NEW2':	$content = $this->get_step2_help(); 	break;
			case 'DETAIL':	$content = $this->get_details_help(); 	break;
			default:
				$content = $this->get_list_help();
				break;
		}

		$guide = '#guide-packs';
		$faq   = '#faq-package';
		$content .= "<b>References:</b><br/>"
					. "<a href='https://snapcreek.com/duplicator/docs/guide/{$guide}' target='_sc-guide'>User Guide</a> | "
					. "<a href='https://snapcreek.com/duplicator/docs/faqs-tech/{$faq}' target='_sc-guide'>FAQs</a> | "
					. "<a href='https://snapcreek.com/duplicator/docs/quick-start/' target='_sc-guide'>Quick Start</a>";

		$this->screen->add_help_tab( array(
				'id'        => 'dup_help_package_overview',
				'title'     => __('Overview','duplicator'),
				'content'   => "<p>{$content}</p>"
			)
		);

		$this->getSupportTab($guide, $faq);
		$this->getHelpSidbar();
	}

	public function get_list_help()
	{
		return  __("<b><i class='fa fa-archive'></i> Packages » All</b><br/> The 'Packages' section is the main interface for managing all the packages that have been created.  A Package consists "
				. "of two core files. The first is the 'installer.php' file and the second is the 'archive.zip/daf' file.  The installer file is a php file that when browsed to via "
				. "a web browser presents a wizard that redeploys or installs the website by extracting the archive file.  The archive file is a zip/daf file containing "
				. "all your WordPress files and a copy of your WordPress database. To create a package, click the 'Create New' button and follow the prompts. <br/><br/>"

                . "<b><i class='fa fa-download'></i> Downloads</b><br/>"
			    . "To download the package files click on the Download button.  Choosing the 'Both Files' option will popup two separate save dialogs.
					On some browsers you may have to enable popups on this site.  In order to download just the 'Installer' or 'Archive' click on that menu item. <i>Note:
					the archive file will have a copy of the installer inside of it named installer-backup.php</i><br/><br/>"

				. "<b><i class='fa fa-bars'></i> More Items</b><br/>"
				. " To see the details, transfer or view remote store locations of a package click the 'More Items' menu button.  If a package contains remote storage endpoints a
					blue dot will show as &nbsp; <i class='fa fa-bars remote-data-pass'></i> &nbsp; on the more items menu button.   <br/><br/>"

				. "<b><i class='fa fa-file-archive-o'></i> Archive Types</b><br/>"
				. "An archive file can be saved as either a .zip file or .daf file.  A zip file is a common archive format used to compress and group files.  The daf file short for "
				. "'Duplicator Archive Format' is a custom format used specifically  for working with larger packages and scale-ability issues on many shared hosting platforms.  Both "
				. "formats work very similar.  The main difference is that the daf file can only be extracted using the installer.php file while the zip file can be used by other zip "
				. "tools like winrar/7zip or other client-side tools. <br/><br/>"

			,'duplicator');
	}


	public function get_step1_help()
	{
		return __("<b>Packages New » 1 Setup</b> <br/>"
				. "The setup screen allows users to choose where they would like to store thier package, such as Google Drive, Dropbox, on the local server or a combination of both."
				. "Setup also allow users to setup optional filtered directory paths, files and database tables to change what is included in the archive file.  The optional option "
				. "to also have the installer pre-filled can be used.  To expedited the workflow consider using a Template. <br/><br/>",'duplicator');
	}


	public function get_step2_help()
	{
		return __("<b>Packages » 2 Scan</b> <br/>"
				. "The plugin will scan your system, files and database to let you know if there are any concerns or issues that may be present.  All items in green mean the checks "
				. "looked good.  All items in red indicate a warning.  Warnings will not prevent the build from running, however if you do run into issues with the build then checking "
				. "the warnings should be considered. <br/><br/>",'duplicator');
	}

	public function get_details_help()
	{
		return __("<b>Packages » Details</b> <br/>"
				. "The details view will give you a full break-down of the package including any errors that may have occured during the install. <br/><br/>",'duplicator');
	}

}


