<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>회원가입</title>

  <link id="header-light-theme" rel="stylesheet" href="css/header_bright_pastel_light.css">
  <link id="header-dark-theme" rel="stylesheet" href="css/header_glass_mood_light.css" disabled>

  <script src="js/theme-mode.js" defer></script>

  <style>

    *{
      box-sizing:border-box;
    }

    html,
    body{
      margin:0;
      min-height:100%;
    }

    body{

      min-height:100vh;

      display:flex;
      flex-direction:column;

      color:#412531;

      font-family:Arial,sans-serif;

      overflow-x:hidden;

      background:
        linear-gradient(
          rgba(255,245,250,0.46),
          rgba(255,232,242,0.66)
        ),
        url("images/cover1.jpg")
        center / cover fixed no-repeat;
    }

    /* =========================
       BLUR BUBBLE
    ========================= */

    body::before,
    body::after{

      content:"";

      position:fixed;

      border-radius:50%;

      pointer-events:none;

      z-index:-1;
    }

    body::before{

      width:520px;
      height:520px;

      right:-140px;
      top:-160px;

      background:
        rgba(255,165,200,0.28);

      filter:blur(120px);
    }

    body::after{

      width:460px;
      height:460px;

      left:-120px;
      bottom:-140px;

      background:
        rgba(255,230,240,0.42);

      filter:blur(110px);
    }

    /* =========================
       MAIN
    ========================= */

    main{

      flex:1;

      display:flex;
      justify-content:center;
      align-items:center;

      padding:70px 20px;
    }

    .join-wrapper{

      width:100%;
      max-width:1200px;

      display:grid;

      grid-template-columns:
        minmax(0,1.15fr)
        minmax(380px,0.85fr);

      gap:28px;
    }

    /* =========================
       LEFT HERO
    ========================= */

    .join-hero{

      padding:60px 50px;

      border-radius:34px;

      background:
        rgba(255,255,255,0.06);

      border:
        1px solid rgba(255,255,255,0.26);

      backdrop-filter:
        blur(18px)
        saturate(150%);

      -webkit-backdrop-filter:
        blur(18px)
        saturate(150%);

      box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.18),
        0 20px 52px rgba(120,50,80,0.10);
    }

    .hero-badge{

      display:inline-flex;

      align-items:center;

      padding:10px 18px;

      border-radius:999px;

      font-size:13px;
      font-weight:800;

      color:#d94f83;

      background:
        rgba(255,255,255,0.12);

      border:
        1px solid rgba(255,255,255,0.22);

      backdrop-filter:blur(10px);
    }

    .join-hero h2{

       margin:22px 0 18px;

      line-height:1.08;

      font-size:58px;
      font-weight:900;

      color:#992c57;
    }

    .join-hero p{

      margin:0;

      line-height:1.7;

      font-size:18px;
      font-weight:700;

      color:#6b4957;
    }

    .hero-buttons{

      display:flex;
      flex-wrap:wrap;

      gap:12px;

      margin-top:34px;
    }

    .hero-buttons button{

      min-height:44px;

      padding:0 22px;

      border-radius:999px;

      cursor:pointer;

      font-weight:800;

      border:none;
    }

    .hero-primary{

      color:#fff;

      background:
        linear-gradient(
          135deg,
          #ff8eb6,
          #f45f98
        );

      box-shadow:
        0 10px 24px rgba(244,95,149,0.25);
    }

    .hero-secondary{

      color:#c54875;

      background:
        rgba(255,255,255,0.12);

      border:
        1px solid rgba(255,255,255,0.24) !important;

      backdrop-filter:blur(10px);
    }

    /* =========================
       FEATURE
    ========================= */

    .feature-grid{

      display:grid;

      grid-template-columns:
        repeat(3,minmax(0,1fr));

      gap:16px;

      margin-top:48px;
    }

    .feature-card{

      padding:24px 20px;

      border-radius:22px;

      background:
        rgba(255,255,255,0.10);

      border:
        1px solid rgba(255,255,255,0.24);

      backdrop-filter:
        blur(12px)
        saturate(140%);

      -webkit-backdrop-filter:
        blur(12px)
        saturate(140%);

      box-shadow:none;
    }

    .feature-card h4{

      margin:0 0 16px;

      color:#c03f70;

      font-size:15px;
      font-weight:900;
    }

    .feature-card p{

      margin:0;

      line-height:1.7;

      color:#664753;

      font-size:15px;
      font-weight:700;
    }

    /* =========================
       JOIN FORM
    ========================= */

    .join-box{

      padding:46px 38px;

      border-radius:30px;

      background:
        rgba(255,255,255,0.07);

      border:
        1px solid rgba(255,255,255,0.28);

      backdrop-filter:
        blur(18px)
        saturate(150%);

      -webkit-backdrop-filter:
        blur(18px)
        saturate(150%);

      box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.18),
        0 20px 50px rgba(120,50,80,0.10);
    }

    .join-box h2{

      margin:0 0 12px;

      color:#b03c69;

      font-size:28px;
      font-weight:900;
    }

    .join-sub{

      margin:0 0 34px;

      line-height:1.6;

      color:#7a5a67;

      font-size:16px;
      font-weight:700;
    }

    .join-box label{

      display:block;

      margin-bottom:10px;

      color:#c24976;

      font-size:14px;
      font-weight:800;
    }

    .join-box input{

      width:100%;

      min-height:50px;

      margin-bottom:18px;

      padding:12px 16px;

      border-radius:14px;

      outline:none;

      font-size:15px;

      color:#412531;

      background:
        rgba(255,255,255,0.14);

      border:
        1px solid rgba(255,255,255,0.30);

      backdrop-filter:blur(10px);
    }

    .join-box input::placeholder{

      color:
        rgba(90,60,72,0.52);
    }

    .join-box input:focus{

      border-color:
        rgba(255,120,168,0.72);

      box-shadow:
        0 0 0 4px rgba(255,120,168,0.16);
    }

    .join-submit{

      width:100%;

      min-height:50px;

      margin-top:12px;

      border:none;

      border-radius:999px;

      cursor:pointer;

      font-size:16px;
      font-weight:900;

      color:#fff;

      background:
        linear-gradient(
          135deg,
          #ff8eb6,
          #f05f95
        );

      box-shadow:
        0 12px 28px rgba(240,95,149,0.25);
    }

    .join-footer{

      margin-top:20px;

      text-align:center;

      color:#7a5a67;

      font-weight:700;
    }

    .join-footer a{

      color:#d94f83;

      text-decoration:none;

      font-weight:900;
    }

    footer{

        position:relative;
        margin-top:auto;
        padding:22px 20px;
        text-align:center;
        color:#b33d69;
        font-weight:800;

        background:
          rgba(255,255,255,0.03);

        border-top:
          1px solid rgba(255,255,255,0.18);

        backdrop-filter:
          blur(10px)
          saturate(145%);

        -webkit-backdrop-filter:
          blur(10px)
          saturate(145%);

        box-shadow:
          inset 0 1px 0 rgba(255,255,255,0.10);
      }

    /* =========================
       DARK MODE
    ========================= */

    body.dark-mode{

      color:#ffe6ef;

      background:
        linear-gradient(
          rgba(28,18,28,0.72),
          rgba(28,18,28,0.84)
        ),
        url("images/cover1.jpg")
        center / cover fixed no-repeat;
    }

    body.dark-mode footer{

      color:#ffd5e3;
      background:
        rgba(255,255,255,0.03);
      border-top:
        1px solid rgba(255,255,255,0.08);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.04);
    }

    body.dark-mode::before{

      background:
        rgba(255,120,180,0.18);
    }

    body.dark-mode::after{

      background:
        rgba(120,70,150,0.22);
    }

    body.dark-mode .join-hero,
    body.dark-mode .join-box{

      background:
        rgba(255,235,244,0.08);

      border-color:
        rgba(255,230,240,0.18);

      box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.10),
        0 24px 54px rgba(5,2,12,0.34);
    }

    body.dark-mode .join-hero h2,
    body.dark-mode .join-box h2,
    body.dark-mode footer{

      color:#ffd4e2;
    }

    body.dark-mode .join-hero p,
    body.dark-mode .join-sub,
    body.dark-mode .feature-card p,
    body.dark-mode .join-footer{

      color:#e6c4d1;
    }

    body.dark-mode .feature-card{

      background:
        rgba(255,255,255,0.06);

      border-color:
        rgba(255,255,255,0.14);
    }

    body.dark-mode .join-box input{

      color:#ffe6ef;

      background:
        rgba(255,235,244,0.08);

      border-color:
        rgba(255,226,238,0.18);
    }

    body.dark-mode .join-box input::placeholder{

      color:
        rgba(255,230,240,0.56);
    }

    body.dark-mode .hero-secondary{

      color:#ffd7e5;

      background:
        rgba(255,255,255,0.06);
    }

    @media(max-width:1200px){

      .join-wrapper{
        grid-template-columns:1fr;
      }

      .join-hero h2{
        font-size:48px;
      }
    }

    @media(max-width:768px){

      main{
        padding:40px 16px;
      }

      .join-hero,
      .join-box{
        padding:34px 24px;
      }

      .join-hero h2{
        font-size:38px;
      }

      .feature-grid{
        grid-template-columns:1fr;
      }
    }

  </style>
