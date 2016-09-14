<?php $out = ''; ?>
<?php $out .= get_header( 'return' ); ?>
<?php ob_start(); ?>
<?php $goals = get_goals(); ?>
<main id="main">
  <div class="container">
    <div class="row">
      <?php if ( !empty($_GET['edit']) ){ ?>
        <?php $goal = get_goal( $_GET['edit'] )[0]; ?>
        <?php if ( $goal['user_id'] == $_COOKIE['user_id'] ): ?>
          <article class="col-md-9 table-wrapper">
            <form id="edit_goal_form" class="edit_goal_form row" action="" method="post">
              <input type="hidden" name="goal_id" value="<?php echo $goal['ID']; ?>">
              <div class="col-md-6">
                <label for="name">
                  Title
                </label>
                <input id="name" type="text" name="name" placeholder="Goal Title" value="<?php echo $goal['name']; ?>">
              </div>
              <div class="col-md-6">
                <label for="date">
                  Date
                </label>
                <input id="date" type="date" name="date" placeholder="Goal Date" value="<?php echo date( 'Y-m-d', strtotime($goal['date']) ); ?>">
              </div>
              <div class="col-md-6">
                <label for="value">
                  Value
                </label>
                <input id="value" type="number" min="0" name="value" placeholder="Goal Amount" value="<?php echo $goal['value']; ?>">
              </div>
              <div class="col-md-6">
                <label for="quantity">
                  Quantity
                </label>
                <input id="quantity" type="number" min="0" name="quantity" placeholder="Goal Quantity" value="<?php echo $goal['quantity']; ?>">
              </div>
              <div class="col-md-12">
                <label for="note">
                  Note
                </label>
                <textarea id="note" name="note" rows="8" cols="40" placeholder="Descripe this goal"><?php echo $goal['note']; ?></textarea>
              </div>
              <div class="col-md-12">
                <input type="submit" name="submit" value="Update Goal">
              </div>
            </form>
          </article>
        <?php else: ?>
          <article class="col-md-9 table-wrapper">
            <h2>Oops, you have no permissions to edit this gain.</h2>
          </article>
        <?php endif; ?>
      <?php } elseif ( !empty($_GET['delete']) ) { ?>
        <?php verify_delete_goal(); ?>
      <?php } else { ?>  
        <article class="col-md-9 table-wrapper">
          <h2>Goals <i class="fa fa-cloud"></i></h2>
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
            <?php foreach ($goals as $key => $goal): ?>
              <tr>
                <td><?php echo $goal['name']; ?></td>
                <td><?php echo date( 'd-M-y', strtotime($goal['date']) ); ?></td>
                <td><?php echo $goal['value']; ?></td>
                <td><?php echo $goal['quantity']; ?></td>
                <td><?php echo $goal['quantity'] * $goal['value']; ?></td>
                <td><?php echo $goal['note']; ?></td>
                <td class="actions">
                  <a href="?action=goals&amp;edit=<?php echo $goal['ID']; ?>" class="action-edit">Edit</a>
                  <a href="?action=goals&amp;delete=<?php echo $goal['ID']; ?>" class="action-delete">Delete</a>
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