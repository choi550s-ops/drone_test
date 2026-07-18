<?php
/**
 * 드론 1종 학과시험 문제은행 - 설정 파일
 * cafe24 호스팅 환경 (자동 생성됨)
 *
 * ⚠️ 보안: 이 파일의 DB 비밀번호를 노출하지 마세요!
 */

// ========== 데이터베이스 설정 ==========
define('DB_HOST', 'localhost');
define('DB_USER', 'staff4');
define('DB_PASS', 'wlstjr8549!@');
define('DB_NAME', 'staff4');
define('DB_PREFIX', 'test1_');

// ========== 애플리케이션 설정 ==========
define('APP_NAME', '드론 1종 학과시험 문제은행');
define('APP_VERSION', '2.0.0');
define('SESSION_TIMEOUT', 86400 * 7); // 7일
define('PASSING_SCORE', 70); // 합격선

// ========== 경로 설정 ==========
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
define('BASE_URL', $protocol . 'staff4.cafe24.com/dronetest/');
define('API_URL', BASE_URL . 'api.php');

// ========== 오류 로깅 ==========
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');

// 로그 디렉토리 생성
if (!is_dir(__DIR__ . '/logs')) {
  mkdir(__DIR__ . '/logs', 0755, true);
}

// ========== 타임존 설정 ==========
date_default_timezone_set('Asia/Seoul');

// ========== 공통 함수 ==========

/**
 * JSON 응답 생성
 */
function json_response($data, $code = 200) {
  http_response_code($code);
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  exit;
}

/**
 * 에러 응답
 */
function error_response($message, $code = 400) {
  json_response(['error' => $message], $code);
}

/**
 * 성공 응답
 */
function success_response($data = null, $message = 'Success') {
  $response = ['success' => true, 'message' => $message];
  if ($data) {
    $response['data'] = $data;
  }
  json_response($response, 200);
}

/**
 * 인증 토큰 생성
 */
function generate_token($username) {
  return bin2hex(random_bytes(32)) . '-' . time();
}

/**
 * 입력값 검증
 */
function validate_username($username) {
  if (empty($username)) {
    return false;
  }
  // 영문, 숫자, 한글만 허용
  return preg_match('/^[a-zA-Z0-9가-힣_-]{2,20}$/', username);
}

?>
