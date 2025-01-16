# Ampridge

## Installation

```shell
composer require thesis/ampridge
```

## Basic usage

```php
<?php

declare(strict_types=1);

use Amp\Socket;
use Thesis\Ampridge\ReaderWriter;

$socket = Socket\connect('tcp://127.0.0.1:5432');

$rw = new ReaderWriter($socket);
$rw->write('test');
echo $rw->read(4);
```
