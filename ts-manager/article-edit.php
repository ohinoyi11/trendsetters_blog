<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();

$admin      = current_admin();
$categories = load_categories();
$error      = '';
$isEdit     = false;

$article = [
    'id'        => '',
    'title'     => '',
    'excerpt'   => '',
    'content'   => '',
    'image'     => '',
    'category'  => $categories[0] ?? '',
    'author_id' => $admin['id'], // Default to current user
    'author_name' => $admin['name'],
    'date'      => date('M j, Y'),
    'read_time' => '5 min read',
    'featured'  => false,
    'trending'  => false,
    'views'     => 0,
    'comment_count' => 0,
];

if (isset($_GET['id'])) {
    $found = get_article((int)$_GET['id']);
    if ($found) {
        $article = $found;
        $isEdit  = true;
        // Access control: Correspondents can only edit their own articles
        if (!is_super_admin() && $article['author_id'] != $admin['id']) {
            header('Location: articles.php'); exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle image upload
    $uploadedPath = handle_image_upload('image_file');
    $imagePath    = $uploadedPath ?? trim($_POST['image'] ?? $article['image']);

    // Data construction
    $saveData = [
        'id'            => $isEdit ? $article['id'] : null,
        'title'         => trim($_POST['title'] ?? ''),
        'excerpt'       => trim($_POST['excerpt'] ?? ''),
        'content'       => $_POST['content'] ?? '',
        'image'         => $imagePath,
        'category'      => $_POST['category'] ?? ($categories[0] ?? ''),
        'author_id'     => is_super_admin() ? (int)($_POST['author_id'] ?? $admin['id']) : $admin['id'],
        'date'          => trim($_POST['date'] ?? date('M j, Y')),
        'read_time'     => trim($_POST['read_time'] ?? '5 min read'),
        'featured'      => isset($_POST['featured']),
        'trending'      => isset($_POST['trending']),
        'views'         => (int)($_POST['views'] ?? 0),
        'comment_count' => (int)($_POST['comment_count'] ?? 0),
    ];

    if (empty($saveData['title'])) {
        $error = 'Title is required.';
    } elseif (empty($saveData['excerpt'])) {
        $error = 'Excerpt is required.';
    } else {
        save_article($saveData, $isEdit);
        header('Location: articles.php?flash=saved'); exit;
    }
}

$pageTitle  = $isEdit ? 'Edit Article' : 'New Article';
$activePage = 'articles';
include __DIR__ . '/layout.php';

// If super admin, load all potential authors for the dropdown
$authors = [];
if (is_super_admin()) {
    $authors = get_pdo()->query("SELECT id, name FROM users WHERE role IN ('super_admin','correspondent') ORDER BY name")->fetchAll();
}
?>

<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
  .ql-container { font-family: 'Inter', sans-serif; font-size: 15px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; }
  .ql-toolbar { border-top-left-radius: 10px; border-top-right-radius: 10px; background: #f8fafc; }
  .ql-editor { min-height: 280px; line-height: 1.75; }
  .img-preview { max-height: 180px; border-radius: 12px; object-fit: cover; border: 2px solid #e0e7ff; }
  .upload-zone { border: 2px dashed #c7d2fe; border-radius: 12px; padding: 24px; text-align: center; cursor: pointer; transition: all .2s; }
  .upload-zone:hover { border-color: #4f46e5; background: #eef2ff; }
</style>

<div style="max-width:820px">
  <div class="mb-6 flex items-center gap-3">
    <a href="articles.php" class="btn-secondary py-2">← Back to Articles</a>
    <?php if ($isEdit): ?>
      <a href="../article/<?= (int)$article['id'] ?>" target="_blank" class="btn-secondary py-2">👁️ Preview</a>
    <?php endif; ?>
  </div>

  <?php if ($error): ?>
    <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-5 py-3 text-sm font-medium text-red-700">⚠️ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="space-y-6">

    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
      <label class="form-label">Title *</label>
      <input type="text" name="title" required class="form-input text-lg font-semibold"
             value="<?= htmlspecialchars($article['title']) ?>" placeholder="Heading…"/>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
      <label class="form-label">Excerpt / Summary *</label>
      <textarea name="excerpt" class="form-textarea" rows="3"><?= htmlspecialchars($article['excerpt']) ?></textarea>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
      <label class="form-label">Featured Image</label>
      <div class="upload-zone" onclick="document.getElementById('image_file').click()">
        <?php if (!empty($article['image'])): ?>
          <img id="img-preview" src="../<?= htmlspecialchars($article['image']) ?>" class="img-preview mx-auto mb-3"/>
        <?php else: ?>
          <div id="preview-wrap">
            <p class="text-3xl mb-2">📷</p>
            <p class="text-sm font-semibold text-gray-600">Click to upload image</p>
          </div>
        <?php endif; ?>
      </div>
      <input type="file" name="image_file" id="image_file" accept="image/*" class="hidden" onchange="previewImage(this)"/>
      <div class="mt-3">
        <input type="text" name="image" id="image-path" class="form-input text-xs"
               value="<?= htmlspecialchars($article['image']) ?>" placeholder="assets/uploads/example.jpg"/>
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
      <label class="form-label mb-3">Content</label>
      <div id="quill-editor"><?= $article['content'] ?></div>
      <input type="hidden" name="content" id="content-hidden"/>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
      <h3 class="font-display text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Meta</h3>
      <div class="grid grid-cols-2 gap-5 mb-5">
        <div>
          <label class="form-label">Category</label>
          <select name="category" class="form-input">
            <?php foreach ($categories as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>" <?= $article['category']===$c?'selected':'' ?>><?= htmlspecialchars($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="form-label">Author</label>
          <?php if (is_super_admin()): ?>
            <select name="author_id" class="form-input">
              <?php foreach ($authors as $auth): ?>
                <option value="<?= $auth['id'] ?>" <?= $article['author_id']==$auth['id']?'selected':'' ?>><?= htmlspecialchars($auth['name']) ?></option>
              <?php endforeach; ?>
            </select>
          <?php else: ?>
            <input type="text" class="form-input bg-slate-50" value="<?= htmlspecialchars($admin['name']) ?>" disabled/>
            <input type="hidden" name="author_id" value="<?= $admin['id'] ?>"/>
          <?php endif; ?>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-5 mb-5">
        <div>
          <label class="form-label">Display Date</label>
          <input type="text" name="date" class="form-input" value="<?= htmlspecialchars($article['date']) ?>"/>
        </div>
        <div>
          <label class="form-label">Read Time</label>
          <input type="text" name="read_time" class="form-input" value="<?= htmlspecialchars($article['read_time']) ?>"/>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm flex gap-10">
      <label class="flex items-center gap-3 cursor-pointer">
        <input type="checkbox" name="featured" class="h-5 w-5 rounded accent-indigo-600" <?= !empty($article['featured'])?'checked':'' ?>>
        <span class="text-sm font-bold italic">Featured</span>
      </label>
      <label class="flex items-center gap-3 cursor-pointer">
        <input type="checkbox" name="trending" class="h-5 w-5 rounded accent-indigo-600" <?= !empty($article['trending'])?'checked':'' ?>>
        <span class="text-sm font-bold italic">Trending</span>
      </label>
    </div>

    <div class="sticky bottom-4 z-10">
      <div class="bg-white/90 backdrop-blur border border-slate-200 rounded-2xl px-6 py-4 shadow-lg flex items-center justify-between">
        <button type="submit" class="btn-primary w-full"><?= $isEdit ? 'Save Changes' : 'Publish Article' ?></button>
      </div>
    </div>

  </form>
</div>

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
  const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: { toolbar: [ [{header:[1,2,false]}], ['bold','italic','underline'], ['link','image','blockquote','code-block'], [{list:'ordered'},{list:'bullet'}], ['clean'] ] }
  });
  document.querySelector('form').onsubmit = () => {
    document.getElementById('content-hidden').value = quill.root.innerHTML;
  };
  function previewImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        let prev = document.getElementById('img-preview');
        if (!prev) {
          prev = document.createElement('img');
          prev.id = 'img-preview';
          prev.className = 'img-preview mx-auto mb-3';
          document.getElementById('preview-wrap').replaceWith(prev);
        }
        prev.src = e.target.result;
        document.getElementById('image-path').value = '';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>

<?php include __DIR__ . '/layout-footer.php'; ?>
