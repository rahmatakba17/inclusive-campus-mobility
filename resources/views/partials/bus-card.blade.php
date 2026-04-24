@php
    $isJalan = $bus->trip_status === 'jalan';
    $isIstirahat = $bus->trip_status === 'istirahat';
    $isStandby = !$isJalan && !$isIstirahat;
    
    $status_key = match($bus->trip_status) {
        'jalan'     => __('On the Way'),
        'istirahat' => __('Resting'),
        default     => __('Ready')
    };

    // Initial server-side rough order so it doesn't look totally random before JS kicks in
    $initialOrder = match($bus->trip_status) { 'standby' => 1, 'jalan' => 500, 'istirahat' => 900, default => 999 };
@endphp

<!-- x-bind for dynamic flex CSS order -->
<article x-show="(!Object.keys(dynamicRouteGroup).length && '{{ $targetRoute }}' === 'perintis_to_gowa') || dynamicRouteGroup[{{ $bus->id }}] === '{{ $targetRoute }}'"
         class="flex-none w-[85vw] sm:w-[340px] snap-start bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-[0_15px_40px_rgba(30,58,95,0.08)] hover:border-slate-300 transition-all duration-500 flex flex-col relative group"
         :style="{ order: busOrder[{{ $bus->id }}] || {{ $initialOrder }} }">
    
    {{-- Minimalist Header Strip --}}
    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-12 h-1 rounded-b-full transition-colors duration-300"
         :class="{
             'bg-emerald-500': (dynamicStatus[{{ $bus->id }}] || '{{ $bus->trip_status }}') === 'jalan',
             'bg-rose-500': (dynamicStatus[{{ $bus->id }}] || '{{ $bus->trip_status }}') === 'istirahat',
             'bg-amber-400': (dynamicStatus[{{ $bus->id }}] || '{{ $bus->trip_status }}') === 'standby'
         }">
    </div>

    {{-- Title & Branding --}}
    <header class="flex justify-between items-start mb-6 pt-2 h-14">
        <div>
            {{-- Status Badge --}}
            <div class="flex items-center gap-1.5 mb-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg text-[8px] font-black uppercase tracking-[0.15em] border transition-colors duration-300"
                      :class="{
                          'bg-emerald-50 text-emerald-600 border-emerald-100': (dynamicStatus[{{ $bus->id }}] || '{{ $bus->trip_status }}') === 'jalan',
                          'bg-rose-50 text-rose-600 border-rose-100': (dynamicStatus[{{ $bus->id }}] || '{{ $bus->trip_status }}') === 'istirahat',
                          'bg-amber-50 text-amber-700 border-amber-200': (dynamicStatus[{{ $bus->id }}] || '{{ $bus->trip_status }}') === 'standby'
                      }">
                    
                    <template x-if="(dynamicStatus[{{ $bus->id }}] || '{{ $bus->trip_status }}') === 'jalan'">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse" aria-hidden="true"></span>
                    </template>
                    <template x-if="(dynamicStatus[{{ $bus->id }}] || '{{ $bus->trip_status }}') === 'istirahat'">
                        <i class="fas fa-moon text-[7px]" aria-hidden="true"></i>
                    </template>
                    <template x-if="(dynamicStatus[{{ $bus->id }}] || '{{ $bus->trip_status }}') === 'standby'">
                        <i class="fas fa-pause text-[7px]" aria-hidden="true"></i>
                    </template>
                    
                    <span x-text="{ 'jalan': window._busT ? window._busT.STATUS_JALAN : '{{ __('On the Way') }}', 'istirahat': window._busT ? window._busT.STATUS_ISTIRAHAT : '{{ __('Resting') }}', 'standby': window._busT ? window._busT.STATUS_STANDBY : '{{ __('Ready') }}' }[dynamicStatus[{{ $bus->id }}] || '{{ $bus->trip_status }}']"></span>
                </span>
                
                {{-- ETA Helper (Only visible via JS injected value) --}}
                <template x-if="dynamicETA[{{ $bus->id }}] !== undefined">
                    <span class="inline-flex items-center text-[7.5px] font-black text-slate-500 uppercase tracking-widest bg-slate-50 px-1.5 py-1 rounded"
                          x-text="`{{ __('ETA') }} ${dynamicETA[{{ $bus->id }}]} {{ __('min') }}`"></span>
                </template>
            </div>
            
            <h3 class="text-base font-black text-[#1e3a5f] tracking-tight truncate max-w-[180px]" title="{{ $bus->name }}">{{ $bus->name }}</h3>
            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-0.5 font-mono">{{ $bus->plate_number }}</p>
        </div>

        <div class="w-12 h-12 border border-slate-100 rounded-2xl flex items-center justify-center p-1.5 bg-slate-50 flex-shrink-0 shadow-inner group-hover:scale-105 transition-transform duration-300">
            <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" class="w-full h-auto opacity-70" alt="{{ __('Logo Bus Kampus') }}" />
        </div>
    </header>

    {{-- Precise Info Details --}}
    <div class="flex-1 space-y-4 mb-6 pt-2 border-t border-slate-100/60">
        <div class="flex items-center gap-3.5 group/info">
            <div class="w-9 h-9 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 border border-slate-100 transition-colors group-hover/info:border-slate-200 group-hover/info:bg-white" aria-hidden="true">
                <i class="fas fa-route text-xs"></i>
            </div>
            <div class="flex-1">
                <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em] mb-0.5">{{ __('Rute') }}</p>
                <p class="text-[10px] font-bold text-slate-700 leading-tight">
                    @if($targetRoute === 'gowa_to_perintis')
                        Kampus Non-Merdeka Gowa - Perintis Kampus Non-Merdeka
                    @else
                        {{ $bus->route }}
                    @endif
                </p>
            </div>
        </div>
        
        <div class="flex items-center gap-3.5 group/info">
            <div class="w-9 h-9 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 border border-slate-100 transition-colors group-hover/info:border-slate-200 group-hover/info:bg-white" aria-hidden="true">
                <i class="far fa-clock text-xs"></i>
            </div>
            <div class="flex-1">
                <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em] mb-0.5">{{ __('Jadwal') }}</p>
                <p class="text-[10px] font-bold text-slate-700 leading-tight">
                    {{ substr($bus->departure_time, 0, 5) }} – {{ substr($bus->arrival_time, 0, 5) }}
                </p>
            </div>
        </div>
        
        <div class="flex items-center gap-3.5 group/info">
            <div class="w-9 h-9 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 border border-slate-100 transition-colors group-hover/info:border-slate-200 group-hover/info:bg-white" aria-hidden="true">
                <i class="fas fa-users text-xs"></i>
            </div>
            <div class="flex-1">
                <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em] mb-0.5">{{ __('Kapasitas') }}</p>
                <p class="text-[10px] font-bold text-slate-700 leading-tight">{{ $bus->capacity }} {{ __('Kursi') }}</p>
            </div>
        </div>
    </div>

    {{-- Bottom Action --}}
    <div class="mt-auto">
        @php $baseUrl = route($bookingRouteName ?? 'user.bookings.create', $bus); @endphp
        @php
            $staticLabel = match($bus->trip_status) {
                'jalan'     => __('On the Way'),
                'istirahat' => __('Resting'),
                default     => $isStandby ? __('View & Book') : __('Not Available'),
            };
        @endphp
        <a :href="bookHref({{ $bus->id }}, '{{ $targetRoute }}', '{{ $baseUrl }}')"
           :class="bookClass({{ $bus->id }}, '{{ $targetRoute }}') + (!canBook({{ $bus->id }}, '{{ $targetRoute }}') ? ' pointer-events-none' : '')"
           class="block w-full py-4 border text-center rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all relative overflow-hidden group {{ $isStandby ? 'bg-slate-900 border-slate-800 text-white' : 'bg-slate-100 text-slate-500' }}">
            <span x-text="bookLabel({{ $bus->id }}, '{{ $targetRoute }}')" class="relative z-10">{{ $staticLabel }}</span>
            <i class="fas fa-arrow-right ml-1 relative z-10" aria-hidden="true"
               x-show="canBook({{ $bus->id }}, '{{ $targetRoute }}')" style="display:{{ $isStandby ? 'inline' : 'none' }}"></i>
        </a>
    </div>
</article>
