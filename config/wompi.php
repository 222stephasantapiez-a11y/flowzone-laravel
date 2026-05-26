<?php
return [
    'public_key'    => env('WOMPI_PUBLIC_KEY'),
    'private_key'   => env('WOMPI_PRIVATE_KEY'),
    'integrity_key' => env('WOMPI_INTEGRITY_KEY'),
    'eventos_key'   => env('WOMPI_EVENTOS_KEY'),
    'env'           => env('WOMPI_ENV', 'sandbox'),
    'api_url'       => env('WOMPI_ENV', 'sandbox') === 'production'
                        ? 'https://production.wompi.co/v1'
                        : 'https://sandbox.wompi.co/v1',
];