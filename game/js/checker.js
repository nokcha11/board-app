const board = document.getElementById("board");
const turnInfo = document.getElementById("turnInfo");
const levelInfo = document.getElementById("levelInfo");
const winModal = document.getElementById("winModal");
const winTitle = document.getElementById("winTitle");
const winMsg = document.getElementById("winMsg");

const killMessage = document.getElementById("killMessage");
const whiteCount = document.getElementById("whiteCount");
const purpleCount = document.getElementById("purpleCount");
const scoreText = document.getElementById("scoreText");

const params = new URLSearchParams(location.search);
const mode = params.get("mode") || "one";
const level = params.get("level") || "easy";

let currentTurn = "red";
let selectedPos = null;
let gameBoard = [];
let gameOver = false;
let mustContinueCapture = false;

let whiteKill = 0;
let purpleKill = 0;

function getLevelName() {
  if (level === "easy") return "초급";
  if (level === "normal") return "중급";
  if (level === "hard") return "고급";
  return "초급";
}

function initBoard() {
  gameBoard = [];
  selectedPos = null;
  currentTurn = "red";
  gameOver = false;
  mustContinueCapture = false;
  whiteKill = 0;
  purpleKill = 0;

  winModal.style.display = "none";
  turnInfo.textContent = "흰색 말 차례 - 내가 움직입니다";
  levelInfo.textContent = "현재 난이도 : " + getLevelName();

  killMessage.textContent = "아직 잡힌 말이 없습니다.";
  updateStatus();

  for (let row = 0; row < 8; row++) {
    gameBoard[row] = [];

    for (let col = 0; col < 8; col++) {
      if ((row + col) % 2 === 1) {
        if (row < 3) gameBoard[row][col] = "black";
        else if (row > 4) gameBoard[row][col] = "red";
        else gameBoard[row][col] = null;
      } else {
        gameBoard[row][col] = null;
      }
    }
  }

  drawBoard();
}

function drawBoard() {
  board.innerHTML = "";

  for (let row = 0; row < 8; row++) {
    for (let col = 0; col < 8; col++) {
      const cell = document.createElement("div");
      cell.className = "cell " + ((row + col) % 2 === 0 ? "light" : "dark");
      cell.dataset.row = row;
      cell.dataset.col = col;
      cell.onclick = () => cellClick(row, col);

      const pieceColor = gameBoard[row][col];

      if (pieceColor) {
        const piece = document.createElement("div");
        piece.className = "piece";

        if (pieceColor.includes("red")) {
          piece.classList.add("white-piece");
          piece.innerHTML = pieceColor.includes("king") ? "♔" : "♘";
        } else {
          piece.classList.add("purple-piece");
          piece.innerHTML = pieceColor.includes("king") ? "♚" : "♞";
        }

        if (pieceColor.includes("king")) {
          piece.classList.add("king-piece");
        }

        cell.appendChild(piece);
      }

      board.appendChild(cell);
    }
  }
}

function cellClick(row, col) {
  if (gameOver) return;
  if (mode === "one" && currentTurn === "black") return;

  const piece = gameBoard[row][col];

  if (piece && piece.includes(currentTurn)) {
    if (mustContinueCapture) return;

    clearHighlights();
    selectedPos = { row, col };
    getCell(row, col).classList.add("selected");
    showPossibleMoves(row, col);
    return;
  }

  if (selectedPos && isPossibleMove(row, col)) {
    const result = movePiece(selectedPos.row, selectedPos.col, row, col);

    clearHighlights();
    drawBoard();
    updateStatus();

    if (checkWinner()) return;

    if (result.captured) {
      const nextCaptures = getMoves(row, col).filter(move => move.capture);

      if (nextCaptures.length > 0) {
        mustContinueCapture = true;
        selectedPos = { row, col };
        getCell(row, col).classList.add("selected");
        showPossibleMoves(row, col);
        turnInfo.textContent = "연속으로 잡을 수 있습니다!";
        return;
      }
    }

    mustContinueCapture = false;
    selectedPos = null;
    changeTurn();
  }
}

function changeTurn() {
  currentTurn = currentTurn === "red" ? "black" : "red";

  if (mode === "one" && currentTurn === "black") {
    turnInfo.textContent = "보라색 말 차례 - 컴퓨터 생각 중...";
    setTimeout(computerMove, 700);
  } else {
    turnInfo.textContent = currentTurn === "red"
      ? "흰색 말 차례"
      : "보라색 말 차례";
  }
}

