<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-xl p-8">

      <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6m7-10V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-5m-15 0h14" />
          </svg>
          Gestion des Articles
        </h1>
        <button
          @click="openAddModal"
          class="bg-indigo-600 text-white font-medium py-3 px-6 rounded-xl hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Ajouter un Article
        </button>
      </div>

      <transition-group name="fade" tag="div">
        <div v-if="successMessage" class="fixed top-6 right-6 z-50 p-4 rounded-lg bg-green-500 text-white shadow-xl flex items-center space-x-2" key="success">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p>{{ successMessage }}</p>
        </div>
        <div v-if="errorMessage" class="fixed top-6 right-6 z-50 p-4 rounded-lg bg-red-500 text-white shadow-xl flex items-center space-x-2" key="error">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p>{{ errorMessage }}</p>
        </div>
      </transition-group>
      
      <div class="flex flex-col sm:flex-row items-center justify-between mb-4 space-y-4 sm:space-y-0">
        <div class="flex items-center space-x-2 w-full sm:w-auto">
          <h2 class="text-2xl font-bold text-gray-800 flex-shrink-0">Articles existants</h2>
          <transition name="fade">
            <button
              v-if="selectedArticles.length === 1"
              @click="openEditModal"
              class="bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors flex items-center text-sm"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zm-7.536 7.536l-3.21 3.21a1 1 0 00.316 1.625l3.524 1.175c.42.14.887-.101 1.027-.521L14.414 7.586 11.586 4.758 7.05 9.293z" />
              </svg>
              Modifier
            </button>
          </transition>
          <transition name="fade">
            <button
              v-if="selectedArticles.length > 0"
              @click="confirmDeleteSelected"
              class="bg-red-500 text-white font-medium py-2 px-4 rounded-lg hover:bg-red-600 transition-colors flex items-center text-sm"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
              </svg>
              Supprimer
            </button>
          </transition>
        </div>
        <div class="relative w-full sm:w-1/3">
          <input
            v-model="searchQuery"
            placeholder="Rechercher des articles..."
            type="text"
            class="w-full border rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
      </div>
      
      <div v-if="loading" class="flex justify-center items-center py-12">
        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      <div v-else-if="paginatedArticles.length === 0 && !loading" class="text-gray-500 text-center py-12">
        <p class="text-lg">Aucun article ne correspond à votre recherche.</p>
      </div>
      <div v-else class="overflow-hidden border border-gray-200 rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="p-4">
                <input type="checkbox" @change="selectAll" :checked="selectedArticles.length === paginatedArticles.length && paginatedArticles.length > 0" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" />
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortBy('name')">
                Nom <span class="ml-1">{{ sortArrow('name') }}</span>
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Description
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortBy('price')">
                Prix <span class="ml-1">{{ sortArrow('price') }}</span>
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortBy('stock')">
                Stock <span class="ml-1">{{ sortArrow('stock') }}</span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="article in paginatedArticles" :key="article.id" :class="{'bg-indigo-50': isSelected(article.id)}">
              <td class="p-4 whitespace-nowrap">
                <input type="checkbox" :checked="isSelected(article.id)" @change="toggleSelect(article.id)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" />
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ article.name }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ article.description }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-bold">
                {{ article.price }} €
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ article.stock }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="filteredArticles.length > itemsPerPage" class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-600">
          Affichage de {{ startItem }} à {{ endItem }} sur {{ filteredArticles.length }} articles
        </div>
        <div class="flex space-x-1">
          <button
            @click="currentPage--"
            :disabled="currentPage === 1"
            class="bg-gray-200 text-gray-700 px-3 py-1 rounded-lg hover:bg-gray-300 disabled:opacity-50"
          >
            Précédent
          </button>
          <button
            v-for="page in totalPages"
            :key="page"
            @click="currentPage = page"
            :class="{'bg-indigo-600 text-white': currentPage === page, 'bg-gray-200 text-gray-700': currentPage !== page}"
            class="px-4 py-1 rounded-lg hover:bg-indigo-500 transition"
          >
            {{ page }}
          </button>
          <button
            @click="currentPage++"
            :disabled="currentPage === totalPages"
            class="bg-gray-200 text-gray-700 px-3 py-1 rounded-lg hover:bg-gray-300 disabled:opacity-50"
          >
            Suivant
          </button>
        </div>
      </div>
    </div>

    <transition name="modal-fade">
      <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex justify-center items-center">
        <div class="relative p-8 bg-white w-full max-w-lg m-4 rounded-lg shadow-2xl">
          <div class="flex justify-between items-center pb-3">
            <h3 class="text-2xl font-bold text-gray-800">{{ modalTitle }}</h3>
            <button class="text-gray-400 hover:text-gray-600" @click="closeModal">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <form @submit.prevent="submitForm" class="grid grid-cols-1 gap-6">
            <input v-model="currentArticle.name" placeholder="Nom" required class="border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            <textarea v-model="currentArticle.description" placeholder="Description (optionnel)" class="border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            <!-- Ajout de step="0.01" pour les décimales du prix -->
            <input v-model.number="currentArticle.price" type="number" step="0.01" placeholder="Prix (€)" required class="border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            <input v-model.number="currentArticle.stock" type="number" placeholder="Stock" required class="border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            
            <button
              type="submit"
              :disabled="loading"
              class="bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-600 transition-all duration-300 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="loading" class="flex items-center">
                <svg class="animate-spin h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Enregistrement...
              </span>
              <span v-else>
                {{ modalButtonText }}
              </span>
            </button>
          </form>
        </div>
      </div>
    </transition>

  </div>
