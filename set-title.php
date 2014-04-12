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
// @todo Move the ini parsing into the SettingsReader class
$titles = parse_ini_file(TITLES_INI_PATH);

// We'll parse the ini file into a nicer format
$SettingsReader = new TerminalTweak\SettingsReader();
$settings = $SettingsReader->formatSettings($titles);

if ($argc == 1)
{
	// Now output the title
	$pwd = $_SERVER['PWD'];
	echo $SettingsReader->getTitle($pwd, DEFAULT_TITLE);
}
else
{
	switch ($argv[1])
	{
		case '--help':
			echo "Call this script with no parameters to generate a title, or\n"
			. "use --validate to check the configuration file.\n\n";
			break;
		case '--validate';
			// @todo This could do with tidying up :)
			print_r($SettingsReader->getErrors());
			break;
		default:
	}
}
