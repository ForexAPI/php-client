<?php

declare(strict_types=1);

namespace ForexAPI\Client\Exception;

use RuntimeException;

class NetworkException extends RuntimeException implements ForexAPIException
{
}
