<?php

function deleteParametersThree($url, $value)
{
    $parsed_url = parse_url($url);
    parse_str($parsed_url['query'], $params);
    
    $result = array_filter($params, function ($paramValue) use ($value) {
        return $paramValue !== (string) $value;
    });
    
    return generateUrl($parsed['host'], $parsed['path'], $result, $parsed['scheme']);
}

function sortParams($url)
{
    $parsed = parse_url($url);
    parse_str($parsed['query'], $params);
    asort($params);

    return generateUrl($parsed['host'], '', $params, $parsed['scheme']);
}

function removePath($url)
{
    $parsed = parse_url($url);
    parse_str($parsed['query'], $params);

    return generateUrl($parsed['host'], '', $params, $parsed['scheme']);
}

function addParam($url, $name, $value)
{
    $parsed = parse_url($url);
    parse_str($parsed['query'], $params);
    $params[$name] = $value;

    return generateUrl($parsed['host'], '', $params, $parsed['scheme']);
}

function generateUrl($host, $path = '', $params = [], $scheme = 'http')
{
    return $scheme . '://' . $host . '/' . $path . '?' . http_build_query($params);
}

function  decisionTaskTwo($url, $params)
{
    $filter = deleteParametersThree($url, 3);
    $sorted = sortParams($filter);
    $pathRemoved = removePath($sorted);
    $updated = addParam($pathRemoved, 'url', $params);

    return $updated;
}

$url = 'https://www.somehost.com/test/index.html?param1=4&param2=3&param3=2&param4=1&param5=3';
echo decisionTaskTwo($url, '/test/index.html');


echo '<br/>';echo '<br/>';
