<?php
$pageTitle = 'Educational Resources - FoodFusion';
include 'includes/header.php';
?>

<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-100 to-teal-100 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Educational 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-green-600">
                    Resources
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Expand your culinary knowledge with our comprehensive collection of educational materials, 
                guides, and learning resources
            </p>
        </div>
    </section>

    

    <!-- Statistics -->
    <section class="py-10 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="resStats" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4"></div>
        </div>
    </section>

    

    

    <!-- All Resources -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters Toolbar -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <input id="resSearch" type="text" placeholder="Search resources..."
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <select id="resType" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="all">All Types</option>
                            <option value="document">Document</option>
                            <option value="infographic">Infographic</option>
                            <option value="video">Video</option>
                            <option value="presentation">Presentation</option>
                            <option value="guide">Guide</option>
                        </select>
                    </div>
                    <div>
                        <select id="resSort" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="newest">Sort: Newest</option>
                            <option value="popular">Sort: Most Popular</option>
                            <option value="title">Sort: Title A-Z</option>
                        </select>
                    </div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="md:justify-self-end">
                        <button id="openUploadModal" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium">
                            <i class="fas fa-upload mr-2"></i>Upload Resource
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex items-end justify-between mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-1">All Educational Resources</h2>
                    <p class="text-gray-600 text-sm">Showing <span id="resCount">0</span> resources</p>
                </div>
            </div>

            <div id="resourcesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

            <div id="noResults" class="text-center py-12 hidden">
                <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No resources found</h3>
                <p class="text-gray-600">Try adjusting your filters or search.</p>
            </div>

            <div id="pagination" class="flex justify-center mt-8 hidden">
                <div class="flex items-center gap-2">
                    <button id="prevPage" class="px-4 py-2 border border-gray-300 rounded-md text-sm disabled:opacity-50">Previous</button>
                    <span id="pageInfo" class="px-4 py-2 text-sm text-gray-600">Page 1 of 1</span>
                    <button id="nextPage" class="px-4 py-2 border border-gray-300 rounded-md text-sm disabled:opacity-50">Next</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Upload Modal -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="bg-white rounded-lg w-full max-w-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Upload Educational Resource</h3>
                    <button id="closeUploadModal" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <form id="uploadForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" id="uplTitle" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea id="uplDesc" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select id="uplType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="document">Document</option>
                            <option value="infographic">Infographic</option>
                            <option value="video">Video</option>
                            <option value="presentation">Presentation</option>
                            <option value="guide">Guide</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File *</label>
                        <input type="file" id="uplFile" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <p class="text-xs text-gray-500 mt-1">Max 50MB. Allowed: pdf, doc, docx, ppt, pptx, txt, jpg, png, gif, mp4, avi, mov</p>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" id="uplSubmit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            <span id="uplSubmitText">Upload</span>
                            <span id="uplSubmitting" class="hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Uploading...</span>
                        </button>
                        <button type="button" id="uplCancel" class="flex-1 border border-gray-300 px-4 py-2 rounded-md">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- CTA Section -->
    <section class="py-16 bg-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Start Your Learning Journey</h2>
            <p class="text-xl text-green-100 mb-8 max-w-3xl mx-auto">
                Join thousands of learners who are already expanding their culinary knowledge with our educational resources.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="index.php?page=register" class="bg-white hover:bg-gray-100 text-green-600 px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    Join FoodFusion
                </a>
                <a href="index.php?page=cooking-tips" class="bg-green-700 hover:bg-green-800 text-white px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center border-2 border-white">
                    <i class="fas fa-lightbulb mr-2"></i>
                    Browse Tips
                </a>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
<script>
// Demo data - can be replaced by server data later
// API-backed; no local dataset
const ALL_RESOURCES = [];

// Helpers
function iconByType(t){
    switch(t){
        case 'video': return 'fa-video';
        case 'guide': return 'fa-book-open';
        case 'presentation': return 'fa-chalkboard';
        case 'infographic': return 'fa-image';
        default: return 'fa-file-alt';
    }
}
function formatDate(iso){ try { const d=new Date(iso); return d.toLocaleDateString(); } catch { return ''; } }
function fileExt(path){ const m=(path||'').match(/\.([a-z0-9]+)$/i); return m?m[1]:'file'; }

