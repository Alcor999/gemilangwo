import os
import re

dir_paths = ['resources/views/customer', 'resources/views/owner']

def process_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Skip files that already use "Playfair Display" to prevent double-processing
    if "Playfair Display" in content and not filepath.endswith('dashboard.blade.php'):
        print(f"Skipping (Already processed): {filepath}")
        return

    original_content = content

    # 1. Update Top-level Header wrapper (d-flex mb-4 to include border bottom)
    content = re.sub(
        r'<div class="d-flex justify-content-between align-items-center mb-4">',
        r'<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light">',
        content
    )

    # 2. Update all H1 tags inside the container (page titles)
    content = re.sub(
        r'<h1>(.*?)</h1>',
        r'<h1 class="mb-1" style="font-family: \'Playfair Display\', serif; font-size: 2rem; font-weight: 600; color: var(--text-dark);">\1</h1>',
        content
    )

    # 3. Card Headers
    content = re.sub(
        r'<div class="card-header bg-light">',
        r'<div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">',
        content
    )
    
    # Update nested h5 tag right after card-header or inside cards
    content = re.sub(
        r'<h5 class="mb-0">(.*?)</h5>',
        r'<h5 class="mb-0 fw-bold" style="font-family: \'Playfair Display\', serif;">\1</h5>',
        content
    )

    # 4. Tables styling
    content = re.sub(
        r'<table class="table">',
        r'<table class="table table-hover align-middle mb-0">',
        content
    )
    
    # Try to make th inside thead more elegant
    # We will regex to add style="background: rgba(0,0,0,0.02);" to thead if it isn't styled
    content = re.sub(
        r'<thead>',
        r'<thead style="background: rgba(0,0,0,0.02);">',
        content
    )
    
    # Style standard btn inside tables if any 
    # Example: btn-warning -> btn-light btn-sm rounded-circle etc? Let's leave standard buttons as is since CSS is doing the job.

    # 5. Fix card-body padding when there's a table inside it directly
    # A bit complex via simple regex, we can just let global styling handle card-body. Or we add .p-0 to card-body if it contains table-responsive.
    if '<div class="table-responsive">' in content and filepath.endswith('index.blade.php'):
        # For index views, if there's a table-responsive, make the card if any look better, though most index pages don't have card wrappers... Wait, package/index.blade.php didn't have <div class=card>, it was directly inside container-fluid!
        pass

    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated: {filepath}")

dir_paths = ['resources/views/customer', 'resources/views/owner']

for dir_path in dir_paths:
    if os.path.exists(dir_path):
        for root, dirs, files in os.walk(dir_path):
            for file in files:
                if file.endswith('.blade.php') and file != 'dashboard.blade.php':
                    process_file(os.path.join(root, file))