function getMoves(row, col) {
  const piece = gameBoard[row][col];
  if (!piece) return [];

  const color = piece.includes("red") ? "red" : "black";
  const isKing = piece.includes("king");

  const directions = isKing ? [-1, 1] : [color === "red" ? -1 : 1];
  const moves = [];

  directions.forEach(direction => {
    [-1, 1].forEach(side => {
      const normalRow = row + direction;
      const normalCol = col + side;

      if (isInside(normalRow, normalCol) && !gameBoard[normalRow][normalCol]) {
        moves.push({ row: normalRow, col: normalCol });
      }

      const enemyRow = row + direction;
      const enemyCol = col + side;
      const jumpRow = row + direction * 2;
      const jumpCol = col + side * 2;

      if (
        isInside(jumpRow, jumpCol) &&
        isInside(enemyRow, enemyCol) &&
        gameBoard[enemyRow][enemyCol] &&
        !gameBoard[enemyRow][enemyCol].includes(color) &&
        !gameBoard[jumpRow][jumpCol]
      ) {
        moves.push({
          row: jumpRow,
          col: jumpCol,
          capture: { row: enemyRow, col: enemyCol }
        });
      }
    });
  });

  return moves;
}

function showPossibleMoves(row, col) {
  let moves = getMoves(row, col);

  if (mustContinueCapture) {
    moves = moves.filter(move => move.capture);
  }

  moves.forEach(move => {
    getCell(move.row, move.col).classList.add("possible");
  });
}

function isPossibleMove(row, col) {
  let moves = getMoves(selectedPos.row, selectedPos.col);

  if (mustContinueCapture) {
    moves = moves.filter(move => move.capture);
  }

  return moves.some(move => move.row === row && move.col === col);
}

function movePiece(fromRow, fromCol, toRow, toCol) {
  const moves = getMoves(fromRow, fromCol);
  const move = moves.find(m => m.row === toRow && m.col === toCol);

  const movingPiece = gameBoard[fromRow][fromCol];

  gameBoard[toRow][toCol] = movingPiece;
  gameBoard[fromRow][fromCol] = null;

  let captured = false;

  if (move && move.capture) {
    const deadPiece = gameBoard[move.capture.row][move.capture.col];

    if (deadPiece && deadPiece.includes("black")) {
      whiteKill++;
      killMessage.textContent = "흰색말이 보라색말 하나를 죽였습니다!";
    }

    if (deadPiece && deadPiece.includes("red")) {
      purpleKill++;
      killMessage.textContent = "보라색말이 흰색말 하나를 죽였습니다!";
    }

    gameBoard[move.capture.row][move.capture.col] = null;
    captured = true;
  }

  if (toRow === 0 && gameBoard[toRow][toCol] === "red") {
    gameBoard[toRow][toCol] = "red-king";
  }

  if (toRow === 7 && gameBoard[toRow][toCol] === "black") {
    gameBoard[toRow][toCol] = "black-king";
  }

  return { captured };
}

function computerMove() {
  if (gameOver) return;

  const allMoves = getAllMoves("black");

  if (allMoves.length === 0) {
    showWin("흰색 승리!", "보라색 말이 움직일 수 없습니다!");
    return;
  }

  const selected = chooseComputerMove(allMoves);

  const result = movePiece(
    selected.from.row,
    selected.from.col,
    selected.move.row,
    selected.move.col
  );

  drawBoard();
  updateStatus();

  if (checkWinner()) return;

  if (result.captured) {
    const nextCaptures = getMoves(selected.move.row, selected.move.col)
      .filter(move => move.capture);

    if (nextCaptures.length > 0) {
      setTimeout(() => {
        computerContinueCapture(selected.move.row, selected.move.col);
      }, 600);
      return;
    }
  }

  currentTurn = "red";
  turnInfo.textContent = "흰색 말 차례 - 내가 움직입니다";
}

function chooseComputerMove(allMoves) {
  if (level === "easy") {
    return randomMove(allMoves);
  }

  if (level === "normal") {
    const captureMoves = allMoves.filter(item => item.move.capture);
    return captureMoves.length > 0 ? randomMove(captureMoves) : randomMove(allMoves);
  }

  if (level === "hard") {
    let bestMove = allMoves[0];
    let bestScore = -9999;

    allMoves.forEach(item => {
      const score = evaluateMove(item);

      if (score > bestScore) {
        bestScore = score;
        bestMove = item;
      }
    });

    return bestMove;
  }

  return randomMove(allMoves);
}

function evaluateMove(item) {
  let score = 0;

  const fromRow = item.from.row;
  const fromCol = item.from.col;
  const toRow = item.move.row;
  const toCol = item.move.col;
  const piece = gameBoard[fromRow][fromCol];

  if (item.move.capture) score += 100;
  if (toRow === 7 && piece === "black") score += 80;
  if (piece.includes("king")) score += 20;
  if (toCol === 0 || toCol === 7) score += 15;
  if (isDangerAfterMove(fromRow, fromCol, toRow, toCol)) score -= 80;

  score += Math.floor(Math.random() * 10);

  return score;
}

