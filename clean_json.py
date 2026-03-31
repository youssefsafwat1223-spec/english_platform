import json
from pathlib import Path

def clean_json_duplicates(file_path):
    path = Path(file_path)
    if not path.exists():
        print(f"File {file_path} not found")
        return
    
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    try:
        # We can't use json.loads directly because it will just overwrite duplicates.
        # But that's actually what we want! Loads keeps the LAST key.
        data = json.loads(content)
        
        # Sort keys to make it clean
        sorted_data = dict(sorted(data.items()))
        
        with open(path, 'w', encoding='utf-8') as f:
            json.dump(sorted_data, f, ensure_ascii=False, indent=4)
        
        print(f"Successfully cleaned duplicates in {file_path}")
    except Exception as e:
        print(f"Error cleaning JSON: {e}")

if __name__ == "__main__":
    clean_json_duplicates("lang/ar.json")
