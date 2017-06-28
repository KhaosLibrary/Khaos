<?php

$data = 'hello <% hello %> some other data <% lol %> hmm.';

function getTokens($data)
{
    $offset = 0;

    while (($current = strpos($data, '<%', $offset)) !== false)
    {
        if ($current != $offset)
            yield 'string' => substr($data, $offset, $current - $offset);

        $offset  = $current + 2;
        $current = strpos($data, '%>', $offset);

        yield 'expression' => trim(substr($data, $offset, $current - $offset));

        $offset = $current + 2;
    }

    if ($offset < strlen($data))
        yield 'string' => substr($data, $offset);
}

$start = microtime(true);

foreach (getTokens($data) as $token => $value)
    echo $token.'("'.$value.'")'."\n";