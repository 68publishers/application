<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Components;

use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use Nette\Utils\Strings;

class TemplateManager
{
	/** @var array  */
	private $map;

	/** @var string */
	private $templatesDir;

	/** @var array */
	private $monitoredComponents = [];

	/**
	 * @param array         $map
	 * @param string|NULL   $templatesDir
	 */
	public function __construct(array $map = [], string $templatesDir = NULL)
	{
		$this->map = $map;
		$this->templatesDir = (string) $templatesDir;
	}

	/**
	 * @param string $presenterName
	 * @param string $lookupPath
	 * @param string $templateFile
	 *
	 * @return NULL|string
	 */
	public function getTemplate(string $presenterName, string $lookupPath, string $templateFile)
	{
		if (!$this->isMonitored($presenterName, $lookupPath)) {
			return NULL;
		}

		$lookupPath = str_replace('-', '/', $lookupPath);
		$templateFile = explode('/', str_replace('\\', '/', $templateFile));
		$regex = '#^' . $presenterName . '/' . $lookupPath . '/' . $templateFile[count($templateFile) - 1] . '$#i';

		$matches = preg_grep($regex, $this->map);

		return count($matches) ? $this->templatesDir . '/' . $matches[array_keys($matches)[0]] : NULL;
	}

	/**
	 * @param string $presenterName
	 * @param string $lookupPath
	 *
	 * @return void
	 */
	public function monitor(string $presenterName, string $lookupPath)
	{
		$this->monitoredComponents[] = Strings::lower("{$presenterName}/{$lookupPath}");
	}

	/**
	 * @param string $presenterName
	 * @param string $lookupPath
	 *
	 * @return bool
	 */
	public function isMonitored(string $presenterName, string $lookupPath)
	{
		return in_array(Strings::lower("{$presenterName}/{$lookupPath}"), $this->monitoredComponents);
	}


	/**
	 * @internal
	 * @param string $dir
	 *
	 * @return array
	 */
	public static function createMap(string $dir)
	{
		FileSystem::createDir($dir);
		$map = [];

		/** @var \SplFileInfo $file */
		foreach (Finder::find('*.latte')->from($dir) as $file) {
			$path = Strings::trim(str_replace($dir, '', $file->getPathname()), "\\/");

			if (!Strings::contains($path, '@')) {
				$map[] = str_replace('\\', '/', $path);
			}
		}

		return $map;
	}
}
