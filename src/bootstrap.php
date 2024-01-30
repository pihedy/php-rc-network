<?php declare(strict_types=1);

/**
 * Defines the path to the vendor directory.
 * 
 * Concatenates the SRC_DIR constant with 'vendor' and the directory separator.
 */
define('VENDOR_DIR', SRC_DIR . DIRECTORY_SEPARATOR . 'vendor');

/**
 * Throws an exception if the vendor autoloader file does not exist.
 */
if (!file_exists(VENDOR_DIR . DIRECTORY_SEPARATOR . 'autoload.php')) {
    throw new \Exception('Vendor autoloader not found!');
}

/**
 * Includes the Composer autoloader file to enable autoloading of classes.
 */
include VENDOR_DIR . DIRECTORY_SEPARATOR . 'autoload.php';

/* $Factory = new \React\Datagram\Factory();

$Factory->createClient('127.0.0.1:59649')->then(function (\React\Datagram\Socket $Client) {
    $Client->send(json_encode(['message' => 'Hello World!']));

    $Client->on('message', function (string $message, string $remoteAddress, \React\Datagram\Socket $Socket) {
        echo "Received message from {$remoteAddress}: {$message}" . PHP_EOL;
    });
}); */
