<?php
require_once __DIR__ . '/common/helpers.php';
ensure_session();
$currentUser = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>QueryHub</title>
   <?php include __DIR__ . '/client/commonFiles.php'; ?>
</head>
<body>
   <?php
   include __DIR__ . '/client/header.php';

   if (isset($_GET['signup']) && !$currentUser) {
      include __DIR__ . '/client/signup.php';
   } else if (isset($_GET['login']) && !$currentUser) {
      include __DIR__ . '/client/login.php';
   } else if (isset($_GET['ask'])) {
      if ($currentUser) {
         include __DIR__ . '/client/ask.php';
      } else {
         set_flash('warning', 'Please log in to ask a question.');
         include __DIR__ . '/client/login.php';
      }
   } else if (isset($_GET['q-id'])) {
      $qid = (int) $_GET['q-id'];
      include __DIR__ . '/client/question-details.php';
   } else if (isset($_GET['c-id'])) {
      $cid = (int) $_GET['c-id'];
      include __DIR__ . '/client/questions.php';
   } else if (isset($_GET['u-id'])) {
      $uid = (int) $_GET['u-id'];
      include __DIR__ . '/client/questions.php';
   } else if (isset($_GET['latest'])) {
      include __DIR__ . '/client/questions.php';
   } else if (isset($_GET['search'])) {
      $search = trim((string) $_GET['search']);
      include __DIR__ . '/client/questions.php';
   } else {
      include __DIR__ . '/client/questions.php';
   }
   ?>
</body>
</html>
