<?php $out = ''; ?>
<?php $out .= get_header( 'return' ); ?>
<?php ob_start(); ?>
<?php $user = get_user(); ?>
<main id="main">
  <div class="container">
    <div class="row">
      <article class="col-md-9 table-wrapper">
        <form id="my_profile_form" class="my_profile_form row" action="" method="post">
          <div class="col-md-6">
            <label for="first_name">
              First Name
            </label>
            <input id="first_name" type="text" name="first_name" value="<?php echo $user['first_name']; ?>">
          </div>
          <div class="col-md-6">
            <label for="last_name">
              Last Name
            </label>
            <input id="last_name" type="text" name="last_name" value="<?php echo $user['last_name']; ?>">
          </div>
          <div class="col-md-6">
            <label for="name">
              Login
            </label>
            <input id="name" type="text" name="name" value="<?php echo $user['name']; ?>">
          </div>
          <div class="col-md-6">
            <label for="email">
              Email
            </label>
            <input id="email" type="email" name="email" value="<?php echo $user['email']; ?>">
          </div>
          <div class="col-md-6">
            <label for="pass">
              New Password
            </label>
            <input id="pass" type="password" name="pass" value="">
          </div>
          <div class="col-md-6">
            <label for="pass_confirmation">
              Confirm Password
            </label>
            <input id="pass_confirmation" type="password" name="pass_confirmation" value="">
          </div>
          <div class="col-md-12">
            <input type="submit" name="submit" value="Update Profile">
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