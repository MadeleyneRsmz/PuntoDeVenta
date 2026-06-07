<?php
/**
 * Genera una imagen SVG tipo "monograma" para cada producto de muestra,
 * usada como marcador cuando el producto no tiene una foto real subida.
 * Ejecutar una sola vez: php docs/generate-demo-photos.php
 */

$productos = [
    'ACC-01' => 'Aretes de perla',        'ACC-02' => 'Pulsera dorada',
    'ACC-03' => 'Collar corazón',         'ACC-04' => 'Diadema de moño',
    'ACC-05' => 'Anillo ajustable',       'BOL-01' => 'Bolsa de mano rosa',
    'BOL-02' => 'Monedero de flores',     'BOL-03' => 'Mochila pequeña',
    'BOL-04' => 'Cartera holográfica',    'BEL-01' => 'Labial mate rosa',
    'BEL-02' => 'Brillo labial frutal',   'BEL-03' => 'Crema para manos',
    'BEL-04' => 'Espejo compacto',        'BEL-05' => 'Set de brochas mini',
    'PAP-01' => 'Agenda floral 2026',     'PAP-02' => 'Set de plumas pastel',
    'PAP-03' => 'Stickers decorativos',   'PAP-04' => 'Libreta de notas mini',
    'VEL-01' => 'Vela aroma vainilla',    'VEL-02' => 'Vela aroma rosa',
    'VEL-03' => 'Difusor de varitas',     'VEL-04' => 'Vela en frasco mini',
];

$paletas = [
    ['#ffd9e8', '#ff5c93'], // rosa
    ['#ffe9c7', '#e0a53f'], // durazno
    ['#e6d9ff', '#8a5cf6'], // lila
    ['#d7f5e6', '#2fa876'], // menta
    ['#ffe1e1', '#e0447a'], // coral
];

$destino = __DIR__ . '/../public/img/products';
if (! is_dir($destino)) {
    mkdir($destino, 0755, true);
}

$i = 0;
foreach ($productos as $sku => $nombre) {
    [$fondo, $acento] = $paletas[$i % count($paletas)];
    $inicial = mb_strtoupper(mb_substr($nombre, 0, 1));

    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
  <rect width="200" height="200" fill="{$fondo}"/>
  <circle cx="100" cy="100" r="58" fill="{$acento}" opacity="0.18"/>
  <circle cx="100" cy="100" r="42" fill="{$acento}"/>
  <text x="100" y="118" font-family="Trebuchet MS, sans-serif" font-size="42" font-weight="bold"
        fill="#ffffff" text-anchor="middle">{$inicial}</text>
</svg>
SVG;

    file_put_contents("{$destino}/{$sku}.svg", $svg);
    $i++;
}

echo "Listas " . count($productos) . " imagenes en {$destino}\n";
