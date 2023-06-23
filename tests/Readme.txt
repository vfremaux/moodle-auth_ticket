To operate test_client.php :

Define a customized webservice fed with auth_ticket_get_ticket and auth_ticket_validate_ticket
Give use to admin account to this service
Create a token for admin on this service.
Create a config.php in this directory with : 

<?php
$baseurl = '<yourwwwroot>';
$wstoken = '<thetoken>';

then run : "php test_client.php" or "php test_client.php <testid>"