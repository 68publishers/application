<?php

namespace SixtyEightPublishers\Application;


interface Exception
{}


class ConfigurationException extends \RuntimeException implements Exception
{}


class NonExistentProfileException extends \InvalidArgumentException implements Exception
{}