<?php
/**
 * 드론 1종 학과시험 API 엔드포인트
 */

require_once 'config.php';
require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$action = isset($_GET['action']) ? $_GET['action'] : '';

// JSON 입력 받기
$input = json_decode(file_get_contents('php://input'), true);

// ==================== 인증 API ====================

/**
 * 로그인: POST /api.php?action=login
 * 요청: { "username": "홍길동" }
 */
if ($action === 'login' && $request_method === 'POST') {
  $username = $input['username'] ?? null;

  if (!validate_username($username)) {
    error_response('유효하지 않은 사용자명입니다 (2~20자, 영문/숫자/한글/_/-)', 400);
  }

  // 사용자 생성 또는 조회
  $user = $db->get_or_create_user($username);

  // 세션 토큰 생성
  $token = generate_token($username);
  $expires_at = date('Y-m-d H:i:s', time() + SESSION_TIMEOUT);

  $db->save_session($user['id'], $token, $expires_at);

  success_response([
    'token' => $token,
    'user' => [
      'id' => $user['id'],
      'username' => $user['username']
    ]
  ], "{$username}님 환영합니다!");
}

/**
 * 로그아웃: POST /api.php?action=logout
 */
if ($action === 'logout' && $request_method === 'POST') {
  $token = $_POST['token'] ?? $_GET['token'] ?? null;

  if ($token) {
    $db->delete_session($token);
  }

  success_response(null, '로그아웃되었습니다');
}

/**
 * 세션 확인: GET /api.php?action=verify_session
 */
if ($action === 'verify_session' && $request_method === 'GET') {
  $token = $_GET['token'] ?? null;

  if (!$token) {
    error_response('토큰이 필요합니다', 401);
  }

  $user = $db->verify_session($token);

  if (!$user) {
    error_response('유효하지 않은 세션입니다', 401);
  }

  success_response([
    'user' => $user,
    'token' => $token
  ], 'Session valid');
}

// ==================== 문제 조회 API ====================

// 문제은행 로드
$questions_json = file_get_contents(__DIR__ . '/data.json');
$quiz_data = json_decode($questions_json, true);
$questions = $quiz_data['questions'] ?? [];

/**
 * 카테고리 목록 및 문항 수: GET /api.php?action=get_categories
 */
if ($action === 'get_categories' && $request_method === 'GET') {
  $counts = [];
  foreach ($questions as $q) {
    $cat = $q['category'];
    $counts[$cat] = ($counts[$cat] ?? 0) + 1;
  }

  $categories = $quiz_data['metadata']['categories'] ?? array_keys($counts);
  $result = [];
  foreach ($categories as $cat) {
    $result[] = [
      'category' => $cat,
      'count' => $counts[$cat] ?? 0
    ];
  }

  success_response(['categories' => $result], 'Categories retrieved');
}

/**
 * 모든 문제 조회: GET /api.php?action=get_questions
 */
if ($action === 'get_questions' && $request_method === 'GET') {
  $category = $_GET['category'] ?? null;
  $difficulty = $_GET['difficulty'] ?? null;

  $filtered = $questions;

  if ($category) {
    $filtered = array_filter($filtered, function($q) use ($category) {
      return $q['category'] === $category;
    });
  }

  if ($difficulty) {
    $filtered = array_filter($filtered, function($q) use ($difficulty) {
      return $q['difficulty'] == $difficulty;
    });
  }

  success_response([
    'count' => count($filtered),
    'questions' => array_values($filtered)
  ], 'Questions retrieved');
}

/**
 * 특정 문제 조회: GET /api.php?action=get_question&id=1
 */
if ($action === 'get_question' && $request_method === 'GET') {
  $id = $_GET['id'] ?? null;

  $question = null;
  foreach ($questions as $q) {
    if ($q['id'] == $id) {
      $question = $q;
      break;
    }
  }

  if (!$question) {
    error_response('문제를 찾을 수 없습니다', 404);
  }

  success_response($question, 'Question retrieved');
}

/**
 * 랜덤 문제 출제: GET /api.php?action=random_quiz&count=10&category=항공법규
 */
if ($action === 'random_quiz' && $request_method === 'GET') {
  $count = min($_GET['count'] ?? 10, count($questions));
  $category = $_GET['category'] ?? null;

  $available = $questions;

  if ($category) {
    $available = array_filter($available, function($q) use ($category) {
      return $q['category'] === $category;
    });
  }

  // 무작위 선택
  $available = array_values($available);
  shuffle($available);
  $selected = array_slice($available, 0, $count);

  // 정답 제거 (정답 공개 방지)
  $selected = array_map(function($q) {
    unset($q['correct'], $q['explanation'], $q['keywords']);
    return $q;
  }, $selected);

  success_response([
    'quiz_id' => 'quiz-' . time(),
    'count' => count($selected),
    'questions' => $selected
  ], 'Quiz generated');
}

