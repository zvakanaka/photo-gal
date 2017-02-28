<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/head.php'; ?>
<main>
    <h1>Manage Users</h1>
    <table>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Admin</th>
          <th>&nbsp;</th>
        </tr>
        <?php foreach ($users as $user) : ?>
            <tr>
              <td>
                <a href="?action=review_favorites&user_id=<?php echo $user['user_id'];?>"><?php echo $user['username']; ?></a>
              </td>
              <td><?php echo $user['email']; ?></td>
              <td><?php echo ($user['is_admin'] == 1) ? "✓" : "✗";?></td>
              <td>
                <form action="." method="post">
                  <input type="hidden" name="action" value="delete_user">
                  <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                  <input type="submit" value="Delete">
              </form>
            </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/toes.php'; ?>
