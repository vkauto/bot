<?php

namespace VKauto\Workers\ExampleWorker;

use VKauto\Interfaces\WorkerInterface;
use VKauto\Utils\Log;

class Example implements WorkerInterface
{
	private function loop()
	{
		for ($i = 0; $i <= 5; $i++)
		{
			sleep(3);
			Log::write('Hello, World!', ['ExampleWorker', 'Testing', "{$i} of 5"]);
		}
	}

	public function start()
	{
		$this->loop();
	}

	public function stop()
	{
		// ...
	}

	public static function needsAccountClass()
	{
		return false;
	}
}
