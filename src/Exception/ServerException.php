<?php

declare(strict_types=1);

namespace ForexAPI\Client\Exception;

use RuntimeException;

class ServerException extends RuntimeException implements ForexAPIException
{
}
