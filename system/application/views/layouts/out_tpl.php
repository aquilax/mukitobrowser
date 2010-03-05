<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $game_title; if (isset($title)) echo ' &raquo; '.$title;?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo site_url('css/out.css?1')?>" />
<?php
  foreach ($js as $row){
    echo '<script type="text/javascript" src="'.$row.'"></script>';
  }
?>
</head>
<body>
  <?php echo $content; ?>
</body>
