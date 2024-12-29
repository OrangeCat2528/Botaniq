<?php
require_once 'connection.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

class AuthHelper {
    private static $instance = null;
    private $auth;
    private $config;
    private $connection;
    
    private function __construct($mysqli, $pdo) {
        $this->connection = $mysqli;
        $this->config = new PHPAuthConfig($pdo);
        $this->auth = new PHPAuth($pdo, $this->config);
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            global $connection, $pdo;
            self::$instance = new self($connection, $pdo);
        }
        return self::$instance;
    }
    
    private function getUserLinkedId($username) {
        $stmt = $this->connection->prepare("SELECT linked_id FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['linked_id'];
        }
        return null;
    }
    
    public function login($username, $password, $remember = true) {
        $result = $this->auth->login($username, $password, $remember);
        if ($result['error']) {
            throw new Exception($result['message']);
        }
        
        // Get linked_id from users table
        $linked_id = $this->getUserLinkedId($username);
        
        if ($remember) {
            // Set auth cookie
            setcookie(
                'botaniq_session',
                $result['hash'],
                time() + (86400 * 365),
                '/',
                '',
                true,
                true
            );
            
            // Set linked_id cookie
            setcookie(
                'botaniq_linked_id',
                $linked_id,
                time() + (86400 * 365),
                '/',
                '',
                true,
                false // Allow JavaScript access for API validation
            );
        }
        
        return array_merge($result, ['linked_id' => $linked_id]);
    }
    
    public function isLogged() {
        if (isset($_COOKIE['botaniq_session'])) {
            return $this->auth->isLogged();
        }
        return false;
    }
    
    public function getCurrentUser() {
        if ($this->isLogged()) {
            $uid = $this->auth->getCurrentUID();
            $authUser = $this->auth->getUser($uid);
            
            // Get additional user data from your users table
            $stmt = $this->connection->prepare("SELECT linked_id FROM users WHERE username = ?");
            $stmt->bind_param("s", $authUser['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            $userData = $result->fetch_assoc();
            
            return array_merge($authUser, ['linked_id' => $userData['linked_id']]);
        }
        return null;
    }
    
    public function logout() {
        $sessionHash = $_COOKIE['botaniq_session'] ?? null;
        
        if ($sessionHash) {
            // Hapus cookie
            setcookie('botaniq_session', '', time() - 3600, '/');
            setcookie('botaniq_linked_id', '', time() - 3600, '/');
            
            // Logout dari PHPAuth dengan session hash
            return $this->auth->logout($sessionHash);
        }
        
        return true; // Return true jika memang tidak ada session
    }
}