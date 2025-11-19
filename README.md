# Project Setup Guide

This project is a PHP web application that uses **Google and Facebook OAuth** for login and requires a MySQL database.

---

## 1. Install XAMPP

1. Download and install [XAMPP](https://www.apachefriends.org/index.html).
2. Start **Apache** and **MySQL** from the XAMPP control panel.
3. Open **phpMyAdmin** by navigating to [http://localhost/phpmyadmin](http://localhost/phpmyadmin) and create a new database.
   - Import the database schema by pasting the contents of `database.sql` into the SQL query window.

---

## 2. Install Composer and Required Packages

1. Download and install [Composer](https://getcomposer.org/download/).
2. Open terminal/PowerShell in the project root and run the following commands to install all required libraries:

```bash
composer install
composer require google/apiclient
composer require facebook/graph-sdk
composer require vlucas/phpdotenv
```

## 3. Setup Google and Facebook OAuth
1. Create your own Google and Facebook Client ID and Client Secret.
2. In the project root, create a .env file with the following content:
 ```env
 GOOGLE_CLIENT_ID=your-google-client-id
 GOOGLE_CLIENT_SECRET=your-google-client-secret
 FACEBOOK_CLIENT_ID=your-facebook-client-id
 FACEBOOK_CLIENT_SECRET=your-facebook-client-secret
 ```
 ### Important: Do not include quotes ' or " around the values.

## 4. Run the Project
 Open your browser and navigate to:
 http://localhost/<your-project-directory>/public
