<?php
namespace PatrykNamyslak\Auth\Enums;

enum UserAuthType:string{
    case TICKETMASTER = "Ticketmaster";
    case GOOGLE = "Google";
    case GITHUB = "Github";
    case APPLE = "Apple";
    case PASSWORD = "Password";
    case MAGIC_LINK = "Magic Link";
}