/* ══════════════════════════════
   Topbar
   ══════════════════════════════ */
.fi-topbar {
    border-bottom: 1px solid var(--nb-border-accent) !important;
}

html.dark body .fi-topbar {
    background: var(--nb-bg-deep) !important;
    background-color: var(--nb-bg-deep) !important;
}

html:not(.dark) body .fi-topbar {
    background: #ffffff !important;
    background-color: #ffffff !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
}

/* Force Topbar Logo/Brand Text to be visible */
html body .fi-topbar .fi-logo {
    color: var(--nb-text-primary) !important;
    font-weight: 800 !important;
}

/* ══════════════════════════════
   Topbar Status Badges
   ══════════════════════════════ */
.nb-topbar-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    line-height: 1;
    white-space: nowrap;
    transition: transform 0.2s ease;
}

.nb-topbar-badge:hover {
    transform: scale(1.05);
}

.nb-topbar-badge-green {
    background: rgba(16, 185, 129, 0.08);
    border: 1px solid rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.dark .nb-topbar-badge-green {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.2);
    color: #34d399;
}

.nb-topbar-badge-amber {
    background: rgba(245, 158, 11, 0.08);
    border: 1px solid rgba(245, 158, 11, 0.2);
    color: #d97706;
}

.dark .nb-topbar-badge-amber {
    background: rgba(245, 158, 11, 0.1);
    border-color: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
}

.nb-topbar-dot-green {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #10b981;
    animation: nb-pulse 2s infinite;
}
