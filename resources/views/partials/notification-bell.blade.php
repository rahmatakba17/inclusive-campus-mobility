{{-- Notification Bell — Pure Vanilla JS (no Alpine.js dependency) --}}
<div class="relative" id="notif-root-{{ $variant ?? 'user' }}">

    {{-- Bell Button --}}
    <button id="notif-btn-{{ $variant ?? 'user' }}"
            type="button"
            class="relative p-2.5 rounded-2xl bg-white border border-slate-100 hover:border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#c41e3a] group"
            aria-label="Notifikasi">
        <i class="far fa-bell text-slate-600 text-[15px] group-hover:rotate-12 transition-transform duration-300"></i>
        <span id="notif-dot-{{ $variant ?? 'user' }}"
              class="absolute top-2 right-2.5 hidden h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
        </span>
    </button>

    {{-- Dropdown Panel --}}
    <div id="notif-panel-{{ $variant ?? 'user' }}"
         class="absolute right-0 mt-4 w-80 sm:w-96 bg-white rounded-[2rem] shadow-2xl border border-slate-100 z-50 overflow-hidden"
         style="display:none; opacity:0; transform:translateY(8px) scale(0.97); transition: opacity 0.2s, transform 0.2s;">

        {{-- Header --}}
        <div class="px-6 py-4 bg-[#1e3a5f] flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-bell text-[#ffd700] text-sm"></i>
                <h4 class="text-white font-black text-base">Notifikasi</h4>
                <span id="notif-badge-{{ $variant ?? 'user' }}"
                      class="hidden bg-[#c41e3a] text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest">0 baru</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[9px] text-white/60 font-black uppercase tracking-widest">Live</span>
                <button id="notif-refresh-{{ $variant ?? 'user' }}"
                        class="ml-1 w-7 h-7 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all"
                        title="Refresh">
                    <i id="notif-refresh-icon-{{ $variant ?? 'user' }}" class="fas fa-sync-alt text-white/70 text-[10px]"></i>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="min-h-[120px]">

            {{-- Loading --}}
            <div id="notif-loading-{{ $variant ?? 'user' }}"
                 class="flex flex-col items-center justify-center py-12 gap-3"
                 style="display:none">
                <div class="w-10 h-10 border-4 border-slate-100 border-t-[#c41e3a] rounded-full animate-spin"></div>
                <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Memuat...</p>
            </div>

            {{-- Empty --}}
            <div id="notif-empty-{{ $variant ?? 'user' }}"
                 class="flex flex-col items-center py-12 px-6 text-center"
                 style="display:none">
                <div class="relative mb-4">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-100">
                        <i class="far fa-bell-slash text-2xl text-slate-300"></i>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-100 rounded-lg flex items-center justify-center border-2 border-white">
                        <i class="fas fa-check text-emerald-500 text-[9px]"></i>
                    </div>
                </div>
                <p class="text-slate-700 font-black text-sm">Semua sudah terkini!</p>
                <p class="text-slate-400 text-xs mt-1">Tidak ada notifikasi baru.</p>
            </div>

            {{-- List --}}
            <div id="notif-list-{{ $variant ?? 'user' }}"
                 class="overflow-y-auto divide-y divide-slate-50"
                 style="display:none; max-height:320px"></div>
        </div>

        {{-- Footer --}}
        <div id="notif-footer-{{ $variant ?? 'user' }}"
             class="px-5 py-3 border-t border-slate-100 flex items-center justify-between bg-slate-50"
             style="display:none">
            <span id="notif-count-{{ $variant ?? 'user' }}" class="text-[10px] text-slate-500 font-black uppercase tracking-widest">0 Notifikasi</span>
            <a href="{{ auth()->check() && auth()->user()->isAdmin() ? route('admin.bookings.index') : (auth()->check() ? route('user.bookings.index') : '#') }}"
               class="text-[10px] font-black text-[#1e3a5f] hover:text-[#c41e3a] uppercase tracking-widest flex items-center gap-1 transition-colors">
                Lihat Semua <i class="fas fa-arrow-right text-[8px]"></i>
            </a>
        </div>
    </div>
</div>

