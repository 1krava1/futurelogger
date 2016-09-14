<?php $out = ''; ?>
<?php $out .= get_header( 'return' ); ?>
<?php ob_start(); ?>
<?php $costs = get_costs(); ?>
<main id="main">
  <div class="container">
    <div class="row">
      <?php if ( !empty($_GET['edit']) ){ ?>
        <?php $cost = get_cost( $_GET['edit'] )[0]; ?>
        <?php if ( $cost['user_id'] == $_COOKIE['user_id'] ): ?>
          <article class="col-md-9 table-wrapper">
            <form id="edit_cost_form" class="edit_cost_form row" action="" method="post">
              <input type="hidden" name="cost_id" value="<?php echo $cost['ID']; ?>">
              <div class="col-md-6">
                <label for="name">
                  Title
                </label>
                <input id="name" type="text" name="name" placeholder="Cost Title" value="<?php echo $cost['name']; ?>">
              </div>
              <div class="col-md-6">
                <label for="date">
                  Date
                </label>
                <input id="date" type="date" name="date" placeholder="Cost Date" value="<?php echo date( 'Y-m-d', strtotime($cost['date']) ); ?>">
              </div>
              <div class="col-md-6">
                <label for="value">
                  Value
                </label>
                <input id="value" type="number" min="0" name="value" placeholder="Cost Amount" value="<?php echo $cost['value']; ?>">
              </div>
              <div class="col-md-6">
                <label for="quantity">
                  Quantity
                </label>
                <input id="quantity" type="number" min="0" name="quantity" placeholder="Cost Quantity" value="<?php echo $cost['quantity']; ?>">
              </div>
              <div class="col-md-12">
                <label for="note">
                  Note
                </label>
                <textarea id="note" name="note" rows="8" cols="40" placeholder="Descripe this cost"><?php echo $cost['note']; ?></textarea>
              </div>
              <div class="col-md-12">
                <input type="submit" name="submit" value="Update Cost">
              </div>
            </form>
          </article>
        <?php else: ?>
          <article class="col-md-9 table-wrapper">
            <h2>Oops, you have no permissions to edit this gain.</h2>
          </article>
        <?php endif; ?>
      <?php } elseif ( !empty($_GET['delete']) ) { ?>
        <?php verify_delete_cost(); ?>
      <?php } else { ?>  
        <article class="col-md-9 table-wrapper">
          <h2>Costs <i class="fa fa-angle-down red-bg"></i></h2>
          <table class="main-table">
            <thead>
              <th>Name</th>
              <th>Date</th>
              <th>Value</th>
              <th>Quantity</th>
              <th>Summary</th>
              <th>Note</th>
              <th>Actions</th>
            </thead>
            <?php foreach ($costs as $key => $cost): ?>
              <tr>
                <td><?php echo $cost['name']; ?></td>
                <td><?php echo date( 'd-M-y', strtotime($cost['date']) ); ?></td>
                <td><?php echo $cost['value']; ?></td>
                <td><?php echo $cost['quantity']; ?></td>
                <td><?php echo $cost['quantity'] * $cost['value']; ?></td>
                <td><?php echo $cost['note']; ?></td>
                <td class="actions">
                  <a href="?action=costs&amp;edit=<?php echo $cost['ID']; ?>" class="action-edit">Edit</a>
                  <a href="?action=costs&amp;delete=<?php echo $cost['ID']; ?>" class="action-delete">Delete</a>
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