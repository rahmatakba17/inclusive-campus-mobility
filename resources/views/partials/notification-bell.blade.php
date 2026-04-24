{{--
    Notification Bell Dropdown — Premium Redesign
    Usage: @include('partials.notification-bell', ['variant' => 'admin'])
    Variants: 'admin' | 'user'
--}}
@php $variant = $variant ?? 'admin'; @endphp

<div x-data="notificationPanel()" x-init="init()" class="relative" id="notif-wrapper-{{ $variant }}">

    {{-- ── Bell Button ────────────────────────────────────────── --}}
    @if($variant === 'admin')
        <button @click="toggle()" id="notif-btn-admin"
                class="relative w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 shadow-sm group overflow-visible"
                :class="open ? 'bg-[#c41e3a] text-white shadow-lg shadow-[#c41e3a]/30' : 'bg-slate-50 text-slate-500 hover:bg-[#c41e3a] hover:text-white hover:shadow-lg hover:shadow-[#c41e3a]/20'"
                title="Notifikasi">
            <i class="far fa-bell text-lg transition-transform duration-300"
               :class="open ? 'rotate-12' : 'group-hover:rotate-12'"></i>
            {{-- Badge --}}
            <span x-show="unreadCount > 0"
                  x-text="unreadCount > 9 ? '9+' : unreadCount"
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 scale-50"
                  x-transition:enter-end="opacity-100 scale-100"
                  class="absolute -top-1.5 -right-1.5 min-w-[20px] h-5 bg-gradient-to-br from-[#ff6b6b] to-[#c41e3a] text-white text-[9px] font-black rounded-full border-2 border-white flex items-center justify-center px-1 leading-none shadow-md animate-pulse">
            </span>
        </button>
    @else
        <button @click="toggle()" id="notif-btn-user"
                class="relative w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 group overflow-visible border"
                :class="open ? 'bg-[#c41e3a] text-white border-[#c41e3a] shadow-lg shadow-[#c41e3a]/30' : 'bg-slate-50 text-slate-500 border-slate-100 hover:bg-[#c41e3a] hover:text-white hover:border-[#c41e3a] hover:shadow-lg hover:shadow-[#c41e3a]/20'"
                title="Notifikasi">
            <i class="far fa-bell text-base transition-transform duration-300"
               :class="open ? 'rotate-12' : 'group-hover:rotate-12'"></i>
            <span x-show="unreadCount > 0"
                  x-text="unreadCount > 9 ? '9+' : unreadCount"
                  x-transition:enter="transition ease-out duration-200"
                  x-transition:enter-start="opacity-0 scale-50"
                  x-transition:enter-end="opacity-100 scale-100"
                  class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] bg-gradient-to-br from-[#ff6b6b] to-[#c41e3a] text-white text-[8px] font-black rounded-full border-2 border-white flex items-center justify-center px-0.5 leading-none shadow-md">
            </span>
        </button>
    @endif

    {{-- ── Dropdown Panel ─────────────────────────────────────── --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 translate-y-3 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-3 scale-95"
         @click.outside="open = false"
         class="absolute right-0 mt-4 w-[22rem] bg-white rounded-[1.75rem] shadow-2xl border border-slate-100/80 z-[999] overflow-hidden"
         style="filter: drop-shadow(0 25px 50px rgba(0,0,0,0.15));">

        {{-- Arrow tip --}}
        <div class="absolute -top-2 right-5 w-4 h-4 bg-[#1e3a5f] rotate-45 rounded-sm z-[1]"></div>

        {{-- ── Header ── --}}
        <div class="relative z-10 px-6 pt-5 pb-4 bg-gradient-to-br from-[#1e3a5f] to-[#0f2137] overflow-hidden">
            {{-- Decorative circles --}}
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/5 rounded-full"></div>
            <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-[#c41e3a]/20 rounded-full"></div>

            <div class="relative flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <i class="fas fa-bell text-[#ffd700] text-sm"></i>
                        <h4 class="text-white font-black text-base tracking-tight">Notifikasi</h4>
                    </div>
                    <div class="flex items-center gap-2">
                        <span x-show="unreadCount > 0"
                              class="inline-flex items-center gap-1 bg-[#c41e3a] text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse inline-block"></span>
                            <span x-text="unreadCount + ' belum dibaca'"></span>
                        </span>
                        <span x-show="unreadCount === 0"
                              class="text-white/40 text-[10px] font-bold uppercase tracking-widest">
                            Semua terbaca
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[9px] text-white/50 font-black uppercase tracking-widest">Live</span>
                    <button @click="refresh()"
                            class="ml-2 w-7 h-7 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all"
                            title="Refresh">
                        <i class="fas fa-sync-alt text-white/60 text-[10px]" :class="loading ? 'animate-spin' : ''"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Loading state ── --}}
        <template x-if="loading">
            <div class="flex flex-col items-center justify-center py-12 gap-3">
                <div class="w-10 h-10 border-4 border-slate-100 border-t-[#c41e3a] rounded-full animate-spin"></div>
                <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Memuat...</p>
            </div>
        </template>

        {{-- ── Empty state ── --}}
        <template x-if="!loading && notifications.length === 0">
            <div class="flex flex-col items-center py-14 px-6 text-center">
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
        </template>

        {{-- ── Notification List ── --}}
        <template x-if="!loading && notifications.length > 0">
            <div class="overflow-y-auto divide-y divide-slate-50" style="max-height: 340px;">
                <template x-for="(notif, index) in notifications" :key="notif.id">
                    <a :href="notif.link"
                       class="flex items-start gap-3.5 px-5 py-4 hover:bg-slate-50/80 transition-all duration-200 group relative cursor-pointer"
                       :class="{ 'bg-blue-50/40': notif.unread }"
                       x-transition:enter="transition ease-out duration-300"
                       x-transition:enter-start="opacity-0 -translate-x-4"
                       x-transition:enter-end="opacity-100 translate-x-0">

                        {{-- Unread indicator bar --}}
                        <div x-show="notif.unread"
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
                                <p class="text-[11px] font-black text-slate-800 leading-tight"
                                   x-text="notif.title"></p>
                                <span x-show="notif.unread"
                                      class="flex-shrink-0 mt-0.5 w-2 h-2 rounded-full bg-[#c41e3a] animate-pulse"></span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-snug line-clamp-2"
                               x-text="notif.message"></p>
                            <div class="flex items-center gap-1.5 mt-1.5">
                                <i class="fas fa-clock text-[8px] text-slate-300"></i>
                                <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider"
                                      x-text="notif.time"></span>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        </template>

        {{-- ── Footer ── --}}
        <div x-show="!loading && notifications.length > 0"
             class="px-5 py-3.5 bg-gradient-to-r from-slate-50 to-white border-t border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest"
                      x-text="notifications.length + ' NOTIFIKASI'">
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
            loading: false,
            error: false,
            notifications: [],
            unreadCount: 0,
            initialized: false,
            _intervalId: null,

            init() {
                this.refresh(true);
                // Poll every 30s (was 3s — too aggressive, caused stuck loading)
                this._intervalId = setInterval(() => this.refresh(false), 30000);
            },

            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.refresh(true);
                    this.markAllAsRead();
                }
            },

            markAllAsRead() {
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
                }, 1000);
            },

            async refresh(isInit = false) {
                if (this.loading) return;
                this.loading = true;
                this.error = false;

                // Timeout: abort fetch after 8 seconds to prevent stuck loading
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
                        this.unreadCount   = computedUnreadCount;
                        if (isInit) this.initialized = true;
                    } else {
                        console.warn('[Notification] API returned:', res.status);
                        this.error = true;
                    }
                } catch (e) {
                    clearTimeout(timeoutId);
                    if (e.name === 'AbortError') {
                        console.warn('[Notification] Fetch timed out after 8s');
                    } else {
                        console.warn('[Notification] Fetch failed:', e.message);
                    }
                    this.error = true;
                } finally {
                    this.loading = false;
                }
            }
        };
    }
}
</script>

