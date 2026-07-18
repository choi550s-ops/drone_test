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
          <div class="text" id="questionText"></div>
          <div class="options" id="optionsContainer"></div>
          <div class="explanation" id="explanation">
            <h4>💡 해설</h4>
            <p id="explanationText"></p>
            <p style="margin-top: 10px; font-size: 12px; color: #999;" id="keywordsText"></p>
          </div>
        </div>

        <div class="quiz-controls">
          <button class="btn btn-secondary" onclick="previousQuestion()" id="prevBtn">← 이전</button>
          <button class="btn btn-primary" id="submitBtn" onclick="submitAnswer()">제출</button>
          <button class="btn btn-secondary" onclick="nextQuestion()" id="nextBtn">다음 →</button>
        </div>
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
    async function startQuiz(type, count) {
      try {
        let url = API_URL + '?action=random_quiz&count=' + count;

        const response = await fetch(url);
        const data = await response.json();

        if (!data.success) {
          alert(data.error || '문제 로드 실패');
          return;
        }

        quizData = data.data.questions;
        currentQuestionIndex = 0;
        answers = {};
        quizStartTime = Date.now();

        document.getElementById('dashboard').style.display = 'none';
        document.getElementById('quizScreen').classList.add('show');
        document.getElementById('totalQuestions').textContent = quizData.length;

        document.getElementById('quizTitle').textContent =
          type === 'random' ? '모의고사' : type === 'weak' ? '약점 복습' : '즐겨찾기';

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

      Object.entries(question.options).forEach(([key, value]) => {
        const option = document.createElement('label');
        option.className = 'option';
        option.innerHTML = `
          <input type="radio" name="answer" value="${key}" onchange="selectAnswer('${key}')">
          <strong>${key}.</strong> ${value}
        `;
        optionsContainer.appendChild(option);
      });

      document.getElementById('explanation').classList.remove('show');

      // 이전 답변이 있으면 선택
      if (answers[currentQuestionIndex]) {
        const radio = document.querySelector(`input[value="${answers[currentQuestionIndex]}"]`);
        if (radio) radio.checked = true;
      }

      updateButtonStates();
    }

    function selectAnswer(value) {
      answers[currentQuestionIndex] = value;
    }

    async function submitAnswer() {
      const answer = answers[currentQuestionIndex];

      if (!answer) {
        alert('답을 선택하세요');
        return;
      }

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
          showExplanation(data.data);
          document.getElementById('submitBtn').disabled = true;
        }
      } catch (error) {
        alert('제출 실패: ' + error.message);
      }
    }

    function showExplanation(result) {
      const explanation = document.getElementById('explanation');
      document.getElementById('explanationText').textContent = result.explanation;
      document.getElementById('keywordsText').textContent = '🏷️ ' + result.keywords.join(', ');
      explanation.classList.add('show');

      // 정답 표시
      const correct = result.is_correct;
      const correctRadio = document.querySelector(`input[value="${result.correct_answer}"]`);
      if (correctRadio) {
        correctRadio.closest('.option').classList.add(correct ? 'correct' : 'incorrect');
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
        if (confirm('시험을 완료하시겠습니까?')) {
          goToDashboard();
        }
      }
    }

    function updateButtonStates() {
      document.getElementById('prevBtn').disabled = currentQuestionIndex === 0;
      document.getElementById('nextBtn').disabled = currentQuestionIndex === quizData.length - 1;
      document.getElementById('submitBtn').disabled = false;
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

    // 더미 함수들 (추후 구현 필요)
    function showCategoryMenu() {
      alert('카테고리별 학습 기능은 곧 추가될 예정입니다');
    }

    function showAllQuestions() {
      alert('전체 문제 보기 기능은 곧 추가될 예정입니다');
    }
  </script>
</body>
</html>
