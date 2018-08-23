<?php

/**
 * A PHP test to check the app is OK
 */

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testApp()
    {
        echo "Docker output:\n";
        print_r($this->getWebPage());
    }

    protected function getWebPage()
    {
        $output = $return = null;
        $command = sprintf(
            'docker exec -ti %s wget -qO- http://localhost',
            escapeshellarg($this->getRepoName())
        );
        exec($command, $output, $return);

        // Check return value first
        if ($return)
        {
            throw new RuntimeException(
                sprintf(
                    'Returned a failure exit code %d on Docker operation',
                    $return
                )
            );
        }

        return $output;
    }

    protected function getRepoName()
    {
        $name = isset($_SERVER['CIRCLE_PROJECT_REPONAME']) ?
            $_SERVER['CIRCLE_PROJECT_REPONAME'] :
            null;
        if (!$name)
        {
            throw new RuntimeException('Repository name not detected');
        }

        return $name;
    }
}
