<?php

namespace App\Enums;

enum TaskStatus: string
{

    case Pending = 'pending';
    case Completed = 'completed';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
