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
