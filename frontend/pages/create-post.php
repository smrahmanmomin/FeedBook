<?php
$basePath = defined('APP_BASE_PATH') ? APP_BASE_PATH : '';
if (!isset($_SESSION['user_id'])) { header('Location: ' . $basePath . '/login'); exit; }
$pageTitle = 'Create Post — FeedBook';
$currentPage = 'create-post';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="dashboard-layout">
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <div class="dashboard-main">
        <h1>Create New Post</h1>
        <div id="postMessage" class="alert hidden"></div>
        <form id="createPostForm" class="post-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" placeholder="An amazing title..." required>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id">
                    <option value="">Select a category</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Featured Image</label>
                <div class="file-upload" id="fileUploadArea">
                    <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(this)">
                    <div id="imagePreviewContainer" class="image-preview-container">
                        <p>📷 Click or drag to upload an image</p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="12" placeholder="Write your story..." required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-large">Publish Post</button>
                <a href="<?= $basePath ?>/dashboard" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