// ==================== 풀이 기록 API ====================

/**
 * 문제 풀이 제출: POST /api.php?action=submit_answer
 * 요청: { "token": "...", "problem_id": 1, "selected": "A", "time_sec": 30 }
 */
if ($action === 'submit_answer' && $request_method === 'POST') {
  $token = $input['token'] ?? null;
  $problem_id = $input['problem_id'] ?? null;
  $selected = $input['selected'] ?? null;
  $time_sec = $input['time_sec'] ?? 0;

  if (!$token) {
    error_response('인증 토큰이 필요합니다', 401);
  }

  $user = $db->verify_session($token);
  if (!$user) {
    error_response('유효하지 않은 세션입니다', 401);
  }

  // 문제 찾기
  $problem = null;
  foreach ($questions as $q) {
    if ($q['id'] == $problem_id) {
      $problem = $q;
      break;
    }
  }

  if (!$problem) {
    error_response('문제를 찾을 수 없습니다', 404);
  }

  // 풀이 기록 저장
  $db->save_attempt($user['id'], $problem_id, $selected, $problem['correct'], $time_sec);

  $is_correct = $selected === $problem['correct'];

  success_response([
    'is_correct' => $is_correct,
    'correct_answer' => $problem['correct'],
    'explanation' => $problem['explanation'],
    'keywords' => $problem['keywords']
  ], $is_correct ? '정답입니다!' : '오답입니다');
}

// ==================== 통계 API ====================

/**
 * 사용자 통계: GET /api.php?action=stats&token=...
 */
if ($action === 'stats' && $request_method === 'GET') {
  $token = $_GET['token'] ?? null;

  if (!$token) {
    error_response('인증 토큰이 필요합니다', 401);
  }

  $user = $db->verify_session($token);
  if (!$user) {
    error_response('유효하지 않은 세션입니다', 401);
  }

  $stats = $db->get_user_stats($user['id']);
  $category_stats = $db->get_category_stats($user['id']);

  $accuracy = $stats['total_attempts'] > 0
    ? round(($stats['correct_count'] / $stats['total_attempts']) * 100, 1)
    : 0;

  $category_accuracy = [];
  foreach ($category_stats as $cat) {
    if ($cat['total'] > 0) {
      $category_accuracy[$cat['category']] = [
        'total' => $cat['total'],
        'correct' => $cat['correct'],
        'accuracy' => round(($cat['correct'] / $cat['total']) * 100, 1)
      ];
    }
  }

  success_response([
    'overall' => [
      'total_attempts' => $stats['total_attempts'] ?? 0,
      'correct_count' => $stats['correct_count'] ?? 0,
      'accuracy' => $accuracy,
      'passing_line' => PASSING_SCORE . '% 이상'
    ],
    'by_category' => $category_accuracy
  ], 'Stats retrieved');
}

// ==================== 즐겨찾기 API ====================

/**
 * 즐겨찾기 추가/제거: POST /api.php?action=toggle_bookmark
 */
if ($action === 'toggle_bookmark' && $request_method === 'POST') {
  $token = $input['token'] ?? null;
  $problem_id = $input['problem_id'] ?? null;
  $bookmarked = $input['bookmarked'] ?? false;

  if (!$token) {
    error_response('인증 토큰이 필요합니다', 401);
  }

  $user = $db->verify_session($token);
  if (!$user) {
    error_response('유효하지 않은 세션입니다', 401);
  }

  if ($bookmarked) {
    $db->add_bookmark($user['id'], $problem_id);
  } else {
    $db->remove_bookmark($user['id'], $problem_id);
  }

  success_response(null, $bookmarked ? '즐겨찾기 추가됨' : '즐겨찾기 제거됨');
}

/**
 * 즐겨찾기 목록: GET /api.php?action=get_bookmarks&token=...
 */
if ($action === 'get_bookmarks' && $request_method === 'GET') {
  $token = $_GET['token'] ?? null;

  if (!$token) {
    error_response('인증 토큰이 필요합니다', 401);
  }

  $user = $db->verify_session($token);
  if (!$user) {
    error_response('유효하지 않은 세션입니다', 401);
  }

  $bookmarks = $db->get_bookmarks($user['id']);
  $bookmarked_questions = array_filter($questions, function($q) use ($bookmarks) {
    return in_array($q['id'], $bookmarks);
  });

  success_response([
    'count' => count($bookmarked_questions),
    'questions' => array_values($bookmarked_questions)
  ], 'Bookmarks retrieved');
}

// ==================== 기본 응답 ====================

error_response('존재하지 않는 API 엔드포인트입니다', 404);

?>
