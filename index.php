<?php

class PokemonAPI {
    private $cachePath = 'cache/';
    private $capturePath = 'captures.json';
    private $cacheExpiry = 3600;

    public function fetchPokemonList($page = 1) {
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $url = "https://pokeapi.co/api/v2/pokemon?limit=$limit&offset=$offset";
        $cacheKey = $this->getCacheKey($url);

        if ($this->isCached($cacheKey)) {
            return $this->getFromCache($cacheKey);
        } else {
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            $this->saveToCache($cacheKey, $data);
            return $data;
        }
    }

    public function fetchPokemonDetails($id) {
        $url = "https://pokeapi.co/api/v2/pokemon/$id";
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    private function loadCaptures() {
        if (!file_exists($this->capturePath)) {
            return [];
        }
        $json = file_get_contents($this->capturePath);
        return json_decode($json, true) ?: [];
    }

    private function saveCaptures($captures) {
        $json = json_encode($captures, JSON_PRETTY_PRINT);
        file_put_contents($this->capturePath, $json);
    }

    public function toggleCapture($pokemonId) {
        $captures = $this->loadCaptures();
        if (array_key_exists($pokemonId, $captures)) {
            unset($captures[$pokemonId]);
        } else {
            $captures[$pokemonId] = true;
        }
        $this->saveCaptures($captures);
        return $captures;
    }

    public function checkCapture($pokemonId) {
        $captures = $this->loadCaptures();
        return array_key_exists($pokemonId, $captures);
    }

    private function getCacheKey($url) {
        parse_str(parse_url($url, PHP_URL_QUERY), $queryParams);
        $page = isset($queryParams['offset']) ? ($queryParams['offset'] / 20 + 1) : 1;
        return "pokemon_list_page" . $page;
    }

    private function saveToCache($key, $data) {
        $filePath = $this->cachePath . $key . ".json";
        file_put_contents($filePath, json_encode($data));
    }

    private function getFromCache($key) {
        $filePath = $this->cachePath . $key . ".json";
        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            return json_decode($json, true);
        }
        return null;
    }

    private function isCached($key) {
        $filePath = $this->cachePath . $key . ".json";
        if (file_exists($filePath) && (filemtime($filePath) + $this->cacheExpiry > time())) {
            return true;
        }
        return false;
    }
}

$pokemonAPI = new PokemonAPI();
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

if ($path == '/pokemon' && $method == 'GET') {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $pokemonList = $pokemonAPI->fetchPokemonList($page);
    echo json_encode($pokemonList, JSON_UNESCAPED_SLASHES);
} elseif (preg_match('#^/pokemon/(\d+)$#', $path, $matches) && $method == 'GET') {
    $pokemonId = $matches[1];
    $pokemonDetails = $pokemonAPI->fetchPokemonDetails($pokemonId);
    echo json_encode($pokemonDetails, JSON_UNESCAPED_SLASHES);
} elseif (preg_match('#^/pokemon/(\d+)/toggleCapture$#', $path, $matches) && $method == 'POST') {
    $pokemonId = $matches[1];
    $captures = $pokemonAPI->toggleCapture($pokemonId);
    echo json_encode(['success' => true, 'captures' => $captures]);
} elseif (preg_match('#^/pokemon/(\d+)/checkCapture$#', $path, $matches) && $method == 'GET') {
    $pokemonId = $matches[1];
    $isCaptured = $pokemonAPI->checkCapture($pokemonId);
    echo json_encode(['isCaptured' => $isCaptured]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found'], JSON_UNESCAPED_SLASHES);
}
?>