<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center items-center p-6">
    <div class="max-w-md w-full mx-auto bg-white rounded-2xl shadow-xl p-8 transform transition-all duration-300 hover:scale-105">

      <div class="flex flex-col items-center mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
        </svg>
        <h2 class="text-3xl font-extrabold text-gray-900">Connexion</h2>
      </div>

      <div v-if="error" class="bg-red-500 text-white p-4 mb-4 rounded-lg shadow-md flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <p>{{ error }}</p>
      </div>

      <form @submit.prevent="login" class="grid grid-cols-1 gap-6">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input
            id="email"
            v-model.trim="email"
            type="email"
            placeholder="Votre adresse email"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
            required
          />
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
          <input
            id="password"
            v-model.trim="password"
            type="password"
            placeholder="Votre mot de passe"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
            required
          />
        </div>
        <button
          type="submit"
          class="w-full bg-indigo-600 text-white font-medium py-3 px-6 rounded-lg hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center justify-center space-x-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
          </svg>
          <span>Se connecter</span>
        </button>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Pas de compte ?
        <button
          @click="$emit('showRegister')"
          class="text-indigo-600 font-medium hover:underline hover:text-indigo-800 transition-colors"
        >
          Inscrivez-vous
        </button>
      </p>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      email: "",
      password: "",
      error: "",
    };
  },
  methods: {
    async login() {
      this.error = "";

      if (!this.email || !this.password) {
        this.error = "Veuillez remplir tous les champs.";
        return;
      }

      try {
        const response = await axios.post("http://127.0.0.1:8000/api/login", {
          email: this.email,
          password: this.password,
        });

        const token = response.data.access_token;
        if (!token) {
          this.error = "Token non reçu du serveur.";
          return;
        }

        localStorage.setItem("token", token);

        const userResponse = await axios.get("http://127.0.0.1:8000/api/user", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.$emit("loginSuccess", token, userResponse.data);
      } catch (err) {
        if (err.response?.status === 401) {
          this.error = "Identifiants incorrects.";
        } else if (err.response?.status === 422) {
          this.error = "Données invalides.";
        } else {
          this.error = "Erreur serveur. Réessayez plus tard.";
        }
        console.error("Erreur login:", err);
      }
    },
  },
};
</script>