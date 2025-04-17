# ArchiveCMS

![GitHub issues](https://img.shields.io/github/issues/Vir0z4/ArchiveCMS)
![GitHub](https://img.shields.io/github/license/Vir0z4/ArchiveCMS)

*ArchiveCMS* is a Content Management System (CMS) specifically created for the VAIO Library Archive. It it used for creating an archive of Sony VAIO recovery discs and drivers, in seperate pages for each model.

A live version of the CMS is available [here](https://archive.vaiolibrary.com/).

## How it works

Pages are created in the administration panel at ``(URL)/admin``, and are stored in SQL. For user management, only ``admin`` user is allowed to create users, and other users are only able to delete themselves. Passwords are hashed (BCRYPT) and stored in SQL.

ArchiveCMS was specifically made for the VAIO Library, therefore it needs to be adapted to suit your needs. The project is licensed under AGPL-3.0, and is provided "as is" with no warranty of any kind.

### Search system

Since version v1.2, the search system has a certain level of "tolerance" for user inputs, such as:

- If the user inputs "PCG-FX590" and there is a "PCG-FX" page, the user is redirected to the "PCG-FX" page.
- If the user inputs "PCG-Z" and there is a "PCG-Z" and a "PCG-Z505" page, it does not redirect to either of those pages and shows search results normally.
- If the user inputs "VPCZ" and there is a "VPCZ1" and a "VPCZ2" page, it does not redirect to either of those pages and shows search results normally.

## Configuration

Requirements: PHP, MariaDB, webserver (e.g. Apache, NGINX)

### Setting up webserver

Ensure ``pdo_mysql`` extension is enabled in PHP configuration file ``php.ini``.

The root folder of the CMS is ``/public``.

For e.g., **Apache** users, please set ``DocumentRoot`` to ``(root)/public``. For **any other users**, do the same for your webserver.

### SQL

ArchiveCMS does not create the database and tables automatically.

First, create a SQL user. Then create a database with the name of your choice. To create the pages and users tables, run the following commands:

**Users:**

    CREATE TABLE `users` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `username` VARCHAR(255) NOT NULL UNIQUE,
      `password_hash` VARCHAR(255) NOT NULL
    );

**Pages:**

    CREATE TABLE `pages` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `slug` VARCHAR(255) NOT NULL UNIQUE,
      `title` VARCHAR(255) NOT NULL,
      `description` TEXT NOT NULL,
      `status` ENUM('Active','Inactive') NOT NULL DEFAULT 'Inactive',
      `last_modified` DATETIME NOT NULL,
      `recovery_discs` TEXT DEFAULT NULL,
      `driver_packs` TEXT DEFAULT NULL,
      `drivers` TEXT DEFAULT NULL,
      `broken_links` TEXT DEFAULT NULL,
      `box_settings` JSON NOT NULL DEFAULT '{}'
    );

Adding admin user requires a BCRYPT hashed password. After creating a hash for the admin password, use this command to add the admin user:

    INSERT INTO `users` (username, password_hash)
    VALUES (
      'admin',
      '$2y...' -- Change to your password hash
    );

Admin user is not deletable on the user management interface of the CMS.

### config.php

After setting up the SQL user and database, the SQL credentials in ``config.php`` need to be changed, as well as domain information and login session lifetime (time after which session expires). Here is an example snippet of ``config.php`` filled in:

    $sessionLifetime = 2592000;                // 30 days in seconds
    $sessionCookiePath = '/';                  // Usually '/'
    $sessionCookieDomain = 'example.com';      // e.g. 'example.com'
    $sessionCookieSecure = true;               // true if using HTTPS
    $sessionCookieHttpOnly = true;
    $sessionCookieSameSite = 'Strict';         // could be 'Strict', 'Lax', 'None' etc.
    $sessionName = 'VAIO_SESSION';
    
    $dbHost = 'localhost';
    $dbName = 'archivecms';
    $dbUser = 'archiveuser';
    $dbPass = '(a secure password)';

### .htaccess

For the CMS to work, URL rewriting needs to be done. Place this file in ``/public``. Here is how to do it in **Apache**:

    RewriteEngine On
    # Rewrite all requests to index.php unless
    # the requested file or directory actually exists
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^.*$ index.php [L,QSA]