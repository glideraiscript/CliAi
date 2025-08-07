<?php
$lang = $_GET['lang'] ?? 'it';

$text = [
  'it' => [
    'title' => 'INSTALLA CliAi',
    'step0' => 'Estrazione file.zip in corso...',
    'step1' => 'Inserisci le impostazioni:',
    'domain' => 'Dominio di installazione',
    'site_title' => 'Titolo del sito',
    'description' => 'Descrizione meta',
    'policy' => 'Policy per banner cookie',
    'key' => 'Chiave personale per protezione download MP3',
    'token' => 'Chiave API Pollinations.ai',
    'referrer' => 'Dominio referer per generazione immagini',
    'save_continue' => 'Salva & Continua'
  ],
  'en' => [
    'title' => 'INSTALL CliAi',
    'step0' => 'Extracting file.zip...',
    'step1' => 'Enter your settings:',
    'domain' => 'Installation domain',
    'site_title' => 'Site title',
    'description' => 'Meta description',
    'policy' => 'Cookie banner policy',
    'key' => 'Personal key to protect MP3 download',
    'token' => 'Pollinations.ai API key',
    'referrer' => 'Domain referrer for image generation',
    'save_continue' => 'Save & Continue'
  ]
][$lang];

// STEP 0: Estrazione ZIP solo alla prima apertura
$zip_message = "";
$extracted_flag = __DIR__ . "/.zip_extracted";

if (!file_exists($extracted_flag)) {
    $zip = new ZipArchive;
    if ($zip->open("file.zip") === TRUE) {
        $zip->extractTo(__DIR__);
        $zip->close();
        $zip_message = "<p style='color:green'>✓ {$text['step0']} OK</p>";
        file_put_contents($extracted_flag, "done");
    } else {
        $zip_message = "<p style='color:red'>⚠️ file.zip non trovato o non leggibile.</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $domain = trim($_POST['domain']);
  $title = trim($_POST['title']) . " - Ai Song - By";
  $description = trim($_POST['description']) . " - Ai Song - By";
  $policy = trim($_POST['policy']);
  $key = trim($_POST['key']);
  $token = trim($_POST['token']);
  $referrer = trim($_POST['referrer']);

  $config_content = <<<PHP
<?php
\$domain = "$domain";
\$title = "$title";
\$description = "$description";
\$policy = <<<HTML
$policy
HTML;
\$key = "$key";
\$token = "$token";
\$AiPol = "$referrer";
PHP;

  file_exists("config.php") && copy("config.php", "config_backup.php");
  file_put_contents("config.php", $config_content);

  header("Location: install_step2.php?lang=$lang");
  exit;
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= $text['title'] ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Playfair Display', serif; background: #f9f9f9; padding: 0; margin: 0; }
    header { display: flex; align-items: center; justify-content: space-between; background: #fff; padding: 1em 2em; border-bottom: 1px solid #ddd; }
    header img { height: 60px; }
    header h1 { margin: 0 0 0 1em; font-size: 1.8em; }
    main { max-width: 800px; margin: 2em auto; background: #fff; padding: 2em; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
    label { display: block; margin-top: 1em; font-weight: bold; }
    input, textarea { width: 100%; padding: 0.5em; margin-top: 0.3em; border: 1px solid #ccc; border-radius: 5px; }
    button { margin-top: 2em; padding: 0.7em 2em; background: #2271b1; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .msg { background: #f0f0f0; padding: 1em; border-left: 5px solid #2271b1; margin-bottom: 2em; }
  </style>
</head>
<body>
  <header>
    <a href="https://www.clivio.biz/AiSong" title="AiSong - CliAi Script" target="_blank"><img src="CliAibanner.webp" alt="CliAi Logo"></a>
    <h1><?= $text['title'] ?></h1>
	<h2><a href="https://www.clivio.biz/AiSong" target="_blank">AiSong</a></h2>
  </header>
  <main>
    <div class="msg"><?= $zip_message ?></div>
    <h2>1. <?= $text['step1'] ?></h2>
    <form method="post">
      <label><?= $text['domain'] ?></label>
      <input type="text" name="domain" value="https://yoursite.com">
      <label><?= $text['site_title'] ?></label>
      <input type="text" name="title" placeholder="Es: My Music Site">
      <label><?= $text['description'] ?></label>
      <textarea name="description"></textarea>
      <label><?= $text['policy'] ?></label>
      <textarea name="policy">Questo sito utilizza solo cookie tecnici essenziali...</textarea>
      <label><?= $text['key'] ?></label>
      <input type="text" name="key">
      <label><?= $text['token'] ?></label>
      <input type="text" name="token">
      <label><?= $text['referrer'] ?></label>
      <input type="text" name="referrer" value="https://yoursite.com/">
      <button type="submit"><?= $text['save_continue'] ?></button>
    </form>
  </main>
</body>
</html>
