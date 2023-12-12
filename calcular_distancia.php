<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["escola"])) {
        $nomeEscola = $_POST["escola"];
        $enderecoUsuario = $_POST["endereco"];
        $cep = isset($_POST["cep"]) ? $_POST["cep"] : null;

        // Utilizar serviço de geocodificação do OpenStreetMap (OSM)
        $usuarioLatLng = geocodeAddress($enderecoUsuario, $cep);

        if ($usuarioLatLng) {
            avaliarNecessidadeOnibus($nomeEscola, $usuarioLatLng);
        } else {
            echo '<h2>Resultado</h2>';
            echo '<p>Erro ao obter as coordenadas do endereço.</p>';
        }
    } else {
        $resultadoHTML = '<h2>Resultado</h2><p>Escola não encontrada.</p>';
    }
}

function geocodeAddress($endereco, $cep)
{
    $cidade = 'Luis Eduardo Magalhães, Bahia';

    $estrategias = [
        $endereco . ', ' . $cidade,
        $cep . ', ' . $cidade,
        $endereco . ', ' . $cidade,
    ];

    foreach ($estrategias as $estrategia) {
        $usuarioLatLng = geocode($estrategia);

        if ($usuarioLatLng) {
            return $usuarioLatLng;
        }
    }

    return null;
}

