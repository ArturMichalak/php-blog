<?php


namespace Libs\GoogleAuth;


interface GoogleAuthInterface
{
    public function info(): array;
    public function logout();
}