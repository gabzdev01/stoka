<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Demo Products — Stoka Admin</title><link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"><style>*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
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
.table-card{background:white;border:1px solid var(--border);border-radius:var(--radius-default);overflow:hidden;box-shadow:0 1px 3px rgba(28,24,20,0.04);}
.flash{border-radius:var(--radius-default);padding:12px 18px;margin-bottom:20px;border-left:3px solid var(--forest);background:#DFF0DD;font-size:13px;font-weight:600;color:var(--forest);}
.btn-primary{display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:var(--terracotta);color:white;border:none;border-radius:var(--radius-md);font-family:"Plus Jakarta Sans",sans-serif;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;transition:opacity 0.15s;flex-shrink:0;}
.btn-primary:hover{opacity:0.88;}
.btn-ghost{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:white;color:var(--espresso);border:1px solid var(--border);border-radius:var(--radius-md);font-family:"Plus Jakarta Sans",sans-serif;font-size:12px;font-weight:500;cursor:pointer;text-decoration:none;transition:border-color 0.13s;}
.btn-ghost:hover{border-color:var(--terracotta);color:var(--terracotta);}
.btn-danger{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#F8E8E4;color:var(--clay);border:1px solid #E8C8C0;border-radius:var(--radius-md);font-family:"Plus Jakarta Sans",sans-serif;font-size:12px;font-weight:500;cursor:pointer;transition:background 0.13s;}
.btn-danger:hover{background:#F0D8D4;}
.form-card{background:white;border:1px solid var(--border);border-radius:var(--radius-default);padding:28px;box-shadow:0 1px 3px rgba(28,24,20,0.04);max-width:760px;}
.field{margin-bottom:20px;}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:6px;}
.field input,.field textarea,.field select{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:var(--radius-md);font-family:"Plus Jakarta Sans",sans-serif;font-size:14px;color:var(--espresso);background:var(--parchment);outline:none;transition:border-color 0.15s;}
.field input:focus,.field textarea:focus{border-color:var(--terracotta);}
.field textarea{resize:vertical;line-height:1.65;}
.field-hint{font-size:11px;color:var(--muted);margin-top:4px;}
.check-row{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--espresso);}
.check-row input{width:auto;}
.row-item{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--border);gap:12px;}
.row-item:last-child{border-bottom:none;}
.row-item:hover{background:var(--parchment);}
.row-title{font-size:13px;font-weight:600;color:var(--espresso);}
.row-sub{font-size:11px;color:var(--muted);margin-top:2px;}
.row-actions{display:flex;gap:6px;flex-shrink:0;}
.badge{font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px;display:inline-block;}
.b-on{background:#DFF0DD;color:var(--forest);}
.b-off{background:var(--surface);color:var(--muted);}
.b-vis{background:#DFF0DD;color:var(--forest);}
.b-hid{background:var(--surface);color:var(--muted);}</style></head><body><div class="shell"><aside class="sidebar"><div class="sidebar-header"><span class="sidebar-logo">Stoka</span><span class="sidebar-tag">Super Admin</span></div><nav class="sidebar-nav"><span class="nav-section-label">Tenants</span><a href="/admin" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>Tenants</a><a href="/admin/inquiries" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M12 7a4 4 0 110 8 4 4 0 010-8z"/></svg>Inquiries</a><a href="/admin/demo-visits" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8zM12 9a3 3 0 110 6 3 3 0 010-6z"/></svg>Demo Visits</a><span class="nav-section-label">Content</span><a href="/admin/articles" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zM14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>Articles</a><a href="/admin/testimonials" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>Testimonials</a><a href="/admin/demo-products" class="nav-link active"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>Demo Products</a><span class="nav-section-label">Actions</span><a href="/admin/tenants/create" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>New Tenant</a></nav><div class="sidebar-footer"><span class="sidebar-role">stoka.co.ke</span><form method="POST" action="/admin/logout">@csrf<button type="submit" class="logout-btn">Log out</button></form></div></aside>
<div class="main">
  <div class="page-header">
    <div><h1 class="page-title">Demo Products</h1><p class="page-sub">Products in the demo shop. Toggle shop_visible to show/hide on the public demo shop page.</p></div>
  </div>
  <div class="page-content">
    @if(session('ok'))<div class="flash">✓ {{ session('ok') }}</div>@endif
    <div class="table-card">
      @forelse($products as $p)
      <div class="row-item">
        <div style="flex:1;min-width:0;">
          <div class="row-title">{{ $p->name }}</div>
          <div class="row-sub" style="display:flex;gap:8px;margin-top:3px;">
            @if($p->category)<span>{{ $p->category }}</span>@endif
            <span style="font-family:DM Mono,monospace;">KES {{ number_format($p->shelf_price) }}</span>
            <span>Stock: {{ $p->stock }}</span>
          </div>
        </div>
        <div class="row-actions" style="align-items:center;gap:10px;">
          <span class="badge {{ $p->shop_visible ? 'b-vis' : 'b-hid' }}">{{ $p->shop_visible ? 'Visible' : 'Hidden' }}</span>
          <form method="POST" action="/admin/demo-products/{{ $p->id }}/toggle">
            @csrf<button type="submit" class="btn-ghost" style="font-size:11px;">{{ $p->shop_visible ? 'Hide' : 'Show' }}</button>
          </form>
        </div>
      </div>
      @empty<div style="padding:40px;text-align:center;color:var(--muted);">No products in demo shop.</div>@endforelse
    </div>
  </div>
</div></div></body></html>