<?php
function loadPeers() {
    global $peersFile;
    if (file_exists($peersFile)) {
        $jsonContent = file_get_contents($peersFile);
        return json_decode($jsonContent, true) ?: [];
    }
    return [];
}

function savePeers($peers) {
    global $peersFile;
    $jsonContent = json_encode($peers, JSON_PRETTY_PRINT);
    file_put_contents($peersFile, $jsonContent);
}

// Simple bencode function
function bencode($data) {
    if (is_array($data)) {
        if (array_keys($data) === range(0, count($data) - 1)) {
            // List
            $pieces = [];
            foreach ($data as $value) {
                $pieces[] = bencode($value);
            }
            return 'l' . implode('', $pieces) . 'e';
        } else {
            // Dictionary
            ksort($data);
            $pieces = [];
            foreach ($data as $key => $value) {
                $pieces[] = bencode((string)$key) . bencode($value);
            }
            return 'd' . implode('', $pieces) . 'e';
        }
    } elseif (is_int($data)) {
        return 'i' . $data . 'e';
    } else {
        return strlen($data) . ':' . $data;
    }
}