<style>
/* ═══════════════════════════════════════════════
   NetBilling Pro — Midnight Purple Clean Theme
   ═══════════════════════════════════════════════ */

:root {
    --bg-deep: #06040d;
    --bg-card: #0d0a1a;
    --border-accent: rgba(168, 85, 247, 0.2);
}

/* Base Body */
.fi-body.dark {
    background-color: var(--bg-deep) !important;
    background-image: none !important;
}

/* Kill all unintentional glows */
* {
    box-shadow: none !important;
    text-shadow: none !important;
}

/* Premium Sidebar */
.dark .fi-sidebar {
    background-color: #1a1625 !important; /* Distinct deep purple-slate */
    border-right: 2px solid rgba(168, 85, 247, 0.3) !important; /* Thicker, brighter border */
}

.dark .fi-sidebar-item-active {
    background: rgba(168, 85, 247, 0.25) !important;
    border-left: 4px solid #a855f7 !important;
}

.dark .fi-sidebar-item:hover {
    background: rgba(255, 255, 255, 0.05) !important;
    transition: all 0.2s ease;
}

.dark .fi-sidebar-item-active .fi-sidebar-item-label {
    color: #ffffff !important;
    font-weight: 900 !important;
}

.dark .fi-sidebar-item-active .fi-sidebar-item-icon {
    color: #ffffff !important;
    filter: drop-shadow(0 0 8px #a855f7);
}

.dark .fi-sidebar-item-icon {
    color: #a855f7 !important;
}

/* Professional Cards */
.dark .fi-wi-stats-overview-stat,
.dark .fi-wi-widget,
.dark .fi-section,
.dark .fi-card {
    background-color: var(--bg-card) !important;
    border: 1px solid var(--border-accent) !important;
    border-radius: 12px !important;
}

/* Typography */
.dark .fi-sidebar-group-label {
    color: #d8b4fe !important; /* Brighter purple for group labels */
    font-weight: 800 !important;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    font-size: 0.65rem;
}

.dark .fi-wi-stats-overview-stat-label {
    color: #94a3b8 !important;
    font-weight: 700;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.dark .fi-wi-stats-overview-stat-value {
    color: #f8fafc !important;
    font-size: 1.5rem !important;
    font-weight: 800 !important;
}

/* Topbar */
.dark .fi-topbar {
    background-color: var(--bg-deep) !important;
    border-bottom: 1px solid var(--border-accent) !important;
}
</style>
