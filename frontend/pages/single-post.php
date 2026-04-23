<?php
$pageTitle = 'Article — FeedBook';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<article class="single-post" id="singlePost">
    <div class="loading-spinner">Loading article...</div>
</article>

<section class="comments-section" id="commentsSection" style="display:none;">
    <div class="comments-container">
        <h3>Comments</h3>
        
        <div id="commentForm" style="display:none;">
            <form id="addCommentForm" onsubmit="handleAddComment(event)">
                <textarea 
                    id="commentContent" 
                    name="content" 
                    placeholder="Share your thoughts..." 
                    required 
                    minlength="1" 
                    maxlength="5000"
                    rows="4"></textarea>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        </div>

        <div id="commentsList">
            <div class="loading-spinner">Loading comments...</div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../components/footer.php'; ?>
