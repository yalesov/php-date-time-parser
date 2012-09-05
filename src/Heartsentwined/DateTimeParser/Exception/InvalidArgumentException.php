<?php
namespace Heartsentwined\DateTimeParser\Exception;

use Heartsentwined\DateTimeParser\ExceptionInterface;

class InvalidArgumentException
    extends \InvalidArgumentException
    implements ExceptionInterface
{
}
