<?php
if (!isset($_SESSION['user_id'])) { header('Location: /feedbook/login'); exit; }
$pageTitle = 'Edit Post — FeedBook';
$currentPage = 'edit-post';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="dashboard-layout">
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <div class="dashboard-main">
        <h1>Edit Post</h1>
        <div id="editMessage" class="alert hidden"></div>
        <form id="editPostForm" class="post-form" enctype="multipart/form-data">
            <input type="hidden" name="id" id="post_id">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" required>
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
                        <p>📷 Upload a new image (optional)</p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="12" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-large">Update Post</button>
                <a href="/feedbook/dashboard" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
