<?php
/**
 * session setting translation
 */
return array (
    'seo' => [
        'edit-setting-session' => "Dashboard - Edit Session - :site_name",
    ],

    'alert' => [
        'session-updated-success' => "Session setting updated successfully.",
    ],

    'edit' => "Session Setting",
    'edit-desc' => "This page allows you to configure website session",

    'session-file' => "File",
    'session-cookie' => "Cookie",
    'session-database' => "Database",

    'session-driver' => "Session Driver",
    'session' => "Session",
    'update-help' => "You need to log in again after the session driver update",
    'session-intro-desc' => "Since HTTP-driven applications are stateless, sessions provide a way to store information about the user across multiple requests. The website supports 3 ways of storing session information: file, cookie, and database. No matter which way of storing session, the session information is encrypted.",

    'session-file-help' => "File - sessions are stored in laravel_project/storage/framework/sessions.",
    'session-cookie-help' => "Cookie - sessions are stored in secure, encrypted cookies.",
    'session-database-help' => "Database - sessions are stored in sessions table of the website database.",

);
