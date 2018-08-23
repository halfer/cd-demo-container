<?php

/**
 * A PHP test to check the app is OK
 */

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testApp()
    {
        echo "Env:\n";
        print_r($_ENV);
        echo "Server:\n";
        print_r($_SERVER);
        echo "Docker output:\n";
        print_R($this->getWebPage());
    }

    protected function getWebPage()
    {
        $output = $return = null;
        exec('docker exec -ti cd-demo-container wget -qO- http://localhost', $output, $return);

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
}
