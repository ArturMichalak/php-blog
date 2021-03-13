<?php


namespace BlogPage\BusinessLogic;


use Twig\Environment;

class BusinessLogic
{
    public static function userNotLogged(array $info, Environment $twig): bool
    {
        if (!array_key_exists('email', $info))
        {
            echo $twig->render('nologged.twig', ['button' => $info]);
            return true;
        }
        return false;
    }

}