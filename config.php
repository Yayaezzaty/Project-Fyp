<?php
require_once 'vendor/autoload.php';

// Set your secret key. Remember to switch to your live secret key in production!
$stripeSecretKey = 'sk_test_51R8EkVDhRU7D1IEvRP882VarQdVWZ4pv1hdD6IfFpxPxc7i3u1fuNq0MHxlXNuumTjJT6V5p6czzJxtus894jDfE003vOpBz6i';
$stripePublishableKey = 'pk_test_51R8EkVDhRU7D1IEvfyaNQ2Jyrl08A70HadyJC9W8MOzzX6Eb93O6EQfw8l37XeTEwem1vVQWUn1fkafjJ1hcFW1Z00qnLuQRHW';

\Stripe\Stripe::setApiKey($stripeSecretKey);
?>