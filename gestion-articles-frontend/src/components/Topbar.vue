<template>
  <header class="bg-slate-900 text-gray-200 flex justify-between items-center px-6 h-16 shadow-md">
    <!-- Titre dynamique -->
    <div class="text-xl font-bold text-white">
      {{ tabTitle }}
    </div>

    <!-- Section compte utilisateur -->
    <div class="flex items-center space-x-4">
      <div class="text-gray-300">Bonjour, {{ user?.name || "Utilisateur" }}</div>
      <img
        :src="user?.avatar || 'https://i.pravatar.cc/150?img=3'"
        alt="Avatar"
        class="w-10 h-10 rounded-full border-2 border-indigo-500"
      />
    </div>
  </header>
</template>

<script>
export default {
  name: "Topbar",
  props: {
    user: {
      type: Object,
      default: () => ({ name: "Utilisateur", avatar: null }),
    },
    activeTab: {
      type: String,
      default: "dashboard",
    },
  },
  computed: {
    tabTitle() {
      switch (this.activeTab) {
        case "dashboard":
          return "Mon Dashboard";
        case "articles":
          return "Gestion des Articles";
        case "categories":
          return "Gestion des Catégories";
        case "history":
          return "Historique des actions";
        default:
          return "Mon Application";
      }
    },
  },
};
</script>
