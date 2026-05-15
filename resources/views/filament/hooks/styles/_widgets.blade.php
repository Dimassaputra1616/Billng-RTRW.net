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

/* Generic Buttons */
.fi-btn {
    border-radius: 10px !important;
    font-weight: 600 !important;
}
