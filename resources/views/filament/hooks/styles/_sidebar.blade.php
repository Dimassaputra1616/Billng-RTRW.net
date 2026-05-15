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

/* Ensure the nav container can scroll if items overflow */
aside.fi-sidebar nav.fi-sidebar-nav {
    background-color: transparent !important;
    overflow-y: auto !important;
    max-height: calc(100vh - 80px); /* Beri ruang buat footer sidebar */
}

/* Compact Sidebar Items to fit everything in one screen */
.fi-sidebar-item {
    margin-top: 0.1rem !important;
}
.fi-sidebar-item-btn {
    padding-top: 0.3rem !important;
    padding-bottom: 0.3rem !important;
    min-height: 2rem !important;
}
.fi-sidebar-group {
    margin-top: 0.4rem !important;
}
.fi-sidebar-group-label {
    margin-bottom: 0.15rem !important;
}

/* Hide Scrollbar for Sidebar completely */
.fi-sidebar-nav::-webkit-scrollbar {
    display: none;
}
.fi-sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}
.fi-sidebar-nav::-webkit-scrollbar-thumb {
    background-color: rgba(168, 85, 247, 0.2);
    border-radius: 10px;
}
.fi-sidebar-nav:hover::-webkit-scrollbar-thumb {
    background-color: rgba(168, 85, 247, 0.5);
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
   Sidebar Sub-menu Consistency Fix
   ══════════════════════════════ */
/* Force sub-item icons to show and hide indicators */
.fi-sidebar-item-icon {
    display: flex !important;
    width: 1.25rem !important;
    height: 1.25rem !important;
}

.fi-sidebar-item-indicator {
    display: none !important;
}

/* Ensure the container doesn't have extra left padding that pushes icons */
.fi-sidebar-group-items {
    padding-left: 0.5rem !important;
    border-left: none !important;
}

.fi-sidebar-group-items::before {
    display: none !important;
}
