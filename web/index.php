<html>
    <head>
        <title>Test page</title>
    </head>
    <body>
        <div>
            This is a 'hello world' for my CD container.
        </div>
        <p>
            GUID: <?php echo file_get_contents(__DIR__ . '/guid.txt') ?>
        </p>
    </body>
</html>
