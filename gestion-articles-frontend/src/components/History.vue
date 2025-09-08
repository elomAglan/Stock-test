<template>
  <div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
      
      <div class="flex flex-col sm:flex-row items-center justify-between mb-4 pb-4 border-b border-gray-200">
        <h1 class="text-3xl font-extrabold text-gray-900 flex items-center mb-4 sm:mb-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14v1a3 3 0 003 3h.5a3 3 0 003-3v-1m-4 2H9.5a3 3 0 01-3-3v-2m-2-2h4m-4 0h1a2 2 0 012 2v2m-2 0H6a2 2 0 01-2-2v-2m2 0h1m2-2h4a2 2 0 002-2V7a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
          </svg>
          Historique des actions
        </h1>
        <div class="relative w-full sm:w-auto">
          <input 
            v-model="search" 
            type="text" 
            placeholder="Rechercher par utilisateur, action..." 
            class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm w-full sm:w-80 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
          />
          <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
      </div>

      <div v-if="loading" class="flex justify-center items-center py-12">
        <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      
      <div v-else-if="filteredLogs.length === 0" class="text-gray-500 text-center py-12 flex flex-col items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M9 13.5a4.5 4.5 0 111.952-6.526 4.5 4.5 0 01-1.952 6.526zM2 10a8 8 0 1116 0 8 8 0 01-16 0z" clip-rule="evenodd" />
        </svg>
        <p class="text-lg font-medium">Aucun log correspondant à votre recherche.</p>
      </div>

      <div v-else class="overflow-hidden border border-gray-200 rounded-xl shadow-sm">
        <div class="overflow-y-auto" style="max-height: 500px;">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Utilisateur</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Action</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Détails</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="log in filteredLogs" :key="log.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ log.user?.name || 'Utilisateur inconnu' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                  <span class="font-semibold text-indigo-600">{{ log.action }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 max-w-lg">
                  <pre class="whitespace-pre-wrap font-mono text-xs text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-200">{{ formatDetails(log.details) }}</pre>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(log.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      logs: [],
      search: "",
      loading: false,
    };
  },
  computed: {
    filteredLogs() {
      if (!this.search) return this.logs;
      const term = this.search.toLowerCase();
      return this.logs.filter(log => 
        (log.user?.name || '').toLowerCase().includes(term) ||
        (log.action || '').toLowerCase().includes(term) ||
        (this.formatDetails(log.details) || '').toLowerCase().includes(term)
      );
    },
  },
  async created() {
    this.loading = true;
    try {
      const res = await axios.get("http://127.0.0.1:8000/api/logs", {
        headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
      });
      this.logs = Array.isArray(res.data) ? res.data : [];
    } catch (error) {
      console.error("Impossible de charger les logs :", error);
    } finally {
      this.loading = false;
    }
  },
  methods: {
    formatDate(dateStr) {
      if (!dateStr) return "Date inconnue";
      const date = new Date(dateStr);
      return isNaN(date.getTime()) ? "Date invalide" : date.toLocaleString('fr-FR');
    },
    formatDetails(details) {
      if (!details) return "-";
      try { return JSON.stringify(JSON.parse(details), null, 2); } 
      catch { return details; }
    },
  },
};
</script>