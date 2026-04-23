// ============================================
// FeedBook — Complete JavaScript (app.js)
// ============================================

(() => {
    const BASE_PATH = (window.BASE_PATH || '').replace(/\/+$/, '');
    const withBase = (path = '') => `${BASE_PATH}${path.startsWith('/') ? path : `/${path}`}`;

    const API = {
        login:       withBase('/api/login'),
        register:    withBase('/api/register'),
        logout:      withBase('/api/logout'),
        me:          withBase('/api/me'),
        posts:       withBase('/api/posts'),
        createPost:  withBase('/api/create_post'),
        updatePost:  withBase('/api/update_post'),
        deletePost:  withBase('/api/delete_post'),
        categories:  withBase('/api/categories'),
        users:       withBase('/api/users'),
        search:      withBase('/api/search'),
        singlePost:  withBase('/api/single_post'),
    };

    // ---- Helpers ----
    function showMessage(id, msg, type = 'info') {
        const el = document.getElementById(id);
        if (!el) return;
        el.className = `alert alert-${type}`;
        el.textContent = msg;
        el.classList.remove('hidden');
        if (type === 'success') setTimeout(() => el.classList.add('hidden'), 4000);
    }

    function formatDate(d) {
        if (!d) return '';
        return new Date(d).toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });
    }

    function truncate(t, n = 150) { return (!t) ? '' : t.length > n ? t.substring(0, n) + '...' : t; }

    function esc(t) { if (!t) return ''; const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }

    // ---- Toast Notifications ----
    window.toast = function(msg, type = 'info') {
        const c = document.getElementById('toastContainer');
        if (!c) return;
        const t = document.createElement('div');
        t.className = `toast toast-${type}`;
        t.textContent = msg;
        c.appendChild(t);
        setTimeout(() => { t.classList.add('fade-out'); setTimeout(() => t.remove(), 300); }, 3500);
    };

    // ---- Image Preview ----
    window.previewImage = function(input) {
        const container = document.getElementById('imagePreviewContainer');
        if (!container || !input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => { container.innerHTML = `<img src="${e.target.result}" alt="Preview">`; };
        reader.readAsDataURL(input.files[0]);
    };

    // ---- Build Post Card HTML ----
    function postCardHTML(post, showActions = false) {
        const imgSrc = post.image ? withBase(`/uploads/${post.image}`) : '';
        const imgHtml = imgSrc
            ? `<img src="${imgSrc}" alt="${esc(post.title)}" class="post-card-image">`
            : `<div class="post-card-image placeholder">📄</div>`;
        return `
        <div class="post-card">
            <a href="${withBase(`/post/${post.id}`)}">${imgHtml}</a>
            <div class="post-card-body">
                ${post.category_name ? `<span class="post-category-badge">${esc(post.category_name)}</span>` : ''}
                <h3><a href="${withBase(`/post/${post.id}`)}">${esc(post.title)}</a></h3>
                <p>${esc(truncate(post.content))}</p>
            </div>
            <div class="post-card-meta">
                <span>By <span class="author">${esc(post.author || 'Unknown')}</span></span>
                <span>${formatDate(post.created_at)}</span>
            </div>
            ${showActions ? `
            <div class="post-card-actions">
                <a href="${withBase(`/edit-post?id=${post.id}`)}" class="btn btn-small btn-secondary">✏️ Edit</a>
                <button class="btn btn-small btn-danger" onclick="deletePost(${post.id})">🗑️ Delete</button>
            </div>` : ''}
        </div>`;
    }

    // ============================================
    // INIT
    // ============================================
    document.addEventListener('DOMContentLoaded', () => {

        // Login
        const loginForm = document.getElementById('loginForm');
        if (loginForm) loginForm.addEventListener('submit', handleLogin);

        // Register
        const registerForm = document.getElementById('registerForm');
        if (registerForm) registerForm.addEventListener('submit', handleRegister);

        // Home page posts
        const homePosts = document.getElementById('homePosts');
        if (homePosts) { loadHomePosts(); loadCategoryFilter(); }

        // Dashboard
        const dashPosts = document.getElementById('posts-container');
        if (dashPosts && document.querySelector('.dashboard-layout')) loadDashboardPosts();

        // Create Post
        const createForm = document.getElementById('createPostForm');
        if (createForm) { loadCategories('category_id'); createForm.addEventListener('submit', handleCreatePost); }

        // Edit Post
        const editForm = document.getElementById('editPostForm');
        if (editForm) { loadCategories('category_id'); loadPostForEditing(); editForm.addEventListener('submit', handleUpdatePost); }

        // Profile
        const profForm = document.getElementById('profileForm');
        if (profForm) { profForm.addEventListener('submit', handleUpdateProfile); loadMyPosts(); }

        // Admin
        const usersT = document.getElementById('users-table');
        if (usersT && document.querySelector('.dashboard-layout')) loadUsers();
        const categoryForm = document.getElementById('categoryForm');
        const categoriesTable = document.getElementById('categories-table');
        if (categoryForm && categoriesTable) {
            categoryForm.addEventListener('submit', handleCreateCategory);
            loadAdminCategories();
        }

        // Single Post
        const singlePost = document.getElementById('singlePost');
        if (singlePost) loadSinglePost();

        // Sidebar categories
        loadSidebarCategories();

        // Close search overlay on click outside
        const overlay = document.getElementById('searchResults');
        if (overlay) overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.add('hidden'); });
        
        // Load current user info for comments
        fetch(API.me).then(r => r.json()).then(d => {
            if (d.id) { window.currentUserId = d.id; window.isAdmin = d.role === 'admin'; }
        }).catch(() => {});
    });

    // ============================================
    // LOGIN
    // ============================================
    async function handleLogin(e) {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        const email = e.target.querySelector('[name="email"]').value.trim();
        const password = e.target.querySelector('[name="password"]').value;
        if (!email || !password) { showMessage('loginMessage', 'Please fill in all fields', 'danger'); return; }
        btn.disabled = true; btn.textContent = 'Signing in...';
        try {
            const r = await fetch(API.login, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({email,password}) });
            const d = await r.json();
            if (d.success) { showMessage('loginMessage', 'Success! Redirecting...', 'success'); setTimeout(()=>location.href=withBase('/dashboard'),800); }
            else { showMessage('loginMessage', d.message||'Login failed', 'danger'); btn.disabled=false; btn.textContent='Sign In'; }
        } catch(err) { showMessage('loginMessage','Network error','danger'); btn.disabled=false; btn.textContent='Sign In'; }
    }

    // ============================================
    // REGISTER
    // ============================================
    async function handleRegister(e) {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        const name = e.target.querySelector('[name="name"]').value.trim();
        const email = e.target.querySelector('[name="email"]').value.trim();
        const password = e.target.querySelector('[name="password"]').value;
        const confirm_password = e.target.querySelector('[name="confirm_password"]').value;
        if (!name||!email||!password) { showMessage('registerMessage','All fields are required','danger'); return; }
        if (password.length<6) { showMessage('registerMessage','Password must be at least 6 characters','danger'); return; }
        if (password!==confirm_password) { showMessage('registerMessage','Passwords do not match','danger'); return; }
        btn.disabled=true; btn.textContent='Creating account...';
        try {
            const r = await fetch(API.register, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({name,email,password,confirm_password}) });
            const d = await r.json();
            if (d.success) { showMessage('registerMessage','Account created! Redirecting to login...','success'); setTimeout(()=>location.href=withBase('/login'),1500); }
            else { showMessage('registerMessage', d.message||'Registration failed','danger'); btn.disabled=false; btn.textContent='Create Account'; }
        } catch(err) { showMessage('registerMessage','Network error','danger'); btn.disabled=false; btn.textContent='Create Account'; }
    }

    // ============================================
    // HOME POSTS (public)
    // ============================================
    async function loadHomePosts(categoryId = null) {
        const c = document.getElementById('homePosts');
        if (!c) return;
        c.innerHTML = '<div class="loading-spinner">Loading posts...</div>';
        try {
            let url = API.posts;
            if (categoryId) url += '?category_id=' + categoryId;
            const r = await fetch(url);
            const d = await r.json();
            const posts = d.posts || [];
            if (!posts.length) { c.innerHTML = '<div class="no-posts"><h3>No posts yet</h3><p>Be the first to share a story!</p></div>'; return; }
            c.innerHTML = posts.map(p => postCardHTML(p)).join('');
        } catch(err) { c.innerHTML = `<div class="no-posts"><h3>Could not load posts</h3><p>Visit <a href="${withBase('/setup.php')}">setup.php</a> first.</p></div>`; }
    }

    // ============================================
    // CATEGORY FILTER (homepage)
    // ============================================
    async function loadCategoryFilter() {
        const c = document.getElementById('categoryFilter');
        if (!c) return;
        try {
            const r = await fetch(API.categories);
            const d = await r.json();
            (d.categories||[]).forEach(cat => {
                const btn = document.createElement('button');
                btn.className = 'cat-btn';
                btn.textContent = cat.name;
                btn.setAttribute('data-cat', cat.id);
                c.appendChild(btn);
            });
            c.addEventListener('click', e => {
                if (!e.target.classList.contains('cat-btn')) return;
                c.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                const catId = e.target.getAttribute('data-cat');
                loadHomePosts(catId || null);
            });
        } catch(err) { /* silent */ }
    }

    // ============================================
    // DASHBOARD POSTS (user's own)
    // ============================================
    async function loadDashboardPosts() {
        const c = document.getElementById('posts-container');
        if (!c) return;
        try {
            const r = await fetch(API.posts);
            const d = await r.json();
            const mr = await fetch(API.me);
            const md = await mr.json();
            const my = (d.posts||[]).filter(p => p.user_id == md.id);
            if (!my.length) {
                c.innerHTML = `<div class="no-posts"><h3>No posts yet</h3><p>Start writing today!</p><a href="${withBase('/create-post')}" class="btn btn-primary" style="margin-top:1rem">Create Your First Post</a></div>`;
                return;
            }
            c.innerHTML = my.map(p => postCardHTML(p, true)).join('');
        } catch(err) { c.innerHTML = '<div class="no-posts"><h3>Could not load posts</h3></div>'; }
    }

    // ============================================
    // CREATE POST (with FormData for image)
    // ============================================
    async function handleCreatePost(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        if (!formData.get('title')?.trim() || !formData.get('content')?.trim()) {
            showMessage('postMessage','Title and content are required','danger'); return;
        }
        try {
            const r = await fetch(API.createPost, { method:'POST', body:formData });
            const d = await r.json();
            if (d.success) { toast('Post published! 🎉','success'); setTimeout(()=>location.href=withBase('/dashboard'),800); }
            else showMessage('postMessage', d.message||'Failed','danger');
        } catch(err) { showMessage('postMessage','Network error','danger'); }
    }

    // ============================================
    // EDIT POST
    // ============================================
    async function loadPostForEditing() {
        const id = new URLSearchParams(location.search).get('id');
        if (!id) { showMessage('editMessage','No post ID','danger'); return; }
        document.getElementById('post_id').value = id;
        try {
            const r = await fetch(API.singlePost + '?id=' + id);
            const post = await r.json();
            if (!post || post.error) { showMessage('editMessage','Post not found','danger'); return; }
            document.getElementById('title').value = post.title;
            document.getElementById('content').value = post.content;
            if (post.image) {
                const preview = document.getElementById('imagePreviewContainer');
                if (preview) preview.innerHTML = `<img src="${withBase(`/uploads/${post.image}`)}" alt="Current image">`;
            }
            setTimeout(() => { const s = document.getElementById('category_id'); if (s && post.category_id) s.value = post.category_id; }, 500);
        } catch(err) { showMessage('editMessage','Failed to load post','danger'); }
    }

    async function handleUpdatePost(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        if (!formData.get('title')?.trim() || !formData.get('content')?.trim()) {
            showMessage('editMessage','Title and content are required','danger'); return;
        }
        try {
            const r = await fetch(API.updatePost, { method:'POST', body:formData });
            const d = await r.json();
            if (d.success) { toast('Post updated! ✅','success'); setTimeout(()=>location.href=withBase('/dashboard'),800); }
            else showMessage('editMessage', d.message||'Failed','danger');
        } catch(err) { showMessage('editMessage','Network error','danger'); }
    }

    // ============================================
    // DELETE POST
    // ============================================
    window.deletePost = async function(postId) {
        if (!confirm('Are you sure you want to delete this post?')) return;
        try {
            const r = await fetch(API.deletePost, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({id:postId}) });
            const d = await r.json();
            if (d.success) { toast('Post deleted','success'); if (document.getElementById('posts-container')) loadDashboardPosts(); else if (document.getElementById('my-posts')) loadMyPosts(); }
            else toast(d.message||'Failed to delete','error');
        } catch(err) { toast('Network error','error'); }
    };

    // ============================================
    // MY POSTS (profile)
    // ============================================
    async function loadMyPosts() {
        const c = document.getElementById('my-posts');
        if (!c) return;
        try {
            const r = await fetch(API.posts);
            const d = await r.json();
            const mr = await fetch(API.me);
            const md = await mr.json();
            const my = (d.posts||[]).filter(p => p.user_id == md.id);
            if (!my.length) { c.innerHTML = `<div class="no-posts"><h3>No posts yet</h3><a href="${withBase('/create-post')}" class="btn btn-primary" style="margin-top:1rem">Write Your First Post</a></div>`; return; }
            c.innerHTML = my.map(p => postCardHTML(p, true)).join('');
        } catch(err) { c.innerHTML = '<div class="no-posts"><h3>Could not load posts</h3></div>'; }
    }

    // ============================================
    // PROFILE UPDATE
    // ============================================
    async function handleUpdateProfile(e) {
        e.preventDefault();
        const name = e.target.querySelector('[name="name"]').value.trim();
        const email = e.target.querySelector('[name="email"]').value.trim();
        if (!name||!email) { showMessage('profileMessage','Name and email required','danger'); return; }
        try {
            const r = await fetch(API.me, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({name,email}) });
            const d = await r.json();
            if (d.success) { toast('Profile updated! ✅','success'); setTimeout(()=>location.reload(),1000); }
            else showMessage('profileMessage', d.message||'Failed','danger');
        } catch(err) { showMessage('profileMessage','Network error','danger'); }
    }

    // ============================================
    // CATEGORIES
    // ============================================
    async function loadCategories(selectId) {
        const s = document.getElementById(selectId);
        if (!s) return;
        try {
            const r = await fetch(API.categories);
            const d = await r.json();
            (d.categories||[]).forEach(cat => {
                const o = document.createElement('option');
                o.value = cat.id; o.textContent = cat.name;
                s.appendChild(o);
            });
        } catch(err) { /* silent */ }
    }

    async function loadSidebarCategories() {
        const c = document.getElementById('sidebar-categories');
        if (!c) return;
        try {
            const r = await fetch(API.categories);
            const d = await r.json();
            c.innerHTML = (d.categories||[]).map(cat => `<a class="sidebar-cat-link" href="${withBase(`/?cat=${cat.id}`)}">${esc(cat.name)}</a>`).join('');
        } catch(err) { /* silent */ }
    }

    // ============================================
    // SEARCH
    // ============================================
    window.handleSearch = async function(e) {
        e.preventDefault();
        const q = document.getElementById('searchInput').value.trim();
        if (!q) return;
        const overlay = document.getElementById('searchResults');
        if (!overlay) return;
        overlay.classList.remove('hidden');
        overlay.innerHTML = '<div class="search-results-inner"><div class="loading-spinner">Searching...</div></div>';
        try {
            const r = await fetch(API.search + '?q=' + encodeURIComponent(q));
            const d = await r.json();
            const posts = d.posts || [];
            const inner = overlay.querySelector('.search-results-inner') || overlay;
            if (!posts.length) { inner.innerHTML = `<h3>No results for "${esc(q)}"</h3><p style="color:var(--gray-400)">Try a different search term.</p>`; return; }
            inner.innerHTML = `<h3>${posts.length} result${posts.length>1?'s':''} for "${esc(q)}"</h3>`
                + posts.map(p => `<div class="search-result-item"><h3><a href="${withBase(`/post/${p.id}`)}">${esc(p.title)}</a></h3><p>${esc(truncate(p.content,100))} · By ${esc(p.author)} · ${formatDate(p.created_at)}</p></div>`).join('');
        } catch(err) { overlay.innerHTML = '<div class="search-results-inner"><h3>Search failed</h3></div>'; }
    };

    // ============================================
    // SINGLE POST
    // ============================================
    async function loadSinglePost() {
        const c = document.getElementById('singlePost');
        if (!c) return;
        const path = location.pathname;
        const match = path.match(/\/post\/(\d+)/);
        if (!match) { c.innerHTML = '<h2>Post not found</h2>'; return; }
        const postId = match[1];
        try {
            const r = await fetch(API.singlePost + '?id=' + postId);
            const p = await r.json();
            if (p.error) { c.innerHTML = `<h2>${p.error}</h2><a href="${withBase('/')}" class="btn btn-primary" style="margin-top:1rem">← Back Home</a>`; return; }
            c.innerHTML = `
                <div class="single-post-header">
                    ${p.category_name ? `<span class="post-category-badge">${esc(p.category_name)}</span>` : ''}
                    <h1>${esc(p.title)}</h1>
                    <div class="single-post-meta">
                        <span class="author-name">${esc(p.author)}</span>
                        <span>·</span><span>${formatDate(p.created_at)}</span>
                        ${p.updated_at && p.updated_at !== p.created_at ? `<span>· Updated ${formatDate(p.updated_at)}</span>` : ''}
                    </div>
                </div>
                ${p.image ? `<img src="${withBase(`/uploads/${p.image}`)}" alt="${esc(p.title)}" class="single-post-image">` : ''}
                <div class="single-post-content">${esc(p.content).replace(/\n/g,'<br>')}</div>
                <div class="single-post-back"><a href="${withBase('/')}" class="btn btn-secondary">← Back to Posts</a></div>`;
            window.currentPostId = postId;
            loadComments(postId);
        } catch(err) { c.innerHTML = '<h2>Failed to load post</h2>'; }
    }

    // ============================================
    // COMMENTS
    // ============================================
    async function loadComments(postId) {
        const section = document.getElementById('commentsSection');
        if (!section) return;
        
        // Check if user is logged in to show comment form
        try {
            const mr = await fetch(API.me);
            const md = await mr.json();
            if (md.id) {
                document.getElementById('commentForm').style.display = 'block';
            }
        } catch(err) {
            document.getElementById('commentForm').style.display = 'none';
        }

        try {
            const r = await fetch(withBase(`/api/comments?action=get&post_id=${postId}`));
            if (!r.ok) {
                document.getElementById('commentsList').innerHTML = '<p>Could not load comments (HTTP ' + r.status + ')</p>';
                section.style.display = 'block';
                return;
            }
            
            const d = await r.json();
            if (!d || d.success === false) { 
                document.getElementById('commentsList').innerHTML = '<p>' + (d?.message || 'Could not load comments') + '</p>'; 
                section.style.display = 'block';
                return; 
            }
            
            const comments = d.comments || [];
            const commentsList = document.getElementById('commentsList');
            
            if (!comments.length) {
                commentsList.innerHTML = '<p class="no-comments">No comments yet. Be the first to share your thoughts!</p>';
                section.style.display = 'block';
                return;
            }

            commentsList.innerHTML = comments.map(c => `
                <div class="comment-item">
                    <div class="comment-header">
                        <strong>${esc(c.author || 'Anonymous')}</strong>
                        <span class="comment-date">${formatDate(c.created_at)}</span>
                        ${(window.currentUserId && window.currentUserId == c.user_id) || window.isAdmin ? `
                            <button class="btn btn-small btn-danger" onclick="deleteComment(${c.id}, ${postId})" title="Delete comment">✕</button>
                        ` : ''}
                    </div>
                    <div class="comment-content">${esc(c.content).replace(/\n/g,'<br>')}</div>
                </div>
            `).join('');
            section.style.display = 'block';
        } catch(err) { 
            console.error('Comment loading error:', err);
            document.getElementById('commentsList').innerHTML = '<p>Error: ' + err.message + '</p>';
            section.style.display = 'block';
        }
    }

    window.handleAddComment = async function(e) {
        e.preventDefault();
        const postId = window.currentPostId;
        const content = document.getElementById('commentContent').value.trim();
        
        if (!content) {
            toast('Comment cannot be empty', 'error');
            return;
        }

        try {
            const r = await fetch(withBase('/api/comments?action=create'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ post_id: postId, content })
            });
            const d = await r.json();
            if (d.success) {
                document.getElementById('commentContent').value = '';
                toast('Comment posted! 💬', 'success');
                loadComments(postId);
            } else {
                toast(d.message || 'Failed to post comment', 'error');
            }
        } catch(err) {
            toast('Network error', 'error');
        }
    };

    window.deleteComment = async function(commentId, postId) {
        if (!confirm('Delete this comment?')) return;
        try {
            const r = await fetch(withBase('/api/comments?action=delete'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: commentId })
            });
            const d = await r.json();
            if (d.success) {
                toast('Comment deleted', 'success');
                loadComments(postId);
            } else {
                toast(d.message || 'Failed to delete comment', 'error');
            }
        } catch(err) {
            toast('Network error', 'error');
        }
    };

    // ============================================
    // ADMIN USERS
    // ============================================
    async function loadUsers() {
        const c = document.getElementById('users-table');
        if (!c) return;
        try {
            const r = await fetch(API.users);
            const d = await r.json();
            const users = d.users || [];
            if (!users.length) { c.innerHTML = '<div class="no-posts"><h3>No users</h3></div>'; return; }
            c.innerHTML = `<table class="data-table"><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead><tbody>`
                + users.map(u => `<tr><td>${u.id}</td><td><strong>${esc(u.name)}</strong></td><td>${esc(u.email)}</td><td><span class="role-badge ${u.role}">${u.role}</span></td><td>${formatDate(u.created_at)}</td><td>${u.role!=='admin'?`<button class="btn btn-small btn-danger" onclick="deleteUser(${u.id})">Delete</button>`:''}</td></tr>`).join('')
                + '</tbody></table>';
        } catch(err) { c.innerHTML = '<div class="no-posts"><h3>Could not load users</h3></div>'; }
    }

    window.deleteUser = async function(id) {
        if (!confirm('Delete this user and all their posts?')) return;
        try {
            const r = await fetch(API.users, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({id}) });
            const d = await r.json();
            if (d.success) { toast('User deleted','success'); loadUsers(); }
            else toast('Failed','error');
        } catch(err) { toast('Network error','error'); }
    };

    // ============================================
    // ADMIN CATEGORIES
    // ============================================
    async function loadAdminCategories() {
        const c = document.getElementById('categories-table');
        if (!c) return;
        try {
            const r = await fetch(API.categories);
            const d = await r.json();
            const categories = d.categories || [];
            if (!categories.length) {
                c.innerHTML = '<div class="no-posts"><h3>No categories yet</h3></div>';
                return;
            }

            c.innerHTML = `<table class="data-table"><thead><tr><th>ID</th><th>Name</th><th>Created</th><th>Actions</th></tr></thead><tbody>`
                + categories.map(cat => `<tr><td>${cat.id}</td><td><strong>${esc(cat.name)}</strong></td><td>${formatDate(cat.created_at)}</td><td><button class="btn btn-small btn-danger" onclick='deleteCategory(${cat.id}, ${JSON.stringify(cat.name)})'>Delete</button></td></tr>`).join('')
                + '</tbody></table>';
        } catch(err) {
            c.innerHTML = '<div class="no-posts"><h3>Could not load categories</h3></div>';
        }
    }

    async function handleCreateCategory(e) {
        e.preventDefault();
        const input = document.getElementById('categoryName');
        if (!input) return;
        const name = input.value.trim();
        if (!name) {
            showMessage('adminMessage', 'Category name is required', 'danger');
            return;
        }

        try {
            const r = await fetch(API.categories, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'create', name })
            });
            const d = await r.json();
            if (d.success) {
                input.value = '';
                showMessage('adminMessage', d.message || 'Category created', 'success');
                loadAdminCategories();
                loadSidebarCategories();
                toast('Category added', 'success');
            } else {
                showMessage('adminMessage', d.message || 'Failed to create category', 'danger');
            }
        } catch(err) {
            showMessage('adminMessage', 'Network error', 'danger');
        }
    }

    window.deleteCategory = async function(id, name) {
        if (!confirm(`Delete category "${name}"? Posts under this category will become uncategorized.`)) return;
        try {
            const r = await fetch(API.categories, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete', id })
            });
            const d = await r.json();
            if (d.success) {
                showMessage('adminMessage', d.message || 'Category deleted', 'success');
                loadAdminCategories();
                loadSidebarCategories();
                toast('Category deleted', 'success');
            } else {
                showMessage('adminMessage', d.message || 'Failed to delete category', 'danger');
            }
        } catch(err) {
            showMessage('adminMessage', 'Network error', 'danger');
        }
    };

})();
