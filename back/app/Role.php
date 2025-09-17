<?php
namespace App;
enum Role: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Staff = 'staff';
    case User = 'user';
}