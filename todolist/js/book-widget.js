document.addEventListener("DOMContentLoaded", function () {
  const bookBox = document.getElementById("bookBox");
  const searchInput = document.getElementById("bookSearchInput");
  const searchBtn = document.getElementById("bookSearchBtn");
  const resultBox = document.getElementById("bookSearchResults");

  const apiKey = window.TODO_CONFIG?.KAKAO_REST_KEY;

  if (!bookBox) return;

  /* =========================
     renderBook()
     화면에 책 표시
  ========================= */
  function renderBook(book) {
    const title = book.title || "제목 없음";

    const authors =
      book.authors && book.authors.length
        ? book.authors.join(", ")
        : "저자 정보 없음";

    const thumb = book.thumbnail || "";

    bookBox.innerHTML =
      "<div class='book-content'>" +
        (
          thumb
            ? "<img src='" + thumb + "' alt='" + title + "'>"
            : "<div class='book-cover'>BOOK</div>"
        ) +
        "<div class='book-info'>" +
          "<p>Selected Book</p>" +
          "<strong>" + title + "</strong>" +
          "<span>" + authors + "</span>" +
        "</div>" +
      "</div>";
  }

  /* =========================
     saveBook()
     선택한 책 DB 저장
  ========================= */
  function saveBook(book) {
    const formData = new FormData();

    formData.append("title", book.title || "");
    formData.append(
      "authors",
      book.authors && book.authors.length
        ? book.authors.join(", ")
        : "저자 정보 없음"
    );
    formData.append("thumbnail", book.thumbnail || "");

    fetch("book_save.php", {
      method: "POST",
      body: formData
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        console.log("Book saved:", data);
      })
      .catch(function (error) {
        console.error("Book save failed:", error);
      });
  }

  /* =========================
     loadSavedBook()
     새로고침 시 저장된 책 불러오기
  ========================= */
  function loadSavedBook() {
    fetch("book_load.php")
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (data.success && data.book) {
          renderBook({
            title: data.book.title,
            authors: data.book.authors
              ? data.book.authors.split(", ")
              : [],
            thumbnail: data.book.thumbnail
          });
        }
      })
      .catch(function (error) {
        console.error("Book load failed:", error);
      });
  }

  /* =========================
     searchBooks()
     Kakao Book API 검색
  ========================= */
  function searchBooks(keyword) {
    if (!apiKey) {
      bookBox.innerHTML =
        "<p class='api-fallback'>Kakao API 키가 없습니다.</p>";
      return;
    }

    if (!keyword.trim()) {
      bookBox.innerHTML =
        "<p class='api-fallback'>검색어를 입력해주세요.</p>";
      return;
    }

    resultBox.innerHTML =
      "<p class='api-fallback'>검색 중...</p>";

    fetch(
      "https://dapi.kakao.com/v3/search/book?query=" +
        encodeURIComponent(keyword) +
        "&size=5",
      {
        headers: {
          Authorization: "KakaoAK " + apiKey
        }
      }
    )
      .then(function (res) {
        if (!res.ok) {
          throw new Error("Kakao Book API error");
        }
        return res.json();
      })
      .then(function (data) {
        if (!data.documents || data.documents.length === 0) {
          resultBox.innerHTML =
            "<p class='api-fallback'>검색 결과가 없습니다.</p>";
          return;
        }

        resultBox.innerHTML = "";

        data.documents.forEach(function (book) {
          const item = document.createElement("button");
          item.type = "button";
          item.className = "book-result-item";

          item.innerHTML =
            (
              book.thumbnail
                ? "<img src='" + book.thumbnail + "' alt='cover'>"
                : "<div class='book-mini-placeholder'>BOOK</div>"
            ) +
            "<span>" + book.title + "</span>";

          item.addEventListener("click", function () {
            renderBook(book);
            saveBook(book);

            resultBox.innerHTML = "";
            searchInput.value = book.title || "";
          });

          resultBox.appendChild(item);
        });
      })
      .catch(function (error) {
        console.error(error);
        resultBox.innerHTML =
          "<p class='api-fallback'>도서 검색에 실패했습니다.</p>";
      });
  }

  /* =========================
     검색 버튼 이벤트
  ========================= */
  if (searchBtn) {
    searchBtn.addEventListener("click", function () {
      searchBooks(searchInput.value);
    });
  }

  /* =========================
     엔터 검색 이벤트
  ========================= */
  if (searchInput) {
    searchInput.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        searchBooks(searchInput.value);
      }
    });
  }

  /* =========================
     페이지 로드시 저장된 책 표시
  ========================= */
  loadSavedBook();
});