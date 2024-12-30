<?php
session_start();

function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}
?>