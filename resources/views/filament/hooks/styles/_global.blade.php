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
