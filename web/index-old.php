<!DOCTYPE html>
<html>
    <head>
        <title>Test page</title>
    </head>
    <body>
        <div>
            This is a 'hello world' for my CD container.
        </div>
        <p>
            Version: <?php echo getenv('CD_DEMO_VERSION') ?>
        </p>
        <p>
            GUID: <?php echo file_get_contents(__DIR__ . '/guid.txt') ?>
        </p>
    </body>
</html>