<script>
(function() {
    var id = '{{ $variant ?? "user" }}';
    var panel  = document.getElementById('notif-panel-' + id);
    var btn    = document.getElementById('notif-btn-' + id);
    var dot    = document.getElementById('notif-dot-' + id);
    var badge  = document.getElementById('notif-badge-' + id);
    var elLoad = document.getElementById('notif-loading-' + id);
    var elEmpty= document.getElementById('notif-empty-' + id);
    var elList = document.getElementById('notif-list-' + id);
    var elFoot = document.getElementById('notif-footer-' + id);
    var elCount= document.getElementById('notif-count-' + id);
    var elRefresh = document.getElementById('notif-refresh-' + id);
    var elRefreshIcon = document.getElementById('notif-refresh-icon-' + id);

    var isOpen    = false;
    var isFetching= false;
    var unread    = 0;
    var interval  = null;

    function show(el) { el.style.display = ''; }
    function hide(el) { el.style.display = 'none'; }

    function setState(state) {
        hide(elLoad); hide(elEmpty); hide(elList); hide(elFoot);
        if (state === 'loading') { show(elLoad); }
        else if (state === 'empty') { show(elEmpty); }
        else if (state === 'list') { show(elList); show(elFoot); }
    }

    function openPanel() {
        isOpen = true;
        panel.style.display = '';
        requestAnimationFrame(function() {
            panel.style.opacity = '1';
            panel.style.transform = 'translateY(0) scale(1)';
        });
    }

    function closePanel() {
        isOpen = false;
        panel.style.opacity = '0';
        panel.style.transform = 'translateY(8px) scale(0.97)';
        setTimeout(function() { panel.style.display = 'none'; }, 200);
    }

    function buildList(notifications) {
        var colorMap = { amber:'bg-amber-100 text-amber-600', emerald:'bg-emerald-100 text-emerald-600', rose:'bg-rose-100 text-rose-600', blue:'bg-blue-100 text-blue-600', indigo:'bg-indigo-100 text-indigo-600' };
        var html = '';
        notifications.forEach(function(n) {
            var cls = colorMap[n.color] || 'bg-slate-100 text-slate-600';
            var dot = n.unread ? '<span class="flex-shrink-0 w-1.5 h-1.5 rounded-full bg-rose-500 mt-1"></span>' : '';
            html += '<a href="' + (n.link||'#') + '" class="flex items-start gap-3 px-5 py-3.5 hover:bg-slate-50 transition-colors cursor-pointer' + (n.unread?' bg-blue-50/30':'') + '">'
                + '<div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center mt-0.5 text-sm ' + cls + '"><i class="fas fa-' + (n.icon||'bell') + '"></i></div>'
                + '<div class="flex-1 min-w-0">'
                + '<div class="flex items-start gap-1.5 mb-0.5"><p class="text-[11px] font-black text-slate-800 truncate flex-1">' + (n.title||'') + '</p>' + dot + '</div>'
                + '<p class="text-[11px] text-slate-500 line-clamp-2">' + (n.message||'') + '</p>'
                + '<p class="text-[10px] text-slate-400 mt-1 font-medium">' + (n.time||'') + '</p>'
                + '</div></a>';
        });
        elList.innerHTML = html;
    }

    function fetchNotifications() {
        if (isFetching) return;
        isFetching = true;
        elRefreshIcon.classList.add('animate-spin');
        if (elList.children.length === 0) setState('loading');

        var ctrl = typeof AbortController !== 'undefined' ? new AbortController() : null;
        var tId = setTimeout(function() { if(ctrl) ctrl.abort(); }, 8000);

        var opts = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || ''
            },
            credentials: 'same-origin'
        };
        if (ctrl) opts.signal = ctrl.signal;

        fetch('/notifications', opts)
            .then(function(r) {
                clearTimeout(tId);
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(function(data) {
                var list = data.notifications || [];
                var readMem = JSON.parse(localStorage.getItem('bk_read') || '[]');
                unread = 0;
                list.forEach(function(n) {
                    if (n.unread && readMem.indexOf(n.id) !== -1) n.unread = false;
                    if (n.unread) unread++;
                });

                if (unread > 0) {
                    show(dot);
                    badge.textContent = unread + ' baru';
                    show(badge);
                } else {
                    hide(dot);
                    hide(badge);
                }

                if (list.length > 0) {
                    buildList(list);
                    elCount.textContent = list.length + ' Notifikasi';
                    setState('list');
                } else {
                    setState('empty');
                }
            })
            .catch(function() {
                if (elList.children.length === 0) setState('empty');
            })
            .finally(function() {
                isFetching = false;
                elRefreshIcon.classList.remove('animate-spin');
            });
    }

    function markRead() {
        if (unread === 0) return;
        setTimeout(function() {
            var readMem = JSON.parse(localStorage.getItem('bk_read') || '[]');
            elList.querySelectorAll('a').forEach(function(a) {
                var dot = a.querySelector('.bg-rose-500');
                if (dot) dot.remove();
                a.classList.remove('bg-blue-50/30');
            });
            hide(dot); hide(badge); unread = 0;
            fetch('/notifications/mark-read', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || '', 'Accept': 'application/json' }
            }).catch(function(){});
        }, 800);
    }

    // Events
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        if (isOpen) { closePanel(); }
        else {
            openPanel();
            fetchNotifications();
            markRead();
        }
    });

    if (elRefresh) {
        elRefresh.addEventListener('click', function(e) {
            e.stopPropagation();
            fetchNotifications();
        });
    }

    document.addEventListener('click', function(e) {
        if (isOpen && !panel.contains(e.target) && !btn.contains(e.target)) {
            closePanel();
        }
    });

    // Init
    fetchNotifications();
    interval = setInterval(fetchNotifications, 30000);
})();
</script>
