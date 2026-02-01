<?php
namespace PatrykNamyslak\Auth;

trait Core{
    public function redirect(string $destination): never{
        header("location: {$destination}");
        exit;
    }
}