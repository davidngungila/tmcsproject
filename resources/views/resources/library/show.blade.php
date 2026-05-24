@extends('layouts.app')

@section('title', $resource->title . ' - PDF Viewer')

@push('styles')
<style>
    .viewer-container {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 120px);
        background: #1a1a1a;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
    }
    .viewer-toolbar {
        height: 56px;
        background: #262626;
        border-bottom: 1px solid #333;
        display: flex;
        align-items: center;
        padding: 0 20px;
        gap: 20px;
        z-index: 10;
    }
    .viewer-main {
        flex: 1;
        overflow-y: auto;
        display: flex;
        justify-content: center;
        padding: 40px;
        scroll-behavior: smooth;
    }
    #pdf-canvas {
        box-shadow: 0 0 20px rgba(0,0,0,0.5);
        max-width: 100%;
    }
    .toolbar-btn {
        width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        color: #ccc;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .toolbar-btn:hover { background: #404040; color: #fff; }
    .toolbar-btn.active { background: var(--green-600); color: #fff; }
    
    .zoom-indicator {
        background: #333;
        color: #fff;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        min-width: 60px;
        text-align: center;
    }
    
    .progress-bar-container {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 4px;
        background: rgba(255,255,255,0.1);
        z-index: 20;
    }
    #progress-bar {
        height: 100%;
        background: var(--green-500);
        width: 0%;
        transition: width 0.3s;
    }
    
    .sidebar-toc {
        width: 280px;
        background: #262626;
        border-right: 1px solid #333;
        overflow-y: auto;
        display: none;
    }
    .sidebar-toc.show { display: block; }
    
    .toc-item {
        padding: 12px 20px;
        color: #ccc;
        font-size: 13px;
        cursor: pointer;
        border-bottom: 1px solid #333;
    }
    .toc-item:hover { background: #333; color: #fff; }

    /* Night Mode */
    .viewer-container.night-mode #pdf-canvas {
        filter: invert(0.9) hue-rotate(180deg);
    }
    
    .page-nav-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #fff;
        font-size: 13px;
    }
    .page-input {
        width: 40px;
        background: #333;
        border: 1px solid #444;
        color: #fff;
        text-align: center;
        border-radius: 4px;
        padding: 2px;
    }

    /* Full Screen */
    .viewer-container:fullscreen {
        height: 100vh;
        width: 100vw;
        border-radius: 0;
    }
</style>
@endpush

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('resources.library') }}" class="text-sm text-muted hover:text-green-600 flex items-center gap-1 mb-2">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Library
        </a>
        <h1 class="text-2xl font-bold">{{ $resource->title }}</h1>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('resources.download', $resource->slug) }}" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold text-sm">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download
        </a>
    </div>
</div>

