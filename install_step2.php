<?php
$admin=1;
$lang = $_GET['lang'] ?? 'it';
$text = [
  'it' => [
    'mp3_note' => 'Copia i tuoi MP3 nella cartella <code>/song</code> per continuare.',
    'confirm_mp3' => 'Confermo di aver copiato gli MP3',
	'confirm_mp3info' => 'Dopo aver confermato attendere la generazione delle cover... Potrebbe richiedere alcuni minuti, non interrompere il processo.',
    'generating_list' => 'Generazione lista MP3 in corso...',
    'wait_for_mp3' => 'Nessun MP3 trovato. Inseriscili nella cartella <code>/song</code> per completare.',
    'generating_covers' => 'Generazione cover per gli MP3 in corso...',
    'success' => 'Installazione completata con successo!',
    'important_note' => 'Importante: elimina i file <code>install</code> dal tuo server.',
    'delete_install' => 'Elimina file installazione',
    'start' => 'Avvia CliSong'
  ],
  'en' => [
    'mp3_note' => 'Copy your MP3 files to the <code>/song</code> folder to continue.',
    'confirm_mp3' => 'I confirm I’ve copied the MP3 files',
	'confirm_mp3info' => 'After confirming, wait for the covers to be generated... It may take a few minutes, do not interrupt the process.',
    'generating_list' => 'Generating MP3 list...',
	'wait_for_mp3' => 'No MP3 found. Please add them to the <code>/song</code> folder to complete.',
    'generating_covers' => 'Generating covers for MP3 files...',
    'success' => 'Installation completed successfully!',
    'important_note' => 'Important: delete <code>install</code> from your server.',
    'delete_install' => 'Delete install file',
    'start' => 'Start CliSong'
  ]
][$lang];

echo <<<HTML
<!DOCTYPE html>
<html lang="$lang">
<head>
  <meta charset="UTF-8">
  <title>CliAi Install Step 2</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Playfair Display', serif; background: #f9f9f9; padding: 0; margin: 0; }
    header { display: flex; align-items: center; justify-content: space-between; background: #fff; padding: 1em 2em; border-bottom: 1px solid #ddd; }
    header img { height: 60px; }
    header h1 { margin: 0 0 0 1em; font-size: 1.8em; }
    main { max-width: 800px; margin: 2em auto; background: #fff; padding: 2em; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
    label { display: block; margin-top: 1em; font-weight: bold; }
    input, textarea { width: 100%; padding: 0.5em; margin-top: 0.3em; border: 1px solid #ccc; border-radius: 5px; }
    .msg { background: #f0f0f0; padding: 1em; border-left: 5px solid #2271b1; margin-bottom: 2em; }
    button { padding: 0.7em 2em; margin-top: 1em; background: #2271b1; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
    .note { background: #ffe; border-left: 4px solid #fc0; padding: 1em; margin-top: 2em; }
	button:disabled {
  background-color: #ccc;
  color: #666;
  cursor: not-allowed;
  border: 1px solid #999;
}
  </style>
</head>
<body>
<header>
  <a href="https://www.clivio.biz/AiSong" title="AiSong - CliAi Script" target="_blank"><img src="CliAibanner.webp" alt="CliAi Logo"></a>
  <h1>Step 2</h1>
	<h2><a href="https://www.clivio.biz/AiSong" target="_blank">AiSong</a></h2>
  
</header>
<main>
HTML;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mp3s = glob(__DIR__ . "/song/*.mp3");
  if (count($mp3s) === 0) {
    echo "<p style='color:red'>{$text['wait_for_mp3']}</p>";
  } else {
    echo "<p>{$text['generating_list']}</p>";
    include_once("generajson.php");
    echo "<p>{$text['generating_covers']}</p>";
    include_once("genera_covers.php");

// crea_htaccess.php

// Configura percorso
$adminDir = __DIR__ . '/admin';
$htaccessFile = $adminDir . '/.htaccess';
$htpasswdFile = $adminDir . '/.htpasswd';

// Crea username e password casuali
$username = 'CliAi_' . bin2hex(random_bytes(4));
$passwordPlain = bin2hex(random_bytes(6));

// Crittografa la password (Apache standard)
$passwordHash = password_hash($passwordPlain, PASSWORD_BCRYPT);
// Salva .htpasswd
file_put_contents($htpasswdFile, "$username:$passwordHash");

// Salva .htaccess
$htaccessContent = <<<HTA
AuthType Basic
AuthName "Restricted Area"
AuthUserFile $htpasswdFile
Require valid-user
HTA;

file_put_contents($htaccessFile, $htaccessContent);

// Mostra all'utente
echo "<br><h2><strong>You personal area Admin Login and Password (<font color=red>save it</font>):</strong></h2>";
echo "Username: <code>$username</code><br>";
echo "Password: <code>$passwordPlain</code><br>";



    echo <<<HTML
<div class="note">
  <h2>✅ {$text['success']}</h2>
  <p>{$text['important_note']}</p>
  <form method="post" action="delete_install.php" onsubmit="return confirm('Delete install file?');">
    <button>{$text['delete_install']}</button>
  </form>
  <a href="index.php"><button>{$text['start']}</button></a>   <a href="admin/" target="_blank"><button>Admin Area</button></a>
</div>
HTML;
  }
} else {
  echo <<<HTML
<h2>3. MP3</h2>
<p>{$text['mp3_note']}</p>
<form method="post">
<button id="confirmBtn" type="submit" name="confirm_mp3" value="1" onclick="this.innerText = 'Attendere...';this.disabled = true;submit();">{$text['confirm_mp3']}</button>
  <br><br>ℹ️ {$text['confirm_mp3info']}
</form>
HTML;
}

echo "</main></body></html>";
