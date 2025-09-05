<template>
  <div>
    <!-- Si pas connecté, afficher Login ou Register -->
    <Login
      v-if="!token && !showRegister"
      @loginSuccess="onLoginSuccess"
      @showRegister="showRegister = true"
    />

    <Register
      v-if="!token && showRegister"
      @switch-to-login="showRegister = false"
    />

    <!-- Si connecté, afficher le layout complet -->
    <div v-else class="flex h-screen bg-gray-100">
      <Sidebar :activeTab="activeTab" @update:tab="activeTab = $event" @logout="onLogout" />
      <div class="flex-1 flex flex-col">
        <Topbar :user="currentUser" @logout="onLogout" />
        <main class="flex-1 p-6 overflow-auto">
          <Dashboard v-if="activeTab === 'dashboard'" />
          <Articles v-if="activeTab === 'articles'" />
          <Categories v-if="activeTab === 'categories'" />
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
import { getUser, logoutUser } from "./services/api.js";

export default {
  components: { Login, Register, Sidebar, Topbar, Dashboard, Articles, Categories },
  data() {
    return {
      activeTab: "dashboard",
      token: localStorage.getItem("token") || null,
      currentUser: null,
      showRegister: false,
    };
  },
  created() {
    if (this.token) this.fetchUser();
  },
  methods: {
    async fetchUser() {
      try {
        const response = await getUser();
        this.currentUser = response.data;
      } catch (error) {
        console.error("Impossible de récupérer l'utilisateur :", error);
        this.onLogout();
      }
    },
    onLoginSuccess(token, user) {
      localStorage.setItem("token", token);
      this.token = token;
      this.currentUser = user;
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
