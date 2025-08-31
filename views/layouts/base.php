<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Online Learning Management System</title>

    <link href="/resources/css/bootstrap.min.css" rel="stylesheet" />
    <link
      href="/resources/font-awesome/css/font-awesome.css"
      rel="stylesheet"
    />

    <link href="/resources/css/animate.css" rel="stylesheet" />
    <link href="/resources/css/style.css" rel="stylesheet" />

    <!-- DATA TABLE -->
    <link
      href="/resources/css/plugins/dataTables/datatables.min.css"
      rel="stylesheet"
    />
    <!-- JQUERY -->
    <script src="/resources/js/jquery-3.1.1.min.js"></script>
    <!-- CHART JS -->
    <script src="/resources/js/plugins/chartJs/Chart.min.js"></script>

    <!-- JQUERY TOAST CSS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css"
      integrity="sha512-8D+M+7Y6jVsEa7RD6Kv/Z7EImSpNpQllgaEIQAtqHcI0H6F4iZknRj0Nx1DCdB+TwBaS+702BGWYC0Ze2hpExQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>

  <?php
  
    $classes = ['fixed-sidebar'];
    if(preg_match('(login|register)',$_SERVER['REQUEST_URI'])) {
      $classes[] = 'gray-bg';
    }
  ?>

  <body class="<?= implode(' ', $classes) ?>">