</head>

<body>

<?php include "header.php"; ?>

<main>

  <div class="join-wrapper">

    <!-- LEFT -->

    <section class="join-hero">

      <span class="hero-badge">
        MY TODO PLANNER
      </span>

      <h2>
        오늘을 기록하고,<br>
        루틴을 만들고,<br>
        나만의 하루를 완성하세요
      </h2>

      <p>
        일정, 목표, 감정 기록까지<br>
        하루의 흐름을 감성적으로 관리할 수 있습니다.
      </p>

      <div class="hero-buttons">

        <button class="hero-primary">
          오늘 일정 보기
        </button>

        <button class="hero-secondary">
          루틴 관리 시작
        </button>

        <button class="hero-secondary">
          기록 아카이브 열기
        </button>

      </div>

      <div class="feature-grid">

        <div class="feature-card">
          <h4>DAILY ROUTINE</h4>
          <p>
            오늘의 목표와 할 일을
            하루 흐름에 맞게 관리합니다.
          </p>
        </div>

        <div class="feature-card">
          <h4>MEMORY NOTE</h4>
          <p>
            감정과 기록을 남기며
            나만의 아카이브를 만들어갑니다.
          </p>
        </div>

        <div class="feature-card">
          <h4>MONTHLY PLAN</h4>
          <p>
            월별 계획표와 주간 목표를
            한눈에 정리할 수 있습니다.
          </p>
        </div>

      </div>

    </section>

    <!-- RIGHT -->

    <section class="join-box">

      <h2>회원가입</h2>

      <p class="join-sub">
        MY TODO에서 나만의 기록과 계획을 시작해보세요.
      </p>

      <form method="post" action="join_ok.php">

        <label>아이디</label>
        <input
          type="text"
          name="id"
          placeholder="아이디 입력"
          required
        >

        <label>비밀번호</label>
        <input
          type="password"
          name="pw"
          placeholder="비밀번호"
          required
        >

        <label>비밀번호 확인</label>
        <input
          type="password"
          name="pw2"
          placeholder="비밀번호 확인"
          required
        >

        <label>이름</label>
        <input
          type="text"
          name="name"
          placeholder="이름"
          required
        >

        <label>이메일</label>
        <input
          type="email"
          name="email"
          placeholder="이메일 입력"
          required
        >

        <button
          type="submit"
          class="join-submit"
        >
          회원가입
        </button>

      </form>

      <div class="join-footer">
        이미 계정이 있으신가요?
        <a href="login.php">로그인</a>
      </div>

    </section>

  </div>

</main>

<footer>
  © 2026 MY TODO | All Rights Reserved
</footer>

</body>
</html>