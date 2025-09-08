<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Historique des actions</h1>

    <table class="min-w-full bg-white border border-gray-200">
      <thead>
        <tr class="bg-gray-100">
          <th class="px-4 py-2 border">Utilisateur</th>
          <th class="px-4 py-2 border">Action</th>
          <th class="px-4 py-2 border">Détails</th>
          <th class="px-4 py-2 border">Date</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="logs.length === 0">
          <td class="px-4 py-2 border text-center" colspan="4">Aucun log disponible</td>
        </tr>
        <tr v-for="log in logs" :key="log.id">
          <td class="px-4 py-2 border">{{ log.user?.name || 'Utilisateur inconnu' }}</td>
          <td class="px-4 py-2 border">{{ log.action }}</td>
          <td class="px-4 py-2 border">
            <pre class="whitespace-pre-wrap">{{ formatDetails(log.details) }}</pre>
          </td>
          <td class="px-4 py-2 border">{{ formatDate(log.created_at) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      logs: [],
    };
  },
  async created() {
    try {
      // ✅ Base URL complet pour pointer sur Laravel
      const res = await axios.get("http://127.0.0.1:8000/api/logs", {
        headers: { 
          Authorization: `Bearer ${localStorage.getItem("token")}`,
          Accept: "application/json" // forcer JSON
        },
      });

      // Vérifie bien que res.data est un tableau
      this.logs = Array.isArray(res.data) ? res.data : [];
      console.log("Logs reçus :", this.logs);
    } catch (error) {
      console.error("Impossible de charger les logs :", error.response || error);
    }
  },
  methods: {
    formatDate(dateStr) {
      if (!dateStr) return "Date inconnue";
      const date = new Date(dateStr);
      return isNaN(date.getTime()) ? "Date invalide" : date.toLocaleString();
    },
    formatDetails(details) {
      if (!details) return "-";
      try {
        const obj = JSON.parse(details);
        return JSON.stringify(obj, null, 2);
      } catch {
        return details;
      }
    },
  },
};
</script>

<style scoped>
pre {
  font-family: monospace;
  font-size: 0.875rem;
}
</style>
