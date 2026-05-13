<style>
/* ═══════════════════════════════════════════════
   NetBilling Pro — Adaptive Theme V4 (Polished)
   ═══════════════════════════════════════════════ */

/* ──── Dark Mode Variables ──── */
.dark {
    --nb-bg-deep: #06040d;
    --nb-bg-card: #0d0a1a;
    --nb-border-accent: rgba(168, 85, 247, 0.2);
    --nb-sidebar-bg: #1a1625;
    --nb-sidebar-border: rgba(168, 85, 247, 0.3);
    --nb-sidebar-hover: rgba(255, 255, 255, 0.05);
    --nb-sidebar-active-bg: rgba(168, 85, 247, 0.25);
    --nb-text-primary: #f8fafc;
    --nb-text-secondary: #94a3b8;
    --nb-text-muted: #64748b;
    --nb-group-label: #d8b4fe;
    --nb-accent: #a855f7;
    --nb-accent-label: #d8b4fe;
    --nb-divider: rgba(255, 255, 255, 0.05);
    --nb-card-bg: rgba(18, 10, 26, 0.6);
    --nb-card-border: 1px solid rgba(168, 85, 247, 0.15);
    --nb-card-shadow: none;
    --nb-glow1: rgba(168, 85, 247, 0.15);
    --nb-glow2: rgba(59, 130, 246, 0.1);
    --nb-welcome-bg: rgba(18, 10, 26, 0.6);
    --nb-welcome-border: 1px solid rgba(168, 85, 247, 0.2);
    --nb-welcome-backdrop: blur(15px);
    --nb-name-from: #d8b4fe;
    --nb-name-to: #818cf8;
    --nb-billing-color: rgba(168, 85, 247, 0.6);
}

