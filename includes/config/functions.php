<?php
function redirect(string $url, int $code = 302) {
    echo "<script>window.location.replace('" . $url . "')</script>";
    exit();
}
function truncate_words($text, $limit = 20, $ending = '...') {
    $words = preg_split('/\s+/', strip_tags($text));
    if (count($words) <= $limit) {
        return implode(' ', $words);
    }
    $truncated = array_slice($words, 0, $limit);
    return implode(' ', $truncated) . $ending;
}