<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-xl p-8">

      <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m0 0v10m0-10L7 17m10-10l-10 10m-3-10h14a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2z" />
          </svg>
          Gestion des Catégories
        </h1>
        <button
          @click="openAddModal"
          class="bg-indigo-600 text-white font-medium py-3 px-6 rounded-xl hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Ajouter une catégorie
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
          <h2 class="text-2xl font-bold text-gray-800 flex-shrink-0">Catégories existantes</h2>
          <transition name="fade">
            <button
              v-if="selectedCategories.length === 1"
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
              v-if="selectedCategories.length > 0"
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
      </div>
      
      <div v-if="loading" class="flex justify-center items-center py-12">
        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <div v-else-if="categories.length === 0" class="text-gray-500 text-center py-12">
        <p class="text-lg">Aucune catégorie trouvée.</p>
      </div>

      <div v-else class="overflow-hidden border border-gray-200 rounded-lg shadow-sm">
        <div class="overflow-y-auto max-h-[60vh] relative">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10">
              <tr>
                <th scope="col" class="p-4 w-10">
                  <input type="checkbox" @change="selectAll" :checked="selectedCategories.length === categories.length && categories.length > 0" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  ID
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Nom
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="category in categories" :key="category.id" :class="{'bg-indigo-50': isSelected(category.id)}">
                <td class="p-4 whitespace-nowrap">
                  <input
                    type="checkbox"
                    :value="category.id"
                    v-model="selectedCategories"
                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                  />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ category.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ category.name }}
                </td>
              </tr>
            </tbody>
          </table>
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
            <input
              type="text"
              v-model="categoryName"
              placeholder="Nom de la catégorie"
              required
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
            />
            <div class="flex justify-end space-x-2">
              <button
                type="button"
                @click="closeModal"
                class="bg-gray-300 text-gray-700 font-medium py-3 px-6 rounded-lg hover:bg-gray-400 transition"
              >
                Annuler
              </button>
              <button
                type="submit"
                class="bg-indigo-600 text-white font-medium py-3 px-6 rounded-lg hover:bg-indigo-700 transition"
              >
                {{ modalButtonText }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { getCategories, createCategory, updateCategory, deleteCategory } from "../services/api";

export default {
  data() {
    return {
      categories: [],
      selectedCategories: [],
      editingCategory: null,
      categoryName: "",
      showModal: false,
      isEditing: false,
      loading: false,
      successMessage: null,
      errorMessage: null,
    };
  },
  computed: {
    modalTitle() {
      return this.isEditing ? "Modifier la catégorie" : "Ajouter une catégorie";
    },
    modalButtonText() {
      return this.isEditing ? "Sauvegarder les modifications" : "Créer la catégorie";
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
    async fetchCategories() {
      this.loading = true;
      try {
        const res = await getCategories();
        this.categories = res.data;
      } catch (error) {
        this.showFeedback("Erreur lors de la récupération des catégories.", 'error');
        console.error("Erreur récupération catégories:", error);
      } finally {
        this.loading = false;
      }
    },
    closeModal() {
      this.showModal = false;
      this.editingCategory = null;
      this.categoryName = "";
      this.isEditing = false;
      this.selectedCategories = [];
    },
    openAddModal() {
      this.isEditing = false;
      this.categoryName = "";
      this.showModal = true;
    },
    openEditModal() {
      if (this.selectedCategories.length === 1) {
        this.isEditing = true;
        const categoryId = this.selectedCategories[0];
        const categoryToEdit = this.categories.find(c => c.id === categoryId);
        if (categoryToEdit) {
          this.editingCategory = categoryToEdit;
          this.categoryName = categoryToEdit.name;
          this.showModal = true;
        }
      } else {
        this.showFeedback("Veuillez sélectionner une seule catégorie à modifier.", 'error');
      }
    },
    submitForm() {
      if (!this.categoryName.trim()) {
        this.showFeedback("Le nom de la catégorie ne peut pas être vide.", 'error');
        return;
      }

      if (this.isEditing) {
        this.updateSelectedCategory();
      } else {
        this.createCategory();
      }
    },
    async createCategory() {
      try {
        await createCategory({ name: this.categoryName });
        this.showFeedback("Catégorie ajoutée avec succès !");
        this.fetchCategories();
        this.closeModal();
      } catch (error) {
        this.showFeedback("Erreur lors de la création de la catégorie.", 'error');
        console.error("Erreur création catégorie:", error);
      }
    },
    async updateSelectedCategory() {
      if (!this.editingCategory || !this.editingCategory.id) return;
      try {
        await updateCategory(this.editingCategory.id, { name: this.categoryName });
        this.showFeedback("Catégorie mise à jour avec succès !");
        this.fetchCategories();
        this.closeModal();
      } catch (error) {
        this.showFeedback("Erreur lors de la mise à jour de la catégorie.", 'error');
        console.error("Erreur modification catégorie:", error);
      }
    },
    async confirmDeleteSelected() {
      if (this.selectedCategories.length === 0) return;
      
      const isConfirmed = confirm(`Voulez-vous vraiment supprimer les ${this.selectedCategories.length} catégorie(s) sélectionnée(s) ?`);
      if (isConfirmed) {
        this.deleteSelectedCategories();
      }
    },
    async deleteSelectedCategories() {
      try {
        await Promise.all(this.selectedCategories.map(id => deleteCategory(id)));
        this.showFeedback("Catégorie(s) supprimée(s) avec succès !");
        this.fetchCategories();
        this.selectedCategories = [];
      } catch (error) {
        this.showFeedback("Erreur lors de la suppression des catégories.", 'error');
        console.error("Erreur suppression de catégories:", error);
      }
    },
    selectAll() {
      if (this.selectedCategories.length === this.categories.length) {
        this.selectedCategories = [];
      } else {
        this.selectedCategories = this.categories.map(c => c.id);
      }
    },
    isSelected(id) {
      return this.selectedCategories.includes(id);
    },
  },
  mounted() {
    this.fetchCategories();
  },
};
</script>

<style>
/* Transitions pour les pop-ups de feedback et la modal */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(20px);
}

.modal-fade-enter-active, .modal-fade-leave-active {
  transition: opacity 0.3s ease;
}
.modal-fade-enter-from, .modal-fade-leave-to {
  opacity: 0;
}
</style>