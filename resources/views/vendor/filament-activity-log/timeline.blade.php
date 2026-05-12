<div class="activity-log-timeline" x-data>
    @php
        $activities = $activities ?? $getState() ?? collect();
        if (!$activities instanceof \Illuminate\Support\Collection) {
            $activities = collect($activities);
        }
    @endphp

    @php
        use AlizHarb\ActivityLog\Support\ActivityChanges;
    @endphp

    <style>
        .al-timeline {
            position: relative;
            padding: 1rem 0;
        }
        .al-timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, rgba(139, 92, 246, 0.6) 0%, rgba(139, 92, 246, 0.1) 100%);
            border-radius: 2px;
        }
        .al-entry {
            position: relative;
            padding-left: 52px;
            padding-bottom: 1.5rem;
        }
        .al-entry:last-child {
            padding-bottom: 0;
        }
        .al-dot {
            position: absolute;
            left: 12px;
            top: 4px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            box-shadow: 0 0 0 4px rgba(30, 30, 46, 0.9);
        }
        .al-dot.created { background: linear-gradient(135deg, #10b981, #059669); }
        .al-dot.updated { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .al-dot.deleted { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .al-dot.default { background: linear-gradient(135deg, #6b7280, #4b5563); }
        .al-dot svg {
            width: 10px;
            height: 10px;
            color: white;
        }
        .al-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            transition: all 0.2s ease;
        }
        .al-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(139, 92, 246, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .al-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }
        .al-card-header-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .al-user {
            font-weight: 600;
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.95);
        }
        .al-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.15rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .al-badge.created {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }
        .al-badge.updated {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }
        .al-badge.deleted {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }
        .al-badge.default {
            background: rgba(107, 114, 128, 0.15);
            color: #9ca3af;
            border: 1px solid rgba(107, 114, 128, 0.25);
        }
        .al-subject {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
        }
        .al-subject strong {
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
        }
        .al-time {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.35);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .al-time svg {
            width: 12px;
            height: 12px;
            opacity: 0.5;
        }
        .al-desc {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 0.25rem;
            line-height: 1.5;
            padding: 0.5rem 0.75rem;
            background: rgba(139, 92, 246, 0.05);
            border-radius: 8px;
            border-left: 3px solid rgba(139, 92, 246, 0.3);
        }
        .al-meta-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }
        .al-meta-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.03);
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .al-meta-badge svg {
            width: 12px;
            height: 12px;
        }
        .al-changes-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-top: 0.75rem;
            padding: 0.5rem 0.75rem;
            background: rgba(139, 92, 246, 0.08);
            border: 1px solid rgba(139, 92, 246, 0.15);
            border-radius: 8px;
            color: rgba(139, 92, 246, 0.8);
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .al-changes-btn:hover {
            background: rgba(139, 92, 246, 0.12);
            border-color: rgba(139, 92, 246, 0.3);
            color: rgba(139, 92, 246, 1);
        }
        .al-changes-btn-inner {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .al-changes-btn svg {
            width: 14px;
            height: 14px;
        }
        .al-chevron {
            transition: transform 0.3s ease;
        }
        .al-chevron.rotated {
            transform: rotate(180deg);
        }
        .al-changes-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-top: 0.75rem;
        }
        @media (max-width: 640px) {
            .al-changes-grid {
                grid-template-columns: 1fr;
            }
        }
        .al-change-panel {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .al-change-panel.old {
            border-color: rgba(239, 68, 68, 0.15);
        }
        .al-change-panel.new {
            border-color: rgba(16, 185, 129, 0.15);
        }
        .al-change-panel-header {
            padding: 0.4rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .al-change-panel.old .al-change-panel-header {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
        }
        .al-change-panel.new .al-change-panel-header {
            background: rgba(16, 185, 129, 0.1);
            color: #34d399;
        }
        .al-change-panel-body {
            padding: 0.5rem 0.75rem;
        }
        .al-change-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 0.3rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }
        .al-change-row:last-child {
            border-bottom: none;
        }
        .al-change-key {
            font-size: 0.7rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.5);
            text-transform: capitalize;
        }
        .al-change-val {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            text-align: right;
            max-width: 60%;
            word-break: break-all;
        }
        .al-empty {
            text-align: center;
            padding: 3rem 1rem;
        }
        .al-empty-icon {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .al-empty-icon svg {
            width: 2.5rem;
            height: 2.5rem;
            color: rgba(139, 92, 246, 0.3);
        }
        .al-empty h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 0.25rem;
        }
        .al-empty p {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.35);
        }
    </style>

    <div class="al-timeline">
        @forelse ($activities as $key => $activity)
            @php
                $oldValues = ActivityChanges::getOldValues($activity);
                $newValues = ActivityChanges::getNewValues($activity);
                $hasChanges = ActivityChanges::hasChanges($activity);
                $eventClass = match($activity->event) {
                    'created' => 'created',
                    'updated' => 'updated',
                    'deleted' => 'deleted',
                    default => 'default',
                };
                $eventLabel = match($activity->event) {
                    'created' => 'Dibuat',
                    'updated' => 'Diubah',
                    'deleted' => 'Dihapus',
                    default => ucfirst($activity->event),
                };
            @endphp

            <div class="al-entry">
                {{-- Dot --}}
                <div class="al-dot {{ $eventClass }}">
                    @if($activity->event === 'created')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    @elseif($activity->event === 'updated')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Z" /></svg>
                    @elseif($activity->event === 'deleted')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
                    @endif
                </div>

                {{-- Card --}}
                <div class="al-card">
                    <div class="al-card-header">
                        <div class="al-card-header-left">
                            <span class="al-user">{{ $activity->causer?->name ?? 'System' }}</span>
                            <span class="al-badge {{ $eventClass }}">{{ $eventLabel }}</span>
                            <span class="al-subject">
                                <strong>{{ class_basename($activity->subject_type) }}</strong>
                                @if($activity->subject_id) #{{ $activity->subject_id }} @endif
                            </span>
                        </div>
                        <time datetime="{{ $activity->created_at->toIso8601String() }}" class="al-time"
                              title="{{ $activity->created_at->format('d M Y H:i:s') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            {{ $activity->created_at->diffForHumans() }}
                        </time>
                    </div>

                    @if($activity->description && $activity->description !== $activity->event)
                        <div class="al-desc">{{ $activity->description }}</div>
                    @endif

                    @if(isset($activity->properties['ip_address']) || isset($activity->properties['user_agent']))
                        <div class="al-meta-row">
                            @if(isset($activity->properties['ip_address']))
                                <span class="al-meta-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" /></svg>
                                    {{ $activity->properties['ip_address'] }}
                                </span>
                            @endif
                        </div>
                    @endif

                    @if($hasChanges)
                        <div x-data="{ open: false }">
                            <button @click="open = !open" type="button" class="al-changes-btn">
                                <span class="al-changes-btn-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                                    Lihat Perubahan
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                     class="al-chevron" :class="{ 'rotated': open }" style="width:14px;height:14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div x-show="open" x-collapse style="display: none;">
                                <div class="al-changes-grid">
                                    @if(!empty($oldValues))
                                        <div class="al-change-panel old">
                                            <div class="al-change-panel-header">Sebelum</div>
                                            <div class="al-change-panel-body">
                                                @foreach($oldValues as $key => $value)
                                                    <div class="al-change-row">
                                                        <span class="al-change-key">{{ str_replace('_', ' ', $key) }}</span>
                                                        <span class="al-change-val">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if(!empty($newValues))
                                        <div class="al-change-panel new">
                                            <div class="al-change-panel-header">Sesudah</div>
                                            <div class="al-change-panel-body">
                                                @foreach($newValues as $key => $value)
                                                    <div class="al-change-row">
                                                        <span class="al-change-key">{{ str_replace('_', ' ', $key) }}</span>
                                                        <span class="al-change-val">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="al-empty">
                <div class="al-empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                </div>
                <h3>Belum Ada Aktivitas</h3>
                <p>Riwayat perubahan akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>