{{--
    Notification Bell Component (Alpine.js v3 via CDN)
    Usage: @include('partials.notification-bell', ['variant' => 'admin'])
    Available variants: 'admin', 'sopir', 'user'
--}}
<div x-data="notificationPanel()"
     x-init="init()"
     class="relative"
     id="notif-wrapper-{{ $variant ?? 'user' }}">

    {{-- ── Bell Icon Button ── --}}
    <button @click="toggle()"
            type="button"
            class="relative p-2.5 rounded-2xl bg-white border border-slate-100 hover:border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#c41e3a] group"
            aria-label="Notifikasi">
        <i class="far fa-bell text-slate-600 text-[15px] group-hover:rotate-12 transition-transform duration-300"></i>
        {{-- Unread dot --}}
        <span x-show="unreadCount > 0"
              class="absolute top-2 right-2.5 flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
        </span>
    </button>

    {{-- ── Dropdown Panel ── --}}
    <div x-show="open"
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         x-cloak
         class="absolute right-0 mt-4 w-80 sm:w-96 bg-white rounded-[2rem] shadow-2xl border border-slate-100 z-50 overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-4 bg-[#1e3a5f] flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-bell text-[#ffd700] text-sm"></i>
                <h4 class="text-white font-black text-base">Notifikasi</h4>
                <span x-show="unreadCount > 0"
                      class="bg-[#c41e3a] text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest"
                      x-text="unreadCount + ' baru'"></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[9px] text-white/60 font-black uppercase tracking-widest">Live</span>
                <button @click.stop="doRefresh()"
                        class="ml-1 w-7 h-7 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all"
                        title="Refresh">
                    <i class="fas fa-sync-alt text-white/70 text-[10px]" :class="viewState === 'loading' ? 'animate-spin' : ''"></i>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="min-h-[120px]">

            {{-- Loading --}}
            <div x-show="viewState === 'loading'" class="flex flex-col items-center justify-center py-12 gap-3">
                <div class="w-10 h-10 border-4 border-slate-100 border-t-[#c41e3a] rounded-full animate-spin"></div>
                <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Memuat...</p>
            </div>

            {{-- Empty --}}
            <div x-show="viewState === 'empty'" class="flex flex-col items-center py-12 px-6 text-center">
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
            <div x-show="viewState === 'list'"
                 class="overflow-y-auto divide-y divide-slate-50"
                 style="max-height:320px">
                <template x-for="notif in notifications" :key="notif.id">
                    <a :href="notif.link"
                       class="flex items-start gap-3 px-5 py-3.5 hover:bg-slate-50 transition-colors relative cursor-pointer"
                       :class="notif.unread ? 'bg-blue-50/30' : ''">
                        <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center mt-0.5 text-sm"
                             :class="{
                                'bg-amber-100 text-amber-600': notif.color === 'amber',
                                'bg-emerald-100 text-emerald-600': notif.color === 'emerald',
                                'bg-rose-100 text-rose-600': notif.color === 'rose',
                                'bg-blue-100 text-blue-600': notif.color === 'blue',
                                'bg-indigo-100 text-indigo-600': notif.color === 'indigo',
                             }">
                            <i class="fas" :class="'fa-' + notif.icon"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <p class="text-[11px] font-black text-slate-800 truncate" x-text="notif.title"></p>
                                <span x-show="notif.unread" class="flex-shrink-0 w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            </div>
                            <p class="text-[11px] text-slate-500 line-clamp-2" x-text="notif.message"></p>
                            <p class="text-[10px] text-slate-400 mt-1 font-medium" x-text="notif.time"></p>
                        </div>
                    </a>
                </template>
            </div>
        </div>

        {{-- Footer --}}
        <div x-show="viewState === 'list'"
             class="px-5 py-3 border-t border-slate-100 flex items-center justify-between bg-slate-50">
            <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest"
                  x-text="notifications.length + ' Notifikasi'"></span>
            <a href="{{ auth()->check() && auth()->user()->isAdmin() ? route('admin.bookings.index') : (auth()->check() ? route('user.bookings.index') : '#') }}"
               class="text-[10px] font-black text-[#1e3a5f] hover:text-[#c41e3a] uppercase tracking-widest flex items-center gap-1 transition-colors">
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
            viewState: 'loading',  // 'loading' | 'empty' | 'list'
            notifications: [],
            unreadCount: 0,
            _interval: null,
            _fetching: false,

            init() {
                this.fetchNotifications();
                this._interval = setInterval(() => this.fetchNotifications(), 30000);
            },

            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.fetchNotifications();
                    this.markRead();
                }
            },

            doRefresh() {
                this.fetchNotifications();
            },

            markRead() {
                if (this.unreadCount === 0) return;
                setTimeout(() => {
                    this.notifications.forEach(n => { n.unread = false; });
                    this.unreadCount = 0;
                    fetch('/notifications/mark-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        }
                    }).catch(() => {});
                }, 800);
            },

            async fetchNotifications() {
                if (this._fetching) return;
                this._fetching = true;

                // Only show loading state if no existing data
                if (this.notifications.length === 0) {
                    this.viewState = 'loading';
                }

                const ctrl = new AbortController();
                const t = setTimeout(() => ctrl.abort(), 8000);

                try {
                    const res = await fetch('/notifications', {
                        signal: ctrl.signal,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'same-origin'
                    });

                    clearTimeout(t);

                    if (res.ok) {
                        const data = await res.json();
                        const list = data.notifications || [];
                        const read = JSON.parse(localStorage.getItem('bk_read') || '[]');

                        let unread = 0;
                        list.forEach(n => {
                            if (n.unread && read.includes(n.id)) n.unread = false;
                            if (n.unread) unread++;
                        });

                        this.notifications = list;
                        this.unreadCount = unread;
                        this.viewState = list.length > 0 ? 'list' : 'empty';
                    } else {
                        if (this.notifications.length === 0) this.viewState = 'empty';
                    }
                } catch (e) {
                    if (this.notifications.length === 0) this.viewState = 'empty';
                } finally {
                    this._fetching = false;
                }
            }
        }
    }
}
</script>
