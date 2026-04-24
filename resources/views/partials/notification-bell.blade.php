{{--
    Notification Bell Component (Alpine.js)
    Usage: @include('partials.notification-bell', ['variant' => 'admin'])
    Available variants: 'admin', 'sopir', 'user'
--}}

@php
    // Definisikan warna ring untuk tiap variant
    $ringColor = match($variant ?? 'user') {
        'admin' => 'ring-[#c41e3a]',
        'sopir' => 'ring-amber-500',
        default => 'ring-[#c41e3a]',
    };
@endphp

<div x-data="notificationPanel()"
     x-init="init()"
     class="relative"
     id="notif-wrapper-{{ $variant }}">

    {{-- Bell Icon --}}
    <button @click="toggle()"
            type="button"
            class="relative p-2.5 rounded-2xl bg-white border border-slate-100 hover:border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $ringColor }} group">

        <i class="far fa-bell text-slate-600 text-[15px] group-hover:rotate-12 transition-transform duration-300"></i>

        {{-- Red Dot Indicator --}}
        <span x-show="unreadCount > 0"
              style="display: none;"
              class="absolute top-2 right-2.5 flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
        </span>
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="absolute right-0 mt-4 w-[22rem] sm:w-96 bg-white rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] border border-slate-100 z-50 overflow-hidden"
         x-cloak>

        {{-- ── Header ── --}}
        <div class="px-6 py-5 bg-[#1e3a5f] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-bell text-[#ffd700] text-sm animate-bounce"></i>
                        <h4 class="text-white font-black text-base tracking-tight">Notifikasi</h4>
                    </div>
                    <div class="flex items-center gap-2">
                        <span x-show="unreadCount > 0" style="display: none;"
                              class="inline-flex items-center gap-1 bg-[#c41e3a] text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse inline-block"></span>
                            <span x-text="unreadCount + ' belum dibaca'"></span>
                        </span>
                        <span x-show="unreadCount === 0" style="display: none;"
                              class="text-white/40 text-[10px] font-bold uppercase tracking-widest">
                            Semua terbaca
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[9px] text-white/50 font-black uppercase tracking-widest">Live</span>
                    <button @click="refresh(false)"
                            class="ml-2 w-7 h-7 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all"
                            title="Refresh">
                        <i class="fas fa-sync-alt text-white/60 text-[10px]" :class="viewState === 'loading' ? 'animate-spin' : ''"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Loading state ── --}}
        <div x-show="viewState === 'loading'" style="display: none;" class="flex flex-col items-center justify-center py-12 gap-3">
            <div class="w-10 h-10 border-4 border-slate-100 border-t-[#c41e3a] rounded-full animate-spin"></div>
            <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Memuat...</p>
        </div>

        {{-- ── Empty state ── --}}
        <div x-show="viewState === 'empty'" style="display: none;" class="flex flex-col items-center py-14 px-6 text-center">
            <div class="relative mb-5">
                <div class="w-20 h-20 bg-gradient-to-br from-slate-50 to-slate-100 rounded-[2rem] flex items-center justify-center shadow-inner border border-slate-100">
                    <i class="far fa-bell-slash text-3xl text-slate-300"></i>
                </div>
                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center border-2 border-white shadow-sm">
                    <i class="fas fa-check text-emerald-500 text-xs"></i>
                </div>
            </div>
            <p class="text-slate-700 font-black text-sm">Semua sudah terkini!</p>
            <p class="text-slate-500 text-xs font-medium mt-1 leading-relaxed max-w-[160px]">Tidak ada notifikasi baru untuk ditampilkan.</p>
        </div>

        {{-- ── Notification List ── --}}
        <div x-show="viewState === 'list'" style="display: none;" class="overflow-y-auto divide-y divide-slate-50" style="max-height: 340px;">
            <template x-for="(notif, index) in notifications" :key="notif.id">
                <a :href="notif.link"
                   class="flex items-start gap-3.5 px-5 py-4 hover:bg-slate-50/80 transition-all duration-200 group relative cursor-pointer"
                   :class="{ 'bg-blue-50/40': notif.unread }">

                    {{-- Unread indicator bar --}}
                    <div x-show="notif.unread" style="display: none;"
                         class="absolute left-0 top-0 bottom-0 w-1 rounded-r-full bg-gradient-to-b from-[#c41e3a] to-[#821326]"></div>

                    {{-- Icon --}}
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-200"
                             :class="{
                                'bg-amber-100 text-amber-600 shadow-amber-100': notif.color === 'amber',
                                'bg-emerald-100 text-emerald-600 shadow-emerald-100': notif.color === 'emerald',
                                'bg-rose-100 text-rose-600 shadow-rose-100': notif.color === 'rose',
                                'bg-blue-100 text-blue-600 shadow-blue-100': notif.color === 'blue',
                             }">
                            <i class="fas text-sm" :class="'fa-' + notif.icon"></i>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-1 mb-0.5">
                            <p class="text-[11px] font-black text-slate-800 leading-tight" x-text="notif.title"></p>
                            <span x-show="notif.unread" style="display: none;"
                                  class="flex-shrink-0 mt-0.5 w-2 h-2 rounded-full bg-[#c41e3a] animate-pulse"></span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-snug line-clamp-2" x-text="notif.message"></p>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <i class="fas fa-clock text-[8px] text-slate-300"></i>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider" x-text="notif.time"></span>
                        </div>
                    </div>
                </a>
            </template>
        </div>

        {{-- ── Footer ── --}}
        <div x-show="viewState === 'list'" style="display: none;"
             class="px-5 py-3.5 bg-gradient-to-r from-slate-50 to-white border-t border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest"
                      x-text="(notifications.length || 0) + ' NOTIFIKASI'">
                </span>
            </div>
            <a href="{{ auth()->check() && auth()->user()->isAdmin() ? route('admin.bookings.index') : (auth()->check() ? route('user.bookings.index') : '#') }}"
               class="text-[9px] font-black text-[#1e3a5f] hover:text-[#c41e3a] uppercase tracking-widest flex items-center gap-1 transition-colors">
                Lihat Semua <i class="fas fa-arrow-right text-[8px]"></i>
            </a>
        </div>
    </div>
