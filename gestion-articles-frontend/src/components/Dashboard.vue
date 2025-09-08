<template>
  <div>
    <h1 class="text-3xl font-bold mb-6">Bienvenue !</h1>

    <!-- Cadres -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <p class="text-gray-500">Articles</p>
        <p class="text-2xl font-bold">{{ articlesCount }}</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md">
        <p class="text-gray-500">Stocks totaux</p>
        <p class="text-2xl font-bold">{{ totalStock }}</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md">
        <p class="text-gray-500">Valeur totale</p>
        <p class="text-2xl font-bold">{{ totalValue }} €</p>
      </div>
    </div>

    <!-- Graphique -->
    <DashboardChart
      v-if="articles.length"
      :articles-count="articlesCount"
      :total-stock="totalStock"
      :total-value="totalValue"
    />
  </div>
</template>

<script>
import axios from "axios";
import DashboardChart from "./DashboardChart.vue";

export default {
  components: { DashboardChart },
  data() {
    return {
      articles: [],
      articlesCount: 0,
      totalStock: 0,
      totalValue: 0
    };
  },
  async mounted() {
    try {
      const token = localStorage.getItem("token");
      if (!token) return console.error("Utilisateur non authentifié !");

      const res = await axios.get("http://127.0.0.1:8000/api/articles", {
        headers: { Authorization: `Bearer ${token}`, Accept: "application/json" }
      });

      this.articles = res.data;
      this.articlesCount = this.articles.length;
      this.totalStock = this.articles.reduce((sum, a) => sum + a.stock, 0);
      this.totalValue = this.articles.reduce(
        (sum, a) => sum + a.stock * a.price,
        0
      );
    } catch (err) {
      console.error("Erreur chargement dashboard:", err.response || err);
    }
  }
};
</script>
