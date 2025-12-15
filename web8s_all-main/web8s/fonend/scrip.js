/* ========================== */
/* QUỐC GIA KHÁC             */
/* ========================== */
function toggleQuocGiaKhac() {
    document.getElementById("quoc_gia_khac_box").style.display =
        (document.getElementById("quoc_gia").value === "Khác") ? "block" : "none";
}

/* ========================== */
/* SLIDER                    */
/* ========================== */
const slider = document.querySelector(".slider");
const images = document.querySelectorAll(".slider img");
let index = 0;

document.querySelector(".next").onclick = () => {
    index++;
    if (index >= images.length) index = 0;
    updateSlider();
};

document.querySelector(".prev").onclick = () => {
    index--;
    if (index < 0) index = images.length - 1;
    updateSlider();
};

function updateSlider() {
    slider.style.transform = `translateX(-${index * 100}%)`;
}

setInterval(() => {
    index++;
    if (index >= images.length) index = 0;
    updateSlider();
}, 4000);

/* ========================== */
/* SCROLL TO FORM            */
/* ========================== */
document.querySelectorAll('a[href="#dangky"]').forEach(btn => {
    btn.addEventListener("click", (e) => {
        e.preventDefault();
        document.getElementById("dangky").scrollIntoView({ behavior: "smooth" });
    });
});

/* ========================== */
/* VALIDATE HỌ TÊN           */
/* ========================== */
document.getElementById("ho_ten").addEventListener("input", function () {
    let value = this.value.replace(/[^\p{L}\s]/gu, "");
    value = value.replace(/\s{2,}/g, " ");
    value = value.replace(/\b\p{L}/gu, ch => ch.toUpperCase());
    this.value = value;
});

/* ========================== */
/* VALIDATE NĂM SINH         */
/* ========================== */
const namSinh = document.getElementById("nam_sinh");

namSinh.addEventListener("keydown", function (e) {
    const allowedKeys = ["Backspace", "Delete", "ArrowLeft", "ArrowRight", "Tab"];
    if (allowedKeys.includes(e.key)) return;
    if (!/^[0-9]$/.test(e.key)) e.preventDefault();
});

namSinh.addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, "");
    let year = parseInt(this.value);
    if (year > 2020) this.value = 2020;
    if (year < 1950 && this.value.length === 4) this.value = 1950;
});

/* ========================== */
/* VALIDATE SĐT              */
/* ========================== */
document.getElementById("sdt").addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, "");
});

/* ========================== */
/* GỬI FORM                  */
/* ========================== */
document.getElementById('userRegistrationForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const messageDisplay = document.getElementById('message');
    messageDisplay.textContent = 'Đang gửi thông tin...';
    messageDisplay.style.color = '#0f75bd';

    const data = {
        ho_ten: document.getElementById('ho_ten').value,
        nam_sinh: document.getElementById('nam_sinh').value,
        dia_chi: document.getElementById('dia_chi').value,
        chuong_trinh: document.getElementById('chuong_trinh').value,
        quoc_gia: document.getElementById('quoc_gia').value,
        quoc_gia_khac: document.getElementById('quoc_gia_khac')?.value || "",
        sdt: document.getElementById('sdt').value
    };

    fetch('http://localhost/web8s/backend_api/insert.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if (result.status) {
            messageDisplay.textContent = '✅ Đăng ký thành công!';
            messageDisplay.style.color = 'green';
            event.target.reset();
        } else {
            messageDisplay.textContent = '❌ ' + result.message;
            messageDisplay.style.color = 'red';
        }
    })
    .catch(() => {
        messageDisplay.textContent = '❌ Không kết nối được server';
        messageDisplay.style.color = 'red';
    });
});
