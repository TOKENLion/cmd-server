<?php

$descriptorspec = array(
    0 => array('pipe', 'r'),
    1 => array('pipe', 'w'),
    2 => array('pipe', 'w'),
);
$pipes = array();

$resource = proc_open('bash', $descriptorspec, $pipes, null, null);
//$stdout = stream_get_contents($pipes[1]);
//$stderr = stream_get_contents($pipes[2]);


if (is_resource($resource)) {
    // $pipes теперь выглядит так:
    // 0 => записывающий обработчик, подключенный к дочернему stdin
    // 1 => читающий обработчик, подключенный к дочернему stdout
    // Вывод сообщений об ошибках будет добавляться в /tmp/error-output.txt

    fwrite($pipes[0], 'ls -la');
    fwrite($pipes[0], "\n");
    fwrite($pipes[0], 'cd .git');
    fwrite($pipes[0], "\n");
    fwrite($pipes[0], 'ls -la');
    fwrite($pipes[0], "\n");
    fwrite($pipes[0], 'pwd');
    fclose($pipes[0]);

    $res = stream_get_contents($pipes[1]);
    $lines = explode("\n", $res);

    $count = count($lines);

    echo $lines[$count - 2];

    unset($lines[$count - 1]);
    unset($lines[$count - 2]);

    echo implode(PHP_EOL, $lines);

    fclose($pipes[1]);

    $return_value = proc_close($resource);

}