<?php

if (!isset($_GET['info_hash']) || !isset($_GET['peer_id']) || ! isset($_GET['port']) ) {
    header("Content-Type: text/plain");
    die('Missing required parameters');
}

$peersFile = __DIR__.'/peers.json';


// Get QUERY parameters
$info_hash  =   bin2hex($_GET['info_hash']) ?? "";
$peer_id    =   bin2hex($_GET['peer_id'])   ?? "";
$port       =   $_GET['port']       ?? 0;
$uploaded   =   $_GET['uploaded']   ?? 0;
$downloaded =   $_GET['downloaded'] ?? 0;
$left       =   $_GET['left']       ?? 0;
$event      =   $_GET['event']      ?? '';

// Get client IP
$ip = $_SERVER['REMOTE_ADDR'];

include_once 'functions.php';

$peers = loadPeers();

// Clean old peers (older than 30 minutes)
$current_time = time();
foreach ($peers as $hash => $torrent_peers) {
    foreach ($torrent_peers as $id => $peer) {
        if ($peer['last_update'] < ($current_time - 1800)) {
            unset($peers[$hash][$id]);
        }
    }
}

// Update peer information
$peers[$info_hash][$peer_id] = [
    'ip' => $ip,
    'port' => $port,
    'uploaded' => $uploaded,
    'downloaded' => $downloaded,
    'left' => $left,
    'last_update' => $current_time
];

// Remove peer if they're stopping
if ($event === 'stopped') {
    unset($peers[$info_hash][$peer_id]);
}



// Prepare response
$response = [
    'interval' => 1800, // 30 minutes
    'min interval' => 900, // 15 minutes
    'complete' => 0,
    'incomplete' => 0,
    'peers' => []
];
savePeers($peers);
// Count seeders/leechers and build peer list
if (isset($peers[$info_hash])) {
    $compact_peers = '';
    foreach ($peers[$info_hash] as $pid => $peer) {
        if ($pid !== $peer_id) { // Don't send the requesting peer to itself
            if (!empty($_GET['compact']) && $_GET['compact'] == 1) {
                // Formato compacto: 6 bytes por peer (4 para IP + 2 para porta)
                $compact_peers .= pack("Nn", ip2long($peer['ip']), (int)$peer['port']);
            } else {
                $response['peers'][] = [
                    'ip' => $peer['ip'],
                    'port' => $peer['port']
                ];
            }
        }
        if ($peer['left'] == 0) {
            $response['complete']++;
        } else {
            $response['incomplete']++;
        }
    }
    // Se compact=1, substituir array de peers pela string binária
    if (!empty($_GET['compact']) && $_GET['compact'] == 1) {
        $response['peers'] = $compact_peers;
    }
}



header("Content-Type: text/plain");
// Send response in bencoded format
echo bencode($response);