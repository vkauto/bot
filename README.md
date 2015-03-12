# bot
Запуск нескольких воркеров, работающих с одним аккаунтом, одновременно.

# Установка
## pthreads
Для работы необходимо установить расширение [pthreads](https://github.com/krakjoe/pthreads).

## Composer
`composer.json`
```json
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/vkauto/utils.git"
        },
        {
            "type": "git",
            "url": "https://github.com/vkauto/auth.git"
        },
        {
            "type": "git",
            "url": "https://github.com/vkauto/captcha-recognition.git"
        },
        {
            "type": "git",
            "url": "https://github.com/vkauto/interfaces.git"
        },
        {
            "type": "git",
            "url": "https://github.com/vkauto/setonlineworker.git"
        },
        {
            "type": "git",
            "url": "https://github.com/vkauto/friendswatcherworker.git"
        },
        {
            "type": "git",
            "url": "https://github.com/vkauto/messagewatcherworker.git"
        },
        {
            "type": "git",
            "url": "https://github.com/vkauto/bot.git"
        }
    ],
    "require": {
        "vkauto/utils": "dev-master",
        "vkauto/auth": "dev-master",
        "vkauto/captcha-recognition": "dev-master",
        "vkauto/interfaces": "dev-master",
        "vkauto/setonlineworker": "dev-master",
        "vkauto/friendswatcherworker": "dev-master",
        "vkauto/messagewatcherworker: "dev-master",
        "vkauto/bot": "dev-master"
    }
}
```

# Использование
```php
<?php

require_once 'vendor/autoload.php';

date_default_timezone_set('Europe/Moscow');

use VKauto\Auth\Auth;
use VKauto\Bot\Single;

$bot = new Single(Auth::directly('+79057151171', 'password'),
[
	'captcha' => [
		'service' => 'http://anti-captcha.com',
		'api_key' => 'your API key'
	],

	'workers' => [
		'VKauto\Workers\SetOnlineWorker\SetOnline' => [7],
		'VKauto\Workers\ExampleWorker\Example',
		'VKauto\Workers\FriendsWatcherWorker\FriendsWatcher' => [1],
		'VKauto\Workers\MessageWatcherWorker\MessageWatcher' => [2, [
			'VKauto\Workers\MessageWatcherWorker\Modules\CommandProcessorModule\CommandProcessor',
			'VKauto\Workers\MessageWatcherWorker\Modules\InfChatBotModule\InfChatBot' => ['53df5848-ac25-4c50-8bfc-e5b7e2ca3960']
		]]
	]
]);

$bot->run();
```
