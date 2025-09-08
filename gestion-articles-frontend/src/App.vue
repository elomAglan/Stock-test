<template>
  <div>
    <!-- ✅ Si pas connecté -->
    <div v-if="!token">
      <Login
        v-if="!showRegister"
        @loginSuccess="onLoginSuccess"
        @showRegister="showRegister = true"
      />

      <Register
        v-else
        @registerSuccess="onRegisterSuccess"
        @backToLogin="showRegister = false"
      />
    </div>

    <!-- ✅ Si connecté, afficher le Dashboard -->
    <div v-else class="flex h-screen bg-gray-100">
      <!-- Sidebar -->
      <Sidebar
        :activeTab="activeTab"
        @update:tab="activeTab = $event"
        @logout="onLogout"
      />

      <div class="flex-1 flex flex-col">
        <!-- Topbar -->
        <Topbar :user="currentUser" :activeTab="activeTab" />

        <!-- Contenu principal -->
        <main class="flex-1 p-6 overflow-auto">
          <Dashboard v-if="activeTab === 'dashboard'" />
          <Articles v-if="activeTab === 'articles'" />
          <Categories v-if="activeTab === 'categories'" />
          <History v-if="activeTab === 'history'" /> <!-- Onglet Historique -->
        </main>
      </div>
    </div>
  </div>
</template>

<script>
import Login from "./components/Login.vue";
import Register from "./components/Register.vue";
import Sidebar from "./components/Sidebar.vue";
import Topbar from "./components/Topbar.vue";
import Dashboard from "./components/Dashboard.vue";
import Articles from "./components/Articles.vue";
import Categories from "./components/Categories.vue";
import History from "./components/History.vue"; // ✅ Historique
import { getUser, logoutUser } from "./services/api.js";

export default {
  name: "App",
  components: {
    Login,
    Register,
    Sidebar,
    Topbar,
    Dashboard,
    Articles,
    Categories,
    History,
  },
  data() {
    return {
      activeTab: "dashboard",
      token: localStorage.getItem("token") || null,
      currentUser: null,
      showRegister: false,
    };
  },
  async created() {
    if (this.token) {
      try {
        await this.fetchUser();
      } catch (e) {
        localStorage.removeItem("token");
        this.token = null;
      }
    }
  },
  methods: {
    async fetchUser() {
      const response = await getUser();
      this.currentUser = response.data;
    },
    onLoginSuccess(token, user) {
      localStorage.setItem("token", token);
      this.token = token;
      this.currentUser = user;
    },
    onRegisterSuccess(token, user) {
      localStorage.setItem("token", token);
      this.token = token;
      this.currentUser = user;
      this.showRegister = false;
    },
    async onLogout() {
      try {
        await logoutUser();
      } catch (error) {
        console.warn("Erreur lors de la déconnexion :", error);
      }
      localStorage.removeItem("token");
      this.token = null;
      this.currentUser = null;
    },
  },
};
</script>

<style scoped>
/* Tout est géré par Tailwind dans le template */
</style>
