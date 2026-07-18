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
   * 범주별 통계
   */
  public function get_category_stats($user_id) {
    $sql = "SELECT
              '항공법규' as category,
              COUNT(*) as total,
              SUM(CASE WHEN selected_answer = correct_answer THEN 1 ELSE 0 END) as correct
            FROM {$this->prefix}attempts a
            WHERE a.user_id = ? AND a.problem_id IN (1,2,3,4,5,6,7,8,9,10,81,82,90)

            UNION ALL

            SELECT
              '기체학' as category,
              COUNT(*) as total,
              SUM(CASE WHEN selected_answer = correct_answer THEN 1 ELSE 0 END) as correct
            FROM {$this->prefix}attempts a
            WHERE a.user_id = ? AND a.problem_id IN (11,12,13,14,15,16,17,18,19,20,51,84,89)

            UNION ALL

            SELECT
              '비행원리' as category,
              COUNT(*) as total,
              SUM(CASE WHEN selected_answer = correct_answer THEN 1 ELSE 0 END) as correct
            FROM {$this->prefix}attempts a
            WHERE a.user_id = ? AND a.problem_id IN (21,22,23,24,25,26,27,28,83)

            UNION ALL

            SELECT
              '안전관리' as category,
              COUNT(*) as total,
              SUM(CASE WHEN selected_answer = correct_answer THEN 1 ELSE 0 END) as correct
            FROM {$this->prefix}attempts a
            WHERE a.user_id = ? AND a.problem_id IN (29,30,31,32,33,34,35,36,37,38,88)

            UNION ALL

            SELECT
              '기체정비' as category,
              COUNT(*) as total,
              SUM(CASE WHEN selected_answer = correct_answer THEN 1 ELSE 0 END) as correct
            FROM {$this->prefix}attempts a
            WHERE a.user_id = ? AND a.problem_id IN (39,40,41,42,43,44,45,51,88,89)

            UNION ALL

            SELECT
              '전자/통신' as category,
              COUNT(*) as total,
              SUM(CASE WHEN selected_answer = correct_answer THEN 1 ELSE 0 END) as correct
            FROM {$this->prefix}attempts a
            WHERE a.user_id = ? AND a.problem_id IN (46,47,48,49,50,85)

            UNION ALL

            SELECT
              '기상학' as category,
              COUNT(*) as total,
              SUM(CASE WHEN selected_answer = correct_answer THEN 1 ELSE 0 END) as correct
            FROM {$this->prefix}attempts a
            WHERE a.user_id = ? AND a.problem_id IN (52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,86,87)";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param('iiiiiii', $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
