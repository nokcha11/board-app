<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>게시판</title>

<link rel="stylesheet" href="css/style.css">

<style>

/* ================= MAIN ================= */
main {
    display: flex;
    justify-content: center;
    padding: 40px 0;
    background: url("images/pattern.png");
    background-size: cover;
    min-height: calc(100vh - 140px);
}

/* ================= 컨테이너 (핵심 수정) ================= */
.container {
    width: 90%;
    max-width: 1200px;   /* ⭐ 핵심: 가로 확장 */
    margin: 0 auto;
}

/* ================= 게시판 박스 ================= */
.board-box {
    width: 100%;
    background: rgba(15, 27, 51, 0.9);
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #1f3b66;
}

/* 제목 */
.board-box h2 {
    text-align: center;
    color: #4aa3ff;
    margin-bottom: 20px;
}

/* ================= 테이블 ================= */
table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #14284a;
    color: #cfe6ff;
    padding: 12px;
    font-size: 14px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #1f3b66;
    text-align: center;
    font-size: 13px;
    color: #e6f1ff;
}

/* 제목 칸 넓게 */
td.title {
    text-align: left;
    padding-left: 15px;
    width: 45%;   /* ⭐ 가독성 개선 */
}

/* hover 효과 */
tr:hover {
    background: #162d55;
}

/* ================= 페이지네이션 ================= */
.pagination {
    text-align: center;
    margin-top: 25px;
}

.pagination a {
    display: inline-block;
    margin: 0 5px;
    padding: 7px 12px;
    color: #cfe6ff;
    border: 1px solid #1f3b66;
    border-radius: 4px;
    text-decoration: none;
}

.pagination a:hover {
    background: #4aa3ff;
    color: #fff;
}

.pagination a.active {
    background: #4aa3ff;
    color: #fff;
}

</style>

</head>

<body>

<!-- 상단 -->
<?php include "header.php"; ?>

<!-- ================= 중앙 ================= -->
<main>

    <div class="container">
        <div class="board-box">

            <h2>게시판 목록</h2>

            <table>
                <tr>
                    <th>순번</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>등록일</th>
                    <th>조회수</th>
                </tr>

                <tr><td>10</td><td class="title">게시글 제목입니다 10</td><td>관리자</td><td>2026-01-10</td><td>25</td></tr>
                <tr><td>9</td><td class="title">게시글 제목입니다 9</td><td>홍길동</td><td>2026-01-09</td><td>18</td></tr>
                <tr><td>8</td><td class="title">게시글 제목입니다 8</td><td>김철수</td><td>2026-01-08</td><td>12</td></tr>
                <tr><td>7</td><td class="title">게시글 제목입니다 7</td><td>이영희</td><td>2026-01-07</td><td>9</td></tr>
                <tr><td>6</td><td class="title">게시글 제목입니다 6</td><td>박민수</td><td>2026-01-06</td><td>15</td></tr>
                <tr><td>5</td><td class="title">게시글 제목입니다 5</td><td>최지은</td><td>2026-01-05</td><td>7</td></tr>
                <tr><td>4</td><td class="title">게시글 제목입니다 4</td><td>오세훈</td><td>2026-01-04</td><td>11</td></tr>
                <tr><td>3</td><td class="title">게시글 제목입니다 3</td><td>정수진</td><td>2026-01-03</td><td>6</td></tr>
                <tr><td>2</td><td class="title">게시글 제목입니다 2</td><td>강민호</td><td>2026-01-02</td><td>4</td></tr>
                <tr><td>1</td><td class="title">게시글 제목입니다 1</td><td>관리자</td><td>2026-01-01</td><td>30</td></tr>
            </table>

            <div class="pagination">
                <a href="#">«</a>
                <a href="#" class="active">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">»</a>
            </div>

        </div>
    </div>

</main>

<!-- ================= 하단 ================= -->
<footer>
    © 2026 MY WEBSITE | All Rights Reserved | Contact: admin@site.com
</footer>

</body>
</html>