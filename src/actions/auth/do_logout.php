<?php
// src/actions/auth/do_logout.php

// 1. Selalu mulai session
session_start();

// 2. Hapus semua variabel session
session_unset();

// 3. Hancurkan session
session_destroy();

// 4. Kembalikan ke landing page
header("Location: /"); // '/' adalah index.php di root
exit;
?>