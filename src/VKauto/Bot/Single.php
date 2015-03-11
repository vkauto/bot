<?php

namespace VKauto\Bot;

use Exception;
use ReflectionClass;
use VKauto\Auth\Account;
use VKauto\CaptchaRecognition\Captcha;
use VKauto\Utils\Log;
use VKauto\Bot\AsyncWorker;

class Single
{
	/**
	 * A
	 * @var VKauto\Auth\Account
	 */
	protected $account;

	/**
	 * Current bot settings
	 * @var array
	 */
	protected $settings;

	/**
	 * Workers
	 * @var array
	 */
	protected $workers;

	public function __construct(Account $account, array $settings)
	{
		$this->account = $account;

		if (!isset($settings['workers']) or !is_array($settings['workers']) or empty($settings['workers']))
		{
			throw new Exception('Bot cannot be launched without any workers!');
		}

		foreach($settings['workers'] as $worker => $parameters)
		{

			if (is_int($worker) and !is_array($parameters))
			{
				$worker = $parameters;
				$parameters = [];
			}

			if (class_exists($worker))
			{
				$this->workers[$worker] = $parameters;
			}
		}

		if (count($this->workers) == 0)
		{
			throw new Exception('Bot cannot be launched without available workers!');
		}

		unset($settings['workers']);
		// $this->settings = $settings;

		if (isset($settings['captcha']) and is_array($settings['captcha']))
		{
			if (isset($settings['captcha']['service']) and isset($settings['captcha']['api_key']))
			{
				$this->account->captcha = new Captcha($settings['captcha']['service'], $settings['captcha']['api_key']);
				unset($settings['captcha']);
			}
		}
	}

	private function loadWorkers()
	{
		$workers = $this->workers;
		unset($this->workers);

		foreach ($workers as $worker => $parameters)
		{
			if ($worker::needsAccountClass())
			{
				array_push($parameters, $this->account);
			}

			$this->workers[] = (new ReflectionClass($worker))->newInstanceArgs($parameters);
		}

		foreach ($this->workers as $index => $worker)
		{
			if (!method_exists($worker, 'start'))
			{
				unset($this->workers[$index]);
			}
		}
	}

	private function startWorkers()
	{
		$threads = [];

		foreach ($this->workers as $worker)
		{
			$asyncWorker = new AsyncWorker($worker);
			$asyncWorker->start();

			$threads[] = $asyncWorker;
		}

		foreach ($threads as $thread)
		{
			$thread->join();
		}
	}

	public function run()
	{
		$this->loadWorkers();
		$this->startWorkers();
	}
}
