<template>
  <div class="flex justify-center items-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-96">
      <h2 class="text-2xl mb-4">Connexion</h2>
      <form @submit.prevent="login">
        <input
          v-model.trim="email"
          type="email"
          placeholder="Email"
          class="w-full p-2 mb-3 border rounded"
          required
        />
        <input
          v-model.trim="password"
          type="password"
          placeholder="Mot de passe"
          class="w-full p-2 mb-3 border rounded"
          required
        />
        <button
          type="submit"
          class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
        >
          Se connecter
        </button>
      </form>

      <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>

      <p class="mt-4 text-center text-gray-600">
        Pas de compte ? 
        <button @click="$emit('showRegister')" class="text-blue-500 hover:underline">
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
        // 1️⃣ Obtenir le CSRF cookie (nécessaire si Sanctum + SPA)
        await axios.get("http://127.0.0.1:8000/sanctum/csrf-cookie", {
          withCredentials: true,
        });

        // 2️⃣ Envoyer la requête de login
        const response = await axios.post(
          "http://127.0.0.1:8000/api/login",
          {
            email: this.email,
            password: this.password,
          },
          { withCredentials: true }
        );

        // 3️⃣ Récupérer le token
        const token = response.data.access_token;
        localStorage.setItem("token", token);

        // 4️⃣ Récupérer l'utilisateur connecté
        const userResponse = await axios.get(
          "http://127.0.0.1:8000/api/user",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        // 5️⃣ Émettre l'événement vers le parent
        this.$emit("loginSuccess", token, userResponse.data);

      } catch (err) {
        if (err.response?.status === 422) {
          this.error = "Email ou mot de passe incorrect.";
        } else {
          this.error = "Erreur lors de la connexion. Réessayez.";
        }
        console.error(err);
      }
    },
  },
};
</script>
