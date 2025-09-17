<?php
namespace App;
enum ReservationStatus: string
{
    case Reserved = 'reserved';
    case Pending = 'pending';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';
    case Done = 'done';
}