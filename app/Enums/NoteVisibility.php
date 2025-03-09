<?php

namespace App\Enums;

enum NoteVisibility: string
{
  case PUBLIC = 'public';
  case PRIVATE = 'private';
  case SHARED = 'shared';
}
