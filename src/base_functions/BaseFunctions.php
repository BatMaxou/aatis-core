<?php

function presentArray(array $array, string $arrayKey = null): void
{
    if (empty($array)) {
        return;
    }

    if ('0' === (string) $arrayKey || $arrayKey) {
        echo "<div style='
            margin-left: 25px;
        '><span style='
            color: #ff9900;
        '>";

        echo 'string' === ($arrayKey) ? '"' . $arrayKey . '"' : $arrayKey;

        echo ' : </span> [';
    } else {
        echo "<div>array [";
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {;
            presentArray($value, $key);
        } else {
            echo '<p style="
                margin: 0 0 0 25px;
                padding: 0;
            "><span style="
                color: #ff9900;
            ">';

            echo 'string' === gettype($key) ? '"' . $key . '"' : $key;

            echo  ' : </span>';

            echo in_array(gettype($value), ['integer', 'float']) ? '<span style="
                    color: #00ccff;
                ">' . $value .  '</span></p>' :  '<span style="
                    color: #00ff00;
                ">"' . $value .  '"</span></p>';
        }
    }
    echo "]</div>";
}

function dump(mixed $value): void
{
    echo '<pre style="
        background-color: #111;
        padding: 25px;
        color: #cc00ff;
    ">';
    if ('array' === gettype($value)) {
        presentArray($value);
    } elseif ('string' === gettype($value) || 'integer' === gettype($value)) {
        echo $value;
    } else {
        var_dump($value);
    }
    echo '</pre>';
}

function dd(mixed $value): void
{
    dump($value);
    die;
}

function verifyAuthentification(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: /');
    }
}

function verifyRequiredArguments(array $arguments): bool
{
    return array_reduce($arguments, function ($carry, $argument) {
        return $carry && $argument['value'] ? true : false;
    }, true);
}

function verifyTypeArguments(array $arguments): bool
{
    $check = true;

    foreach ($arguments as $argument) {
        if (!isset($argument['isInt']) || !$argument['isInt'] || !intval($argument['value'])) {
            $check = false;
        }
    }


    return $check;
}

function verifyArguments(array $arguments): bool
{
    if (!verifyRequiredArguments($arguments)) {
        header('HTTP/1.0 404 Not Found');
        require_once('../views/errors/404.php');

        return false;
    }

    if (!verifyTypeArguments($arguments)) {
        header('HTTP/1.0 404 Not Found');
        require_once('../views/errors/404.php');

        return false;
    }

    return true;
}

function parseExplodeUrl(array $explode): array
{
    return array_map('parseUrlElement', $explode);
}

function parseUrlElement(string $element): string
{
    $element = explode('-', $element);
    $element = array_map('ucfirst', [...$element]);
    return lcfirst(join('', $element));
}
