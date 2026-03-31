import os
import re

dir_path = r'd:\english-platform\english-platform\resources\views\student'

# Regex to match the typical full-width top gradient divs causing the "annoying line"
# We match `<div class="absolute top-0 ... pointer-events-none z-0"></div>`
# Specifically looking for gradients that span the top of the body
pattern1 = re.compile(
    r'\s+<div class="absolute top-0 left-0 w-full h-\[.*?px\] bg-gradient-.*?pointer-events-none z-0"><\/div>',
    re.IGNORECASE
)
pattern2 = re.compile(
    r'\s+<div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-\[.*?px\] bg-gradient-.*?pointer-events-none z-0"><\/div>',
    re.IGNORECASE
)

# For courses/learn and courses/show, they use 50vh:
pattern3 = re.compile(
    r'\s+<div class="absolute top-0 left-0 w-full h-\[50vh\] bg-gradient-.*?pointer-events-none z-0"><\/div>',
    re.IGNORECASE
)

count = 0
for root, _, files in os.walk(dir_path):
    for filename in files:
        if filename.endswith('.blade.php'):
            filepath = os.path.join(root, filename)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            new_content = pattern1.sub('', content)
            new_content = pattern2.sub('', new_content)
            new_content = pattern3.sub('', new_content)
            
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                count += 1
                print(f'- Cleaned {os.path.basename(filepath)}')

print(f'\nTotal files cleaned: {count}')
