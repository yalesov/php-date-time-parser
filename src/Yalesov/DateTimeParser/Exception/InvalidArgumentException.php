<?php
namespace Yalesov\DateTimeParser\Exception;

use Yalesov\DateTimeParser\ExceptionInterface;

class InvalidArgumentException
  extends \InvalidArgumentException
  implements ExceptionInterface
{
}
