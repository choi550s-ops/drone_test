<?php
/**
 * 데이터베이스 초기화 스크립트
 * cafe24에 업로드 후 브라우저에서 한 번만 실행하면 된다.
 * 실행 후 이 파일은 삭제해도 된다.
 */

require_once 'config.php';

// 데이터베이스 연결 (테이블 생성용)
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
  die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

// 데이터베이스 선택 또는 생성
$dbname = DB_NAME;
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
$conn->select_db($dbname);
$conn->set_charset('utf8mb4');

$prefix = DB_PREFIX;

// ========== 테이블 생성 ==========

$sql_users = "CREATE TABLE IF NOT EXISTS {$prefix}users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_sessions = "CREATE TABLE IF NOT EXISTS {$prefix}sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token VARCHAR(191) UNIQUE NOT NULL,
  expires_at TIMESTAMP NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES {$prefix}users(id) ON DELETE CASCADE,
  INDEX idx_token (token),
  INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_attempts = "CREATE TABLE IF NOT EXISTS {$prefix}attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  problem_id INT NOT NULL,
  selected_answer VARCHAR(5) NOT NULL,
  correct_answer VARCHAR(5) NOT NULL,
  time_sec INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES {$prefix}users(id) ON DELETE CASCADE,
  INDEX idx_user (user_id),
  INDEX idx_problem (problem_id),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_bookmarks = "CREATE TABLE IF NOT EXISTS {$prefix}bookmarks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  problem_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_bookmark (user_id, problem_id),
  FOREIGN KEY (user_id) REFERENCES {$prefix}users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_error_logs = "CREATE TABLE IF NOT EXISTS {$prefix}error_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES {$prefix}users(id) ON DELETE SET NULL,
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// 테이블 생성 실행
$queries = [
  'users' => $sql_users,
  'sessions' => $sql_sessions,
  'attempts' => $sql_attempts,
  'bookmarks' => $sql_bookmarks,
  'error_logs' => $sql_error_logs
];

echo '<html><head><meta charset="utf-8"><style>
  body { font-family: Arial; margin: 20px; background: #f5f5f5; }
  .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
  h1 { color: #333; }
  .success { color: #4caf50; margin: 10px 0; }
  .error { color: #f44336; margin: 10px 0; }
  .info { color: #2196f3; margin: 10px 0; background: #e3f2fd; padding: 10px; border-radius: 5px; }
  .code { background: #f5f5f5; padding: 10px; border-radius: 5px; font-family: monospace; margin: 10px 0; }
  button { background: #667eea; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 20px; }
  button:hover { background: #764ba2; }
</style></head><body><div class="container">';

echo '<h1>🚁 드론 시험 문제은행 - DB 초기화</h1>';

$success_count = 0;
$error_count = 0;

foreach ($queries as $table => $sql) {
  if ($conn->query($sql)) {
    echo '<p class="success">✓ 테이블 ' . $prefix . $table . ' 생성 완료</p>';
    $success_count++;
  } else {
    echo '<p class="error">✗ 테이블 ' . $prefix . $table . ' 생성 실패: ' . $conn->error . '</p>';
    $error_count++;
  }
}

echo '<hr>';

if ($error_count === 0) {
  echo '<p class="success"><strong>✓ 모든 테이블이 성공적으로 생성되었습니다!</strong></p>';
  echo '<div class="info">
    <strong>다음 단계:</strong><br>
    1. 이 파일(setup-db.php)을 삭제합니다<br>
    2. index.php에 접속하여 사용을 시작합니다<br>
    3. 이름을 입력하고 로그인하면 됩니다
  </div>';
} else {
  echo '<p class="error"><strong>✗ 일부 테이블 생성에 실패했습니다</strong></p>';
  echo '<div class="info">
    <strong>해결 방법:</strong><br>
    - DB 사용자 권한 확인<br>
    - DB 연결 정보 확인 (config.php)<br>
    - cafe24 고객센터에 문의
  </div>';
}

echo '<p><strong>생성된 테이블:</strong></p>';
echo '<div class="code">';
echo '- ' . $prefix . 'users (사용자)<br>';
echo '- ' . $prefix . 'sessions (세션)<br>';
echo '- ' . $prefix . 'attempts (풀이 기록)<br>';
echo '- ' . $prefix . 'bookmarks (즐겨찾기)<br>';
echo '- ' . $prefix . 'error_logs (에러 로그)';
echo '</div>';

echo '<p style="color: #999; font-size: 12px; margin-top: 30px;">테이블 접두사: <strong>' . $prefix . '</strong></p>';

echo '<button onclick="window.location.href=\'index.php\'">사용 시작하기 →</button>';

echo '</div></body></html>';

$conn->close();

?>