/* ──── Light Mode Variables ──── */
:root:not(.dark), html:not(.dark) {
    --nb-bg-deep: #f5f3ff;
    --nb-bg-card: #ffffff;
    --nb-border-accent: rgba(124, 58, 237, 0.08);
    --nb-sidebar-bg: #120d1d;
    --nb-sidebar-border: rgba(255, 255, 255, 0.05);
    --nb-sidebar-hover: rgba(255, 255, 255, 0.05);
    --nb-sidebar-active-bg: linear-gradient(135deg, #7c3aed, #a855f7);
    --nb-text-primary: #1e1b4b;
    --nb-text-secondary: #475569;
    --nb-text-muted: #94a3b8;
    --nb-group-label: #6d28d9;
    --nb-accent: #7c3aed;
    --nb-accent-label: #7c3aed;
    --nb-divider: rgba(124, 58, 237, 0.06);
    --nb-card-bg: #ffffff;
    --nb-card-border: 1px solid rgba(124, 58, 237, 0.1);
    --nb-card-shadow: 0 4px 16px -4px rgba(124, 58, 237, 0.08);
    --nb-glow1: rgba(168, 85, 247, 0.06);
    --nb-glow2: rgba(59, 130, 246, 0.04);
    --nb-welcome-bg: linear-gradient(135deg, #ffffff, #faf5ff);
    --nb-welcome-border: 1px solid rgba(124, 58, 237, 0.12);
    --nb-welcome-backdrop: none;
    --nb-name-from: #7c3aed;
    --nb-name-to: #6366f1;
    --nb-billing-color: #a855f7;
}

/* ══════════════════════════════
   Global
   ══════════════════════════════ */
* {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.fi-body {
    background-color: var(--nb-bg-deep) !important;
    background-image: none !important;
}

html:not(.dark) body {
    background-image:
        radial-gradient(at 0% 0%, rgba(168, 85, 247, 0.03) 0px, transparent 50%),
        radial-gradient(at 100% 0%, rgba(59, 130, 246, 0.03) 0px, transparent 50%) !important;
}

/* ══════════════════════════════
   Sidebar
   ══════════════════════════════ */
/* More specific selector for the sidebar */
aside.fi-sidebar, 
.fi-sidebar,
.fi-main-sidebar {
    background-color: var(--nb-sidebar-bg) !important;
    border-right: 1px solid var(--nb-sidebar-border) !important;
    backdrop-filter: blur(10px) !important;
}

/* Ensure the nav container is also colored if nested */
.fi-sidebar-nav {
    background-color: transparent !important;
}

/* ══════════════════════════════
   Sidebar Active & Hover States (Final Fix)
   ══════════════════════════════ */
/* Active Menu Item */
html body .fi-sidebar li.fi-active > a.fi-sidebar-item-btn {
    background: var(--nb-sidebar-active-bg) !important;
    background-color: #a855f7 !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px -2px rgba(124, 58, 237, 0.5) !important;
}

html body .fi-sidebar li.fi-active > a.fi-sidebar-item-btn .fi-sidebar-item-label,
html body .fi-sidebar li.fi-active > a.fi-sidebar-item-btn .fi-sidebar-item-icon {
    color: #ffffff !important;
    opacity: 1 !important;
}

/* Hover Menu Item */
html body .fi-sidebar li:not(.fi-active) > a.fi-sidebar-item-btn:hover {
    background-color: #2D1B5B !important;
    background: #2D1B5B !important;
    background-image: none !important;
    border-radius: 8px !important;
}

html body .fi-sidebar li:not(.fi-active) > a.fi-sidebar-item-btn:hover .fi-sidebar-item-label,
html body .fi-sidebar li:not(.fi-active) > a.fi-sidebar-item-btn:hover .fi-sidebar-item-icon {
    color: #ffffff !important;
    opacity: 1 !important;
}

/* Base Contrast for Side-Items */
:root:not(.dark) .fi-sidebar li:not(.fi-active) > a.fi-sidebar-item-btn .fi-sidebar-item-label,
:root:not(.dark) .fi-sidebar li:not(.fi-active) > a.fi-sidebar-item-btn .fi-sidebar-item-icon {
    color: rgba(255, 255, 255, 0.75) !important;
}

:root:not(.dark) .fi-sidebar .fi-sidebar-group-label {
    color: #a855f7 !important;
}

.fi-sidebar-group-label {
    color: var(--nb-group-label) !important;
    font-weight: 800 !important;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    font-size: 0.65rem !important;
}

/* ══════════════════════════════
   Cards & Widgets
   ══════════════════════════════ */
.fi-wi-stats-overview-stat,
.fi-wi-widget,
.fi-section,
.fi-card {
    background-color: var(--nb-bg-card) !important;
    border: 1px solid var(--nb-border-accent) !important;
    border-radius: 16px !important;
}

html:not(.dark) body .fi-wi-stats-overview-stat,
html:not(.dark) body .fi-wi-widget,
html:not(.dark) body .fi-section,
html:not(.dark) body .fi-card {
    box-shadow: var(--nb-card-shadow) !important;
}

.fi-wi-stats-overview-stat-label {
    color: var(--nb-text-secondary) !important;
    font-weight: 700 !important;
    font-size: 0.7rem !important;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.fi-wi-stats-overview-stat-value {
    color: var(--nb-text-primary) !important;
    font-size: 1.5rem !important;
    font-weight: 800 !important;
}

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

/* ══════════════════════════════
   Table Enhancements
   ══════════════════════════════ */
.fi-ta-ctn {
    border-radius: 16px !important;
    overflow: hidden !important;
}

html.dark body .fi-ta-header-cell {
    background-color: rgba(255, 255, 255, 0.02) !important;
    color: var(--nb-text-secondary) !important;
    font-weight: 700 !important;
}

html:not(.dark) body .fi-ta-header-cell {
    background-color: #fcfaff !important;
    color: var(--nb-text-secondary) !important;
    font-weight: 700 !important;
}

/* Empty State Green Check */
.fi-ta-empty-state-icon {
    color: #10b981 !important;
}

/* ══════════════════════════════
   Welcome Widget (Adaptive)
   ══════════════════════════════ */
.nb-welcome-card {
    background: var(--nb-welcome-bg);
    border: var(--nb-welcome-border);
    backdrop-filter: var(--nb-welcome-backdrop);
}

.nb-glow-1 { background: var(--nb-glow1); }
.nb-glow-2 { background: var(--nb-glow2); }

.nb-pulse-dot {
    background: var(--nb-accent);
}
.dark .nb-pulse-dot {
    box-shadow: 0 0 10px var(--nb-accent);
}

.nb-status-label { color: var(--nb-accent); }
.nb-icon-accent { color: var(--nb-accent) !important; }
.nb-greeting { color: var(--nb-text-primary); }

.nb-name-gradient {
    background: linear-gradient(to right, var(--nb-name-from), var(--nb-name-to));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.nb-date-text { color: var(--nb-text-secondary); }
.nb-billing-label { color: var(--nb-billing-color); }

/* ══════════════════════════════
   Sidebar Widget (Adaptive)
   ══════════════════════════════ */
.nb-card-adaptive {
    background: var(--nb-card-bg);
    border: var(--nb-card-border);
}

.dark .nb-card-adaptive {
    backdrop-filter: blur(10px);
}

html:not(.dark) body .nb-card-adaptive {
    box-shadow: var(--nb-card-shadow);
}

.nb-card-header {
    border-bottom: 1px solid var(--nb-divider);
}

.nb-header-label {
    color: var(--nb-accent-label);
}

/* Action Cards */
.nb-action-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    cursor: pointer;
}

.nb-action-card:hover {
    transform: translateY(-4px) !important;
}

.dark .nb-action-card:hover {
    background: rgba(255, 255, 255, 0.06) !important;
}

html:not(.dark) body .nb-action-card:hover {
    background: #ffffff !important;
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.08) !important;
}

.nb-action-label {
    color: var(--nb-text-primary) !important;
}

/* ══════════════════════════════
   Buttons
   ══════════════════════════════ */
.fi-btn {
    border-radius: 10px !important;
    font-weight: 600 !important;
}

/* ══════════════════════════════
   Animations
   ══════════════════════════════ */
@keyframes nb-pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
    100% { opacity: 1; transform: scale(1); }
}
</style>
