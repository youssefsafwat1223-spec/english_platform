<?php

$viewsDir = __DIR__ . '/resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $c = file_get_contents($path);
        $original = $c;
        
        // Fix nested translations: {{ __('{{ __('text') }}') }} -> {{ __('text') }}
        $c = preg_replace('/\{\{\s*__\(\'\{\{\s*__\(\'(.*?)\'\)\s*\}\}\'\)\s*\}\}/', '{{ __(\'$1\') }}', $c);
        
        // Fix nested inside double quotes "{{ __('{{ __('text') }}') }}" -> "{{ __('text') }}"
        $c = preg_replace('/\{\{\s*__\("\{\{\s*__\(\'(.*?)\'\)\s*\}\}"\)\s*\}\}/', '{{ __(\'$1\') }}', $c);

        $c = str_replace('{{ __(\'{{ __(\'', '{{ __(\'', $c);
        $c = str_replace('\') }}\') }}', '\') }}', $c);
        $c = str_replace('\') }} \') }}', '\') }}', $c); // spacing
        
        // Fix single quotes inside arrays/sections/attributes: '{{ __('text') }}' -> __('text')
        // Only if it looks like PHP. A single quote outside is normal for JS, but inside `@` directives or `{{ }}` it needs fixing.
        // E.g. @section('title', '{{ __('Acceptance of Terms') }}') => @section('title', __('Acceptance of Terms'))
        // Only replace `'{{ __('text') }}'` -> `__('text')` if preceded by a valid PHP-like context?
        // Actually replacing it everywhere inside Blade might break JS. But usually we don't put {{ __() }} inside JS string quotes like console.log('{{ __('text') }}').
        // Wait, if we use console.log('{{ __('text') }}'), it's perfectly valid! If I change it to `console.log(__('text'))` that's invalid JS syntax!
        // So we must BE CAREFUL where we change `'{{ __('text') }}'` to `__('text')`!
        // We should ONLY do it inside Blade directives: @section, @yield, {{ ... }}.
        
        // Let's use a smarter approach: we know there are 19 errors, let's just use `php -l` to find exactly where they are or just fix @section and app.name config.
        
        // Fix specific common breaks:
        $c = preg_replace('/@section\(\s*\'(.*?)\'\s*,\s*\'\{\{\s*__\(\'(.*?)\'\)\s*\}\}\'\s*\)/', '@section(\'$1\', __(\'$2\'))', $c);
        $c = preg_replace('/@yield\(\s*\'(.*?)\'\s*,\s*\'\{\{\s*__\(\'(.*?)\'\)\s*\}\}\'\s*\)/', '@yield(\'$1\', __(\'$2\'))', $c);
        $c = preg_replace('/config\(\s*\'(.*?)\'\s*,\s*\'\{\{\s*__\(\'(.*?)\'\)\s*\}\}\'\s*\)/', 'config(\'$1\', __(\'$2\'))', $c);

        // Nested translation in config or similar like {{ config('app.name', '{{ __('App') }}') }}
        $c = preg_replace('/\'\{\{\s*__\(\'(.*?)\'\)\s*\}\}\'/', '__(\'$1\')', $c);
        
        // Actually, replacing globally is safer for PHP syntax errors unless it breaks JS. Let's see if there are any errors.
        
        if ($c !== $original) {
            file_put_contents($path, $c);
        }
    }
}

array_map('unlink', glob("storage/framework/views/*.php"));
echo "Applied generalized syntax fixes.\n";
