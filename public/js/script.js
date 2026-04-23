document.addEventListener("DOMContentLoaded", () => {
    initTracking();
    initAdmitCard();
    initAuthLogic(); 
});

// =========================================
// 1. AUTHENTICATION (LOGIN/REGISTER)
// =========================================

function initAuthLogic() {
    const loginBtn = document.getElementById('pills-login-tab');
    const signupBtn = document.getElementById('pills-signup-tab');
    // Note: The Bootstrap pills handle most of the toggle logic now.
    // If you need custom logic, add it here.
}


// =========================================
// 2. TRACKING & ADMIT CARD
// =========================================
function initTracking() {
    const trackingForm = document.getElementById('trackingForm');
    const resultSection = document.getElementById('tracking-result-section');
    if (!trackingForm || !resultSection) return;

    trackingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button');
        const appNumber = this.querySelector('input[type="text"]').value;

        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Checking...';
        btn.disabled = true;

        setTimeout(() => {
            resultSection.style.display = 'block';
            const statusHeader = resultSection.querySelector('h4');
            if(statusHeader) statusHeader.innerHTML = `<i class="fas fa-circle-check me-2"></i>Status for ${appNumber}`;
            resultSection.scrollIntoView({ behavior: 'smooth' });
            btn.innerHTML = '<i class="fas fa-magnifying-glass me-2"></i>Check Status';
            btn.disabled = false;
        }, 1500);
    });
}

function initAdmitCard() {
    const admitForm = document.getElementById('admitCardForm');
    const resultSection = document.getElementById('admit-result-section');
    if (!admitForm || !resultSection) return;

    admitForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button');

        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';
        btn.disabled = true;

        // Dummy data (normally backend se aata hai)
        let user = {
            name: "Rahul Sharma",
            mobile: "9876543210",
            dob: document.querySelector('input[name="dob"]').value,
            roll: document.querySelector('input[name="roll"]').value,
            image: "https://via.placeholder.com/100"
        };

        // Fill data in admit card
        const c_name = document.getElementById("c_name");
        if(c_name) c_name.innerText = user.name;
        const c_mobile = document.getElementById("c_mobile");
        if(c_mobile) c_mobile.innerText = user.mobile;
        const c_dob = document.getElementById("c_dob");
        if(c_dob) c_dob.innerText = user.dob;
        const c_roll = document.getElementById("c_roll");
        if(c_roll) c_roll.innerText = user.roll;
        const c_img = document.getElementById("c_img");
        if(c_img) c_img.src = user.image;

        setTimeout(() => {
            resultSection.style.display = 'block';
            resultSection.scrollIntoView({ behavior: 'smooth' });
            btn.innerHTML = '<i class="fas fa-download me-2"></i>Generate Admit Card';
            btn.disabled = false;
            
            // PDF Generation logic if needed
            generatePDF();
        }, 1500);
    });
}

function generatePDF() {
    const card = document.getElementById("admitCard");
    if (!card) return;
    
    // Check if libraries are loaded
    if (typeof html2canvas !== "undefined" && typeof jspdf !== "undefined") {
        html2canvas(card).then(canvas => {
            const imgData = canvas.toDataURL("image/png");
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF();
            pdf.addImage(imgData, 'PNG', 10, 10, 180, 0);
            pdf.save("AdmitCard.pdf");
        });
    }
}
