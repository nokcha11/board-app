document.addEventListener(
  "DOMContentLoaded",
  function () {

    function updateProgress() {

      const checks =
        document.querySelectorAll(
          ".todo-check"
        );

      const total =
        checks.length;

      const done =
        document.querySelectorAll(
          ".todo-check:checked"
        ).length;

      const percent =

        total > 0

          ? Math.round(
              (done / total) * 100
            )

          : 0;

      const percentText =
        document.getElementById(
          "completePercent"
        );

      const progressBar =
        document.getElementById(
          "completeBar"
        );

      if (percentText) {

        percentText.textContent =
          percent + "%";
      }

      if (progressBar) {

        progressBar.style.width =
          percent + "%";
      }
    }

    document
      .querySelectorAll(
        ".todo-check"
      )

      .forEach(function (checkbox) {

        checkbox.addEventListener(
          "change",
          function () {

            const todoIdx =
              checkbox.dataset.idx;

            const status =

              checkbox.checked

                ? 1

                : 0;

            const formData =
              new FormData();

            formData.append(
              "idx",
              todoIdx
            );

            formData.append(
              "status",
              status
            );

            fetch(
              "todo_done_update.php",

              {

                method: "POST",

                body: formData
              }
            )

            .then(function (res) {

              return res.json();
            })

            .then(function (data) {

              if (!data.success) {

                checkbox.checked =
                  !checkbox.checked;

                return;
              }

              const title =

                checkbox
                  .closest("label")

                  ?.querySelector(
                    ".todo-title"
                  );

              if (title) {

                title.classList.toggle(

                  "done",

                  checkbox.checked
                );
              }

              updateProgress();
            })

            .catch(function () {

              checkbox.checked =
                !checkbox.checked;
            });
          }
        );
      });

    updateProgress();
  }
);