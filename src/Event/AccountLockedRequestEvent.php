<?php

namespace App\Event;

use App\DTO\AccountLockDTO;

class AccountLockedRequestEvent
{
  public function __construct(public readonly AccountLockDTO $data)
  {
  }
}
