<?php

namespace App\Services\Card;

use RuntimeException;

// A rule stopped the order. The message is safe to show to the user.
class CardOrderException extends RuntimeException
{
}
