# dig-deeper-cms

# Deployment and build

    npm install
    cd src && composer install
    cp ../cfg/settings-example.php ../cfg/settings.php

# Running the webserver locally
    Assuming you're still in the src directory:
    php -S localhost:8888 -t public public/index.php
