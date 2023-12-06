<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["nome_escola"])) {
        $nomeEscola = $_POST["nome_escola"];
        $enderecoUsuario = $_POST["endereco"];

        // Serviço de geocodificação do OpenStreetMap (OSM)
        $usuarioLatLng = geocodeOSM($enderecoUsuario);

        if ($usuarioLatLng) {
            calcularDistanciaEscola($nomeEscola, $usuarioLatLng);
        } else {
            echo '<h2>Resultado</h2>';
            echo '<p>Erro ao obter as coordenadas do endereço.</p>';
        }
    } else {
        echo '<h2>Resultado</h2>';
        echo '<p>Nome da escola não fornecido.</p>';
    }
}

function geocodeOSM($endereco)
{
    $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($endereco);

    $opts = [
        'http' => [
            'header' => 'User-Agent: PHP'
        ]
    ];
    $context = stream_context_create($opts);

    $resultado = file_get_contents($url, false, $context);

    if ($resultado) {
        $data = json_decode($resultado, true);

        if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
            $latitude = (float)$data[0]['lat'];
            $longitude = (float)$data[0]['lon'];
            return ['lat' => $latitude, 'lng' => $longitude];
        }
    }

    return null;
}

function calcularDistanciaEscola($nomeEscola, $usuarioLatLng)
{
    $escolas = [
        // Aqui ficam as coordenadas e o nome da escola dessa forma:
        ['nome' => 'ESCOLA EXEMPLO', 'lat' => -12.107200, 'lng' => -45.804070],
    ];

    $escolaSelecionada = buscarEscolaPorNome($nomeEscola, $escolas);

    if ($escolaSelecionada) {
        $escolaLatLng = ['lat' => $escolaSelecionada['lat'], 'lng' => $escolaSelecionada['lng']];
        $distancia = haversine($usuarioLatLng, $escolaLatLng);

        echo '<h2>Resultado</h2>';
        if ($distancia <= 2000) {
            echo '<p>Você não precisa pegar o ônibus para a escola ' . $nomeEscola;
        } else {
            echo '<p>Você precisa pegar o ônibus para a escola ' . $nomeEscola;
        }
    } else {
        echo '<h2>Resultado</h2>';
        echo '<p>Escola não encontrada.</p>';
    }
}

function buscarEscolaPorNome($nomeEscola, $escolas)
{
    foreach ($escolas as $escola) {
        if (strcasecmp($escola['nome'], $nomeEscola) === 0) {
            return $escola;
        }
    }

    return null;
}

function haversine($coord1, $coord2)
{
    $R = 6371; // Raio da Terra em quilômetros
    $dLat = deg2rad($coord2['lat'] - $coord1['lat']);
    $dLon = deg2rad($coord2['lng'] - $coord1['lng']);

    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($coord1['lat'])) * cos(deg2rad($coord2['lat'])) *
        sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distancia = $R * $c;
    return $distancia;
}
