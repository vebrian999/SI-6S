var ctx = document.getElementById("myChart").getContext("2d");
var myChart = new Chart(ctx, {
  type: "bar",
  data: {
    labels: ["Jumlah Pengguna Terdaftar", "Total Produk Tersedia", "Total Produk Terjual", "Jumlah Produk yang Dihapus"], // Label untuk data
    datasets: [
      {
        label: "Statistik Bulan Ini", // Label untuk grafik
        data: [10, 12, 19, 3], // Data jumlah pengguna terdaftar, total produk tersedia, total produk terjual, dan jumlah produk yang dihapus dalam satu bulan
        backgroundColor: ["rgba(54, 162, 235, 0.2)", "rgba(75, 192, 192, 0.2)", "rgba(153, 102, 255, 0.2)", "rgba(255, 159, 64, 0.2)"],
        borderColor: ["rgba(54, 162, 235, 1)", "rgba(75, 192, 192, 1)", "rgba(153, 102, 255, 1)", "rgba(255, 159, 64, 1)"],
        borderWidth: 1,
      },
    ],
  },
  options: {
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  },
});
