<?php

function formatSettings($titles) {
	$settings = array();
	foreach ($titles as $key => $entry) {
		// Take a key "tabName.keyName" and explode it
		$keyParts = explode('.', $key);

		// Only use this if it has only two parts
		if (count($keyParts) == 2)
		{
			// This reads the tabName and the keyName
			$tabNamePart = $keyParts[0];
			$tabKeyPart = $keyParts[1];

			// Only allow these keys
			if (isPermittedKey($tabKeyPart))
			{
				if (!isset($settings[$tabNamePart]))
				{
					$settings[$tabNamePart] = array();
				}
				$settings[$tabNamePart][$tabKeyPart] = $entry;
			}
		}
	}

	return $settings;
}

function isPermittedKey($key)
{
	return in_array($key, array('folder', 'title', 'regexp',));
}

/**
 * This does the comparison to see if the pwd matches the folder expression
 * 
 * @param array $tabSettings
 * @param string $pwd
 * @return boolean
 */
function comparePwd(array $tabSettings, $pwd)
{
	# Get some useful properties for this tab
	$folder = isset($tabSettings['folder']) ?
		$tabSettings['folder'] :
		''
	;
	$regexp = isset($tabSettings['regexp']) ?
		(bool) $tabSettings['regexp'] :
		false
	;

	if ($regexp)
	{
		# @todo We need to check for regexp validity before using
		$result = (bool) preg_match($folder, $pwd);
	}
	else
	{
		$result = $folder == $pwd;
	}

	return $result;
}