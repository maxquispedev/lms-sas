<?php

return [
    'temporary_file_upload' => [
        'disk' => 'local',
        'rules' => ['required', 'file', 'max:20480'], // 20 MB (para adjuntos PDF/Word en RichEditor)
        'directory' => 'livewire-tmp',
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'jpg', 'jpeg', 'webp',
            'pdf', 'doc', 'docx', // documentos para adjuntos en descripción/módulos/lecciones
        ],
        'temporary_url' => true,
    ],
];