<?php

// Fix 1: payment-invoice.blade.php
$p1 = 'resources/views/emails/payment-invoice.blade.php';
if (file_exists($p1)) {
    $c = file_get_contents($p1);
    // Let's just fix any nested __('تفاصيل הפاتورة') wait, the error is unexpected identifier "تفاصيل".
    // This happens if "تفاصيل الفاتورة" is concatenated wrongly: "تفاصيل الفاتورة" without proper quotes.
    $c = str_replace("{{ __('{{ __('تفاصيل الفاتورة') }}') }}", "{{ __('تفاصيل الفاتورة') }}", $c);
    $c = str_replace('{{ config(\'app.name\', \'{{ __(\'تفاصيل الفاتورة\') }}\') }}', '{{ config(\'app.name\', __(\'تفاصيل الفاتورة\')) }}', $c);
    file_put_contents($p1, $c);
}

// Fix 2: student/courses/show.blade.php
$p2 = 'resources/views/student/courses/show.blade.php';
if (file_exists($p2)) {
    $c = file_get_contents($p2);
    // Unclosed '(' does not match '}'. This might be from `@section('meta_keywords'...`
    // Let's replace the broken section entirely.
    // We know it broke around line 3.
    // I will just use regex to fix `__('__('...'))`
    $c = preg_replace('/\{\{\s*__\(\'\{\{\s*__\(\'(.*?)\'\)\s*\}\}\'\)\s*\}\}/', '{{ __(\'$1\') }}', $c);
    // Fix unclosed paren: maybe `__('text'` instead of `__('text')`
    $c = str_replace("@section('title', \$course->title . ' | ' . config('app.name', '{{ __('إتقان الإنجليزية') }}'", "@section('title', \$course->title . ' | ' . config('app.name', __('إتقان الإنجليزية')))", $c);
    // Also meta keywords might be broken from previous edits
    $c = preg_replace('/@section\(\'meta_keywords\',\s*\'\{\{\s*__\(\'كورس\'\)\s*\}\}\s*\'\s*\.\s*\$course->title\s*\.\s*\'\s*\{\{\s*__\(\'تعلم الإنجليزية\'\)\s*\}\}/', '@section(\'meta_keywords\', __(\'كورس\') . \' \' . $course->title . \', \' . __(\'تعلم الإنجليزية\') . ', $c);
    file_put_contents($p2, $c);
}

// Fix 3: layouts/app.blade.php
$p3 = 'resources/views/layouts/app.blade.php';
if (file_exists($p3)) {
    $c = file_get_contents($p3);
    // unexpected identifier "customer"
    // "customer service" was translated.
    $c = str_replace("'{{ __('customer service') }}'", "__('customer service')", $c);
    $c = str_replace('"{{ __(\'customer service\') }}"', '__(\'customer service\')', $c);
    $c = str_replace('{{ config(\'app.name\', \'{{ __(\'إتقان الإنجليزية — Simple English\') }}\') }}', '{{ config(\'app.name\', __(\'إتقان الإنجليزية — Simple English\')) }}', $c);
    file_put_contents($p3, $c);
}

// Fix 4: welcome.blade.php
$p4 = 'resources/views/welcome.blade.php';
if (file_exists($p4)) {
    $c = file_get_contents($p4);
    // unexpected identifier "إتقان"
    $c = str_replace('{{ config(\'app.name\', \'{{ __(\'إتقان الإنجليزية\') }}\') }}', '{{ config(\'app.name\', __(\'إتقان الإنجليزية\')) }}', $c);
    file_put_contents($p4, $c);
}

array_map('unlink', glob("storage/framework/views/*.php"));
echo "Fixed specific errors.\n";
