<?php

namespace VKauto\Bot;

use Worker;

class AsyncWorker extends Worker
{
	private $worker;

	public function __construct($worker)
	{
		$this->worker = $worker;
	}

	public function run()
	{
		require realpath(__DIR__ . '\\..\\..\\..\\' . 'vendor\autoload.php');
		$this->worker->start();
	}
}
