<?php

/**
 * Manage Notifications, Errors, and Form Values with Sessions
 * 
 * The FlashMessage class is a utility designed to handle session-based flash messages, form validation errors,
 * and previously posted form values in a PHP application. Flash messages are typically used to notify users
 * of the outcome of an action, such as success, warnings, or errors, and are displayed after a page reload,
 * then automatically cleared. This class helps manage and display such messages, errors, and input data
 * without requiring persistent storage (i.e., it leverages PHP sessions).
 * 
 * Author: Ideaglory
 * GitHub: https://github.com/ideaglory/php-class-flash-message
 * 
 */

class FlashMessage
{
    // Message types
    const SUCCESS = 'success';
    const DANGER = 'danger';
    const WARNING = 'warning';
    const INFO = 'info';

    // Initialize the session if not already started
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Combined method to set messages, errors, and posted values
    public function set($messages = null, $errors = null, $posted_values = null)
    {
        // Set messages if provided
        if ($messages !== null) {
            if (!isset($_SESSION['msg_messages'])) {
                $_SESSION['msg_messages'] = [];
            }
            foreach ($messages as $message) {
                $_SESSION['msg_messages'][] = [
                    'text' => $message['text'],
                    'type' => isset($message['type']) ? $message['type'] : self::INFO,
                ];
            }
        }

        // Set errors if provided
        if ($errors !== null) {
            $_SESSION['msg_errors'] = $errors;
        }

        // Set posted values if provided
        if ($posted_values !== null) {
            // Sanitize the posted values
            $_SESSION['msg_values'] = $this->sanitizePostedValues($posted_values);
        }
    }

    // Sanitize posted values to prevent XSS and other security issues
    private function sanitizePostedValues($posted_values)
    {
        $sanitized = [];
        foreach ($posted_values as $key => $value) {
            // Sanitize each value to prevent malicious input
            $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
        return $sanitized;
    }

    // Display all messages, errors, and posted values as an array, then clear session data
    public function display()
    {
        $data = [
            'messages' => $this->getMessages(),
            'errors' => $this->getErrors(),
            'values' => $this->getPostedValues(),
        ];
        $this->clear();  // Automatically clear session data after collecting it
        return $data; // Return all data as an array
    }

    // Get all messages (notifications)
    private function getMessages()
    {
        return isset($_SESSION['msg_messages']) ? $_SESSION['msg_messages'] : [];
    }

    // Get all validation errors
    private function getErrors()
    {
        return isset($_SESSION['msg_errors']) ? $_SESSION['msg_errors'] : [];
    }

    // Get posted values (to repopulate forms)
    private function getPostedValues()
    {
        return isset($_SESSION['msg_values']) ? $_SESSION['msg_values'] : [];
    }

    // Clear all session data (messages, errors, posted values)
    private function clear()
    {
        unset($_SESSION['msg_messages']);
        unset($_SESSION['msg_errors']);
        unset($_SESSION['msg_values']);
    }
}
