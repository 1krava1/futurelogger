<?php $out = ''; ?>
<?php $out .= get_header( 'return' ); ?>
<?php ob_start(); ?>
<main id="main">
  <div class="container">
    <div class="row">
      <article class="col-md-9 table-wrapper">
        <form id="add_gain_form" class="add_gain_form row" action="" method="post">
          <div class="col-md-6">
            <label for="name">
              Title
            </label>
            <input id="name" type="text" name="name" placeholder="Gain Title">
          </div>
          <div class="col-md-6">
            <label for="date">
              Date
            </label>
            <input id="date" type="date" name="date" placeholder="Gain Date">
          </div>
          <div class="col-md-6">
            <label for="value">
              Value
            </label>
            <input id="value" type="number" min="0" name="value" placeholder="Gain Amount">
          </div>
          <div class="col-md-6">
            <label for="note">
              Note
            </label>
            <textarea id="note" name="note" rows="8" cols="40" placeholder="Descripe this gain"></textarea>
          </div>
          <div class="col-md-12">
            <input type="submit" name="submit" value="Add Gain">
          </div>
        </form>
      </article>
      <?php template( 'sidebar', 'echo' ); ?>
    </div>
  </div>
</main>
<?php $out .= ob_get_contents(); ?>
<?php ob_end_clean(); ?>
<?php $out .= get_footer( 'return' );?>
<?php return $out; ?>