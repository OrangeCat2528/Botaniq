<?php
$config = array(
    'site_name' => 'Botaniq',
    'site_url' => 'https://your-domain.com',
    'smtp' => false,
    'cookie_name' => 'remember_token',
    'cookie_expiry' => 2592000, // 30 days
    'cookie_path' => '/',
    'cookie_domain' => null,
    'cookie_secure' => true,
    'cookie_http' => true,
    'table_attempts' => 'phpauth_attempts',
    'table_requests' => 'phpauth_requests',
    'table_sessions' => 'phpauth_sessions',
    'table_users' => 'users',
    'table_emails_banned' => 'phpauth_emails_banned',
    'bcrypt_cost' => 10,
);