<?php
	require('password.php');


	echo password_hash('gogroup', PASSWORD_DEFAULT) . '<br />';
	echo password_hash('whatsUp', PASSWORD_DEFAULT) . '<br />';
	echo password_hash('gosam', PASSWORD_DEFAULT) . '<br />';
?>