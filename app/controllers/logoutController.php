<?php

class LogoutController {
    public function logout() {
        // Clear all session data
        session_unset();
        session_destroy();
    }
}