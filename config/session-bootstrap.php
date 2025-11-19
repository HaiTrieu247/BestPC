<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}