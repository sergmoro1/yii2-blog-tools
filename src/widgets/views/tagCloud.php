<?php
/*
 * Tag cloud.
 * @var $items - list of links
 */
?>

<div class="tag-cloud">
  <h3><?= $title ?></h3>
  <ul>
  
  <?php foreach($items as $item): ?>
    <li>
	  <?= $item ?>
    </li>
  <?php endforeach; ?>
  
  </ul>
</div>
