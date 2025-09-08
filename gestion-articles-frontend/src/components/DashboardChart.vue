<template>
  <div class="bg-white p-6 rounded-lg shadow-md">
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>

<script>
import { Chart, registerables } from "chart.js";
Chart.register(...registerables);

export default {
  name: "DashboardChart",
  props: {
    articlesCount: { type: Number, required: true },
    totalStock: { type: Number, required: true },
    totalValue: { type: Number, required: true }
  },
  data() {
    return { chart: null };
  },
  methods: {
    renderChart() {
      if (!this.$refs.chartCanvas) return;

      const ctx = this.$refs.chartCanvas.getContext("2d");

      // Détruit l’ancien graphique avant d’en recréer un
      if (this.chart) {
        this.chart.destroy();
      }

      this.chart = new Chart(ctx, {
        type: "line",
        data: {
          labels: ["Articles", "Stock total", "Valeur totale (€)"],
          datasets: [
            {
              label: "Évolution du Dashboard",
              data: [this.articlesCount, this.totalStock, this.totalValue],
              borderColor: "#6366F1",
              backgroundColor: "rgba(99,102,241,0.2)",
              fill: true,
              tension: 0.3, // rend la courbe lissée
              pointBackgroundColor: "#6366F1",
              pointRadius: 5
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: true },
            title: { display: true, text: "Courbe des Statistiques" }
          },
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    }
  },
  mounted() {
    this.renderChart();
  },
  watch: {
    articlesCount: "renderChart",
    totalStock: "renderChart",
    totalValue: "renderChart"
  }
};
</script>
