<template>
  <ConfigLayout>
    <div class="container mx-auto p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gestion des Utilisateurs</h1>
        <IconButton 
          @click="openCreateModal"
          iconClass="UserPlus"
          bgColor="#007AFF"
          iconColor="white"
          tooltip="Ajouter un utilisateur"
        />
      </div>

      <!-- Table des utilisateurs -->
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom d'utilisateur</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id" 
                :class="{'bg-yellow-50': !user.has_password}">
              <td class="px-6 py-4 whitespace-nowrap">{{ user.username }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ user.firstname }} {{ user.lastname }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                      :class="getRoleClass(user.role.name)">
                  {{ user.role.name }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <div class="flex gap-2">
                  <IconButton 
                    @click="openEditModal(user)"
                    iconClass="Edit2"
                    bgColor="#007AFF"
                    iconColor="white"
                    small
                    tooltip="Modifier"
                  />
                  <IconButton 
                    @click="resetPassword(user.id)"
                    iconClass="KeyRound"
                    :bgColor="user.has_password ? '#007AFF' : '#FF9500'"
                    iconColor="white"
                    small
                    tooltip="Gérer le mot de passe"
                  />
                  <IconButton 
                    @click="confirmDelete(user)"
                    iconClass="Trash2"
                    bgColor="#FF3B30"
                    iconColor="white"
                    small
                    tooltip="Supprimer"
                  />
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal de création/modification -->
      <Popup 
        :show="isModalOpen"
        :title="editingUser ? 'Modifier un utilisateur' : 'Ajouter un utilisateur'"
        @close="closeModal"
      >
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
            <input type="text" v-model="form.username" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Prénom</label>
            <input type="text" v-model="form.firstname" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Nom</label>
            <input type="text" v-model="form.lastname" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" v-model="form.email" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Rôle</label>
            <select v-model="form.role_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
              <option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.name }}
              </option>
            </select>
          </div>

          <div class="mt-6 flex justify-end space-x-3">
            <button type="button" @click="closeModal"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
              Annuler
            </button>
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
              {{ editingUser ? 'Modifier' : 'Ajouter' }}
            </button>
          </div>
        </form>
      </Popup>

      <!-- Modal de confirmation de suppression -->
      <Popup
        :show="isDeleteModalOpen"
        title="Confirmer la suppression"
        @close="closeDeleteModal"
      >
        <p class="text-sm text-gray-500 mb-4">
          Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.
        </p>

        <div class="flex justify-end space-x-3">
          <button @click="closeDeleteModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
            Annuler
          </button>
          <button @click="deleteUser"
                  class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
            Supprimer
          </button>
        </div>
      </Popup>
    </div>
  </ConfigLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ConfigLayout from '@/Layouts/ConfigLayout.vue'
import Popup from '@/Components/Popup/Popup.vue'
import IconButton from '@/Components/IconButton.vue'
import axios from 'axios'

const users = ref([])
const roles = ref([])
const isModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const editingUser = ref(null)
const userToDelete = ref(null)

const form = ref({
  username: '',
  firstname: '',
  lastname: '',
  email: '',
  role_id: ''
})

// Charger les utilisateurs
const loadUsers = async () => {
  try {
    const response = await axios.get('/api/users')
    users.value = response.data
  } catch (error) {
    alert('Erreur lors du chargement des utilisateurs')
  }
}

// Charger les rôles
const loadRoles = async () => {
  try {
    const response = await axios.get('/api/roles')
    roles.value = response.data
  } catch (error) {
    alert('Erreur lors du chargement des rôles')
  }
}

// Ouvrir le modal de création
const openCreateModal = () => {
  editingUser.value = null
  form.value = {
    username: '',
    firstname: '',
    lastname: '',
    email: '',
    role_id: ''
  }
  isModalOpen.value = true
}

// Ouvrir le modal de modification
const openEditModal = (user) => {
  editingUser.value = user
  form.value = {
    username: user.username,
    firstname: user.firstname,
    lastname: user.lastname,
    email: user.email,
    role_id: user.role_id
  }
  isModalOpen.value = true
}

// Fermer le modal
const closeModal = () => {
  isModalOpen.value = false
  editingUser.value = null
  form.value = {
    username: '',
    firstname: '',
    lastname: '',
    email: '',
    role_id: ''
  }
}

// Gérer la soumission du formulaire
const handleSubmit = async () => {
  try {
    if (editingUser.value) {
      await axios.put(`/api/users/${editingUser.value.id}`, form.value)
    } else {
      await axios.post('/api/users', form.value)
    }
    await loadUsers()
    closeModal()
  } catch (error) {
    alert(error.response?.data?.message || 'Une erreur est survenue')
  }
}

// Confirmer la suppression
const confirmDelete = (user) => {
  userToDelete.value = user
  isDeleteModalOpen.value = true
}

// Fermer le modal de suppression
const closeDeleteModal = () => {
  isDeleteModalOpen.value = false
  userToDelete.value = null
}

// Supprimer un utilisateur
const deleteUser = async () => {
  try {
    await axios.delete(`/api/users/${userToDelete.value.id}`)
    await loadUsers()
    closeDeleteModal()
  } catch (error) {
  }
}

// Réinitialiser le mot de passe
const resetPassword = async (userId) => {
  try {
    await axios.post(`/api/users/${userId}/create-or-reset-password`)
    window.location.reload()
  } catch (error) {
    alert('Erreur lors de la réinitialisation du mot de passe')
  }
}

// Classes pour les badges de rôle
const getRoleClass = (roleName) => {
  const classes = {
    'Admin': 'bg-red-100 text-red-800',
    'Teacher': 'bg-blue-100 text-blue-800',
    'default': 'bg-gray-100 text-gray-800'
  }
  return classes[roleName] || classes.default
}

onMounted(() => {
  loadUsers()
  loadRoles()
})
</script>
