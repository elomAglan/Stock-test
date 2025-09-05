<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-xl p-8">

      <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m0 0v10m0-10L7 17m10-10l-10 10m-3-10h14a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2z" />
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
        <input
          v-model="searchQuery"
          placeholder="Rechercher..."
          class="border rounded-lg px-4 py-2 w-full sm:w-1/3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
        />
      </div>

      <div v-if="loading" class="flex justify-center items-center py-12">
        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      <div v-else-if="paginatedArticles.length === 0" class="text-center py-12 text-gray-500">
        <p class="text-lg">Aucun article trouvé.</p>
      </div>
      <div v-else class="overflow-hidden border border-gray-200 rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="p-4 w-10">
                <input type="checkbox" @change="selectAll" :checked="isAllSelected" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" />
              </th>
              <th @click="sortBy('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Nom</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
              <th @click="sortBy('category')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Catégorie</th>
              <th @click="sortBy('price')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Prix</th>
              <th @click="sortBy('stock')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Stock</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="article in paginatedArticles" :key="article.id" :class="{'bg-indigo-50': isSelected(article.id)}">
              <td class="p-4 whitespace-nowrap">
                <input type="checkbox" :checked="isSelected(article.id)" @change="toggleSelect(article.id)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ article.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ article.description }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ article.category?.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ article.price }} €</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ article.stock }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="totalPages > 1" class="flex justify-center items-center mt-6 space-x-2">
        <button
          @click="currentPage--"
          :disabled="currentPage === 1"
          class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-200 transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Précédent
        </button>
        <div class="flex space-x-2">
          <button
            v-for="page in totalPages"
            :key="page"
            @click="currentPage = page"
            :class="['px-4 py-2 rounded-lg transition', currentPage === page ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-700 border hover:bg-gray-200']"
          >
            {{ page }}
          </button>
        </div>
        <button
          @click="currentPage++"
          :disabled="currentPage === totalPages"
          class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-200 transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Suivant
        </button>
      </div>

      <transition name="modal-fade">
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex justify-center items-center">
          <div class="relative p-8 bg-white w-full max-w-lg m-4 rounded-lg shadow-2xl">
            <div class="flex justify-between items-center pb-3">
              <h3 class="text-2xl font-bold text-gray-800">{{ isEditing ? 'Modifier l\'Article' : 'Ajouter un Article' }}</h3>
              <button class="text-gray-400 hover:text-gray-600" @click="closeModal">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <form @submit.prevent="submitForm" class="grid grid-cols-1 gap-6">
              <input v-model="currentArticle.name" placeholder="Nom de l'article" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"/>
              <textarea v-model="currentArticle.description" placeholder="Description de l'article" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"></textarea>
              <select v-model="currentArticle.category_id" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                <option disabled value="">Sélectionnez une catégorie</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
              </select>
              <input v-model.number="currentArticle.price" type="number" placeholder="Prix (€)" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"/>
              <input v-model.number="currentArticle.stock" type="number" placeholder="Stock" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"/>
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
                  {{ isEditing ? 'Sauvegarder les modifications' : 'Créer l\'article' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </transition>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { getArticles, createArticle, updateArticle, deleteArticle, getCategories } from '../services/api';

const articles = ref([]);
const categories = ref([]);
const selectedArticles = ref([]);
const currentArticle = reactive({ id: null, name:'', description:'', category_id:null, price:null, stock:null });
const showModal = ref(false);
const isEditing = ref(false);
const loading = ref(false);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);

const successMessage = ref(null);
const errorMessage = ref(null);

// --- Computed ---
const filteredArticles = computed(() => {
  let filtered = articles.value.filter(a =>
    a.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    (a.description && a.description.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
    (a.category?.name && a.category.name.toLowerCase().includes(searchQuery.value.toLowerCase()))
  );
  return filtered;
});

const totalPages = computed(() => Math.ceil(filteredArticles.value.length / itemsPerPage.value));
const paginatedArticles = computed(() => {
  const start = (currentPage.value -1)*itemsPerPage.value;
  return filteredArticles.value.slice(start, start + itemsPerPage.value);
});
const isAllSelected = computed(() => paginatedArticles.value.length > 0 && selectedArticles.value.length === paginatedArticles.value.length);

// --- Methods ---
const showFeedback = (message, type = 'success') => {
  if (type === 'success') {
    successMessage.value = message;
    setTimeout(() => successMessage.value = null, 3000);
  } else {
    errorMessage.value = message;
    setTimeout(() => errorMessage.value = null, 3000);
  }
};

const loadArticles = async () => {
  loading.value = true;
  try { articles.value = (await getArticles()).data; }
  catch (err) { showFeedback('Erreur lors du chargement des articles', 'error'); }
  finally { loading.value = false; }
};

const loadCategories = async () => {
  try { categories.value = (await getCategories()).data; }
  catch (err) { showFeedback('Erreur lors du chargement des catégories', 'error'); }
};

const submitForm = async () => {
  try {
    if (isEditing.value) {
      await updateArticle(currentArticle.id, currentArticle);
      showFeedback('Article modifié avec succès !');
    } else {
      await createArticle(currentArticle);
      showFeedback('Article créé avec succès !');
    }
    await loadArticles();
    closeModal();
  } catch {
    showFeedback('Erreur lors de l\'opération', 'error');
  }
};

const openAddModal = () => {
  isEditing.value = false;
  Object.assign(currentArticle, {id:null, name:'', description:'', category_id:null, price:null, stock:null});
  showModal.value = true;
  selectedArticles.value = [];
};

const openEditModal = () => {
  if (selectedArticles.value.length !==1) {
    showFeedback('Veuillez sélectionner un seul article à modifier.', 'error');
    return;
  }
  const art = articles.value.find(a => a.id === selectedArticles.value[0]);
  if (!art) return;
  Object.assign(currentArticle, {...art, category_id:art.category_id});
  isEditing.value = true;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedArticles.value = [];
};

const confirmDeleteSelected = async () => {
  if (selectedArticles.value.length === 0) return;
  const isConfirmed = confirm(`Voulez-vous vraiment supprimer les ${selectedArticles.value.length} article(s) sélectionné(s) ?`);
  if (isConfirmed) {
    await deleteSelectedArticles();
  }
};

const deleteSelectedArticles = async () => {
  try {
    await Promise.all(selectedArticles.value.map(id => deleteArticle(id)));
    showFeedback('Article(s) supprimé(s) avec succès !');
    await loadArticles();
    selectedArticles.value = [];
  } catch (err) {
    showFeedback('Erreur lors de la suppression des articles', 'error');
  }
};

const toggleSelect = id => {
  const i = selectedArticles.value.indexOf(id);
  i>-1 ? selectedArticles.value.splice(i,1) : selectedArticles.value.push(id);
};

const selectAll = () => {
  if (isAllSelected.value) {
    selectedArticles.value = [];
  } else {
    selectedArticles.value = paginatedArticles.value.map(a=>a.id);
  }
};
const isSelected = id => selectedArticles.value.includes(id);

const sortKey = ref('name');
const sortDirection = ref('asc');
const sortBy = key => {
  if (sortKey.value===key) sortDirection.value = sortDirection.value==='asc'?'desc':'asc';
  else { sortKey.value=key; sortDirection.value='asc'; }
  articles.value.sort((a,b)=>{
    let aVal = key==='category'?a.category?.name:a[key];
    let bVal = key==='category'?b.category?.name:b[key];
    if(typeof aVal==='string') return sortDirection.value==='asc'? aVal.localeCompare(bVal): bVal.localeCompare(aVal);
    return (aVal-bVal)*(sortDirection.value==='asc'?1:-1);
  });
};

onMounted(() => { loadArticles(); loadCategories(); });
watch(searchQuery, () => currentPage.value = 1);
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