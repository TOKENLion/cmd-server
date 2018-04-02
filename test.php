<?php
//sleep(5);

if (!empty($_POST['cmd'])) {
    include_once ("function.php");
    try {
        $temp_cmd = $cmd = $_POST['cmd'];
        $pwd = $_POST['pwd'];

        $rules_command = [
            ';', '&', '&&', '|', '<', '>', '>>'
        ];

        $allowed_commands = [
            'git', 'dir', 'ls', 'pull'
        ];

        $error_command = "";
        $all_commands = array();

        foreach ($rules_command as $rule) {
            $all_commands = array_merge($all_commands, explode($rule, $temp_cmd));
        }

        $all_commands = array_unique(array_map('trim', array_filter($all_commands)));

        foreach ($all_commands as $command) {
            $passed = false;
            foreach ($allowed_commands as $allowed) {
                if (strpos($command, $allowed) !== false) {
                    $passed = true;
                    break;
                }
            }

            if (!$passed) {
                $error_command .= "Incorrect command instruction ({$command})\n";
            }
        }

        if (!empty($error_command)) {
            display_json("", "error", array('result' => $error_command));
        }

        $result = run_command($cmd, $pwd);
        display_json("", "success", array('result' => $result['res'], 'pwd' => $result['pwd'] . PHP_EOL . '$ '));
    } catch (Exception $e) {
        display_json("", "error", array('result' => $e->getMessage()));
    }

}


