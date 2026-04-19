<?php
$files = glob('d:\english-platform\english-platform\database\seeders\WritingSpeakingSeederPart*.php');

$search = <<<EOD
            // Writing
            WritingExercise::updateOrCreate(
                ['course_level_id' => \$level->id],
                [
                    'title' => \$level->title . ' — Writing',
                    'prompt' => \$exercises['writing']['prompt'],
                    'instructions' => \$exercises['writing']['instructions'],
                    'model_answer' => \$exercises['writing']['model_answer'],
                    'min_words' => \$exercises['writing']['min_words'],
                    'max_words' => \$exercises['writing']['max_words'],
                    'passing_score' => 60,
                ]
            );
EOD;

$replace = <<<EOD
            \$writingData = \$exercises['writing'];
            
            // Parse for exact match short answers
            \$questionsJson = null;
            \$evalType = 'ai';
            
            if (str_contains(\$writingData['prompt'] ?? '', "\\n1. ")) {
                \$evalType = 'exact_match';
                \$questionsJson = [];
                \$promptLines = explode("\\n", \$writingData['prompt']);
                \$answerLines = explode("\\n", \$writingData['model_answer'] ?? '');
                
                // Extract questions
                foreach (\$promptLines as \$line) {
                    if (preg_match('/^\d+\.\s+(.*)$/', trim(\$line), \$matches)) {
                        \$questionsJson[] = ['question' => \$matches[1], 'answer' => ''];
                    }
                }
                
                // Extract answers
                \$aIndex = 0;
                foreach (\$answerLines as \$line) {
                    if (preg_match('/^\d+\.\s+(.*)$/', trim(\$line), \$matches)) {
                        if (isset(\$questionsJson[\$aIndex])) {
                            \$questionsJson[\$aIndex]['answer'] = \$matches[1];
                        }
                        \$aIndex++;
                    }
                }
            }

            // Writing
            WritingExercise::updateOrCreate(
                ['course_level_id' => \$level->id],
                [
                    'title' => \$level->title . ' — Writing',
                    'prompt' => \$writingData['prompt'] ?? '',
                    'instructions' => \$writingData['instructions'] ?? '',
                    'model_answer' => \$writingData['model_answer'] ?? '',
                    'min_words' => \$writingData['min_words'] ?? 0,
                    'max_words' => \$writingData['max_words'] ?? 0,
                    'passing_score' => 60,
                    'evaluation_type' => \$evalType,
                    'questions_json' => \$questionsJson,
                ]
            );
EOD;

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, "'prompt' => \$exercises['writing']['prompt']") !== false) {
        $content = str_replace($search, $replace, $content);
        file_put_contents($file, $content);
        echo "Updated \$file\\n";
    }
}