</template>

<script>
import axios from "axios";

const API_BASE_URL = "http://127.0.0.1:8000/api";

export default {
  data() {
    return {
      articles: [],
      selectedArticles: [],
      currentArticle: {
        id: null,
        name: "",
        description: "",
        price: null, // Changé à null pour une meilleure distinction du 0
        stock: null, // Changé à null pour une meilleure distinction du 0
      },
      showModal: false,
      isEditing: false,
      loading: false,
      successMessage: null,
      errorMessage: null,
      searchQuery: "",
      currentPage: 1,
      itemsPerPage: 10,
      sortKey: 'name',
      sortDirection: 'asc',
    };
  },
  mounted() {
    this.loadArticles();
  },
  computed: {
    modalTitle() {
      return this.isEditing ? "Modifier l'article" : "Ajouter un article";
    },
    modalButtonText() {
      return this.isEditing ? "Sauvegarder les modifications" : "Créer l'article";
    },
    filteredArticles() {
      const query = this.searchQuery.toLowerCase().trim();
      let filtered = this.articles.filter(article => 
        article.name.toLowerCase().includes(query) ||
        (article.description && article.description.toLowerCase().includes(query)) // Vérifier si description existe
      );

      // Tri
      if (this.sortKey) {
        filtered.sort((a, b) => {
          const aValue = a[this.sortKey];
          const bValue = b[this.sortKey];
          
          if (typeof aValue === 'string') {
             return this.sortDirection === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
          } else {
             // Gérer les valeurs null/undefined pour le tri numérique
             const numA = aValue === null || isNaN(aValue) ? -Infinity : aValue;
             const numB = bValue === null || isNaN(bValue) ? -Infinity : bValue;

             if (numA < numB) return this.sortDirection === 'asc' ? -1 : 1;
             if (numA > numB) return this.sortDirection === 'asc' ? 1 : -1;
             return 0;
          }
        });
      }

      return filtered;
    },
    paginatedArticles() {
      // Clear selection when changing page/filters only if not actively editing
      if (!this.isEditing) {
        this.selectedArticles = []; 
      }
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredArticles.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredArticles.length / this.itemsPerPage);
    },
    startItem() {
      if (this.filteredArticles.length === 0) return 0;
      return (this.currentPage - 1) * this.itemsPerPage + 1;
    },
    endItem() {
      const end = this.currentPage * this.itemsPerPage;
      return end > this.filteredArticles.length ? this.filteredArticles.length : end;
    },
  },
  methods: {
    showFeedback(message, type = 'success') {
      if (type === 'success') {
        this.successMessage = message;
        setTimeout(() => this.successMessage = null, 3000);
      } else {
        this.errorMessage = message;
        setTimeout(() => this.errorMessage = null, 3000);
      }
    },
    async loadArticles() {
      this.loading = true;
      try {
        const res = await axios.get(`${API_BASE_URL}/articles`);
        this.articles = res.data;
      } catch (err) {
        console.error("Erreur chargement articles:", err);
        this.showFeedback("Impossible de charger les articles.", 'error');
      } finally {
        this.loading = false;
      }
    },
    async submitForm() {
      // Validation côté client plus stricte
      if (!this.currentArticle.name.trim()) {
        this.showFeedback("Le nom de l'article est requis.", 'error');
        return;
      }
      if (this.currentArticle.price === null || isNaN(this.currentArticle.price) || this.currentArticle.price < 0) {
        this.showFeedback("Veuillez entrer un prix valide (positif ou zéro).", 'error');
        return;
      }
      if (this.currentArticle.stock === null || isNaN(this.currentArticle.stock) || this.currentArticle.stock < 0) {
        this.showFeedback("Veuillez entrer un stock valide (positif ou zéro).", 'error');
        return;
      }

      if (this.isEditing) {
        await this.updateArticle();
      } else {
        await this.addArticle();
      }
    },
    async addArticle() {
      this.loading = true;
      try {
        const res = await axios.post(`${API_BASE_URL}/articles`, this.currentArticle);
        this.articles.push(res.data);
        this.showFeedback("Article ajouté avec succès !");
        this.closeModal();
      } catch (err) {
        console.error("Erreur ajout article:", err);
        this.showFeedback("Erreur lors de l'ajout de l'article. Assurez-vous que tous les champs sont valides.", 'error');
      } finally {
        this.loading = false;
      }
    },
    async updateArticle() {
      this.loading = true;
      try {
        const res = await axios.put(`${API_BASE_URL}/articles/${this.currentArticle.id}`, this.currentArticle);
        const index = this.articles.findIndex(a => a.id === this.currentArticle.id);
        if (index !== -1) {
          this.articles.splice(index, 1, res.data);
        }
        this.showFeedback("Article mis à jour avec succès !");
        this.closeModal();
      } catch (err) {
        console.error("Erreur mise à jour article:", err);
        this.showFeedback("Erreur lors de la mise à jour de l'article. Assurez-vous que tous les champs sont valides.", 'error');
      } finally {
        this.loading = false;
      }
    },
    confirmDeleteSelected() {
      if (this.selectedArticles.length === 0) return;
      const message = this.selectedArticles.length > 1
        ? `Êtes-vous sûr de vouloir supprimer les ${this.selectedArticles.length} articles sélectionnés ?`
        : "Êtes-vous sûr de vouloir supprimer cet article ?";
      
      if (confirm(message)) {
        this.deleteSelectedArticles();
      }
    },
    async deleteSelectedArticles() {
      try {
        await axios.post(`${API_BASE_URL}/articles/delete-multiple`, { ids: this.selectedArticles });
        this.articles = this.articles.filter(article => !this.selectedArticles.includes(article.id));
        this.selectedArticles = []; // Réinitialiser la sélection
        this.showFeedback("Article(s) supprimé(s) avec succès !");
      } catch (err) {
        console.error("Erreur suppression article(s):", err);
        this.showFeedback("Erreur lors de la suppression des articles.", 'error');
      }
    },
    openAddModal() {
      this.isEditing = false;
      this.currentArticle = {
        id: null,
        name: "",
        description: "",
        price: null, // Initialisé à null pour indiquer qu'aucune valeur n'a été saisie
        stock: null, // Initialisé à null
      };
      this.showModal = true;
    },
    openEditModal() {
      if (this.selectedArticles.length === 1) {
        this.isEditing = true;
        const articleToEdit = this.articles.find(a => a.id === this.selectedArticles[0]);
        if (articleToEdit) {
          // Utiliser parseFloat pour s'assurer que le prix est un nombre si jamais il vient comme string
          this.currentArticle = { 
            ...articleToEdit,
            price: parseFloat(articleToEdit.price),
            stock: parseInt(articleToEdit.stock, 10),
          };
          this.showModal = true;
        }
      } else {
        this.showFeedback("Veuillez sélectionner un seul article à modifier.", 'error');
      }
    },
    closeModal() {
      this.showModal = false;
      this.currentArticle = {
        id: null,
        name: "",
        description: "",
        price: null,
        stock: null,
      };
      this.selectedArticles = []; // Clear selection when closing modal
    },
    toggleSelect(id) {
      const index = this.selectedArticles.indexOf(id);
      if (index > -1) {
        this.selectedArticles.splice(index, 1);
      } else {
        this.selectedArticles.push(id);
      }
    },
    selectAll(event) {
      if (event.target.checked) {
        this.selectedArticles = this.paginatedArticles.map(article => article.id);
      } else {
        this.selectedArticles = [];
      }
    },
    isSelected(id) {
      return this.selectedArticles.includes(id);
    },
    sortBy(key) {
      if (this.sortKey === key) {
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortKey = key;
        this.sortDirection = 'asc';
      }
      this.currentPage = 1; // Reset pagination on sort
    },
    sortArrow(key) {
      if (this.sortKey !== key) {
        return '';
      }
      return this.sortDirection === 'asc' ? '▲' : '▼';
    }
  },
  watch: {
    searchQuery() {
      this.currentPage = 1;
    },
    articles: {
      handler() {
        // Recalculate totalPages and adjust currentPage if it's out of bounds
        // This handles cases where articles are deleted and the current page becomes empty
        if (this.currentPage > this.totalPages && this.totalPages > 0) {
          this.currentPage = this.totalPages;
        } else if (this.totalPages === 0) {
          this.currentPage = 1;
        }
      },
      deep: true // Watch for changes inside the articles array as well
    }
  }
};
</script>

<style>
/* Transitions pour les pop-ups de feedback */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>