<?php

namespace App;

/**
 * Flash notification messages: messages for one-time display using the session
 * for storage between requests.
 *
 * PHP version 7.0
 */
class Flash
{

    const SUCCESS = 'success';
    const INFO = 'info';
    const WARNING = 'warning';

    public static function addMessageToFlashNotifications(string $message, string $message_type = 'success')
    {
        // Create array in the session if it doesn't already exist
        if (! isset($_SESSION['flash_notifications'])) {
            $_SESSION['flash_notifications'] = [];
        }

        // Append the message to the array
        $_SESSION['flash_notifications'][] = [
            'body' => $message,
            'type' => $message_type
        ];
    }

    public static function getAllFlashNotifications()
    {
        if (isset($_SESSION['flash_notifications'])) {
            $messages = $_SESSION['flash_notifications'];
            unset($_SESSION['flash_notifications']);

            return $messages;
        }
    }
}
