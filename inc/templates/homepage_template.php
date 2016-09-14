<?php $out = ''; ?>
<?php $out .= get_header( 'return' ); ?>
<?php ob_start(); ?>
<?php $user = get_user(); ?>
<section id="banner" style="background: url('<?php echo URL . 'src/img/banner.jpg' ?>');">
  <div class="col-md-4 col-md-offset-8">
    <h2>Hello, it's FutureLogger</h2>
    <p>
      It's designed to help you in your money management, to set goals and reach them,
      track your progress in the simplest way
    </p>
  </div>
</section>
<main id="main" class="homepage">
  <div class="container-fluid">
    <div class="row">
      <div class="container">
        <div class="row cards">
          <article class="col-md-4 col-sm-4 col-xs-6" style="background: url('<?php echo URL . 'src/img/gain.jpg' ?>');">
            <h3>
              <a href="<?php echo URL; ?>?action=gains">Manage your gains</a>
            </h3>
          </article>
          <article class="col-md-4 col-sm-4 col-xs-6" style="background: url('<?php echo URL . 'src/img/cost.jpg' ?>');">
            <h3>
              <a href="<?php echo URL; ?>?action=costs">Manage your costs</a>
            </h3>
          </article>
          <article class="col-md-4 col-sm-4 col-xs-6" style="background: url('<?php echo URL . 'src/img/goal.jpg' ?>');">
            <h3>
              <a href="<?php echo URL; ?>?action=goals">Manage your goals</a>
            </h3>
          </article>
        </div>
      </div>
    </div>
  </div>
</main>
<?php $out .= ob_get_contents(); ?>
<?php ob_end_clean(); ?>
<?php $out .= get_footer( 'return' );?>
<?php return $out; ?>