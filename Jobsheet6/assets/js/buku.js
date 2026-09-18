// Fungsi generik untuk memuat data tabel dari JSON (Latihan 2)
async function muatDataTabel(urlJson, kunciKolom) {
  const tbody = document.querySelector(".table-responsive table tbody");
  const loading = document.getElementById("loading-indicator");
  if (!tbody) return;

  loading.style.display = "block";
  tbody.innerHTML = "";

  try {
    await new Promise((resolve) => setTimeout(resolve, 600));

    const res = await fetch(urlJson);
    if (!res.ok) {
      throw new Error("Gagal mengambil data (status " + res.status + ")");
    }

    const daftarData = await res.json();

    daftarData.forEach(function (item) {
      const tr = document.createElement("tr");
      let selCells = "";
      kunciKolom.forEach(function (kunci) {
        selCells += "<td>" + item[kunci] + "</td>";
      });
      tr.innerHTML =
        selCells +
        "<td>" +
        '<button type="button">Detail</button> ' +
        '<button type="button">Edit</button> ' +
        '<button type="button" class="btn-hapus">Hapus</button>' +
        "</td>";
      tbody.appendChild(tr);
    });

    if (typeof updateCounter === "function") {
      updateCounter(tbody.closest("table"));
    }
  } catch (err) {
    tbody.innerHTML =
      '<tr><td colspan="6">Gagal memuat data: ' + err.message + "</td></tr>";
  } finally {
    loading.style.display = "none";
  }
}

// Muat data buku dengan kolom Kategori (Latihan 3)
document.addEventListener("DOMContentLoaded", function () {
  muatDataTabel("../data/buku.json", [
    "judul",
    "pengarang",
    "tahun",
    "stok",
    "kategori",
  ]);

  // Tombol Muat Ulang (Latihan 1)
  const btnMuatUlang = document.getElementById("btn-muat-ulang");
  if (btnMuatUlang) {
    btnMuatUlang.addEventListener("click", function () {
      muatDataTabel("../data/buku.json", [
        "judul",
        "pengarang",
        "tahun",
        "stok",
        "kategori",
      ]);
    });
  }
});
