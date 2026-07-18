<?php
/**
 * 데이터베이스 연결 클래스
 */

class Database {
  private $conn;
  private $prefix;

  public function __construct() {
    $this->prefix = DB_PREFIX;
    $this->connect();
  }

  /**
   * 데이터베이스 연결
   */
  private function connect() {
    try {
      $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

      if ($this->conn->connect_error) {
        throw new Exception('DB 연결 실패: ' . $this->conn->connect_error);
      }

      $this->conn->set_charset('utf8mb4');
    } catch (Exception $e) {
      error_response($e->getMessage(), 500);
    }
  }

  /**
   * 쿼리 실행
   */
  public function query($sql, $types = '', $params = []) {
    try {
      $stmt = $this->conn->prepare($sql);

      if (!$stmt) {
        throw new Exception('쿼리 준비 실패: ' . $this->conn->error);
      }

      if ($types && $params) {
        $stmt->bind_param($types, ...$params);
      }

      $stmt->execute();
      return $stmt;
    } catch (Exception $e) {
      error_response($e->getMessage(), 500);
    }
  }

  /**
   * 사용자 존재 확인 및 생성
   */
  public function get_or_create_user($username) {
    // 사용자 조회
    $sql = "SELECT id, username, created_at FROM {$this->prefix}users WHERE username = ?";
    $stmt = $this->query($sql, 's', [$username]);
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      return $result->fetch_assoc();
    }

    // 사용자 생성
    $sql = "INSERT INTO {$this->prefix}users (username, created_at) VALUES (?, ?)";
    $now = date('Y-m-d H:i:s');
    $this->query($sql, 'ss', [$username, $now]);

    return [
      'id' => $this->conn->insert_id,
      'username' => $username,
      'created_at' => $now
    ];
  }

  /**
   * 문제 풀이 기록 저장
   */
  public function save_attempt($user_id, $problem_id, $selected, $correct, $time_sec) {
    $sql = "INSERT INTO {$this->prefix}attempts
            (user_id, problem_id, selected_answer, correct_answer, time_sec, created_at)
            VALUES (?, ?, ?, ?, ?, ?)";

    $now = date('Y-m-d H:i:s');
    $this->query($sql, 'iissss', [$user_id, $problem_id, $selected, $correct, $time_sec, $now]);

    return $this->conn->insert_id;
  }

  /**
   * 사용자 풀이 통계
   */
  public function get_user_stats($user_id) {
    $sql = "SELECT
              COUNT(*) as total_attempts,
              SUM(CASE WHEN selected_answer = correct_answer THEN 1 ELSE 0 END) as correct_count
            FROM {$this->prefix}attempts
            WHERE user_id = ?";

    $stmt = $this->query($sql, 'i', [$user_id]);
    $result = $stmt->get_result();

    return $result->fetch_assoc();
  }

  /**
   * 문제별(problem_id별) 풀이 통계 - 각 문제의 최신 시도가 아니라 전체 시도 누적 기준
   * 카테고리 매핑은 data.json 기준으로 호출부(api.php)에서 수행한다.
   */
  public function get_problem_stats($user_id) {
    $sql = "SELECT
              problem_id,
              COUNT(*) as total,
              SUM(CASE WHEN selected_answer = correct_answer THEN 1 ELSE 0 END) as correct
            FROM {$this->prefix}attempts
            WHERE user_id = ?
            GROUP BY problem_id";

    $stmt = $this->query($sql, 'i', [$user_id]);
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  /**
   * 약점 문제(가장 최근 시도가 오답인 문제) ID 목록
   */
  public function get_weak_problem_ids($user_id) {
    $sql = "SELECT a.problem_id
            FROM {$this->prefix}attempts a
            INNER JOIN (
              SELECT problem_id, MAX(id) as max_id
              FROM {$this->prefix}attempts
              WHERE user_id = ?
              GROUP BY problem_id
            ) latest ON a.problem_id = latest.problem_id AND a.id = latest.max_id
            WHERE a.user_id = ? AND a.selected_answer <> a.correct_answer";

    $stmt = $this->query($sql, 'ii', [$user_id, $user_id]);
    $result = $stmt->get_result();

    $ids = [];
    while ($row = $result->fetch_assoc()) {
      $ids[] = (int)$row['problem_id'];
    }

    return $ids;
  }

  /**
   * 즐겨찾기 추가
   */
  public function add_bookmark($user_id, $problem_id) {
    $sql = "INSERT IGNORE INTO {$this->prefix}bookmarks (user_id, problem_id) VALUES (?, ?)";
    $this->query($sql, 'ii', [$user_id, $problem_id]);
  }

  /**
   * 즐겨찾기 제거
   */
  public function remove_bookmark($user_id, $problem_id) {
    $sql = "DELETE FROM {$this->prefix}bookmarks WHERE user_id = ? AND problem_id = ?";
    $this->query($sql, 'ii', [$user_id, $problem_id]);
  }

  /**
   * 즐겨찾기 목록 조회
   */
  public function get_bookmarks($user_id) {
    $sql = "SELECT problem_id FROM {$this->prefix}bookmarks WHERE user_id = ?";
    $stmt = $this->query($sql, 'i', [$user_id]);
    $result = $stmt->get_result();

    $bookmarks = [];
    while ($row = $result->fetch_assoc()) {
      $bookmarks[] = $row['problem_id'];
    }

    return $bookmarks;
  }

  /**
   * 세션 저장
   */
  public function save_session($user_id, $token, $expires_at) {
    $sql = "INSERT INTO {$this->prefix}sessions (user_id, token, expires_at)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE expires_at = ?";

    $this->query($sql, 'isss', [$user_id, $token, $expires_at, $expires_at]);
  }

  /**
   * 세션 검증
   */
  public function verify_session($token) {
    $sql = "SELECT u.id, u.username
            FROM {$this->prefix}sessions s
            JOIN {$this->prefix}users u ON s.user_id = u.id
            WHERE s.token = ? AND s.expires_at > NOW()";

    $stmt = $this->query($sql, 's', [$token]);
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      return $result->fetch_assoc();
    }

    return null;
  }

  /**
   * 세션 삭제
   */
  public function delete_session($token) {
    $sql = "DELETE FROM {$this->prefix}sessions WHERE token = ?";
    $this->query($sql, 's', [$token]);
  }

  /**
   * 오류 기록
   */
  public function log_error($user_id, $message) {
    $sql = "INSERT INTO {$this->prefix}error_logs (user_id, message, created_at) VALUES (?, ?, ?)";
    $now = date('Y-m-d H:i:s');
    $this->query($sql, 'iss', [$user_id, $message, $now]);
  }

  /**
   * 연결 종료
   */
  public function close() {
    if ($this->conn) {
      $this->conn->close();
    }
  }
}

// 데이터베이스 인스턴스 생성
$db = new Database();

?>
