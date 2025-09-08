<template>
  <aside class="w-64 bg-slate-900 text-gray-200 flex flex-col h-screen transition-all duration-300 ease-in-out shadow-2xl">
    <!-- Logo / Titre -->
    <div class="p-6 text-3xl font-extrabold text-white border-b border-slate-700">
      Mon <span class="text-indigo-400">App</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 flex flex-col justify-between">
      <ul class="space-y-2">
        <!-- Dashboard -->
        <li class="relative group" @click="$emit('update:tab', 'dashboard')">
          <a
            class="flex items-center px-4 py-3 rounded-md text-sm font-semibold transition-all duration-200 cursor-pointer"
            :class="{'bg-slate-800 text-white': activeTab === 'dashboard'}"
          >
            <span 
              class="absolute inset-y-0 left-0 w-1 bg-indigo-500 rounded-tr-md rounded-br-md transition-all duration-300 transform scale-y-0 group-hover:scale-y-100"
              :class="{'scale-y-100': activeTab === 'dashboard'}"
            ></span>
            <svg class="h-6 w-6 mr-3 text-gray-400 group-hover:text-indigo-400" 
                 :class="{'text-indigo-400': activeTab === 'dashboard'}" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
          </a>
        </li>

        <!-- Articles -->
        <li class="relative group" @click="$emit('update:tab', 'articles')">
          <a
            class="flex items-center px-4 py-3 rounded-md text-sm font-semibold transition-all duration-200 cursor-pointer"
            :class="{'bg-slate-800 text-white': activeTab === 'articles'}"
          >
            <span 
              class="absolute inset-y-0 left-0 w-1 bg-indigo-500 rounded-tr-md rounded-br-md transition-all duration-300 transform scale-y-0 group-hover:scale-y-100"
              :class="{'scale-y-100': activeTab === 'articles'}"
            ></span>
            <svg class="h-6 w-6 mr-3 text-gray-400 group-hover:text-indigo-400" 
                 :class="{'text-indigo-400': activeTab === 'articles'}" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2a2 2 0 012-2m14 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v2m7-9v2m0 0v2m0 6v2m0 0v2" />
            </svg>
            <span>Articles</span>
          </a>
        </li>

        <!-- Categories -->
        <li class="relative group" @click="$emit('update:tab', 'categories')">
          <a
            class="flex items-center px-4 py-3 rounded-md text-sm font-semibold transition-all duration-200 cursor-pointer"
            :class="{'bg-slate-800 text-white': activeTab === 'categories'}"
          >
            <span 
              class="absolute inset-y-0 left-0 w-1 bg-indigo-500 rounded-tr-md rounded-br-md transition-all duration-300 transform scale-y-0 group-hover:scale-y-100"
              :class="{'scale-y-100': activeTab === 'categories'}"
            ></span>
            <svg class="h-6 w-6 mr-3 text-gray-400 group-hover:text-indigo-400" 
                 :class="{'text-indigo-400': activeTab === 'categories'}" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span>Catégories</span>
          </a>
        </li>
      </ul>

      <!-- Historique -->
<li class="relative group" @click="$emit('update:tab', 'history')">
  <a
    class="flex items-center px-4 py-3 rounded-md text-sm font-semibold transition-all duration-200 cursor-pointer"
    :class="{'bg-slate-800 text-white': activeTab === 'history'}"
  >
    <span 
      class="absolute inset-y-0 left-0 w-1 bg-indigo-500 rounded-tr-md rounded-br-md transition-all duration-300 transform scale-y-0 group-hover:scale-y-100"
      :class="{'scale-y-100': activeTab === 'history'}"
    ></span>
    <svg class="h-6 w-6 mr-3 text-gray-400 group-hover:text-indigo-400" 
         :class="{'text-indigo-400': activeTab === 'history'}" 
         fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
    <span>Historique</span>
  </a>
</li>


      <!-- Déconnexion tout en bas -->
      <div class="mt-auto p-4">
        <button
          @click="$emit('logout')"
          class="flex items-center px-4 py-3 w-full text-sm font-semibold text-red-500 rounded-md hover:bg-red-50 hover:text-red-600 transition-all"
        >
          <svg class="h-6 w-6 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
          </svg>
          <span>Déconnexion</span>
        </button>
      </div>
    </nav>

    <!-- Version / Footer -->
    <div class="p-6 text-xs text-gray-500 border-t border-slate-700">
      <p class="mb-1">Version 1.0.0</p>
      <p>&copy; 2025 Ma société</p>
    </div>
  </aside>
</template>

<script>
export default {
  props: {
    activeTab: { type: String, default: 'articles' }
  }
}
</script>

<style scoped>
/* Tout est géré par Tailwind dans le template */
</style>
