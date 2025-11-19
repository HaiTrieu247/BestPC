To run this project
1. Install XAMPP
    1.1. Start Apache and MySQL on XAMPP
    1.2. Go to localhost/phpmyadmin to create new database (paste everything in my database.sql, into query)
2. Install composer
3. To successfully use the Google/Facebook OAuth:
    3.1. RUn the following commands
        composer require google/apiclient
        composer require facebook/graph-sdk
        composer require vlucas/phpdotenv
    3.2 Create your own Google/Facebook client id/secret
    3.3 Create a .env file with these contents
        GOOGLE_CLIENT_ID=Your-google-client-id
        GOOGLE_CLIENT_SECRET=Your-google-client-secret
        FACEBOOK_CLIENT_ID=Your-facebook-client-id
        FACEBOOK_CLIENT_SECRET=Your-facebook-client-secret
        **Note: only paste the code no (' Or " needed)
4.  Start browser, localhost/<your project directory>/public
