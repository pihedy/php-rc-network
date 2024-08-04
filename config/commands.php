<?php declare(strict_types=1);

/** 
 * Commands config.
 * 
 * @author Pihe Edmond <pihedy@gmail.com>
 */

return [
    'list' => [
        'test:valami'   => \RcNetwork\Command\TestCommand::class,
        'serial:test'   => \RcNetwork\Command\SerialTestCommand::class,
        'control:start' => \RcNetwork\Command\ControlStartCommand::class,
    ]
];
