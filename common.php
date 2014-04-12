<?php

namespace TerminalTweak;

class SettingsReader
{
	const OPTION_DIR = 'dir';
	const OPTION_TITLE = 'title';
	const OPTION_REGEXP = 'regexp';

	protected $settings;

	public function formatSettings($titles)
	{
		$this->settings = array();

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
				if ($this->isPermittedKey($tabKeyPart))
				{
					if (!isset($this->settings[$tabNamePart]))
					{
						$this->settings[$tabNamePart] = array();
					}
					$this->setSetting($tabNamePart, $tabKeyPart, $entry);
				}
				else
				{
					// @todo Push an 'unrecognised key' error to an error list
				}
			}
		}

		return $this->settings;
	}

	/**
	 * Derive a title based on the pwd and the settings
	 * 
	 * @param string $pwd
	 * @param string $defaultTitle
	 */
	public function getTitle($pwd, $defaultTitle)
	{
		$title = $defaultTitle;
		foreach ($this->settings as $tabName => $tabSettings)
		{
			if ($this->comparePwd($tabSettings, $pwd))
			{
				$title = $this->settings[$tabName][self::OPTION_TITLE];
				break;
			}
		}

		return $title;
	}

	/**
	 * This does the comparison to see if the pwd matches the folder expression
	 * 
	 * @param array $tabSettings
	 * @param string $pwd
	 * @return boolean
	 */
	protected function comparePwd(array $tabSettings, $pwd)
	{
		# Get some useful properties for this tab
		$folder = isset($tabSettings[self::OPTION_DIR]) ?
			$tabSettings[self::OPTION_DIR] :
			''
		;
		$regexp = isset($tabSettings[self::OPTION_REGEXP]) ?
			(bool) $tabSettings[self::OPTION_REGEXP] :
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

	/**
	 * Return a list of errors
	 * 
	 * These errors can only be evaluated once the whole file has been processed
	 * 
	 * @return string
	 */
	public function getErrors()
	{
		$errors = array();
		foreach ($this->settings as $tabName => $tabSettings)
		{
			// Create an entry for this tab name, in case we get errors
			if (!isset($errors[$tabName]))
			{
				$errors[$tabName] = array();
			}

			// Check for missing folder parameter
			if (!isset($tabSettings[self::OPTION_DIR]))
			{
				$errors[$tabName][] = 'Missing folder parameter';
			}

			// Check for missing title parameter
			if (!isset($tabSettings[self::OPTION_TITLE]))
			{
				$errors[$tabName][] = 'Missing title parameter';
			}

			// Check for folder existence, but not if it is a regexp
			if (isset($tabSettings[self::OPTION_DIR]) && !isset($tabSettings[self::OPTION_REGEXP]))
			{
				$fileExists = file_exists($tabSettings[self::OPTION_DIR]);
				if ($fileExists)
				{
					$isDirectory = is_dir($tabSettings[self::OPTION_DIR]);
					if (!$isDirectory)
					{
						$errors[$tabName][] = 'The path specified is not a directory';						
					}
				}
				else
				{
					$errors[$tabName][] = 'The specified directory does not exist';					
				}
			}
		}

		// Simplify the errors array by removing any empty entries
		foreach($errors as $tabName => $tabErrors)
		{
			if (!$tabErrors)
			{
				unset($errors[$tabName]);
			}
		}

		return $errors;
	}

	protected function setSetting($tabName, $keyName, $value)
	{
		$this->settings[$tabName][$keyName] = $value;
	}

	protected function isPermittedKey($key)
	{
		return in_array($key, array(self::OPTION_DIR, self::OPTION_TITLE, self::OPTION_REGEXP, ));
	}
}
