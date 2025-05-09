CREATE TABLE refresh_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash VARCHAR(255) NOT NULL, -- Store hash of refresh token for security
    session_id VARCHAR(50) NOT NULL, -- Unique session identifier
    expires_at DATETIME NOT NULL, -- Token expiration time
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_session_id (session_id)
);