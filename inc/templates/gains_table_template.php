<?php $out = ''; ?>
<?php $out .= get_header( 'return' ); ?>
<?php ob_start(); ?>
<?php $gains = get_gains(); ?>
<main id="main">
  <div class="container">
    <div class="row">
      <?php if ( !empty($_GET['edit']) ){ ?>
        <?php $gain = get_gain( $_GET['edit'] )[0]; ?>
        <?php if ( $gain['user_id'] == $_COOKIE['user_id'] ): ?>
          <article class="col-md-9 table-wrapper">
            <form id="edit_gain_form" class="edit_gain_form row" action="" method="post">
              <input type="hidden" name="gain_id" value="<?php echo $gain['ID']; ?>">
              <div class="col-md-6">
                <label for="name">
                  Title
                </label>
                <input id="name" type="text" name="name" placeholder="Gain Title" value="<?php echo $gain['name']; ?>">
              </div>
              <div class="col-md-6">
                <label for="date">
                  Date
                </label>
                <input id="date" type="date" name="date" placeholder="Gain Date" value="<?php echo date( 'Y-m-d', strtotime($gain['date']) ); ?>">
              </div>
              <div class="col-md-6">
                <label for="value">
                  Value
                </label>
                <input id="value" type="number" min="0" name="value" placeholder="Gain Amount" value="<?php echo $gain['value']; ?>">
              </div>
              <div class="col-md-6">
                <label for="note">
                  Note
                </label>
                <textarea id="note" name="note" rows="8" cols="40" placeholder="Descripe this gain"><?php echo $gain['note']; ?></textarea>
              </div>
              <div class="col-md-12">
                <input type="submit" name="submit" value="Update Gain">
              </div>
            </form>
          </article>
        <?php else: ?>
          <article class="col-md-9 table-wrapper">
            <h2>Oops, you have no permissions to edit this gain.</h2>
          </article>
        <?php endif; ?>
      <?php } elseif ( !empty($_GET['delete']) ) { ?>
        <?php verify_delete_gain(); ?>
      <?php } else { ?>  
        <article class="col-md-9 table-wrapper">
          <h2>Gains <i class="fa fa-angle-up green-bg"></i></h2>
          <table class="main-table">
            <thead>
              <th>Name</th>
              <th>Date</th>
              <th>Value</th>
              <th>Note</th>
              <th>Actions</th>
            </thead>
            <?php foreach ($gains as $key => $gain): ?>
              <tr>
                <td><?php echo $gain['name']; ?></td>
                <td><?php echo date( 'd-M-y', strtotime($gain['date']) ); ?></td>
                <td><?php echo $gain['value']; ?></td>
                <td><?php echo $gain['note']; ?></td>
                <td class="actions">
                  <a href="?action=gains&amp;edit=<?php echo $gain['ID']; ?>" class="action-edit">Edit</a>
                  <a href="?action=gains&amp;delete=<?php echo $gain['ID']; ?>" class="action-delete">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
        </article>
      <?php } ?>
      <?php template( 'sidebar', 'echo' ); ?>
    </div>
  </div>
</main>
<?php $out .= ob_get_contents(); ?>
<?php ob_end_clean(); ?>
<?php $out .= get_footer( 'return' );?>
<?php return $out; ?>