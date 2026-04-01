import json
import os

def clean_json(file_path):
    if not os.path.exists(file_path):
        print(f"File not found: {file_path}")
        return
    
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    try:
        # Load JSON into a dictionary (this naturally keeps only the last occurrence of duplicate keys)
        data = json.loads(content)
        
        # Sort keys to keep it organized (optional, but good for large files)
        # sorted_data = dict(sorted(data.items()))
        
        with open(file_path, 'w', encoding='utf-8') as f:
            json.dump(data, f, ensure_ascii=False, indent=4)
        print(f"Successfully cleaned {file_path}")
    except json.JSONDecodeError as e:
        print(f"Error decoding JSON in {file_path}: {e}")
        # Try to fix the common "missing comma" issue before the newly added block
        # This is a very basic fix for the specific mistake I made
        if "Expecting ',' delimiter" in str(e):
            print("Attempting to fix missing comma...")
            # Find the line with the error and add a comma to the previous one
            lines = content.split('\n')
            # Extract line number from error message if possible, or just look for the first line of my block
            for i in range(len(lines)):
                if '"About the Platform"' in lines[i] and i > 0 and not lines[i-1].strip().endswith(','):
                    lines[i-1] = lines[i-1].rstrip() + ','
            
            fixed_content = '\n'.join(lines)
            try:
                data = json.loads(fixed_content)
                with open(file_path, 'w', encoding='utf-8') as f:
                    json.dump(data, f, ensure_ascii=False, indent=4)
                print(f"Successfully fixed and cleaned {file_path}")
            except Exception as e2:
                print(f"Failed to fix automatically: {e2}")

if __name__ == "__main__":
    clean_json(r'd:\english-platform\english-platform\lang\en.json')
    clean_json(r'd:\english-platform\english-platform\lang\ar.json')