function geocode($endereco)
{
    $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($endereco);

    $options = [
        'http' => [
            'header' => 'User-Agent: PHP'
        ]
    ];
    $context = stream_context_create($options);

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

function avaliarNecessidadeOnibus($nomeEscola, $usuarioLatLng)
{
    $escolas = [
        ['nome' => 'ESCOLA MUNICIPAL ONERO COSTA DA ROSA', 'lat' => -12.107204, 'lng' => -45.804077],
        ['nome' => 'ESCOLA MUNICIPAL JOSÉ CARDOSO DE LIMA', 'lat' => -12.0874583, 'lng' => -45.7981511],
        ['nome' => 'ESCOLA MUNICIPAL OTTOMAR SCHWENGBER', 'lat' => -12.0799909, 'lng' => -45.7976451],
        ['nome' => 'ESCOLA MUNICIPAL AMELIO GATTO', 'lat' => -12.1038404, 'lng' => -45.7993179],
        ['nome' => 'ESCOLA MUNICIPAL IVO HERING', 'lat' => -12.0875098, 'lng' => -45.8065298],
        ['nome' => 'ESCOLA MUNICIPAL CEZER PELISSARI', 'lat' => -12.0801919, 'lng' => -45.7658264],
        ['nome' => 'ESCOLA MUNICIPAL MOZART FELICIANO', 'lat' => -12.0904852, 'lng' => -45.7952833],
        ['nome' => 'SEED - ESCOLA MUNICIPAL HENRIQUE DE FREITAS MOREIRA', 'lat' => -12.3707999, 'lng' => -45.8061769],
        ['nome' => 'SEED - ESCOLA MUNICIPAL SAO PAULO', 'lat' => -12.3269217, 'lng' => -45.8595609],
        ['nome' => 'ESCOLA MUNICIPAL VANIA APARECIDA SANTOS RIBEIRO', 'lat' => -12.0806855, 'lng' => -45.8089693],
        ['nome' => 'ESCOLA MUNICIPAL IRANI LEITE MATUTINO SANTOS', 'lat' => -12.0787029, 'lng' => -45.7939863],
        ['nome' => 'COLEGIO MUNICIPAL ANGELO BOSA', 'lat' => -12.1064826, 'lng' => -45.7963575],
        ['nome' => 'ESCOLA MUNICIPAL ALDORI LUIZ TOLAZZI', 'lat' => -12.1074664, 'lng' => -45.8044062],
        ['nome' => 'ESCOLA MUNICIPAL EDALEIO BARBOSA DE SOUSA', 'lat' => -12.0993624, 'lng' => -45.8049024],
        ['nome' => 'ESCOLA MUNICIPAL AMABILIO VIEIRA DOS SANTOS', 'lat' => -12.0991083, 'lng' => -45.7991738],
        ['nome' => 'ESCOLA MUNICIPAL HERMINIO CARLOS BRANDAO', 'lat' => -12.0790505, 'lng' => -45.768786],
        ['nome' => 'ESCOLA MUNICIPAL JARDIM PARAISO', 'lat' => -12.0984533, 'lng' => -45.7813242],
        ['nome' => 'SEED - ESCOLA MUNICIPAL SAO FRANCISCO', 'lat' => null, 'lng' => null],
        ['nome' => 'ESCOLA MUNICIPAL LUZIA DA ROSA FONTANA', 'lat' => -12.0780646, 'lng' => -45.7533513],
        ['nome' => 'CENTRO MUNICIPAL DE EDUCACAO INFANTIL MIMOSO', 'lat' => -12.0778937, 'lng' => -45.7924891],
        ['nome' => 'CRECHE MUNICIPAL PEQUENO PRINCIPE', 'lat' => -12.102524, 'lng' => -45.7970522],
        ['nome' => 'SEED-CRECHE ESPERANCA MARIA AMALIA UCHOUA SANTA CRUZ', 'lat' => null, 'lng' => null],
        ['nome' => 'ESCOLA MUNICIPAL MARLEI TEREZINHA PRETTO', 'lat' => -12.0763467, 'lng' => -45.7298687],
        ['nome' => 'ESCOLA MUNICIPAL DOM RICARDO JOSEF WEBERBERGER', 'lat' => -12.1005848, 'lng' => -45.7968613],
        ['nome' => 'CEMEI - PATRICIA OSHIRO BRENTAN', 'lat' => -12.0781226, 'lng' => -45.7911574],
        ['nome' => 'SEED-ESCOLA MUNICIPAL CECILIA MEIRELES', 'lat' => -12.0627272, 'lng' => -45.7148463],
        ['nome' => 'CEMEI - CLEUSA SANTOS SILVA E SILVA', 'lat' => -12.1121105, 'lng' => -45.8006071],
        ['nome' => 'CRECHE MUNICIPAL MENINO JESUS', 'lat' => -12.0816774, 'lng' => -45.8194227],
        ['nome' => 'CEMEI VITORIA FONTANA', 'lat' => -12.0826482, 'lng' => -45.7720719],
        ['nome' => 'SEED-CEMEI MAURILIO COMPARIN', 'lat' => -12.0817862, 'lng' => -45.7587447],
        ['nome' => 'SEED-ESCOLA MUN PEDRO PAULO CORTE FILHO', 'lat' => -12.0671554, 'lng' => -45.797672],
        ['nome' => 'SEED-CEMEI ZILDA ARNS NEUMANN', 'lat' => -12.081188, 'lng' => -45.7713909],
        ['nome' => 'SEED-CENTRO INFANTIL DE APRENDIZADO SEMENTES DO FUTURO', 'lat' => -12.0812836, 'lng' => -45.7259007],
        ['nome' => 'SEED- ESCOLA MUNICIPAL VEREADOR MARDONIO DA ROCHA CARVALHO', 'lat' => -12.0764048, 'lng' => -45.8152466],
        ['nome' => 'ESCOLA MUNICIPAL VEREADOR LUCIR FICANHA', 'lat' => -12.1023666, 'lng' => -45.792108],
        ['nome' => 'SEED - ESCOLA MUNICIPAL FABIO JOHNER', 'lat' => -12.1110158, 'lng' => -46.2921113],
        ['nome' => 'SEED-ESCOLA MUNICIPAL IVANILDE DOS SANTOS CEDRO', 'lat' => null, 'lng' => null],
        ['nome' => 'SEED-ESC MUNICIPAL TIAGO ALFREDO LIESENFELD', 'lat' => -12.104146, 'lng' => -45.8329679],
        ['nome' => 'ESCOLA TESTE', 'lat' => null, 'lng' => null],
        ['nome' => 'ESCOLA MUNICIPAL CORNELIO DIAS DOS SANTOS', 'lat' => -11.9424183, 'lng' => -45.7839149],
        ['nome' => 'Centro Municipal de Reforço Escolar', 'lat' => null, 'lng' => null],
    ];

    $escolaSelecionada = buscarEscolaPorNome($nomeEscola, $escolas);

    if ($escolaSelecionada) {
        $escolaLatLng = ['lat' => $escolaSelecionada['lat'], 'lng' => $escolaSelecionada['lng']];
        $distancia = haversine($usuarioLatLng, $escolaLatLng);

        echo '<h2>Resultado</h2>';
        if ($distancia <= 2000) {
            echo '<p>Você não precisa pegar o ônibus para a escola ' . $nomeEscola . '. Distância: ' . number_format($distancia, 2) . ' km</p>';
        } else {
            echo '<p>Você precisa pegar o ônibus para a escola ' . $nomeEscola . '. Distância: ' . number_format($distancia, 2) . ' km</p>';
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
// fórmula de haversine
{
    $ray = 6371; // Raio da Terra em quilômetros
    $dLat = deg2rad($coord2['lat'] - $coord1['lat']);
    $dLon = deg2rad($coord2['lng'] - $coord1['lng']);

    $angularDistance = sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($coord1['lat'])) * cos(deg2rad($coord2['lat'])) *
        sin($dLon / 2) * sin($dLon / 2);

    $haversineFormulaResult = 2 * atan2(sqrt($angularDistance), sqrt(1 - $angularDistance));

    $distance = $ray * $haversineFormulaResult; // Distância em quilômetros
    return $distance;
}
