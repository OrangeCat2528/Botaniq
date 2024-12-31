<?php
// helper/auth_helper.php
require_once 'connection.php';
require_once 'encryption.php';
require_once 'session_manager.php';

class AuthHelper {
    private static $instance = null;
    private $connection;
    private $pdo;
    private $sessionManager;
    private const BCRYPT_COST = 12;
    
    private function __construct($mysqli, $pdo) {
        $this->connection = $mysqli;
        $this->pdo = $pdo;
        $this->sessionManager = SessionManager::getInstance();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            global $connection, $pdo;
            self::$instance = new self($connection, $pdo);
        }
        return self::$instance;
    }
    
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => self::BCRYPT_COST]);
    }

    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
    
    public function isLogged() {
        // Check token first
        if (isset($_COOKIE['botaniq_token'])) {
            try {
                $encryptedToken = $_COOKIE['botaniq_token'];
                $rawToken = decryptToken($encryptedToken);
                
                $stmt = $this->pdo->prepare("
                    SELECT ut.*, u.* 
                    FROM user_tokens ut 
                    JOIN users u ON ut.user_id = u.id 
                    WHERE ut.token = ? AND ut.expires_at > NOW()
                    LIMIT 1
                ");
                $stmt->execute([$rawToken]);
                
                if ($user = $stmt->fetch()) {
                    $_SESSION['login'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'linked_id' => $user['linked_id']
                    ];
                    
                    // Refresh token
                    $newExpiry = date('Y-m-d H:i:s', time() + 31536000);
                    $this->pdo->prepare("
                        UPDATE user_tokens 
                        SET expires_at = ? 
                        WHERE token = ?
                    ")->execute([$newExpiry, $rawToken]);
                    
                    // Refresh cookie
                    setcookie(
                        'botaniq_token',
                        $encryptedToken,
                        [
                            'expires' => time() + 31536000,
                            'path' => '/',
                            'secure' => true,
                            'httponly' => true,
                            'samesite' => 'Lax'
                        ]
                    );
                    
                    return true;
                }
            } catch (Exception $e) {
                error_log("Auth Error: " . $e->getMessage());
            }
        }
        
        // Fallback to session
        return isset($_SESSION['login']) && !empty($_SESSION['login']['id']);
    }
    
    public function getCurrentUser() {
        if (!$this->isLogged()) {
            return null;
        }

        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM users 
                WHERE id = ? 
                LIMIT 1
            ");
            $stmt->execute([$_SESSION['login']['id']]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("GetCurrentUser Error: " . $e->getMessage());
            return null;
        }
    }
    
    public function login($username, $password) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if (!$user || !$this->verifyPassword($password, $user['password'])) {
                throw new Exception("Invalid username or password");
            }
            
            $linked_id = is_null($user['linked_id']) ? 0 : $user['linked_id'];
            
            $_SESSION['login'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'linked_id' => $linked_id
            ];

            // Generate and save token
            $rawToken = bin2hex(random_bytes(32));
            $encryptedToken = encryptToken($rawToken);
            $device_info = $_SERVER['HTTP_USER_AGENT'];
            $expires_at = date('Y-m-d H:i:s', time() + 31536000);

            // Remove old tokens for this device
            $deleteStmt = $this->pdo->prepare("
                DELETE FROM user_tokens 
                WHERE user_id = ? AND device_info = ?
            ");
            $deleteStmt->execute([$user['id'], $device_info]);

            // Save new token
            $tokenStmt = $this->pdo->prepare("
                INSERT INTO user_tokens (user_id, token, device_info, expires_at) 
                VALUES (?, ?, ?, ?)
            ");
            $tokenStmt->execute([$user['id'], $rawToken, $device_info, $expires_at]);

            // Set token cookie
            setcookie(
                'botaniq_token',
                $encryptedToken,
                [
                    'expires' => time() + 31536000,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]
            );

            return $user;
        } catch (Exception $e) {
            error_log("Login Error: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function logout() {
        if (isset($_COOKIE['botaniq_token'])) {
            try {
                $encryptedToken = $_COOKIE['botaniq_token'];
                $rawToken = decryptToken($encryptedToken);
                
                $stmt = $this->pdo->prepare("DELETE FROM user_tokens WHERE token = ?");
                $stmt->execute([$rawToken]);
                
                setcookie('botaniq_token', '', time() - 3600, '/');
            } catch (Exception $e) {
                error_log("Logout Error: " . $e->getMessage());
            }
        }
        
        SessionManager::destroySession();
    }

    public function cleanExpiredTokens() {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM user_tokens WHERE expires_at < NOW()");
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Clean Tokens Error: " . $e->getMessage());
        }
    }

    public function refreshTokenIfNeeded() {
        if (isset($_COOKIE['botaniq_token'])) {
            try {
                $encryptedToken = $_COOKIE['botaniq_token'];
                $rawToken = decryptToken($encryptedToken);
                
                $stmt = $this->pdo->prepare("
                    SELECT * FROM user_tokens 
                    WHERE token = ? AND expires_at > NOW()
                    LIMIT 1
                ");
                $stmt->execute([$rawToken]);
                
                if ($token = $stmt->fetch()) {
                    $newExpiry = date('Y-m-d H:i:s', time() + 31536000);
                    $this->pdo->prepare("
                        UPDATE user_tokens 
                        SET expires_at = ? 
                        WHERE token = ?
                    ")->execute([$newExpiry, $rawToken]);
                    
                    setcookie(
                        'botaniq_token',
                        $encryptedToken,
                        [
                            'expires' => time() + 31536000,
                            'path' => '/',
                            'secure' => true,
                            'httponly' => true,
                            'samesite' => 'Lax'
                        ]
                    );
                }
            } catch (Exception $e) {
                error_log("Token Refresh Error: " . $e->getMessage());
            }
        }
    }
}