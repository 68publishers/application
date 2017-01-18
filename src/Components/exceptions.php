<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Components;

interface Exception
{
}

class ConfigurationException extends \RuntimeException implements Exception
{
}
