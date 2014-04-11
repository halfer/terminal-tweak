<?php
/**
 * Script to reset terminal titles depending on the PWD
 *
 * The format of the ini file is thus:
 *
 * tabName.folder = (full folder path)
 * tabName.title = (The title you want)
 *
 * Repeat for as many tabs as you want.
 *
 * The string "tabName" has no signficance other than each pair needs a unique name.
 */
require_once 'common.php';

define('TITLES_INI_PATH', __DIR__ . '/titles.ini');
define('DEFAULT_TITLE', 'Not found');

// Let's get the raw ini file
$titles = parse_ini_file(TITLES_INI_PATH);

// We'll parse the ini file into a nicer format
$SettingsReader = new TerminalTweak\SettingsReader();
$settings = $SettingsReader->formatSettings($titles);

// Here's the working dir
$pwd = $_SERVER['PWD'];

// Now let's use these cleaned settings to set a title based on the PWD
$title = DEFAULT_TITLE;
foreach ($settings as $tabName => $tabSettings)
{
	if (TerminalTweak\comparePwd($tabSettings, $pwd))
	{
		$title = $settings[$tabName]['title'];
		break;
	}
}

// Now output the title
echo "$title";
