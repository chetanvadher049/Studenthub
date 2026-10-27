<?php

require_once __DIR__ . "/../php/auth.php";
requireAdmin();
header("Location: admin/dashboard.php");
exit;
