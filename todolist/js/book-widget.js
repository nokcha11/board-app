const bookBox =
  document.getElementById("bookBox");

function initBook(){

  if(!bookBox) return;

  const apiKey =
    window.TODO_CONFIG?.KAKAO_REST_KEY;

  if(!apiKey){

    bookBox.innerHTML =
      "<p class='api-fallback'>Kakao API 키가 없습니다.</p>";

    return;
  }

  fetch(
    "https://dapi.kakao.com/v3/search/book?target=title&query=습관&size=1",
    {
      headers:{
        Authorization:
          "KakaoAK " + apiKey
      }
    }
  )

  .then(function(res){

    if(!res.ok){
      throw new Error(
        "Kakao Book API Error"
      );
    }

    return res.json();
  })

  .then(function(data){

    if(
      !data.documents ||
      !data.documents[0]
    ){
      throw new Error("No Book");
    }

    const book =
      data.documents[0];

    const title =
      book.title || "추천 도서";

    const authors =
      book.authors
      ? book.authors.join(", ")
      : "저자 없음";

    const thumb =
      book.thumbnail || "";

    bookBox.innerHTML =

      "<div class='book-current'>" +

        (
          thumb
          ? "<img src='" + thumb + "' alt='" + title + "'>"
          : "<div class='book-cover-placeholder'>BOOK</div>"
        )

        +

        "<div>" +

          "<span class='api-label'>Book Recommend</span>" +

          "<strong>" +
            title +
          "</strong>" +

          "<p>" +
            authors +
          "</p>" +

        "</div>" +

      "</div>";
  })

  .catch(function(error){

    console.error(error);

    bookBox.innerHTML =
      "<p class='api-fallback'>도서를 불러올 수 없습니다.</p>";
  });
}

initBook();