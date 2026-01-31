<?php
namespace PatrykNamyslak\Auth\Enums;


enum HttpErrorResponseCode: int{
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case PAYMENT_REQUIRED = 402;
    case PERMISSION_DENIED = 403;
    case AUTHENTICATION_FAILED = 401;
    case BAD_REQUEST = 400;
}

?>