<div class="viewer-container" id="viewerContainer">
    <div class="viewer-toolbar">
        <div class="toolbar-btn" id="toggleToc" title="Table of Contents">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
        </div>
        
        <div class="h-6 w-px bg-white/10 mx-2"></div>
        
        <div class="page-nav-controls">
            <div class="toolbar-btn" id="prevPage">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </div>
            <span>Page</span>
            <input type="text" id="pageNumber" class="page-input" value="1">
            <span>of <span id="pageCount">0</span></span>
            <div class="toolbar-btn" id="nextPage">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </div>

        <div class="h-6 w-px bg-white/10 mx-2"></div>

        <div class="flex items-center gap-2">
            <div class="toolbar-btn" id="zoomOut">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            </div>
            <div class="zoom-indicator" id="zoomLevel">100%</div>
            <div class="toolbar-btn" id="zoomIn">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
        </div>

        <div class="h-6 w-px bg-white/10 mx-2"></div>

        <div class="toolbar-btn" id="toggleNightMode" title="Night Mode">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        </div>

        <div class="toolbar-btn" id="toggleFullscreen" title="Full Screen">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
        </div>

        <div class="ml-auto flex items-center gap-4 text-xs text-muted">
            <div class="flex items-center gap-1">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                {{ strtoupper($resource->file_type) }}
            </div>
            <div class="flex items-center gap-1">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ round($resource->file_size / 1024 / 1024, 2) }} MB
            </div>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <div class="sidebar-toc" id="sidebarToc">
            <div class="p-4 font-bold text-white border-bottom border-[#333]">Table of Contents</div>
            <div id="tocList">
                <div class="p-4 text-sm text-muted">No table of contents available</div>
            </div>
        </div>

        <div class="viewer-main" id="viewerMain">
            <canvas id="pdf-canvas"></canvas>
        </div>
    </div>

    <div class="progress-bar-container">
        <div id="progress-bar"></div>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 glass-card p-6">
        <h3 class="text-lg font-bold mb-4">About this document</h3>
        <p class="text-muted leading-relaxed">{{ $resource->description }}</p>
    </div>
    <div class="glass-card p-6">
        <h3 class="text-lg font-bold mb-4">Metadata</h3>
        <div class="space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-muted">Category</span>
                <span class="font-semibold">{{ $resource->category->name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-muted">Upload Date</span>
                <span class="font-semibold">{{ $resource->created_at->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-muted">Total Views</span>
                <span class="font-semibold">{{ $resource->view_count }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-muted">Downloads</span>
                <span class="font-semibold">{{ $resource->download_count }}</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    const url = "{{ Storage::url($resource->file_path) }}";
    const pdfjsLib = window['pdfjs-dist/build/pdf'];
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    let pdfDoc = null,
        pageNum = {{ $interaction->last_page_read ?? 1 }},
        pageRendering = false,
        pageNumPending = null,
        scale = 1.5,
        canvas = document.getElementById('pdf-canvas'),
        ctx = canvas.getContext('2d');

    function renderPage(num) {
        pageRendering = true;
        pdfDoc.getPage(num).then(function(page) {
            const viewport = page.getViewport({ scale: scale });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            const renderTask = page.render(renderContext);

            renderTask.promise.then(function() {
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
        });

        document.getElementById('pageNumber').value = num;
        updateProgress(num);
    }

    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    function updateProgress(num) {
        const percent = (num / pdfDoc.numPages) * 100;
        document.getElementById('progress-bar').style.width = percent + '%';
        
        // Save progress to server
        fetch("{{ route('resources.progress', $resource->slug) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ page: num })
        });
    }

    // Load PDF
    pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
        pdfDoc = pdfDoc_;
        document.getElementById('pageCount').textContent = pdfDoc.numPages;
        renderPage(pageNum);

        // Try to get TOC
        pdfDoc.getOutline().then(function(outline) {
            if (outline && outline.length > 0) {
                const tocList = document.getElementById('tocList');
                tocList.innerHTML = '';
                outline.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'toc-item';
                    div.textContent = item.title;
                    div.onclick = () => {
                        // In a real app, we'd resolve the destination to a page number
                        // For now, this is a placeholder
                    };
                    tocList.appendChild(div);
                });
            }
        });
    });

    // Toolbar Controls
    document.getElementById('prevPage').onclick = () => {
        if (pageNum <= 1) return;
        pageNum--;
        queueRenderPage(pageNum);
    };

    document.getElementById('nextPage').onclick = () => {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum++;
        queueRenderPage(pageNum);
    };

    document.getElementById('zoomIn').onclick = () => {
        if (scale >= 3.0) return;
        scale += 0.25;
        document.getElementById('zoomLevel').textContent = Math.round(scale * 100) + '%';
        renderPage(pageNum);
    };

    document.getElementById('zoomOut').onclick = () => {
        if (scale <= 0.5) return;
        scale -= 0.25;
        document.getElementById('zoomLevel').textContent = Math.round(scale * 100) + '%';
        renderPage(pageNum);
    };

    document.getElementById('toggleNightMode').onclick = function() {
        document.getElementById('viewerContainer').classList.toggle('night-mode');
        this.classList.toggle('active');
    };

    document.getElementById('toggleFullscreen').onclick = () => {
        const container = document.getElementById('viewerContainer');
        if (!document.fullscreenElement) {
            container.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    };

    document.getElementById('toggleToc').onclick = function() {
        document.getElementById('sidebarToc').classList.toggle('show');
        this.classList.toggle('active');
    };

    document.getElementById('pageNumber').onchange = function() {
        const num = parseInt(this.value);
        if (num > 0 && num <= pdfDoc.numPages) {
            pageNum = num;
            renderPage(pageNum);
        }
    };
</script>
@endpush
@endsection