<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <Sidebar :activeTab="activeTab" @update:tab="activeTab = $event" />

    <!-- Contenu principal -->
    <div class="flex-1 flex flex-col">
      <!-- Topbar -->
      <Topbar :user="currentUser" />

      <!-- Main content -->
      <main class="flex-1 p-6 overflow-auto">
        <Dashboard v-if="activeTab === 'dashboard'" />
        <Articles v-if="activeTab === 'articles'" />
      </main>
    </div>
  </div>
</template>

<script>
import Sidebar from './components/Sidebar.vue';
import Topbar from './components/Topbar.vue';
import Dashboard from './components/Dashboard.vue';
import Articles from './components/Articles.vue';

export default {
  name: 'App',
  components: { Sidebar, Topbar, Dashboard, Articles },
  data() {
    return {
      activeTab: 'dashboard', // valeur initiale
      currentUser: {
        name: 'Aglan Elom',
        avatar: 'https://i.pravatar.cc/150?img=3'
      }
    };
  }
}
</script>

<style>
/* Ajoute ici des styles globaux si nécessaire */
</style>
