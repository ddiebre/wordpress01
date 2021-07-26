<?php
// check user capabilities
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}
echo "<div id='gutentor-admin-settings'></div>";