function resourceCard(res) {
    return `
<article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
  <div class="h-48 bg-gradient-to-br from-green-100 to-blue-100 flex items-center justify-center"><i class="fas ${iconByType(res.type)} text-green-600 text-4xl"></i></div>
  <div class="p-5">
    <div class="flex items-center mb-2">
      <span class="px-2 py-1 text-xs font-medium rounded-full ${typePill(res.type)}">${titleCase(res.type)}</span>
      <span class="text-sm text-gray-500 ml-auto">${formatDate(res.created_at)}</span>
    </div>
    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">${escapeHtml(res.title)}</h3>
    <p class="text-gray-600 text-sm mb-3 line-clamp-2">${escapeHtml(res.description)}</p>
    <div class="flex items-center justify-between text-sm text-gray-500">
      <div class="flex items-center space-x-2">
        <span><i class="fas fa-download mr-1"></i>${res.download_count}</span>
      </div>
      <span>${fileExt(res.file_path).toUpperCase()}</span>
    </div>
    <button class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md" data-download-id="${res.id}">
      <i class="fas fa-arrow-down mr-2"></i>Download
    </button>
  </div>
</article>`;
}

function typePill(type) {
    switch (type) {
        case 'video': return 'bg-red-100 text-red-800';
        case 'guide': return 'bg-blue-100 text-blue-800';
        case 'course': return 'bg-purple-100 text-purple-800';
        case 'infographic': return 'bg-amber-100 text-amber-800';
        case 'worksheet': return 'bg-emerald-100 text-emerald-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function titleCase(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
function escapeHtml(s) { return s.replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c])); }

async function renderResources() {
    const q = document.getElementById('resSearch').value;
    const typeSel = document.getElementById('resType').value;
    const sortSel = document.getElementById('resSort');
    const sort = sortSel ? sortSel.value : 'newest';
    const type = (typeSel === 'all') ? '' : typeSel;

    const state = window.__RES_PAGINATION__ || { page: 1 };
    window.__RES_PAGINATION__ = state;
    const limit = 9;
    const offset = (state.page - 1) * limit;

    // Fetch from API
    const params = new URLSearchParams({ search: q, type, sort, limit: String(limit), offset: String(offset) });
    params.append('_', String(Date.now()));
    let rows = [], total = 0;
    try {
        const r = await fetch('api/educational_resources_list.php?' + params.toString());
        const j = await r.json();
        if (j.success) { rows = j.data || []; total = j.total || 0; }
        else { showToast(j.message || 'Failed to load resources', 'error'); }
    } catch (e) { console.error(e); showToast('Failed to load resources', 'error'); }

    const grid = document.getElementById('resourcesGrid');
    const count = document.getElementById('resCount');
    const noRes = document.getElementById('noResults');
    const pagination = document.getElementById('pagination');
    const pageInfo = document.getElementById('pageInfo');
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');

    count.textContent = total;
    if (total === 0) {
        grid.innerHTML = '';
        noRes.classList.remove('hidden');
        if (pagination) pagination.classList.add('hidden');
        return;
    }
    noRes.classList.add('hidden');
    grid.innerHTML = rows.map(resourceCard).join('');

    const totalPages = Math.max(1, Math.ceil(total / limit));
    if (pagination) {
        pagination.classList.remove('hidden');
        pageInfo.textContent = `Page ${state.page} of ${totalPages}`;
        prevBtn.disabled = state.page === 1;
        nextBtn.disabled = state.page === totalPages;
        prevBtn.onclick = () => { state.page = Math.max(1, state.page - 1); renderResources(); renderFeatured(); };
        nextBtn.onclick = () => { state.page = Math.min(totalPages, state.page + 1); renderResources(); renderFeatured(); };
    }

    // Wire download buttons
    grid.querySelectorAll('[data-download-id]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.getAttribute('data-download-id'));
            window.location.href = 'api/educational_resources_download.php?id=' + id;
        });
    });
}

