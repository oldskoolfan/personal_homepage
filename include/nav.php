<?php
$path = str_replace('/', '', $_SERVER['REQUEST_URI']);
$active = 'class="active"';
?>
<nav>
  <a href="/about" <?= 'about' === $path ? $active : '' ?> >About</a> |
  <a href="/csci2412" <?= 'csci2412' === $path ? $active : '' ?> >CSCI 2412</a> |
  <a href="/music" <?= 'music' === $path ? $active : '' ?> >Music</a> |
  <a href="/thoughts" <?= 'thoughts' === $path ? $active : '' ?> >Thoughts</a>
</nav>
