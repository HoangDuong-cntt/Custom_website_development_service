<?php require_once __DIR__.'/auth.php'; header('Location: print_contract.php?id='.(int)($_GET['id']??0)); exit;