async function renderStatistics() {
    const slot = document.getElementById('resStats');
    if (!slot) return;
    try {
        const r = await fetch('api/educational_resources_statistics.php?_=' + Date.now());
        const j = await r.json();
        if (!j.success) return;
        const s = j.data;
        slot.innerHTML = `
            <div class="bg-white rounded-lg p-4 text-center shadow-sm"><div class="text-2xl font-bold text-blue-600">${s.total_resources}</div><div class="text-sm text-gray-600">Total Resources</div></div>
            <div class="bg-white rounded-lg p-4 text-center shadow-sm"><div class="text-2xl font-bold text-green-600">${s.documents}</div><div class="text-sm text-gray-600">Documents</div></div>
            <div class="bg-white rounded-lg p-4 text-center shadow-sm"><div class="text-2xl font-bold text-purple-600">${s.infographics}</div><div class="text-sm text-gray-600">Infographics</div></div>
            <div class="bg-white rounded-lg p-4 text-center shadow-sm"><div class="text-2xl font-bold text-red-600">${s.videos}</div><div class="text-sm text-gray-600">Videos</div></div>
            <div class="bg-white rounded-lg p-4 text-center shadow-sm"><div class="text-2xl font-bold text-yellow-600">${s.presentations}</div><div class="text-sm text-gray-600">Presentations</div></div>
            <div class="bg-white rounded-lg p-4 text-center shadow-sm"><div class="text-2xl font-bold text-indigo-600">${s.guides}</div><div class="text-sm text-gray-600">Guides</div></div>
        `;
    } catch (e) {
        console.error(e);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Ensure defaults on first load
    const typeEl = document.getElementById('resType');
    if (typeEl) typeEl.value = 'all';
    const searchEl = document.getElementById('resSearch');
    if (searchEl) searchEl.value = '';

    ['resSearch','resType'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        const handler = () => { window.__RES_PAGINATION__ = { page: 1 }; renderResources(); };
        el.addEventListener(id === 'resSearch' ? 'input' : 'change', handler);
    });
    const sortEl = document.getElementById('resSort');
    if (sortEl) sortEl.addEventListener('change', () => { window.__RES_PAGINATION__ = { page: 1 }; renderResources(); });
    const openU = document.getElementById('openUploadModal');
    const modal = document.getElementById('uploadModal');
    const closeU = document.getElementById('closeUploadModal');
    const cancelU = document.getElementById('uplCancel');
    if (openU && modal) openU.addEventListener('click', () => modal.classList.remove('hidden'));
    if (closeU && modal) closeU.addEventListener('click', () => modal.classList.add('hidden'));
    if (cancelU && modal) cancelU.addEventListener('click', () => modal.classList.add('hidden'));

    const uplForm = document.getElementById('uploadForm');
    if (uplForm) {
        uplForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('uplSubmit');
            const txt = document.getElementById('uplSubmitText');
            const spin = document.getElementById('uplSubmitting');
            btn.disabled = true; txt.classList.add('hidden'); spin.classList.remove('hidden');
            try {
                const fd = new FormData();
                fd.append('title', document.getElementById('uplTitle').value.trim());
                fd.append('description', document.getElementById('uplDesc').value.trim());
                fd.append('type', document.getElementById('uplType').value);
                const file = document.getElementById('uplFile').files[0];
                fd.append('file', file);
                const resp = await fetch('api/educational_resources_upload.php', { method: 'POST', body: fd });
                const json = await resp.json();
                if (json.success) {
                    showToast('Resource uploaded successfully!', 'success');
                    document.getElementById('uploadModal').classList.add('hidden');
                    uplForm.reset();
                    window.__RES_PAGINATION__ = { page: 1 };
                    await renderResources();
                    await renderStatistics();
                } else {
                    showToast(json.message || 'Upload failed', 'error');
                }
            } finally {
                btn.disabled = false; txt.classList.remove('hidden'); spin.classList.add('hidden');
            }
        });
    }
    renderStatistics();
    renderResources();
});

function featuredCard(res) {
    return `
<article class=\"bg-gradient-to-br from-[#78C841]/10 to-[#B4E50D]/10 rounded-lg shadow-lg overflow-hidden border-2 border-[#78C841]/30\">
  <img src=\"${res.imageUrl}\" alt=\"${res.title}\" class=\"w-full h-48 object-cover\" />
  <div class=\"p-6\">
    <div class=\"flex items-center mb-2\">
      <span class=\"px-2 py-1 text-xs font-medium rounded-full ${typePill(res.type)}\">${titleCase(res.type)}</span>
      <span class=\"text-sm text-gray-500 ml-auto\">${res.duration}</span>
    </div>
    <h3 class=\"text-xl font-bold text-gray-900 mb-3\">${escapeHtml(res.title)}</h3>
    <p class=\"text-gray-600 text-sm mb-4 line-clamp-3\">${escapeHtml(res.description)}</p>
    <div class=\"flex items-center justify-between text-sm text-gray-500\">
      <div class=\"flex items-center space-x-3\">
        <span class=\"inline-flex items-center\"><i class=\"fas fa-star text-yellow-500 mr-1\"></i>${res.rating} <span class=\"text-gray-400 ml-1\">(${res.reviewCount || 0})</span></span>
        <span>${res.downloads} downloads</span>
      </div>
      <span>${res.size}</span>
    </div>
    <button class=\"mt-4 w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md\" data-download-id=\"${res.id}\">
      <i class=\"fas fa-arrow-down mr-2\"></i>Download
    </button>
  </div>
</article>`;
}

function renderFeatured() {
    const q = document.getElementById('resSearch').value;
    const category = document.getElementById('resCategory').value;
    const type = document.getElementById('resType').value;
    const grade = document.getElementById('resGrade').value;
    const filters = { q, category, type, grade };
    const featured = ALL_RESOURCES.filter(r => r.isFeatured).filter(r => matchesFilters(r, filters));
    const wrap = document.getElementById('featuredResources');
    const empty = document.getElementById('noFeatured');
    if (!wrap) return;
    if (featured.length === 0) {
        wrap.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }
    empty.classList.add('hidden');
    wrap.innerHTML = featured.map(featuredCard).join('');
}
</script>
