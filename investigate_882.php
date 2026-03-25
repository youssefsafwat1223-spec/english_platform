<?php
$lesson = \App\Models\Lesson::find(882);
if (!$lesson) {
    echo "Lesson 882 not found\n";
    exit;
}
echo "Lesson found: " . $lesson->title . "\n";
$quiz = $lesson->quiz;
if ($quiz) {
    echo "Quiz exists with ID: " . $quiz->id . "\n";
} else {
    echo "No quiz for this lesson yet\n";
}
