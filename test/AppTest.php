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
        // Do the HTTP fetch first
        $this->exec(
            sprintf(
                'docker exec -ti %s wget -qO- http://localhost > /tmp/home-page',
                escapeshellarg($this->getRepoName())
            )
        );

        // Now try copying it out
        $this->exec(
            sprintf(
                'docker cp %:/tmp/home-page /tmp/home-page',
                escapeshellarg($this->getRepoName())
            )
        );
        $html = file_get_contents('/tmp/home-page');

        return $html;
    }

    protected function exec($command)
    {
        $output = $return = null;

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

        return [$output, $return];
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
