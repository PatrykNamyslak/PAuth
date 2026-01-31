<?php
namespace PatrykNamyslak\Auth\Enums;

enum AuthMode{
    case LOGIN_USERNAME;
    case LOGIN_EMAIL;
    case LOGIN_MAGIC_LINK;
    case REGISTER;
    case OAUTH;
}