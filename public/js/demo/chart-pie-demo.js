var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: productLabels,
    datasets: [{
      data: productTotals,
      backgroundColor: [
        "#f36c6c", "#6cb6f3", "#f3e96c", "#6cf3d5", "#b76cf3", "#f3c36c",
        "#4e73df", "#1cc88a", "#36b9cc"
      ],
      hoverBackgroundColor: [
        "#e74a3b", "#4e8cff", "#f7da00", "#1de9b6", "#a26cf3", "#f5b96b",
        "#2e59d9", "#17a673", "#2c9faf"
      ],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: true,
      position: 'top'
    },
    cutoutPercentage: 60,
  },
});