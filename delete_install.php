<?php
if (file_exists("install.php")) {
	file_exists("install.php") && copy("install.php", "install_backup.php");
	file_exists("install_step2.php") && copy("install_step2.php", "install_step2_backup.php");
	file_exists("delete_install.php") && copy("delete_install.php", "delete_install_backup.php");
    unlink("install.php");
	unlink("install_step2.php");
	unlink("delete_install.php");
    echo "<p>install.php eliminato correttamente.</p><a href='index.php'>Vai all'app</a>";
} else {
    echo "<p>install.php non trovato o già eliminato.</p>";
}
?>
