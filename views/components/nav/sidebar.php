<?php
$menu = [
  // SO YUNG ^ SA REGEX MEANS START NG STRING AND YUNG $ NAMAN NAG I-INDICATE SA END NG STRING
  'admin' => [
    ['url' => '/admin', 'icon' => 'fa-th-large', 'label' => 'Dashboard', 'match' => '^admin$'],
    ['url' => '/admin/student', 'icon' => 'fa-users', 'label' => 'Students', 'match' => 'student'],
    ['url' => '/admin/instructor', 'icon' => 'fa-graduation-cap', 'label' => 'Instructors', 'match' => 'instructor'],
    ['url' => '/admin/courses', 'icon' => 'fa-book', 'label' => 'Courses', 'match' => 'course'],
    // ['url' => '/admin/setting', 'icon' => 'fa-gear', 'label' => 'Settings', 'match' => 'setting'],
  ],

  'student' => [
    ['url' => '/student', 'icon' => 'fa-th-large', 'label' => 'Dashboard', 'match' => '^student$'],
    ['url' => '/student/courses', 'icon' => 'fa-book', 'label' => 'My Courses', 'match' => 'courses'],
    // ['url' => '/student/profile', 'icon' => 'fa-user', 'label' => 'Profile', 'match' => 'profile'],
  ],

  'instructor' => [
    ['url' => '/instructor', 'icon' => 'fa-th-large', 'label' => 'Dashboard', 'match' => '^instructor$'],
    ['url' => '/instructor/courses', 'icon' => 'fa-book', 'label' => 'My Courses', 'match' => 'course'],
    // ['url' => '/instructor/students', 'icon' => 'fa-users', 'label' => 'Students', 'match' => 'students'],
    // ['url' => '/instructor/profile', 'icon' => 'fa-user', 'label' => 'Profile', 'match' => 'profile'],
  ]
];

// GET SIDEBAR ITEM USING USER ROLE VIA SESSION
$role = $_SESSION['user_role'];

$current = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

?>

<nav class="navbar-default navbar-static-side navbar-fixed-side" role="navigation">
  <div class="sidebar-collapse">
    <ul class="nav metismenu" id="side-menu">
      <li class="nav-header">
        <div class="dropdown profile-element">
          <span class="block m-t-xs text-light font-bold"><?= $_SESSION['user_name'] ?></span>
          <span class="text-muted text-xs block"><?= $_SESSION['user_email'] ?></span>
        </div>
        <div class="logo-element">LMS</div>
      </li>
      <?php foreach ($menu[$role] as $item): ?>
        <li class="<?= (preg_match("#{$item['match']}#", $current)) ? 'active' : '' ?>">
          <a href="<?= $item['url'] ?>"><i class="fa <?= $item['icon'] ?> fa-xl"></i>
            <span class="nav-label"><?= $item['label'] ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>