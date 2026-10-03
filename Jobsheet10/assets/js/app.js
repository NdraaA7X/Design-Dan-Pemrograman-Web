// ===== Hamburger menu (JS-driven) =====
function initNavToggle() {
  const toggleBtn = document.getElementById("nav-toggle-btn");
  const nav = document.querySelector("header nav");
  if (!toggleBtn || !nav) return;

  toggleBtn.addEventListener("click", function () {
    nav.classList.toggle("nav-open");
  });
}

// ===== Konfirmasi hapus via event submit =====
function initHapusConfirm() {
  document.addEventListener("submit", function (e) {
    const form = e.target;
    if (!form.classList.contains("form-hapus")) return;

    const row = form.closest("tr");
    const nama = row ? row.querySelector("td")?.textContent : "data ini";
    const yakin = confirm('Yakin ingin menghapus "' + nama + '"?');

    if (!yakin) {
      e.preventDefault();
    }
  });
}

// ===== Filter + counter =====
function updateCounter(table) {
  const counter = document.getElementById("filter-count");
  if (!counter) return;
  const rows = table.querySelectorAll("tbody tr");
  const visible = Array.from(rows).filter(
    (r) => r.style.display !== "none",
  ).length;
  counter.textContent =
    "Menampilkan " + visible + " dari " + rows.length + " data";
}

function initTableFilter() {
  const input = document.getElementById("search-input");
  const table = document.querySelector(".table-responsive table");
  if (!input || !table) return;

  updateCounter(table);

  input.addEventListener("keyup", function () {
    const keyword = input.value.toLowerCase();
    const rows = table.querySelectorAll("tbody tr");
    rows.forEach(function (row) {
      const judul = row.querySelector("td")?.textContent.toLowerCase() || "";
      row.style.display = judul.includes(keyword) ? "" : "none";
    });
    updateCounter(table);
  });
}

// ===== Validasi form (client-side) =====
function tampilkanError(input, pesan) {
  hapusError(input);
  const span = document.createElement("span");
  span.className = "error";
  span.textContent = pesan;
  input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
  const next = input.nextElementSibling;
  if (next && next.classList.contains("error")) {
    next.remove();
  }
}

function initValidasiForm() {
  const form = document.getElementById("form-tambah");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let valid = true;

    // Validasi nama / judul
    const judul = form.querySelector("[name='judul'], [name='nama']");
    if (judul && judul.value.trim() === "") {
      tampilkanError(judul, "Field ini wajib diisi.");
      valid = false;
    } else if (judul) {
      hapusError(judul);
    }

    // Validasi pengarang / no_anggota
    const pengarang = form.querySelector(
      "[name='pengarang'], [name='no_anggota']",
    );
    if (pengarang && pengarang.value.trim() === "") {
      tampilkanError(pengarang, "Field ini wajib diisi.");
      valid = false;
    } else if (pengarang) {
      hapusError(pengarang);
    }

    // Validasi username (form auth)
    const username = form.querySelector("[name='username']");
    if (username && username.value.trim() === "") {
      tampilkanError(username, "Username wajib diisi.");
      valid = false;
    } else if (username) {
      hapusError(username);
    }

    // Validasi password (form auth) — minimal 6 karakter
    const password = form.querySelector("[name='password']");
    if (password && password.value.length > 0 && password.value.length < 6) {
      tampilkanError(password, "Password minimal 6 karakter.");
      valid = false;
    } else if (password && password.value.length === 0 && password.hasAttribute("required")) {
      tampilkanError(password, "Password wajib diisi.");
      valid = false;
    } else if (password) {
      hapusError(password);
    }

    // Validasi tahun
    const tahun = form.querySelector("[name='tahun']");
    if (tahun) {
      const nilai = parseInt(tahun.value, 10);
      if (isNaN(nilai) || nilai < 1900 || nilai > 2026) {
        tampilkanError(tahun, "Tahun harus di antara 1900-2026.");
        valid = false;
      } else {
        hapusError(tahun);
      }
    }

    // Validasi stok
    const stok = form.querySelector("[name='stok']");
    if (stok) {
      const nilaiStok = parseInt(stok.value, 10);
      if (isNaN(nilaiStok) || nilaiStok < 0) {
        tampilkanError(stok, "Stok tidak boleh negatif.");
        valid = false;
      } else {
        hapusError(stok);
      }
    }

    if (!valid) {
      e.preventDefault();
    }
  });
}

document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initTableFilter();
  initValidasiForm();
});
