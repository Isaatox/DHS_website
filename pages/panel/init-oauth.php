<?php

$discord_url = "https://discord.com/api/oauth2/authorize?client_id=1161011120925057065&redirect_uri=https%3A%2F%2Fjoin-hls.us%2Fpages%2Fpanel%2Fprocess-oauth.php&response_type=code&scope=guilds%20identify%20guilds.join%20guilds.members.read";
header("Location: $discord_url");
exit();

?>