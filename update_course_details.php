<?php

/**
 * Script to update the main course details
 * php update_course_details.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Course;

try {
    // We assume there's one main course or we update the first one found
    $course = Course::first();

    if (!$course) {
        die("❌ No course found in the database.\n");
    }

    $course->update([
        'title'             => 'احتراف اللغة الإنجليزية',
        'short_description' => 'منهج شامل لتعلم اللغة الإنجليزية من الصفر حتى الاحتراف',
        'description'       => '<p>كورس شامل لتعلم اللغة الإنجليزية يغطي كل شيء من الحروف الأبجدية وحتى الأزمنة المتقدمة والقواعد النحوية الكاملة.</p>',
        'price'             => 0.00,
        // Optional: you can set duration here if the column exists
        // 'duration_weeks' => 12, 
    ]);

    echo "✅ Course updated successfully!\n";
    echo "Title: " . $course->title . "\n";
    echo "Price: SAR " . number_format($course->price, 2) . "\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