</div>

<script>
if (typeof notificationPanel === 'undefined') {
    function notificationPanel() {
        return {
            open: false,
            viewState: 'loading', // 'loading' | 'empty' | 'list'
            notifications: [],
            unreadCount: 0,
            initialized: false,
            _intervalId: null,

            init() {
                this.refresh(true);
                this._intervalId = setInterval(() => this.refresh(false), 30000);
            },

            toggle() {
                this.open = !this.open;
                if (this.open) {
                    if (this.viewState === 'empty' || this.viewState === 'error') {
                        this.refresh(false);
                    }
                    this.markAllAsRead();
                }
            },

            markAllAsRead() {
                if (this.unreadCount === 0) return;
                
                setTimeout(() => {
                    const readMem = JSON.parse(localStorage.getItem('bus_kampus_read_notifs') || '[]');
                    let updated = false;
                    
                    this.notifications.forEach(n => {
                        if (n.unread) {
                            n.unread = false;
                            if (!readMem.includes(n.id)) {
                                readMem.push(n.id);
                                updated = true;
                            }
                        }
                    });
                    
                    if (updated) {
                        if (readMem.length > 100) readMem.splice(0, readMem.length - 100);
                        localStorage.setItem('bus_kampus_read_notifs', JSON.stringify(readMem));
                    }
                    
                    this.unreadCount = 0;
                    
                    // Mark as read in server cache
                    fetch('/notifications/mark-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        }
                    }).catch(() => {});
                    
                }, 800);
            },

            async refresh(isInit = false) {
                // Prevent duplicate fetches
                if (this.viewState === 'loading' && !isInit) return;
                
                if (this.notifications.length === 0) {
                    this.viewState = 'loading';
                }

                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 8000);

                try {
                    const res = await fetch('/notifications', {
                        signal: controller.signal,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'same-origin'
                    });

                    clearTimeout(timeoutId);

                    if (res.ok) {
                        const data = await res.json();
                        const latestNotifications = data.notifications || [];
                        const readMem = JSON.parse(localStorage.getItem('bus_kampus_read_notifs') || '[]');
                        let computedUnreadCount = 0;

                        latestNotifications.forEach(n => {
                            if (n.unread && readMem.includes(n.id)) n.unread = false;
                            if (n.unread) computedUnreadCount++;
                        });

                        // Only show toast if new unread appears
                        if (this.initialized && !isInit && computedUnreadCount > this.unreadCount) {
                            const newestUnread = latestNotifications.find(n => n.unread);
                            if (typeof Swal !== 'undefined' && newestUnread) {
                                Swal.fire({
                                    toast: true, position: 'top-end', icon: 'info',
                                    title: newestUnread.title || 'Pemberitahuan Baru',
                                    text: newestUnread.message || 'Periksa menu notifikasi Anda',
                                    showConfirmButton: false, timer: 4500,
                                    background: '#1e3a5f', color: '#ffffff', iconColor: '#ffd700'
                                });
                            }
                        }

                        this.notifications = latestNotifications;
                        this.unreadCount = computedUnreadCount;
                        
                        // Set state based on array length
                        this.viewState = this.notifications.length > 0 ? 'list' : 'empty';
                        
                        if (isInit) this.initialized = true;
                    } else {
                        // Keep old state or show empty on error
                        if (this.notifications.length === 0) {
                            this.viewState = 'empty';
                        }
                    }
                } catch (err) {
                    if (this.notifications.length === 0) {
                        this.viewState = 'empty';
                    }
                }
            }
        }
    }
}
</script>
