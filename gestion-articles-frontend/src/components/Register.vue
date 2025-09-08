<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center items-center p-6">
    <div class="max-w-md w-full mx-auto bg-white rounded-2xl shadow-xl p-8 transform transition-all duration-300 hover:scale-105">

      <!-- Header -->
      <div class="flex flex-col items-center mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM12 18h.01M17 18h.01M19 18h.01M12 11V6a2 2 0 012-2h2a2 2 0 012 2v2m0 0h-4m-4 0v-4" />
        </svg>
        <h2 class="text-3xl font-extrabold text-gray-900">Créer un compte</h2>
      </div>

      <!-- Erreur -->
      <div v-if="error" class="bg-red-500 text-white p-4 mb-4 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300">
        <p>{{ error }}</p>
      </div>

      <!-- Formulaire d'inscription -->
      <form @submit.prevent="register" class="grid grid-cols-1 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
          <input v-model="name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" required />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input v-model="email" type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" required />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
          <input v-model="password" type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" required />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
          <input v-model="password_confirmation" type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" required />
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white font-medium py-3 px-6 rounded-lg hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center justify-center">
          S'inscrire
        </button>
      </form>

      <!-- Lien vers Login -->
      <p class="mt-6 text-center text-gray-600">
        Déjà un compte ?
        <a href="#" @click.prevent="$emit('backToLogin')" class="text-indigo-600 font-medium hover:underline">
          Connectez-vous ici
        </a>
      </p>
    </div>
  </div>
</template>

<script>
import api from "../services/api"; // votre fichier api.js avec Axios

export default {
  name: "Register",
  data() {
    return {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      error: null
    };
  },
  methods: {
    async register() {
      this.error = null;
      try {
        await api.post("/register", {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        });

        // Après création, retourner sur le Login
        this.$emit("backToLogin");

      } catch (err) {
        if (err.response && err.response.status === 422) {
          this.error = Object.values(err.response.data.errors).flat().join(", ");
        } else {
          this.error = err.response?.data?.message || "Erreur lors de l'inscription";
        }
      }
    }
  }
};
</script>
