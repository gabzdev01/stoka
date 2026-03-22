<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Demo Product — Stoka Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--parchment:#FAF7F2;--surface:#F2EDE6;--border:#E8E0D6;--espresso:#1C1814;--mid:#4A3728;--muted:#8C7B6E;--terracotta:#C17F4A;--forest:#4A6741;--clay:#B85C38;--sidebar-w:220px;--radius-sm:6px;--radius-md:10px;--radius-default:14px;}
body{font-family:"Plus Jakarta Sans",sans-serif;background:var(--parchment);color:var(--espresso);min-height:100vh;-webkit-font-smoothing:antialiased;}
a{color:inherit;text-decoration:none;}
.shell{display:flex;min-height:100vh;}
.sidebar{width:var(--sidebar-w);flex-shrink:0;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;height:100vh;z-index:100;overflow:hidden;}
.sidebar-header{padding:24px 20px 16px;border-bottom:1px solid var(--border);flex-shrink:0;}
.sidebar-logo{font-family:"Cormorant Garamond",serif;font-size:26px;font-weight:600;color:var(--espresso);letter-spacing:0.02em;display:block;line-height:1;margin-bottom:5px;}
.sidebar-tag{font-size:10px;font-weight:700;color:var(--terracotta);text-transform:uppercase;letter-spacing:0.1em;display:block;}
.sidebar-nav{flex:1;padding:12px 10px;overflow-y:auto;}
.nav-section-label{font-size:10px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.1em;padding:12px 12px 4px;display:block;}
.nav-section-label:first-child{padding-top:4px;}
.nav-link{display:flex;align-items:center;gap:9px;padding:9px 12px;border-radius:var(--radius-md);font-size:13px;font-weight:400;color:var(--mid);transition:background 0.13s,color 0.13s;margin-bottom:1px;}
.nav-link:hover{background:#EBE3D8;color:var(--espresso);}
.nav-link.active{background:var(--terracotta);color:#fff;font-weight:500;}
.nav-icon{width:15px;height:15px;flex-shrink:0;opacity:0.65;}
.nav-link.active .nav-icon,.nav-link:hover .nav-icon{opacity:1;}
.sidebar-footer{flex-shrink:0;padding:14px 18px 18px;border-top:1px solid var(--border);}
.sidebar-role{font-size:11px;color:var(--muted);display:block;margin-bottom:10px;}
.logout-btn{width:100%;padding:8px 12px;background:transparent;border:1px solid var(--border);border-radius:var(--radius-md);font-family:"Plus Jakarta Sans",sans-serif;font-size:12px;font-weight:500;color:var(--muted);cursor:pointer;text-align:left;transition:background 0.13s,color 0.13s,border-color 0.13s;}
.logout-btn:hover{background:var(--clay);border-color:var(--clay);color:#fff;}
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh;min-width:0;}
.page-header{padding:30px 36px 22px;border-bottom:1px solid var(--border);flex-shrink:0;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;}
.page-title{font-family:"Cormorant Garamond",serif;font-size:30px;font-weight:600;color:var(--espresso);line-height:1.1;}
.page-sub{font-size:13px;color:var(--muted);margin-top:5px;}
.page-content{padding:28px 36px;flex:1;}
.btn-primary{display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:var(--terracotta);color:white;border:none;border-radius:var(--radius-md);font-family:"Plus Jakarta Sans",sans-serif;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;transition:opacity 0.15s;flex-shrink:0;}
.btn-primary:hover{opacity:0.88;}
.btn-ghost{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:white;color:var(--espresso);border:1px solid var(--border);border-radius:var(--radius-md);font-family:"Plus Jakarta Sans",sans-serif;font-size:12px;font-weight:500;cursor:pointer;text-decoration:none;transition:border-color 0.13s;}
.btn-ghost:hover{border-color:var(--terracotta);color:var(--terracotta);}
.btn-danger{display:inline-flex;align-items:center;padding:8px 14px;background:#F8E8E4;color:var(--clay);border:1px solid #E8C8C0;border-radius:var(--radius-md);font-family:"Plus Jakarta Sans",sans-serif;font-size:12px;font-weight:500;cursor:pointer;}
.form-card{background:white;border:1px solid var(--border);border-radius:var(--radius-default);padding:28px;box-shadow:0 1px 3px rgba(28,24,20,0.04);max-width:700px;}
.field{margin-bottom:20px;}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:6px;}
.field input,.field textarea,.field select{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:var(--radius-md);font-family:"Plus Jakarta Sans",sans-serif;font-size:14px;color:var(--espresso);background:var(--parchment);outline:none;transition:border-color 0.15s;}
.field input:focus,.field textarea:focus{border-color:var(--terracotta);}
.field textarea{resize:vertical;line-height:1.65;}
.field-hint{font-size:11px;color:var(--muted);margin-top:4px;}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.check-row{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--espresso);cursor:pointer;}
.check-row input{width:auto;cursor:pointer;}
.flash-ok{border-radius:var(--radius-default);padding:12px 18px;margin-bottom:20px;border-left:3px solid var(--forest);background:#DFF0DD;font-size:13px;font-weight:600;color:var(--forest);}
.flash-err{border-radius:var(--radius-default);padding:12px 18px;margin-bottom:20px;border-left:3px solid var(--clay);background:#F8E8E4;font-size:13px;color:var(--clay);}
.photo-preview{width:120px;height:120px;object-fit:cover;border-radius:var(--radius-md);border:1px solid var(--border);display:block;margin-bottom:10px;}
.drop-zone{border:2px dashed var(--border);border-radius:var(--radius-md);padding:28px 20px;text-align:center;cursor:pointer;transition:border-color 0.15s,background 0.15s;background:var(--parchment);}
.drop-zone:hover,.drop-zone.dragover{border-color:var(--terracotta);background:#FDF6EE;}
.drop-zone input{display:none;}
.drop-zone-label{font-size:13px;color:var(--muted);margin-top:6px;}
.drop-zone-label strong{color:var(--terracotta);}</style>
</head>
<body>
<div class="shell">
<aside class="sidebar"><div class="sidebar-header"><span class="sidebar-logo">Stoka</span><span class="sidebar-tag">Super Admin</span></div><nav class="sidebar-nav"><span class="nav-section-label">Tenants</span><a href="/admin" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>Tenants</a><a href="/admin/inquiries" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M12 7a4 4 0 110 8 4 4 0 010-8z"/></svg>Inquiries</a><a href="/admin/demo-visits" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8zM12 9a3 3 0 110 6 3 3 0 010-6z"/></svg>Demo Visits</a><span class="nav-section-label">Content</span><a href="/admin/articles" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zM14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>Articles</a><a href="/admin/testimonials" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>Testimonials</a><a href="/admin/demo-products" class="nav-link active"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>Demo Products</a><span class="nav-section-label">Actions</span><a href="/admin/tenants/create" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>New Tenant</a></nav><div class="sidebar-footer"><span class="sidebar-role">stoka.co.ke</span><form method="POST" action="/admin/logout">@csrf<button type="submit" class="logout-btn">Log out</button></form></div></aside>
<div class="main">
  <div class="page-header">
    <div>
      <h1 class="page-title">{{ $product ? 'Edit Product' : 'New Demo Product' }}</h1>
      <p class="page-sub">This product will appear in the demo shop if marked visible.</p>
    </div>
    <a href="/admin/demo-products" class="btn-ghost">← Back</a>
  </div>
  <div class="page-content">

    @if(session('ok'))<div class="flash-ok">✓ {{ session('ok') }}</div>@endif
    @if(session('error'))<div class="flash-err">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="flash-err">{{ implode(', ', $errors->all()) }}</div>@endif

    <form method="POST"
          action="{{ $product ? '/admin/demo-products/'.$product->id.'/update' : '/admin/demo-products' }}"
          enctype="multipart/form-data">
      @csrf
      <div class="form-card">

        <div class="grid-2">
          <div class="field">
            <label>Product name</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required placeholder="e.g. Ankara Wrap Dress">
          </div>
          <div class="field">
            <label>Category</label>
            <input type="text" name="category" value="{{ old('category', $product->category ?? '') }}" placeholder="e.g. Dresses">
          </div>
        </div>

        <div class="grid-2">
          <div class="field">
            <label>Price (KES)</label>
            <input type="number" name="shelf_price" value="{{ old('shelf_price', $product->shelf_price ?? '') }}" required min="0" step="50">
          </div>
          <div class="field">
            <label>Stock</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 10) }}" required min="0">
          </div>
        </div>

        <div class="field">
          <label>Description <span style="font-weight:400;text-transform:none;letter-spacing:0;">(shown on shop page)</span></label>
          <textarea name="description" rows="3" placeholder="A short description customers will see...">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="field">
          <label>Product image</label>
          @if($product && $product->photo)
          <img src="{{ Storage::url($product->photo) }}" class="photo-preview" id="photoPreview" alt="Current photo">
          <label class="check-row" style="margin-bottom:10px;">
            <input type="checkbox" name="remove_photo" value="1" id="removePhoto"> Remove current photo
          </label>
          @else
          <img src="" class="photo-preview" id="photoPreview" style="display:none;" alt="">
          @endif
          <div class="drop-zone" id="dropZone" onclick="document.getElementById('photoInput').click()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted);margin:0 auto;display:block;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <div class="drop-zone-label"><strong>Click to upload</strong> or drag and drop</div>
            <div class="drop-zone-label" style="font-size:11px;margin-top:2px;">JPG, PNG, WebP — max 4MB</div>
            <input type="file" name="photo" id="photoInput" accept="image/*">
          </div>
          <div class="field-hint">Images are compressed and stored at 1200px max width.</div>
        </div>

        <div class="field" style="margin-bottom:24px;">
          <label class="check-row">
            <input type="checkbox" name="shop_visible" value="1" {{ old('shop_visible', $product->shop_visible ?? 1) ? 'checked' : '' }}>
            Visible on demo shop page
          </label>
        </div>

        <div style="display:flex;gap:10px;align-items:center;">
          <button type="submit" class="btn-primary">
            {{ $product ? 'Save changes' : 'Add product' }}
          </button>
          @if($product)
          <form method="POST" action="/admin/demo-products/{{ $product->id }}/delete"
                onsubmit="return confirm('Delete this product? This cannot be undone.')"
                style="display:inline;margin:0;">
            @csrf
            <button type="submit" class="btn-danger">Delete product</button>
          </form>
          @endif
        </div>

      </div>
    </form>
  </div>
</div>
</div>

<script>
const dropZone = document.getElementById('dropZone');
const photoInput = document.getElementById('photoInput');
const photoPreview = document.getElementById('photoPreview');

photoInput.addEventListener('change', function() {
  if (this.files && this.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      photoPreview.src = e.target.result;
      photoPreview.style.display = 'block';
    };
    reader.readAsDataURL(this.files[0]);
  }
});

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', e => {
  e.preventDefault();
  dropZone.classList.remove('dragover');
  const file = e.dataTransfer.files[0];
  if (file && file.type.startsWith('image/')) {
    photoInput.files = e.dataTransfer.files;
    const reader = new FileReader();
    reader.onload = ev => {
      photoPreview.src = ev.target.result;
      photoPreview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
});
</script>
</body>
</html>