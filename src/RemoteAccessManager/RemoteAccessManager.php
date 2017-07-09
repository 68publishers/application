<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\RemoteAccessManager;

use Nette\Http\IRequest;
use Nette\SmartObject;
use SixtyEightPublishers\Application\RemoteAccessManager\Handler\IAccessHandler;
use Tracy\Debugger;

/**
 * @method  RemoteAccessManager   onAccess()
 * @method  RemoteAccessManager   onDeny()
 */
class RemoteAccessManager implements IRemoteAccessManager
{
	use SmartObject;

	const
		COOKIE_SECRET = 'ram-secret-key',
		ALLOWED_ALL = TRUE,
		DENY_ALL = FALSE;

	/** @var \Nette\Http\IRequest */
	private $request;

	/** @var array */
	private $whitelist;

	/** @var array */
	private $blacklist;

	/** @var string|null */
	private $key;

	/** @var bool|true */
	private $mode;

	/** @var bool|false */
	private $consoleMode;

	/** @var \SixtyEightPublishers\Application\RemoteAccessManager\Handler\IAccessHandler */
	private $handler;

	/** @var null|callable */
	public $onAllow;

	/** @var null|callable */
	public $onDeny;

	/**
	 * @param \Nette\Http\IRequest    $request
	 * @param string|array            $blacklist
	 * @param string|array            $whitelist
	 * @param string                  $key
	 * @param bool|TRUE               $mode
	 * @param bool|FALSE              $consoleMode
	 * @param IAccessHandler          $handler
	 */
	public function __construct(IRequest $request, $blacklist = [], $whitelist = [], $mode = self::ALLOWED_ALL, $key = self::COOKIE_SECRET, $consoleMode = FALSE, IAccessHandler $handler)
	{
		$this->request = $request;
		$this->blacklist = $blacklist;
		$this->whitelist = $whitelist;
		$this->mode = $mode;
		$this->consoleMode = $consoleMode;
		$this->key = $key;
		$this->handler = $handler;

		$this->onAllow[] = [$this->handler, 'allow'];
		$this->onDeny[] = [$this->handler, 'deny'];
	}

	public function process()
	{
		$this->isAllowed()
			? $this->onAllow()
			: $this->onDeny();
	}

	/**
	 * @return bool
	 */
	private function isAllowed() : bool
	{
		if ($this->consoleMode) {
			return TRUE;
		}

		$addr = $this->request->getRemoteAddress() ?: php_uname('n');
		$secret = $this->request->getCookie($this->key);

		if ($this->isAllowedAll()) {
			$blacklist = is_string($this->blacklist)
				? preg_split('#[,\s]+#', $this->blacklist)
				: (array) $this->blacklist;
			$allow = !(in_array($addr, $blacklist, TRUE) || in_array("$secret@$addr", $blacklist, TRUE));
		} else {
			$whitelist = is_string($this->whitelist)
				? preg_split('#[,\s]+#', $this->whitelist)
				: (array) $this->whitelist;
			if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$whitelist[] = '127.0.0.1';
				$whitelist[] = '::1';
			}
			$allow = in_array($addr, $whitelist, TRUE) || in_array("$secret@$addr", $whitelist, TRUE) || in_array($secret, $whitelist, TRUE);
		}

		return $allow;
	}

	/**
	 * @return bool
	 */
	private function isAllowedAll() : bool
	{
		return $this->mode === self::ALLOWED_ALL;
	}
}
