<?php

class Filter
{
    // // Funkcja do filtrowania imienia i nazwiska
    // public function sanitizeName($name)
    // {
    //     $name = filter_var($name, FILTER_SANITIZE_STRING);
    //     $name = trim($name);
    //     $name = stripslashes($name);
    //     $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    //     return $name;
    // }

    // // Funkcja do filtrowania adresu email
    // public function sanitizeEmail($email)
    // {
    //     $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    //     $email = trim($email);
    //     $email = stripslashes($email);
    //     $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    //     return $email;
    // }

    // // Funkcja do filtrowania adresu URL
    // public function sanitizeUrl($url)
    // {
    //     $url = filter_var($url, FILTER_SANITIZE_URL);
    //     $url = trim($url);
    //     $url = stripslashes($url);
    //     $url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    //     return $url;
    // }

    // public function sanitizeTitle($title)
    // {
    //     $title = filter_var($title, FILTER_SANITIZE_STRING);
    //     $title = trim($title);
    //     $title = stripslashes($title);
    //     $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    //     return $title;
    // }

    // Funkcja generyczna
    private static function preventXSS($data)
    {
        // Usunięcie potencjalnie niebezpiecznych znaczników HTML i JavaScript
        $data = strip_tags($data);
        $data = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data);
        $data = preg_replace('/<\s*\/?script\s*>/i', '', $data);
        return $data;
    }
    public static function sanitizeData($data, $filterType)
    {
        $filter = FILTER_DEFAULT;
        switch ($filterType) {
            case 'str':
                $filter = FILTER_SANITIZE_STRING;
                break;
            case 'num':
                $filter = FILTER_SANITIZE_NUMBER_INT;
                break;
            case 'url':
                $filter = FILTER_SANITIZE_URL;
                break;
            case 'email':
                $filter = FILTER_SANITIZE_EMAIL;
                break;
            case 'def':
                break;
        }

        $data = filter_var($data, $filter);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        $data = Filter::preventXSS($data); // Dodatkowe zabezpieczenie przed atakami XSS
        return $data;
    }
}

?>