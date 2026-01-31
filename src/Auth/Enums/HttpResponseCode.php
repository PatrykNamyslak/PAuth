<?php
namespace PatrykNamyslak\Auth\Enums;


enum HttpResponseCode: int{
    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;
    case NO_CONTENT = 204;
    case RESET = 205;
    case PARTIAL_CONTENT = 206;
}

?>