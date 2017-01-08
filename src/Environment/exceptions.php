<?php

namespace SixtyEightPublishers\Application;

interface Exception
{
}

class ConfigurationException extends \RuntimeException implements Exception
{
}

class ProfileConfigurationException extends ConfigurationException implements Exception
{
}

class NonExistentProfileException extends \InvalidArgumentException implements Exception
{
}
