<?php $out = ''; ?>
<?php ob_start(); ?>
<aside class="col-md-3">
  <div class="side-menu-wrapper">
    <ul class="side-menu">
      <li><a href="?action=add_gain"><i class="fa fa-angle-up green-bg"></i>Add Gain</a></li>
      <li><a href="?action=add_cost"><i class="fa fa-angle-down red-bg"></i>Add Cost</a></li>
      <li><a href="?action=add_goal"><i class="fa fa-cloud"></i>Add Goal</a></li>
      <li><a href="?action=logout">Logout</a></li>
    </ul>
  </div>
</aside>
<?php $out .= ob_get_contents(); ?>
<?php ob_end_clean(); ?>
<?php return $out; ?>