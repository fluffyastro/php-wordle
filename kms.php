<?php
// Szavak listája
$words = ["apple", "grape", "peach", "mango", "berry", "lemon", "cherry", "melon", "olive", "plums"];
$targetWord = $words[array_rand($words)];
// Kezdeti állapot beállítása session-ben
session_start();
if (!isset($_SESSION['targetWord'])) {
    $_SESSION['targetWord'] = $targetWord;
    $_SESSION['attempts'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guess = strtolower(trim($_POST['guess']));
    if (strlen($guess) === 5 && ctype_alpha($guess)) {
        $_SESSION['attempts'][] = str_split($guess);
        if ($guess === $_SESSION['targetWord']) {
            $message = "Congratulations! You guessed the word!";
            session_destroy();
        } elseif (count($_SESSION['attempts']) >= 6) {
            $message = "Game over! The correct word was: " . $_SESSION['targetWord'];
            session_destroy();
        }
    } else {
        $error = "Please enter a valid 5-letter word.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wordle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Wordle Game</h1>
        <form method="post" action="">
            <input type="text" name="guess" maxlength="5" required autofocus>
            <button type="submit">Submit</button>
        </form>
        <div class="message">
            <?= $message ?? '' ?>
            <?= $error ?? '' ?>
        </div>
        <div class="grid">
            <?php foreach ($_SESSION['attempts'] as $attempt): ?>
                <div class="row">
                    <?php for ($i = 0; $i < 5; $i++): 
                        $letter = $attempt[$i];
                        $class = 'gray';
                        if ($letter === $_SESSION['targetWord'][$i]) {
                            $class = 'green';
                        } elseif (strpos($_SESSION['targetWord'], $letter) !== false) {
                            $class = 'yellow';
                        }
                    ?>
                        <div class="cell <?= $class ?>"><?= strtoupper($letter) ?></div>
                    <?php endfor; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>