<?php
/** Shared helpers for Lab 06. */
declare(strict_types=1);
function esc(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function lab_header(string $title, int $exercise=0): void {
    $pageTitle=esc($title);
    echo <<<HTML
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{$pageTitle} | Lab 06 - Lập trình Web</title>
<link rel="stylesheet" href="vendor/bootstrap.min.css">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-light bg-white border-bottom mb-4">
  <div class="container d-flex justify-content-between flex-wrap">
    <a class="navbar-brand font-weight-bold text-primary" href="index.php">Lab 06 · PHP</a>
    <div class="nav small">
HTML;
    for($i=1;$i<=5;$i++){
        $cls=$exercise===$i?'font-weight-bold text-primary':'text-secondary';
        echo '<a class="nav-link '.$cls.'" href="ex'.$i.'.php">Bài '.$i.'</a>';
    }
    echo "</div></div></nav>\n";
}
function lab_footer():void{
    echo '<footer class="container text-muted small text-center py-4 mt-4 border-top">Lab 06 · Web Programming and Applications · PHP 8+</footer></body></html>';
}
