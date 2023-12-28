<?php

declare(strict_types=1);

namespace ForexAPI\Client\Exception;

use InvalidArgumentException;

class ClientException extends InvalidArgumentException implements ForexAPIException
{
}
