<template>
  <aside class="w-64 bg-slate-900 text-gray-200 flex flex-col h-screen transition-all duration-300 ease-in-out shadow-2xl">
    <div class="p-6 text-3xl font-extrabold text-white border-b border-slate-700">
      Mon <span class="text-indigo-400">App</span>
    </div>

    <nav class="flex-1 p-4">
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
      </ul>
    </nav>

    <div class="p-6 text-xs text-gray-500 border-t border-slate-700">
      <p class="mb-1">Version 1.0.0</p>
      <p>&copy; 2025 Ma société</p>
    </div>
  </aside>
</template>

<script>
export default {
  props: {
    activeTab: {
      type: String,
      default: 'articles'
    }
  }
}
</script>

<style scoped>
/* Plus besoin de @apply, tout est géré par les classes Tailwind dans le template */
</style>
