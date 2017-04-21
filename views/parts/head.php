<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $action = (isset($action)) ? $action : filter_input(INPUT_GET, 'action'); ?>
    <title> <?php echo ucfirst($action); ?> | howtoterminal</title>
    <meta name="description" content="Photo Gallery">
    <meta name="keywords" content="php, programming, byui, photography">
    <meta name="author" content="Adam Quinton">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ($action === 'album') { ?>
      <link rel="manifest" href="<?php echo "$_SERVER['DOCUMENT_ROOT'].$project_dir./manifest_json/?album=$album";?>">
    <?php } else { ?>
      <link rel="manifest" href="/manifest_json/default.json">
    <?php } ?>
  </head>
  <body>
  <!-- nav -->
    <?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/nav.php'; ?>
    <!-- nav -->
  <div class="singularity">
      <?php if (isset($error)) { ?>
        <p class="error">
          <?php echo $error; ?>
        </p>
      <?php } ?>
    </div>
    <div class="singularity">
    <?php if (isset($message)) { ?>
      <p class="message">
        <?php echo $message; ?>
      </p>
    <?php } ?>
  </div>
