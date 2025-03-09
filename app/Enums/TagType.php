<?php

namespace App\Enums;

enum TagType: string
{
  case PRIORITY = 'priority';
  case STATUS = 'status';
  case CATEGORY = 'category';
  case LABEL = 'label';
}
