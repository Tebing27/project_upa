<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Component Namespaces
    |--------------------------------------------------------------------------
    |
    | This value allows you to specify custom namespaces for organizing your
    | Livewire components and their corresponding views.
    |
    */

    'component_namespaces' => [
        'layouts' => resource_path('views/components/layouts'),
        'pages' => resource_path('views/pages'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Component Layout
    |--------------------------------------------------------------------------
    |
    | This value determines the default layout used when rendering full-page
    | Livewire components.
    |
    */

    'component_layout' => 'layouts::app',

];
