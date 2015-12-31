<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mmi_vl_media_list", $Language->MenuPhrase("1", "MenuText"), "vl_media_listlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(2, "mmi_vl_media_type_list", $Language->MenuPhrase("2", "MenuText"), "vl_media_type_listlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
