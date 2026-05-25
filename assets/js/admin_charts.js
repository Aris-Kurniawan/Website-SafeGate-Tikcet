// assets/js/admin_charts.js - Interaktivitas Grafik Dasbor Admin SafeGate

document.addEventListener("DOMContentLoaded", () => {
    const bars = document.querySelectorAll(".sg-chart-bar-rect");
    
    // Animasi bar saat halaman dimuat
    bars.forEach((bar, idx) => {
        const targetHeight = parseFloat(bar.getAttribute("data-height") || "0");
        const targetY = parseFloat(bar.getAttribute("data-y") || "0");
        const baseHeight = 280; // Koordinat dasar lantai grafik
        
        // Mulai dari tinggi 0 di bagian bawah
        bar.setAttribute("height", "0");
        bar.setAttribute("y", baseHeight.toString());
        
        // Berikan transisi CSS smooth
        bar.style.transition = "height 0.8s cubic-bezier(0.25, 1, 0.5, 1), y 0.8s cubic-bezier(0.25, 1, 0.5, 1)";
        bar.style.transitionDelay = `${idx * 80}ms`;
        
        // Picu animasi setelah render awal selesai
        setTimeout(() => {
            bar.setAttribute("height", targetHeight.toString());
            bar.setAttribute("y", targetY.toString());
        }, 50);
        
        // Efek Hover
        bar.addEventListener("mouseenter", (e) => {
            bar.style.filter = "drop-shadow(0 0 8px rgba(217, 255, 0, 0.7))";
            bar.style.fillOpacity = "0.75";
            
            const label = bar.getAttribute("data-label") || "";
            const value = bar.getAttribute("data-value") || "";
            showTooltip(e, `${label}: ${value}`);
        });
        
        bar.addEventListener("mouseleave", () => {
            bar.style.filter = "none";
            bar.style.fillOpacity = "0.35";
            hideTooltip();
        });
    });
});

let tooltipEl = null;

function showTooltip(e, text) {
    if (!tooltipEl) {
        tooltipEl = document.createElement("div");
        tooltipEl.className = "sg-chart-tooltip";
        document.body.appendChild(tooltipEl);
    }
    tooltipEl.textContent = text;
    tooltipEl.style.display = "block";
    tooltipEl.style.position = "absolute";
    tooltipEl.style.background = "#12161F";
    tooltipEl.style.color = "#D9FF00";
    tooltipEl.style.border = "1px solid rgba(217, 255, 0, 0.4)";
    tooltipEl.style.padding = "6px 12px";
    tooltipEl.style.borderRadius = "6px";
    tooltipEl.style.fontSize = "12px";
    tooltipEl.style.fontWeight = "700";
    tooltipEl.style.pointerEvents = "none";
    tooltipEl.style.boxShadow = "0 8px 24px rgba(0,0,0,0.6)";
    tooltipEl.style.zIndex = "9999";
    tooltipEl.style.fontFamily = "'Inter', sans-serif";
    
    moveTooltip(e);
}

function moveTooltip(e) {
    if (tooltipEl) {
        tooltipEl.style.left = `${e.pageX + 12}px`;
        tooltipEl.style.top = `${e.pageY - 38}px`;
    }
}

function hideTooltip() {
    if (tooltipEl) {
        tooltipEl.style.display = "none";
    }
}

document.addEventListener("mousemove", (e) => {
    if (tooltipEl && tooltipEl.style.display === "block") {
        moveTooltip(e);
    }
});
