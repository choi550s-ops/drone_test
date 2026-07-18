<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>드론 1종 학과시험 문제은행</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .container {
      width: 100%;
      max-width: 1200px;
    }

    /* ========== 로그인 화면 ========== */
    .login-screen {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .login-card {
      background: white;
      border-radius: 15px;
      padding: 50px 40px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-card h1 {
      color: #333;
      margin-bottom: 10px;
      font-size: 28px;
    }

    .login-card .subtitle {
      color: #666;
      margin-bottom: 30px;
      font-size: 14px;
    }

    .login-card .logo {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 50%;
      margin: 0 auto 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 40px;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #333;
      font-weight: 500;
    }

    .form-group input {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s;
    }

    .form-group input:focus {
      outline: none;
      border-color: #667eea;
    }

    .login-btn {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .login-btn:active {
      transform: translateY(0);
    }

    .error {
      background: #ffebee;
      color: #c62828;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: none;
    }

    .error.show {
      display: block;
    }

    /* ========== 대시보드 화면 ========== */
    .dashboard {
      display: none;
    }

    .header {
      background: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .user-avatar {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 20px;
    }

    .logout-btn {
      padding: 8px 20px;
      background: #f44336;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .logout-btn:hover {
      background: #d32f2f;
    }

    .menu {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    }

    .menu-item {
      background: white;
      padding: 20px;
      border-radius: 10px;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .menu-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .menu-item .icon {
      font-size: 40px;
      margin-bottom: 10px;
    }

    .menu-item .title {
      font-size: 18px;
      font-weight: bold;
      color: #333;
      margin-bottom: 5px;
    }

    .menu-item .description {
      font-size: 13px;
      color: #666;
    }

    .stats {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .stats h2 {
      margin-bottom: 15px;
      color: #333;
    }

    .stat-item {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid #eee;
    }

    .stat-item:last-child {
      border-bottom: none;
    }

    .stat-label {
      color: #666;
    }

    .stat-value {
      font-weight: bold;
      color: #667eea;
    }

    /* ========== 문제풀이 화면 ========== */
    .quiz-screen {
      display: none;
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .quiz-screen.show {
      display: block;
    }

    .quiz-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #e0e0e0;
    }

    .quiz-header .progress {
      color: #666;
      font-size: 14px;
    }

    .quiz-header .timer {
      font-size: 24px;
      font-weight: bold;
      color: #f44336;
    }

    .quiz-question {
      margin-bottom: 30px;
    }

    .quiz-question .text {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #333;
    }

    .options {
      display: grid;
      gap: 10px;
    }

    .option {
      padding: 15px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.2s;
      background: white;
    }

    .option:hover {
      border-color: #667eea;
      background: #f5f7ff;
    }

    .option input[type="radio"] {
      margin-right: 10px;
    }

    .option.selected {
      border-color: #667eea;
      background: #f5f7ff;
    }

    .option.correct {
      border-color: #4caf50;
      background: #f1f8e9;
    }

    .option.incorrect {
      border-color: #f44336;
      background: #ffebee;
    }

    .explanation {
      background: #fff3e0;
      padding: 15px;
      border-radius: 8px;
      margin-top: 20px;
      display: none;
      border-left: 4px solid #ff9800;
    }

    .explanation.show {
      display: block;
    }

    .explanation h4 {
      color: #e65100;
      margin-bottom: 10px;
    }

    .explanation p {
      color: #666;
      line-height: 1.6;
    }

    .quiz-controls {
      display: flex;
      gap: 10px;
      justify-content: center;
      margin-top: 30px;
    }

    .btn {
      padding: 12px 30px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
      background: #e0e0e0;
      color: #333;
    }

    .btn-secondary:hover {
      background: #d0d0d0;
    }

    .btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    /* ========== 범주별 학습 / 전체문제보기 화면 ========== */
    .category-screen, .browse-screen {
      display: none;
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .category-screen.show, .browse-screen.show {
      display: block;
    }

    .category-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 15px;
      margin-top: 20px;
    }

    .browse-toolbar {
      display: flex;
      gap: 10px;
      align-items: center;
      margin: 20px 0;
      flex-wrap: wrap;
    }

    .browse-toolbar select, .browse-toolbar input {
      padding: 10px 14px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 14px;
    }

    .browse-item {
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      padding: 18px;
      margin-bottom: 14px;
    }

    .browse-item .b-meta {
      display: flex;
      gap: 8px;
      align-items: center;
      margin-bottom: 8px;
      flex-wrap: wrap;
    }

    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: bold;
    }

    .badge-category {
      background: #e3e8ff;
      color: #4353c9;
    }

    .badge-frequent {
      background: #fff3e0;
      color: #e65100;
    }

    .browse-item .b-question {
      font-weight: bold;
      color: #333;
      margin-bottom: 10px;
    }

    .browse-item .b-options {
      list-style: none;
      margin-bottom: 10px;
    }

    .browse-item .b-options li {
      padding: 6px 10px;
      border-radius: 6px;
      color: #555;
    }

    .browse-item .b-options li.b-correct {
      background: #e8f5e9;
      color: #2e7d32;
      font-weight: bold;
    }

    .browse-item .b-explanation {
      background: #fff3e0;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 13px;
      color: #666;
      border-left: 4px solid #ff9800;
    }

    .loading {
      text-align: center;
      padding: 40px;
      color: #666;
    }

    .spinner {
      border: 4px solid #f3f3f3;
      border-top: 4px solid #667eea;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
      margin: 0 auto 20px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
      .login-card {
        padding: 30px 20px;
      }

      .header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }

      .menu {
        grid-template-columns: 1fr;
      }

      .quiz-screen {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- ========== 로그인 화면 ========== -->
    <div class="login-screen" id="loginScreen">
      <div class="login-card">
        <div class="logo">🚁</div>
        <h1>드론 1종 학과시험</h1>
        <p class="subtitle">문제은행 및 학습 시스템</p>

        <div class="error" id="loginError"></div>

        <form onsubmit="handleLogin(event)">
          <div class="form-group">
            <label for="username">이름을 입력하세요</label>
            <input
              type="text"
              id="username"
              placeholder="예: 홍길동"
              autocomplete="off"
              required
            >
          </div>
          <button type="submit" class="login-btn">시작하기</button>
        </form>

        <p style="margin-top: 20px; color: #999; font-size: 12px;">
          💡 별도 회원가입 없이 이름만 입력하면 계속 관리됩니다
        </p>
      </div>
    </div>

    <!-- ========== 대시보드 ========== -->
    <div class="dashboard" id="dashboard">
      <div class="header">
        <div class="user-info">
          <div class="user-avatar" id="userAvatar">🚁</div>
          <div>
            <h2 style="margin: 0; color: #333;">안녕하세요, <span id="userName">사용자</span>님</h2>
            <p style="margin: 5px 0 0 0; color: #999; font-size: 13px;" id="userStats">
              방문 중입니다
            </p>
          </div>
        </div>
        <button class="logout-btn" onclick="handleLogout()">로그아웃</button>
      </div>

      <div class="stats">
        <h2>📊 학습 현황</h2>
        <div class="stat-item">
          <span class="stat-label">풀이 문제</span>
          <span class="stat-value" id="statAttempts">0개</span>
        </div>
        <div class="stat-item">
          <span class="stat-label">정답 수</span>
          <span class="stat-value" id="statCorrect">0개</span>
        </div>
        <div class="stat-item">
          <span class="stat-label">정답률</span>
          <span class="stat-value" id="statAccuracy">--%</span>
        </div>
        <div class="stat-item">
          <span class="stat-label">즐겨찾기</span>
          <span class="stat-value" id="statBookmarks">0개</span>
        </div>
      </div>

      <div class="menu">
        <div class="menu-item" onclick="startQuiz('random', 10)">
          <div class="icon">📝</div>
          <div class="title">모의고사</div>
          <div class="description">10문제 무작위 풀이</div>
        </div>

        <div class="menu-item" onclick="startQuiz('random', 30)">
          <div class="icon">📚</div>
          <div class="title">온전한 시험</div>
          <div class="description">30문제 무작위 풀이</div>
        </div>

        <div class="menu-item" onclick="startQuiz('weak')">
          <div class="icon">🔄</div>
          <div class="title">약점 복습</div>
          <div class="description">틀린 문제만 집중 학습</div>
        </div>

        <div class="menu-item" onclick="startQuiz('bookmarks')">
          <div class="icon">⭐</div>
          <div class="title">즐겨찾기</div>
          <div class="description">저장한 문제 풀이</div>
        </div>

        <div class="menu-item" onclick="showCategoryMenu()">
          <div class="icon">🗂️</div>
          <div class="title">범주별 학습</div>
          <div class="description">카테고리별 집중 학습</div>
        </div>

        <div class="menu-item" onclick="showAllQuestions()">
          <div class="icon">📖</div>
          <div class="title">전체 문제 보기</div>
          <div class="description">모든 문제 검색 및 학습</div>
        </div>
      </div>
    </div>

    <!-- ========== 문제 풀이 화면 ========== -->
    <div class="quiz-screen" id="quizScreen">
      <button class="logout-btn" onclick="goToDashboard()" style="margin-bottom: 20px;">← 대시보드로</button>

      <div class="quiz-header">
        <div>
          <h1 style="margin-bottom: 5px;" id="quizTitle">모의고사</h1>
          <div class="progress">
            <span id="currentQuestion">1</span> / <span id="totalQuestions">10</span>
          </div>
        </div>
        <div class="timer" id="timer">00:00</div>
      </div>

      <div id="loadingQuiz" class="loading">
        <div class="spinner"></div>
        <p>문제 로딩 중...</p>
      </div>

      <div id="quizContent" style="display: none;">
        <div class="quiz-question">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
            <div class="text" id="questionText" style="flex: 1;"></div>
            <button class="btn" id="bookmarkBtn" onclick="toggleBookmark()" style="background: none; font-size: 26px; padding: 0 5px; line-height: 1;" title="즐겨찾기">☆</button>
          </div>
          <div class="options" id="optionsContainer"></div>
          <div class="explanation" id="explanation">
            <h4 id="explanationHeader">💡 해설</h4>
            <p id="explanationText"></p>
            <p style="margin-top: 10px; font-size: 12px; color: #999;" id="keywordsText"></p>
          </div>
        </div>

        <div class="quiz-controls">
          <button class="btn btn-secondary" onclick="previousQuestion()" id="prevBtn">← 이전</button>
          <button class="btn btn-primary" onclick="nextQuestion()" id="nextBtn">다음 →</button>
        </div>
      </div>
    </div>

    <!-- ========== 범주별 학습 화면 ========== -->
    <div class="category-screen" id="categoryScreen">
      <button class="logout-btn" onclick="goToDashboard()" style="margin-bottom: 20px;">← 대시보드로</button>
      <h1>🗂️ 범주별 학습</h1>
      <p style="color: #666; margin-top: 8px;">카테고리를 선택하면 해당 범주의 문제만 모아서 풀 수 있습니다.</p>
      <div class="category-list" id="categoryList">
        <div class="loading"><div class="spinner"></div><p>불러오는 중...</p></div>
      </div>
    </div>

    <!-- ========== 전체 문제 보기 화면 ========== -->
    <div class="browse-screen" id="browseScreen">
      <button class="logout-btn" onclick="goToDashboard()" style="margin-bottom: 20px;">← 대시보드로</button>
      <h1>📖 전체 문제 보기</h1>
      <p style="color: #666; margin-top: 8px;">정답과 해설을 바로 확인하며 학습할 수 있습니다. 엉뚱한 범위를 공부하지 않도록 실제 시험 범위(항공법규·항공기상·항공역학·비행운용이론)에 맞춰 검수된 문제입니다.</p>

      <div class="browse-toolbar">
        <select id="browseCategoryFilter" onchange="renderBrowseList()">
          <option value="">전체 카테고리</option>
        </select>
        <label style="font-size: 14px; color: #555;">
          <input type="checkbox" id="browseFrequentOnly" onchange="renderBrowseList()"> 빈출만 보기
        </label>
        <input type="text" id="browseSearch" placeholder="키워드 검색..." oninput="renderBrowseList()" style="flex: 1; min-width: 180px;">
        <span id="browseCount" style="color: #999; font-size: 13px;"></span>
      </div>

      <div id="browseList">
        <div class="loading"><div class="spinner"></div><p>불러오는 중...</p></div>
      </div>
    </div>
  </div>

  <script>
    const API_URL = '<?php echo API_URL; ?>';
    let currentUser = null;
    let currentToken = null;
    let quizData = null;
    let currentQuestionIndex = 0;
    let answers = {};
    let quizStartTime = null;
    let timerInterval = null;

    // ========== 로그인 ==========
    async function handleLogin(e) {
      e.preventDefault();
      const username = document.getElementById('username').value.trim();
      const errorEl = document.getElementById('loginError');

      if (!username) {
        showError('사용자명을 입력하세요', errorEl);
        return;
      }

      try {
        const response = await fetch(API_URL + '?action=login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username })
        });

        const data = await response.json();

        if (!data.success) {
          showError(data.error || '로그인 실패', errorEl);
          return;
        }

        currentUser = data.data.user;
        currentToken = data.data.token;

        localStorage.setItem('authToken', currentToken);
        localStorage.setItem('userName', currentUser.username);

        showDashboard();
      } catch (error) {
        showError('네트워크 오류: ' + error.message, errorEl);
      }
    }

    function showError(message, errorEl) {
      errorEl.textContent = message;
      errorEl.classList.add('show');
      setTimeout(() => errorEl.classList.remove('show'), 5000);
    }

    // ========== 대시보드 ==========
    function showDashboard() {
      document.getElementById('loginScreen').style.display = 'none';
      document.getElementById('dashboard').style.display = 'block';
      document.getElementById('quizScreen').classList.remove('show');

      document.getElementById('userName').textContent = currentUser.username;
      document.getElementById('userAvatar').textContent = currentUser.username.charAt(0).toUpperCase();

      loadStats();
    }

    async function loadStats() {
      try {
        const response = await fetch(API_URL + '?action=stats&token=' + encodeURIComponent(currentToken));
        const data = await response.json();

        if (data.success) {
          const overall = data.data.overall;
          document.getElementById('statAttempts').textContent = overall.total_attempts + '개';
          document.getElementById('statCorrect').textContent = overall.correct_count + '개';
          document.getElementById('statAccuracy').textContent = overall.accuracy + '%';

          // 즐겨찾기 개수
          const bookmarkResponse = await fetch(API_URL + '?action=get_bookmarks&token=' + encodeURIComponent(currentToken));
          const bookmarkData = await bookmarkResponse.json();
          document.getElementById('statBookmarks').textContent = bookmarkData.data.count + '개';
        }
      } catch (error) {
        console.error('통계 로드 실패:', error);
      }
    }

    // ========== 문제풀이 ==========
    let gradedResults = {};
    let bookmarkIds = new Set();

    async function startQuiz(type, count, category) {
      try {
        let url;
        if (type === 'weak') {
          url = API_URL + '?action=weak_review&token=' + encodeURIComponent(currentToken);
        } else if (type === 'bookmarks') {
          url = API_URL + '?action=bookmark_quiz&token=' + encodeURIComponent(currentToken);
        } else {
          url = API_URL + '?action=random_quiz&count=' + count;
          if (category) {
            url += '&category=' + encodeURIComponent(category);
          }
        }

        const response = await fetch(url);
        const data = await response.json();

        if (!data.success) {
          alert(data.error || '문제 로드 실패');
          return;
        }

        if (data.data.questions.length === 0) {
          if (type === 'weak') {
            alert('아직 틀린 문제가 없습니다. 모의고사를 먼저 풀어보세요!');
          } else if (type === 'bookmarks') {
            alert('즐겨찾기한 문제가 없습니다. 문제 풀이 중 ☆ 버튼으로 추가해보세요!');
          } else {
            alert('해당 조건의 문제가 없습니다.');
          }
          return;
        }

        // 즐겨찾기 상태 로드 (문제 화면에서 별 표시용)
        try {
          const bmRes = await fetch(API_URL + '?action=get_bookmarks&token=' + encodeURIComponent(currentToken));
          const bmData = await bmRes.json();
          if (bmData.success) {
            bookmarkIds = new Set(bmData.data.questions.map(q => q.id));
          }
        } catch (e) { /* 무시: 즐겨찾기 표시만 안 될 뿐 퀴즈는 진행 */ }

        quizData = data.data.questions;
        currentQuestionIndex = 0;
        answers = {};
        gradedResults = {};
        quizStartTime = Date.now();

        document.getElementById('dashboard').style.display = 'none';
        document.getElementById('categoryScreen').classList.remove('show');
        document.getElementById('browseScreen').classList.remove('show');
        document.getElementById('quizScreen').classList.add('show');
        document.getElementById('totalQuestions').textContent = quizData.length;

        document.getElementById('quizTitle').textContent =
          category ? ('🗂️ ' + category) :
          (type === 'random' ? '모의고사' : type === 'weak' ? '⚠️ 약점 복습' : '⭐ 즐겨찾기');

        displayQuestion();
        startTimer();
      } catch (error) {
        alert('오류: ' + error.message);
      }
    }

    function displayQuestion() {
      const question = quizData[currentQuestionIndex];
      document.getElementById('currentQuestion').textContent = currentQuestionIndex + 1;
      document.getElementById('questionText').textContent = question.question;
      document.getElementById('loadingQuiz').style.display = 'none';
      document.getElementById('quizContent').style.display = 'block';

      const optionsContainer = document.getElementById('optionsContainer');
      optionsContainer.innerHTML = '';

      const already = gradedResults[currentQuestionIndex];

      Object.entries(question.options).forEach(([key, value]) => {
        const option = document.createElement('label');
        option.className = 'option';
        option.innerHTML = `
          <input type="radio" name="answer" value="${key}" onchange="selectAnswer('${key}')" ${already ? 'disabled' : ''}>
          <strong>${key}.</strong> ${value}
        `;
        optionsContainer.appendChild(option);
      });

      document.getElementById('explanation').classList.remove('show');

      // 이전에 선택한 답이 있으면 표시
      if (answers[currentQuestionIndex]) {
        const radio = document.querySelector(`input[value="${answers[currentQuestionIndex]}"]`);
        if (radio) radio.checked = true;
      }

      // 이미 채점된 문제면 즉시 해설/정오답 표시 복원
      if (already) {
        showExplanation(already);
      }

      updateBookmarkStar(question.id);
      updateButtonStates();
    }

    function selectAnswer(value) {
      // 이미 채점된 문제는 재선택 불가 (즉시채점 방식)
      if (gradedResults[currentQuestionIndex]) return;

      answers[currentQuestionIndex] = value;

      // 라디오 버튼 즉시 비활성화 후 자동 채점
      document.querySelectorAll('#optionsContainer input[type="radio"]').forEach(r => r.disabled = true);
      submitAnswer();
    }

    async function submitAnswer() {
      const answer = answers[currentQuestionIndex];
      if (!answer) return;

      const question = quizData[currentQuestionIndex];
      const timeTaken = Math.round((Date.now() - quizStartTime) / 1000);

      try {
        const response = await fetch(API_URL + '?action=submit_answer', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            token: currentToken,
            problem_id: question.id,
            selected: answer,
            time_sec: timeTaken
          })
        });

        const data = await response.json();

        if (data.success) {
          gradedResults[currentQuestionIndex] = data.data;
          showExplanation(data.data);
        } else {
          alert(data.error || '채점 실패');
          document.querySelectorAll('#optionsContainer input[type="radio"]').forEach(r => r.disabled = false);
        }
      } catch (error) {
        alert('제출 실패: ' + error.message);
        document.querySelectorAll('#optionsContainer input[type="radio"]').forEach(r => r.disabled = false);
      }
    }

    function showExplanation(result) {
      const explanation = document.getElementById('explanation');
      document.getElementById('explanationHeader').textContent = result.is_correct ? '✅ 정답입니다! 해설' : '❌ 오답입니다. 해설';
      document.getElementById('explanationText').textContent = result.explanation;
      document.getElementById('keywordsText').textContent = '🏷️ ' + (result.keywords || []).join(', ');
      explanation.classList.add('show');

      // 정답/오답 표시 (모든 옵션 비활성화 상태로 색상만 표시)
      document.querySelectorAll('#optionsContainer input[type="radio"]').forEach(r => r.disabled = true);

      const correctRadio = document.querySelector(`#optionsContainer input[value="${result.correct_answer}"]`);
      if (correctRadio) {
        correctRadio.closest('.option').classList.add('correct');
      }

      if (!result.is_correct) {
        const selectedValue = answers[currentQuestionIndex];
        const selectedRadio = document.querySelector(`#optionsContainer input[value="${selectedValue}"]`);
        if (selectedRadio && selectedValue !== result.correct_answer) {
          selectedRadio.closest('.option').classList.add('incorrect');
        }
      }
    }

    function previousQuestion() {
      if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        displayQuestion();
      }
    }

    function nextQuestion() {
      if (currentQuestionIndex < quizData.length - 1) {
        currentQuestionIndex++;
        displayQuestion();
      } else {
        if (confirm('학습을 마치고 대시보드로 돌아가시겠습니까?')) {
          goToDashboard();
        }
      }
    }

    async function toggleBookmark() {
      const question = quizData[currentQuestionIndex];
      const nowBookmarked = !bookmarkIds.has(question.id);

      if (nowBookmarked) {
        bookmarkIds.add(question.id);
      } else {
        bookmarkIds.delete(question.id);
      }
      updateBookmarkStar(question.id);

      try {
        await fetch(API_URL + '?action=toggle_bookmark', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            token: currentToken,
            problem_id: question.id,
            bookmarked: nowBookmarked
          })
        });
      } catch (error) {
        console.error('즐겨찾기 저장 실패:', error);
      }
    }

    function updateBookmarkStar(questionId) {
      const btn = document.getElementById('bookmarkBtn');
      if (bookmarkIds.has(questionId)) {
        btn.textContent = '★';
        btn.style.color = '#ff9800';
      } else {
        btn.textContent = '☆';
        btn.style.color = '#999';
      }
    }

    function updateButtonStates() {
      document.getElementById('prevBtn').disabled = currentQuestionIndex === 0;
      const isLast = currentQuestionIndex === quizData.length - 1;
      document.getElementById('nextBtn').textContent = isLast ? '완료 ✓' : '다음 →';
    }

    function startTimer() {
      clearInterval(timerInterval);
      let seconds = 0;

      timerInterval = setInterval(() => {
        seconds++;
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        document.getElementById('timer').textContent =
          String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
      }, 1000);
    }

    function goToDashboard() {
      clearInterval(timerInterval);
      document.getElementById('quizScreen').classList.remove('show');
      document.getElementById('categoryScreen').classList.remove('show');
      document.getElementById('browseScreen').classList.remove('show');
      document.getElementById('dashboard').style.display = 'block';
      loadStats();
    }

    // ========== 로그아웃 ==========
    function handleLogout() {
      localStorage.removeItem('authToken');
      localStorage.removeItem('userName');
      currentUser = null;
      currentToken = null;

      document.getElementById('loginScreen').style.display = 'flex';
      document.getElementById('dashboard').style.display = 'none';
      document.getElementById('quizScreen').classList.remove('show');
      document.getElementById('username').value = '';
    }

    // ========== 초기화 ==========
    window.addEventListener('load', () => {
      const token = localStorage.getItem('authToken');
      const userName = localStorage.getItem('userName');

      if (token && userName) {
        currentToken = token;
        currentUser = { username: userName };
        showDashboard();
      }
    });

    // ========== 범주별 학습 ==========
    function escapeHtml(str) {
      const div = document.createElement('div');
      div.textContent = str;
      return div.innerHTML;
    }

    async function showCategoryMenu() {
      document.getElementById('dashboard').style.display = 'none';
      document.getElementById('categoryScreen').classList.add('show');

      const listEl = document.getElementById('categoryList');
      listEl.innerHTML = '<div class="loading"><div class="spinner"></div><p>불러오는 중...</p></div>';

      try {
        const response = await fetch(API_URL + '?action=get_categories');
        const data = await response.json();

        if (!data.success) {
          listEl.innerHTML = '<p>카테고리를 불러오지 못했습니다.</p>';
          return;
        }

        const icons = {
          '항공법규': '⚖️', '기체학': '🚁', '비행원리': '🌀',
          '안전관리': '🦺', '기체정비': '🔧', '전자/통신': '📡', '기상학': '🌤️'
        };

        listEl.innerHTML = data.data.categories.map(c => `
          <div class="menu-item" onclick="startQuiz('category', ${c.count}, '${escapeHtml(c.category)}')">
            <div class="icon">${icons[c.category] || '📘'}</div>
            <div class="title">${escapeHtml(c.category)}</div>
            <div class="description">${c.count}문제 전체 풀이</div>
          </div>
        `).join('');
      } catch (error) {
        listEl.innerHTML = '<p>오류: ' + error.message + '</p>';
      }
    }

    // ========== 전체 문제 보기 ==========
    let allQuestionsCache = null;

    async function showAllQuestions() {
      document.getElementById('dashboard').style.display = 'none';
      document.getElementById('browseScreen').classList.add('show');

      if (!allQuestionsCache) {
        const listEl = document.getElementById('browseList');
        listEl.innerHTML = '<div class="loading"><div class="spinner"></div><p>불러오는 중...</p></div>';

        try {
          const response = await fetch(API_URL + '?action=get_questions');
          const data = await response.json();

          if (!data.success) {
            listEl.innerHTML = '<p>문제를 불러오지 못했습니다.</p>';
            return;
          }

          allQuestionsCache = data.data.questions;

          const categories = [...new Set(allQuestionsCache.map(q => q.category))];
          const filterEl = document.getElementById('browseCategoryFilter');
          filterEl.innerHTML = '<option value="">전체 카테고리</option>' +
            categories.map(c => `<option value="${escapeHtml(c)}">${escapeHtml(c)}</option>`).join('');
        } catch (error) {
          listEl.innerHTML = '<p>오류: ' + error.message + '</p>';
          return;
        }
      }

      renderBrowseList();
    }

    function renderBrowseList() {
      if (!allQuestionsCache) return;

      const category = document.getElementById('browseCategoryFilter').value;
      const frequentOnly = document.getElementById('browseFrequentOnly').checked;
      const keyword = document.getElementById('browseSearch').value.trim().toLowerCase();

      let filtered = allQuestionsCache;
      if (category) filtered = filtered.filter(q => q.category === category);
      if (frequentOnly) filtered = filtered.filter(q => q.frequent);
      if (keyword) {
        filtered = filtered.filter(q =>
          q.question.toLowerCase().includes(keyword) ||
          (q.keywords || []).some(k => k.toLowerCase().includes(keyword))
        );
      }

      document.getElementById('browseCount').textContent = filtered.length + '개 문제';

      const listEl = document.getElementById('browseList');
      if (filtered.length === 0) {
        listEl.innerHTML = '<p style="color: #999;">조건에 맞는 문제가 없습니다.</p>';
        return;
      }

      listEl.innerHTML = filtered.map(q => `
        <div class="browse-item">
          <div class="b-meta">
            <span class="badge badge-category">${escapeHtml(q.category)}</span>
            ${q.frequent ? '<span class="badge badge-frequent">⭐ 빈출</span>' : ''}
          </div>
          <div class="b-question">${escapeHtml(q.question)}</div>
          <ul class="b-options">
            ${Object.entries(q.options).map(([k, v]) => `
              <li class="${k === q.correct ? 'b-correct' : ''}">${k}. ${escapeHtml(v)}${k === q.correct ? ' ✓' : ''}</li>
            `).join('')}
          </ul>
          <div class="b-explanation">💡 ${escapeHtml(q.explanation)}</div>
        </div>
      `).join('');
    }
  </script>
</body>
</html>
