<?php

/**
 * A PHP test to check the app is OK
 */

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testApp()
    {
        // Get the page and convert it to an XML object
        $html = $this->getWebPage();

        // CI is giving me some parsing issues, so let's see it
        echo str_replace("\n", "⏎", $html);

        $doc = simplexml_load_string($html);
        $message = trim((string) $doc->body->div);

        $this->assertEquals(
            "This is a 'hello world' for my CD container.",
            $message
        );
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

        $imploded = implode(PHP_EOL, $output);

        return $imploded;
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
