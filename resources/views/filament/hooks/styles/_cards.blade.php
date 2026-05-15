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
