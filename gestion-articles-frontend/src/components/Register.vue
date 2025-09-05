<template>
  <div class="flex justify-center items-center h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-6 rounded shadow">
      <h2 class="text-2xl font-bold mb-4">Inscription</h2>

      <div v-if="error" class="bg-red-100 text-red-700 p-2 mb-4 rounded">
        {{ error }}
      </div>

      <form @submit.prevent="register">
        <div class="mb-4">
          <label class="block mb-1">Nom</label>
          <input v-model="name" type="text" class="w-full border p-2 rounded" required />
        </div>

        <div class="mb-4">
          <label class="block mb-1">Email</label>
          <input v-model="email" type="email" class="w-full border p-2 rounded" required />
        </div>

        <div class="mb-4">
          <label class="block mb-1">Mot de passe</label>
          <input v-model="password" type="password" class="w-full border p-2 rounded" required />
        </div>

        <div class="mb-4">
          <label class="block mb-1">Confirmer le mot de passe</label>
          <input v-model="password_confirmation" type="password" class="w-full border p-2 rounded" required />
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
          S'inscrire
        </button>
      </form>

      <p class="mt-4 text-center text-gray-600">
        Déjà inscrit ? 
        <a href="#" @click.prevent="$emit('switch-to-login')" class="text-indigo-600 hover:underline">Connexion</a>
      </p>
    </div>
  </div>
</template>

<script>
import api from "../services/api"; // ton fichier api.js avec Axios

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
        // Envoi des données au backend
        await api.post("/register", {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        });

        // Après création, rediriger vers login (au lieu de stocker le token)
        this.$emit("switch-to-login");

      } catch (err) {
        if (err.response && err.response.status === 422) {
          // Affiche toutes les erreurs de validation
          this.error = Object.values(err.response.data.errors).flat().join(", ");
        } else {
          this.error = err.response?.data?.message || "Erreur lors de l'inscription";
        }
      }
    }
  }
};
</script>
