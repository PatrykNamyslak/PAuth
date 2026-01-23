<?php
namespace PatrykNamyslak\Auth;

enum AuthMode{
    case LOGIN;
    case REGISTER;
    case OAUTH;
}