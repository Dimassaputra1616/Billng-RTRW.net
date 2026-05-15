/* ══════════════════════════════
   Table Enhancements (Premium)
   ══════════════════════════════ */
.fi-ta-ctn {
    border-radius: 20px !important;
    overflow: hidden !important;
    border: 1px solid var(--nb-divider) !important;
    box-shadow: 0 8px 30px -5px rgba(0, 0, 0, 0.04) !important;
}

html.dark body .fi-ta-ctn {
    box-shadow: none !important;
}

html.dark body .fi-ta-header-cell {
    background-color: transparent !important;
    color: var(--nb-text-secondary) !important;
    font-weight: 800 !important;
    border-bottom: 2px solid rgba(255, 255, 255, 0.05) !important;
}

html:not(.dark) body .fi-ta-header-cell {
    background-color: transparent !important;
    color: var(--nb-text-secondary) !important;
    font-weight: 800 !important;
    border-bottom: 2px solid rgba(124, 58, 237, 0.05) !important;
}

.fi-ta-row {
    transition: background-color 0.2s ease !important;
}
.fi-ta-row:hover {
    background-color: var(--nb-glow1) !important;
}

/* Empty State Modernization */
.fi-ta-empty-state {
    padding: 60px 20px !important;
}
.fi-ta-empty-state-icon {
    width: 64px !important;
    height: 64px !important;
    padding: 16px !important;
    border-radius: 50% !important;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)) !important;
    color: #10b981 !important;
    margin: 0 auto 20px !important;
    box-shadow: inset 0 0 0 1px rgba(16, 185, 129, 0.2), 0 8px 20px -4px rgba(16, 185, 129, 0.2) !important;
}
.fi-ta-empty-state-heading {
    font-size: 16px !important;
    font-weight: 800 !important;
    color: var(--nb-text-primary) !important;
}

/* Gradient Buttons for Tables */
.fi-ta-header .fi-btn[style*="--c-400:var(--primary-400)"],
.fi-ta-header .fi-btn.fi-color-primary {
    background: linear-gradient(135deg, #a855f7, #6366f1) !important;
    box-shadow: 0 4px 15px -3px rgba(168, 85, 247, 0.4) !important;
    border: none !important;
    color: white !important;
}
.fi-ta-header .fi-btn[style*="--c-400:var(--danger-400)"],
.fi-ta-header .fi-btn.fi-color-danger {
    background: linear-gradient(135deg, #f43f5e, #e11d48) !important;
    box-shadow: 0 4px 15px -3px rgba(244, 63, 94, 0.4) !important;
    border: none !important;
    color: white !important;
}
