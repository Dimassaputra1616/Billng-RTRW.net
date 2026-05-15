/* ══════════════════════════════
   Authentication Pages (Login)
   ══════════════════════════════ */
.fi-simple-layout {
    background: linear-gradient(rgba(6, 4, 13, 0.3), rgba(6, 4, 13, 0.4)), url('{{ asset('images/backgrounds/logo-bg.jpg') }}'), url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop') !important;
    background-size: cover !important;
    background-position: center !important;
    background-attachment: fixed !important;
    min-height: 100vh !important;
    height: 100vh !important;
    overflow: hidden !important; /* Kunci biar ga bisa scroll */
    position: relative !important;
}

.fi-simple-main-ctn {
    position: relative !important;
    z-index: 10 !important;
}

/* Background Glow Blobs */
.fi-simple-layout::after {
    content: '' !important;
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    width: 600px !important;
    height: 600px !important;
    background: radial-gradient(circle, rgba(168, 85, 247, 0.3) 0%, transparent 70%) !important;
    filter: blur(80px) !important;
    z-index: 1 !important;
    animation: nb-float 15s infinite ease-in-out !important;
    pointer-events: none !important;
}

/* Login Card (Ultra Premium Glassmorphism) */
.fi-simple-main {
    border-radius: 40px !important;
    backdrop-filter: blur(25px) saturate(180%) !important;
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    box-shadow: 
        0 40px 100px -20px rgba(0, 0, 0, 0.7),
        inset 0 0 20px rgba(255, 255, 255, 0.05) !important;
    padding: 60px 48px !important;
    animation: 
        nb-fade-up 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards,
        nb-float-card 6s ease-in-out infinite !important;
}

html:not(.dark) .fi-simple-main {
    background: rgba(255, 255, 255, 0.7) !important;
    border: 1px solid rgba(255, 255, 255, 0.4) !important;
    backdrop-filter: blur(20px) !important;
}

/* Brand Logo (Pulsing Glow - Fixed Cut-off) */
.fi-simple-main .fi-logo {
    font-size: 40px !important;
    font-weight: 950 !important;
    background: linear-gradient(135deg, #a855f7, #6366f1) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    filter: drop-shadow(0 0 25px rgba(168, 85, 247, 0.5)) !important;
    margin-bottom: 32px !important;
    display: block !important;
    text-align: center !important;
    padding: 15px 0 !important; /* Tambahin ruang biar nggak kepotong */
    overflow: visible !important;
    animation: nb-pulse-logo 4s ease-in-out infinite !important;
}


/* Auth Header */
.fi-simple-header-heading {
    font-size: 26px !important;
    font-weight: 900 !important;
    color: var(--nb-text-primary) !important;
    text-align: center !important;
    margin-bottom: 40px !important;
    letter-spacing: -0.02em !important;
}

/* Inputs (Cyber Focus) */
.fi-simple-main .fi-input-wrapper {
    height: 58px !important;
    background: rgba(0, 0, 0, 0.3) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 20px !important;
    transition: all 0.3s ease !important;
}

.fi-simple-main .fi-input-wrapper:focus-within {
    border-color: #a855f7 !important;
    box-shadow: 0 0 25px rgba(168, 85, 247, 0.3) !important;
    background: rgba(0, 0, 0, 0.4) !important;
    transform: scale(1.02) !important;
}

/* Button (3D Effect) */
.fi-simple-main .fi-btn[type="submit"] {
    position: relative !important;
    overflow: hidden !important;
    height: 60px !important;
    border-radius: 20px !important;
    background: linear-gradient(135deg, #a855f7, #6366f1) !important;
    text-transform: uppercase !important;
    font-weight: 950 !important;
    letter-spacing: 0.1em !important;
    box-shadow: 0 20px 40px -10px rgba(168, 85, 247, 0.5) !important;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
}

.fi-simple-main .fi-btn[type="submit"]:hover {
    transform: translateY(-5px) scale(1.02) !important;
    box-shadow: 0 30px 60px -15px rgba(168, 85, 247, 0.6) !important;
}

.fi-simple-main .fi-btn[type="submit"]::after {
    content: '' !important;
    position: absolute !important;
    top: -50% !important;
    left: -100% !important;
    width: 100% !important;
    height: 200% !important;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent) !important;
    transform: rotate(35deg) !important;
    animation: nb-shine 4s infinite linear !important;
}

/* ══════════════════════════════
   Mobile Optimizations (PWA Fix)
   ══════════════════════════════ */
@media (max-width: 640px) {
    .fi-simple-layout {
        background-attachment: scroll !important; /* Fix background goyang di HP */
    }

    .fi-simple-main {
        padding: 40px 24px !important;
        border-radius: 30px !important;
        backdrop-filter: blur(15px) !important; /* Blur lebih ringan biar nggak lag */
        margin: 16px !important;
        animation: nb-fade-up 0.8s ease-out forwards !important; /* Matikan float di HP biar stabil */
    }

    .fi-simple-main .fi-logo {
        font-size: 32px !important;
        margin-bottom: 24px !important;
    }

    .fi-simple-header-heading {
        font-size: 20px !important;
        margin-bottom: 30px !important;
    }
}

