/* ══════════════════════════════
   Modern Animations
   ══════════════════════════════ */
@keyframes nb-ken-burns {
    from { transform: scale(1.0); }
    to { transform: scale(1.1); }
}

@keyframes nb-shine {
    0% { left: -100%; }
    20%, 100% { left: 150%; }
}

@keyframes nb-float {
    0%, 100% { transform: translate(-50%, -50%) scale(1); }
    50% { transform: translate(-48%, -52%) scale(1.05); }
}

@keyframes nb-fade-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes nb-pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
    100% { opacity: 1; transform: scale(1); }
}

@keyframes nb-float-card {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes nb-pulse-logo {
    0%, 100% { filter: drop-shadow(0 0 20px rgba(168, 85, 247, 0.4)); transform: scale(1); }
    50% { filter: drop-shadow(0 0 35px rgba(168, 85, 247, 0.7)); transform: scale(1.05); }
}

