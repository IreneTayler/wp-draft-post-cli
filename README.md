# WordPress Draft Post Creator

CLI PHP script that creates a draft post on a WordPress site via the REST API using Basic Auth and Application Passwords.

## Installation

```bash
composer install
```

## Usage

```bash
php create-post.php <site_url> <username> <application_password>
```

Example:

```bash
php create-post.php https://example.com admin xxxx-xxxx-xxxx-xxxx
```

## Features

* Creates WordPress draft posts via REST API
* Handles connection errors, invalid credentials, and timeouts
* Returns created post ID on success
