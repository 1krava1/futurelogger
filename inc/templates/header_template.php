<?php ob_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>M-M-M</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700|Slabo+27px' rel='stylesheet' type='text/css'>
    <?php connect_style( array( 'bootstrap_grid.css', 'css_reset.css', 'font-awesome.css', 'style.css' ) ); ?>
  </head>
  <body>
    <header id="header">
      <div class="logo-wrapper">
        <a href="<?php echo URL; ?>">
          <i class="fa fa-bar-chart"></i>
        </a>
      </div>
      <h2 class="site-title">
        <a href="<?php echo URL; ?>">
          FutureLogger
        </a>
      </h2>
      <nav id="main-nav" class="pull-right">
        <ul>
          <li><a href="<?php echo URL; ?>?action=gains">Gains</a></li>
          <li><a href="<?php echo URL; ?>?action=costs">Costs</a></li>
          <li><a href="<?php echo URL; ?>?action=goals">Goals</a></li>
          <li><a href="<?php echo URL; ?>?action=my_profile">My Profile</a></li>
        </ul>
      </nav>
    </header>
<?php $out = ob_get_contents(); ?>
<?php ob_end_clean(); ?>
<?php return $out; ?>