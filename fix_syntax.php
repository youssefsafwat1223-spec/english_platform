<?php

$files = [
    'resources/views/emails/payment-invoice.blade.php',
    'resources/views/student/courses/checkout.blade.php',
    'resources/views/emails/achievement.blade.php',
    'resources/views/student/courses/show.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/welcome.blade.php',
    'resources/views/home.blade.php'
];

foreach($files as $f) {
    if (!file_exists($f)) continue;
    $c = file_get_contents($f);
    
    // Fix nested translations: {{ __('{{ __('text') }}') }} -> {{ __('text') }}
    $c = preg_replace('/\{\{\s*__\(\'\{\{\s*__\(\'(.*?)\'\)\s*\}\}\'\)\s*\}\}/', '{{ __(\'$1\') }}', $c);
    
    // Fix nested translations with prefix: 🎉 {{ __('{{ __('text') }}') }} -> 🎉 {{ __('text') }}
    // Wait, the previous regex handles the nesting itself!
    // But what if it's "{{ __('🎊 {{ __(\'text\') }}') }}" ?
    // Let's just fix it by string replacement:
    $c = str_replace('{{ __(\'{{ __(\'', '{{ __(\'', $c);
    $c = str_replace('\') }}\') }}', '\') }}', $c);
    $c = str_replace('\') }} \') }}', '\') }}', $c); // spacing
    
    // Fix single quotes inside arrays/sections: '{{ __('text') }}' -> __('text')
    // e.g. ['{{ __('text') }}'] => [__('text')]
    // e.g. @section('title', '{{ __('text') }}') => @section('title', __('text'))
    $c = preg_replace('/\'\{\{\s*__\(\'(.*?)\'\)\s*\}\}\'/', '__(\'$1\')', $c);
    
    // Also fix double quotes inside arrays/sections: "{{ __('text') }}" -> __('text')
    $c = preg_replace('/"\{\{\s*__\(\'(.*?)\'\)\s*\}\}"/', '__(\'$1\')', $c);

    file_put_contents($f, $c);
}

// Ensure the compiled views are cleared so the next cache catches the syntax errors or success
array_map('unlink', glob("storage/framework/views/*.php"));

echo "Fixed Blade syntax in broken files.\n";