function isDangerAfterMove(fromRow, fromCol, toRow, toCol) {
  const originalFrom = gameBoard[fromRow][fromCol];
  const originalTo = gameBoard[toRow][toCol];

  gameBoard[toRow][toCol] = originalFrom;
  gameBoard[fromRow][fromCol] = null;

  let danger = false;

  for (let row = 0; row < 8; row++) {
    for (let col = 0; col < 8; col++) {
      if (gameBoard[row][col] && gameBoard[row][col].includes("red")) {
        const moves = getMoves(row, col);

        if (moves.some(move => move.capture && move.capture.row === toRow && move.capture.col === toCol)) {
          danger = true;
        }
      }
    }
  }

  gameBoard[fromRow][fromCol] = originalFrom;
  gameBoard[toRow][toCol] = originalTo;

  return danger;
}

function randomMove(moves) {
  return moves[Math.floor(Math.random() * moves.length)];
}

function computerContinueCapture(row, col) {
  const captureMoves = getMoves(row, col).filter(move => move.capture);

  if (captureMoves.length === 0) {
    currentTurn = "red";
    turnInfo.textContent = "흰색 말 차례 - 내가 움직입니다";
    return;
  }

  let selectedMove;

  if (level === "hard") {
    selectedMove = captureMoves[0];
  } else {
    selectedMove = captureMoves[Math.floor(Math.random() * captureMoves.length)];
  }

  const result = movePiece(row, col, selectedMove.row, selectedMove.col);

  drawBoard();
  updateStatus();

  if (checkWinner()) return;

  if (result.captured) {
    setTimeout(() => {
      computerContinueCapture(selectedMove.row, selectedMove.col);
    }, 600);
  }
}

function getAllMoves(color) {
  const result = [];

  for (let row = 0; row < 8; row++) {
    for (let col = 0; col < 8; col++) {
      if (gameBoard[row][col] && gameBoard[row][col].includes(color)) {
        getMoves(row, col).forEach(move => {
          result.push({
            from: { row, col },
            move
          });
        });
      }
    }
  }

  return result;
}

function updateStatus() {
  let white = 0;
  let purple = 0;

  gameBoard.flat().forEach(piece => {
    if (piece && piece.includes("red")) white++;
    if (piece && piece.includes("black")) purple++;
  });

  whiteCount.textContent = "현재 흰색말 " + white + "개 남았습니다.";
  purpleCount.textContent = "현재 보라색말 " + purple + "개 남았습니다.";
  scoreText.textContent = "현재 " + whiteKill + "대" + purpleKill + "입니다.";
}

function checkWinner() {
  let white = 0;
  let purple = 0;

  gameBoard.flat().forEach(piece => {
    if (piece && piece.includes("red")) white++;
    if (piece && piece.includes("black")) purple++;
  });

  if (white === 0) {
    showWin("보라색 승리!", "축하합니다!");
    return true;
  }

  if (purple === 0) {
    showWin("흰색 승리!", "축하합니다!");
    return true;
  }

  return false;
}

function showWin(title, msg) {
  gameOver = true;
  winTitle.textContent = title;
  winMsg.textContent = msg;
  winModal.style.display = "flex";
  fireworks();
}

function fireworks() {
  const colors = ["#ffffff", "#7f27ff", "#ffd700", "#ff4dff", "#00d9ff"];

  for (let i = 0; i < 180; i++) {
    const particle = document.createElement("div");
    particle.className = "particle";

    particle.style.left = Math.random() * window.innerWidth + "px";
    particle.style.top = Math.random() * window.innerHeight + "px";

    particle.style.setProperty("--x", (Math.random() * 500 - 250) + "px");
    particle.style.setProperty("--y", (Math.random() * 500 - 250) + "px");
    particle.style.setProperty("--color", colors[Math.floor(Math.random() * colors.length)]);

    document.body.appendChild(particle);

    setTimeout(() => {
      particle.remove();
    }, 1200);
  }
}

function clearHighlights() {
  document.querySelectorAll(".cell").forEach(cell => {
    cell.classList.remove("selected", "possible");
  });
}

function getCell(row, col) {
  return document.querySelector(`.cell[data-row='${row}'][data-col='${col}']`);
}

function isInside(row, col) {
  return row >= 0 && row < 8 && col >= 0 && col < 8;
}

function resetGame() {
  initBoard();
}

initBoard();

