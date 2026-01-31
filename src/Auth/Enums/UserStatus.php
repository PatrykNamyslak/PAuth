<?php
namespace PatrykNamyslak\Auth\Enums;

enum UserStatus:string{
    case ACTIVE = "active";
    case BANNED = "banned";
    case LOCKED = "locked";
    case DELETED = "deleted";
}