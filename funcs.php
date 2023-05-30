<?php

function get_start(int $page, int $per_page): int
{
    return ($page - 1) * $per_page;
}

function get_cities(int $start, int $per_page): array
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM city LIMIT $start, $per_page");
    $stmt->execute();
    return $stmt->fetchAll();
}
